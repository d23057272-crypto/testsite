<?php

namespace App\Mail;

use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Log;

class ResendApiTransport extends AbstractTransport
{
    protected $apiKey;
    protected $fromEmail;
    protected $fromName;

    public function __construct($apiKey, $fromEmail, $fromName)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        if (!$email instanceof Email) {
            return;
        }

        $from = $email->getFrom();
        $fromEmail = $from[0]->getAddress() ?? $this->fromEmail;
        $fromName = $from[0]->getName() ?? $this->fromName;

        $to = [];
        foreach ($email->getTo() as $address) {
            $name = $address->getName();
            $addr = $address->getAddress();
            $to[] = $name ? $name . ' <' . $addr . '>' : $addr;
        }

        $subject = $email->getSubject() ?? 'Notification';
        $html = $email->getHtmlBody() ?? $email->getTextBody() ?? '';

        $data = [
            'from' => $fromName . ' <' . $fromEmail . '>',
            'to' => $to,
            'subject' => $subject,
            'html' => $html,
        ];

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error('Resend API cURL error: ' . $error);
            throw new \Exception('Resend API cURL error: ' . $error);
        }

        if ($httpCode !== 200 && $httpCode !== 202) {
            Log::error('Resend API error: HTTP ' . $httpCode . ' - ' . $response);
            throw new \Exception('Resend API error: HTTP ' . $httpCode);
        }
    }

    public function __toString(): string
    {
        return 'resend_api';
    }
}
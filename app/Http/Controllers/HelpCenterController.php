<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HelpCenterController extends Controller
{
    public function create()
    {
        return view('user.help-center');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Send email to admin
        Mail::send('emails.help-center', [
            'name' => $request->name,
            'email' => $request->email,
            'user_message' => $request->message,
        ], function ($mail) use ($request) {
            $mail->to('support@pursekey.pro')
                 ->subject('New Help Center Message from ' . $request->name);
        });

        return redirect()->back()->with('success', 'Your request has been received. We will respond as soon as possible.');
    }
}
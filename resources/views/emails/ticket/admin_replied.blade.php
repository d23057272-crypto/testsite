{{-- resources/views/emails/ticket-reply.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket Updated</title>
</head>
<body style="margin:0;padding:0;background-color:#0f172a;">

  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#0f172a;">
    <tr>
      <td align="center" style="padding:0;">

        <!-- BANNER -->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;width:96%;">
          <tr>
            <td style="padding:0;margin:0;line-height:0;border-radius:12px 12px 0 0;overflow:hidden;">
              <img src="https://covachain.org/uploads/logos/support.jpg" 
                   alt="Support Update" 
                   width="100%" 
                   style="display:block;width:100%;height:auto;border:0;outline:none;border-radius:12px 12px 0 0;"
                   onerror="this.style.display='none'">
            </td>
          </tr>
        </table>

        <!-- HEADER -->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;width:96%;background:#0f172a;">
          <tr>
            <td align="center" style="padding:20px 12px 12px;">
              <div style="width:48px;height:48px;background:#10b981;border-radius:50%;text-align:center;line-height:48px;font-size:20px;margin:0 auto 12px;">💬</div>
              <h1 style="color:#ffffff;font-size:20px;font-weight:700;margin:0 0 4px;font-family:Arial,Helvetica,sans-serif;">Ticket Updated</h1>
              <p style="color:#94a3b8;font-size:12px;margin:0;font-family:Arial,Helvetica,sans-serif;">Our support team has responded to your request</p>
            </td>
          </tr>
        </table>

        <!-- MAIN CARD -->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;width:96%;">
          <tr>
            <td style="background:#ffffff;border-radius:16px;overflow:hidden;">

              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td style="padding:20px 14px;">

                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td style="padding-bottom:14px;">
                          <p style="color:#475569;font-size:13px;line-height:1.6;margin:0;font-family:Arial,Helvetica,sans-serif;">
                            Hello <strong style="color:#0f172a;">{{optional($ticket->user)->name ?? 'User'}}</strong>,
                          </p>
                        </td>
                      </tr>
                    </table>

                    <p style="color:#475569;font-size:13px;line-height:1.6;margin:0 0 16px;font-family:Arial,Helvetica,sans-serif;">
                      Your support ticket has been updated by our admin team. Please review the response below.
                    </p>

                    <!-- TICKET DETAILS -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;margin-bottom:16px;">
                      <tr>
                        <td style="padding:12px;">
                          <p style="color:#0f172a;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;">Ticket Information</p>

                          <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-bottom:1px solid #e2e8f0;">
                            <tr><td style="padding:7px 0;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td style="color:#64748b;font-size:12px;font-family:Arial,Helvetica,sans-serif;">Ticket ID</td><td style="color:#0f172a;font-size:12px;font-weight:600;text-align:right;font-family:Arial,Helvetica,sans-serif;">#{{$ticket->ticket_id}}</td></tr></table></td></tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-bottom:1px solid #e2e8f0;">
                            <tr><td style="padding:7px 0;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td style="color:#64748b;font-size:12px;font-family:Arial,Helvetica,sans-serif;">Subject</td><td style="color:#0f172a;font-size:12px;font-weight:500;text-align:right;font-family:Arial,Helvetica,sans-serif;">{{$ticket->subject}}</td></tr></table></td></tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr><td style="padding:7px 0 0;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td style="color:#64748b;font-size:12px;font-family:Arial,Helvetica,sans-serif;">Status</td><td style="text-align:right;"><span style="background:{{$ticket->status == 'closed' ? '#f0fdf4' : '#fef3c7'}};color:{{$ticket->status == 'closed' ? '#166534' : '#92400e'}};padding:3px 8px;border-radius:20px;font-size:11px;font-weight:600;font-family:Arial,Helvetica,sans-serif;">{{ucfirst($ticket->status)}}</span></td></tr></table></td></tr>
                          </table>
                        </td>
                      </tr>
                    </table>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:16px 0;">
                      <tr><td align="center"><a href="{{url('/support/'.$ticket->ticket_id)}}" style="display:inline-block;background:#10b981;color:#ffffff;text-decoration:none;padding:11px 24px;border-radius:8px;font-weight:600;font-size:12px;font-family:Arial,Helvetica,sans-serif;">View Ticket →</a></td></tr>
                    </table>

                    <!-- DIVIDER -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:16px 0;">
                      <tr><td style="height:1px;background:#e2e8f0;font-size:0;line-height:0;">&nbsp;</td></tr>
                    </table>

                    <!-- SUPPORT -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td align="center">
                          <p style="color:#64748b;font-size:11px;margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;">Need further assistance? We're here <strong>24/7</strong></p>
                          <a href="{{url('/support')}}" style="display:inline-block;background:transparent;color:#0f172a;text-decoration:none;padding:8px 16px;border-radius:6px;font-weight:600;font-size:11px;border:2px solid #0f172a;font-family:Arial,Helvetica,sans-serif;">Contact Support</a>
                        </td>
                      </tr>
                    </table>

                  </td>
                </tr>
              </table>

              <!-- FOOTER -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                <tr>
                  <td style="padding:16px 14px;text-align:center;">
                    <p style="color:#0f172a;font-size:12px;font-weight:700;margin:0 0 2px;font-family:Arial,Helvetica,sans-serif;">{{config('app.name')}}</p>
                    <p style="color:#94a3b8;font-size:10px;margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;">Your Trusted Investment Partner</p>
                    <p style="color:#cbd5e1;font-size:10px;margin:0;font-family:Arial,Helvetica,sans-serif;">© {{date('Y')}} {{config('app.name')}}. All rights reserved.</p>
                  </td>
                </tr>
              </table>

            </td>
          </tr>
        </table>

        <!-- BOTTOM SPACING -->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;width:96%;">
          <tr><td style="height:16px;">&nbsp;</td></tr>
        </table>

      </td>
    </tr>
  </table>

</body>
</html>
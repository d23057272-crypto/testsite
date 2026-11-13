{{-- resources/views/emails/ticket/user_opened.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Support Ticket</title>
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
                   alt="Support Alert" 
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
              <div style="width:48px;height:48px;background:#4f46e5;border-radius:50%;text-align:center;line-height:48px;font-size:20px;margin:0 auto 12px;">📩</div>
              <h1 style="color:#ffffff;font-size:20px;font-weight:700;margin:0 0 4px;font-family:Arial,Helvetica,sans-serif;">New Support Ticket</h1>
              <p style="color:#94a3b8;font-size:12px;margin:0;font-family:Arial,Helvetica,sans-serif;">Admin notification — action required</p>
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

                    <p style="color:#475569;font-size:13px;line-height:1.6;margin:0 0 16px;font-family:Arial,Helvetica,sans-serif;">
                      Hello <strong style="color:#0f172a;">Admin</strong>, a new support ticket has been created and requires your attention.
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
                          <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-bottom:1px solid #e2e8f0;">
                            <tr><td style="padding:7px 0;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td style="color:#64748b;font-size:12px;font-family:Arial,Helvetica,sans-serif;">User</td><td style="color:#0f172a;font-size:12px;font-weight:600;text-align:right;font-family:Arial,Helvetica,sans-serif;">{{optional($ticket->user)->name ?? 'Unknown User'}}</td></tr></table></td></tr>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr><td style="padding:7px 0 0;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td style="color:#64748b;font-size:12px;font-family:Arial,Helvetica,sans-serif;">Email</td><td style="color:#0f172a;font-size:12px;font-weight:500;text-align:right;font-family:Arial,Helvetica,sans-serif;">{{optional($ticket->user)->email ?? 'No Email'}}</td></tr></table></td></tr>
                          </table>
                        </td>
                      </tr>
                    </table>

                    <!-- MESSAGE BOX -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;margin-bottom:16px;">
                      <tr>
                        <td style="padding:14px;">
                          <p style="color:#0f172a;font-size:12px;font-weight:700;margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;">📝 Message</p>
                          <p style="color:#475569;font-size:12px;line-height:1.6;margin:0;white-space:pre-line;font-family:Arial,Helvetica,sans-serif;">{{$messageContent}}</p>
                        </td>
                      </tr>
                    </table>

                    <!-- ACTION REQUIRED -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:12px;">
                      <tr>
                        <td style="background:#eff6ff;border-left:3px solid #3b82f6;padding:10px 12px;border-radius:0 6px 6px 0;">
                          <p style="color:#1e40af;font-size:11px;margin:0;font-weight:500;font-family:Arial,Helvetica,sans-serif;">📋 Please log in to the admin panel to respond to this ticket.</p>
                        </td>
                      </tr>
                    </table>

                    <!-- CTA BUTTON -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:16px 0;">
                      <tr><td align="center"><a href="{{url('/admin/support/'.$ticket->id)}}" style="display:inline-block;background:#4f46e5;color:#ffffff;text-decoration:none;padding:11px 24px;border-radius:8px;font-weight:600;font-size:12px;font-family:Arial,Helvetica,sans-serif;">View Ticket →</a></td></tr>
                    </table>

                    <!-- DIVIDER -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:16px 0;">
                      <tr><td style="height:1px;background:#e2e8f0;font-size:0;line-height:0;">&nbsp;</td></tr>
                    </table>

                    <!-- SUPPORT -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td align="center">
                          <p style="color:#64748b;font-size:11px;margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;">Admin Dashboard — Support Management</p>
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
                    <p style="color:#94a3b8;font-size:10px;margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;">Admin Notification System</p>
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
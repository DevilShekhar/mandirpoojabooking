<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temple Visit - We Welcome You Again</title>
</head>
<body style="margin:0; padding:20px; background:#f5f5f5; font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:auto; background:#ffffff; border-radius:8px; overflow:hidden;">
        <tr>
            <td style="background:#ed741f; padding:25px; text-align:center; color:#ffffff;">
                <h2 style="margin:0;">🙏 Temple Visit Update</h2>
                <p style="margin:8px 0 0; font-size:14px;">
                    We Look Forward to Welcoming You Again!
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding:30px; color:#333333; font-size:15px; line-height:1.7;">
                <h3>Dear {{ $ticketBooking->member_name }},</h3>
                <p>
                    Warm greetings from {{ config('app.name') }}!
                </p>
                <p>
                    We hope you and your family are doing well and are blessed
                    with happiness, peace, and good health.
                </p>
                <p>
                    As per our booking records, your scheduled visit to
                    <strong>{{ $ticketBooking->temple->name ?? 'the temple' }}</strong>
                    on
                    <strong>
                        {{ \Carbon\Carbon::parse($ticketBooking->booking_date)->format('d M Y') }}
                    </strong>
                    was not recorded as verified.
                </p>
                <p>
                    We understand that sometimes plans change due to
                    personal reasons or unforeseen circumstances.
                    We sincerely hope everything is well.
                </p>
                <div style="background:#fff7f0; border-left:4px solid #ed741f; padding:15px; margin:22px 0;">
                    <h3 style="color:#ed741f; margin:0 0 8px;">
                        We Would Love to Welcome You Again!
                    </h3>
                    <p style="margin:0;">
                        If you could not visit the temple on your scheduled date,
                        we warmly invite you to make a fresh booking at your
                        convenience and seek the divine blessings.
                    </p>
                </div>
                <h3 style="color:#ed741f;">Your Ticket Booking Details</h3>
                <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse; border:1px solid #eeeeee;">
                    <tr>
                        <td style="border-bottom:1px solid #eeeeee;">
                            <strong>Booking Reference</strong>
                        </td>
                        <td style="border-bottom:1px solid #eeeeee;">
                            {{ $ticketBooking->booking_reference }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #eeeeee;">
                            <strong>Temple Name</strong>
                        </td>
                        <td style="border-bottom:1px solid #eeeeee;">
                            {{ $ticketBooking->temple->name ?? 'Temple' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Visit Date</strong>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($ticketBooking->booking_date)->format('d M Y') }}
                        </td>
                    </tr>
                </table>
                <p style="margin-top:25px;">
                    If you have already visited the temple but your ticket
                    was not verified, please contact our support team.
                    We will be happy to assist you.
                </p>
                <p>
                    May the divine blessings bring happiness, prosperity,
                    good health, and peace to you and your family.
                </p>
                <p>
                    Thank you for choosing {{ config('app.name') }}.
                    We look forward to welcoming you again for a blessed
                    temple visit.
                </p>
                <p style="margin-top:25px;">
                    With warm regards and divine blessings,<br>
                    <strong>Team {{ config('app.name') }}</strong>
                    <br>🙏
                </p>
            </td>
        </tr>
        <tr>
            <td style="background:#ed741f; padding:15px; text-align:center;color:#777777; font-size:12px; line-height:1.6;">
                This is an automatically generated email.<br>
                Please do not reply directly to this message.
                <br><br>
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
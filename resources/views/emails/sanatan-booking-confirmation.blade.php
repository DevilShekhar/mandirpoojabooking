<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sanatan Event Booking Confirmation</title>
</head>
<body style="margin:0; padding:20px; background:#f5f5f5; font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:650px; margin:auto; background:#ffffff; border:1px solid #eeeeee;">
        <tr>
            <td style="background:#ed741f; padding:25px; text-align:center; color:#ffffff;">
                <h2 style="margin:0;">Sanatan Event Booking Confirmation</h2>
                <p style="margin:8px 0 0;">Your booking has been confirmed!</p>
            </td>
        </tr>
        <tr>
            <td style="padding:25px; color:#333333;">
                <h3>Hello {{ $customer->name ?? 'Devotee' }},</h3>
                <p>
                    Thank you for booking with us. Your payment has been successfully
                    received and your Sanatan event booking is confirmed.
                </p>
                <h3 style="color:#ed741f;">Booking Details</h3>
                <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Booking ID</strong></td>
                        <td style="border:1px solid #dddddd;">{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Event Name</strong></td>
                        <td style="border:1px solid #dddddd;">{{ $sanatan->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Event Date</strong></td>
                        <td style="border:1px solid #dddddd;">
                            {{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('d M Y') : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Event Time</strong></td>
                        <td style="border:1px solid #dddddd;">
                            {{ $booking->event_time ? \Carbon\Carbon::parse($booking->event_time)->format('h:i A') : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Customer Name</strong></td>
                        <td style="border:1px solid #dddddd;">{{ $customer->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Email</strong></td>
                        <td style="border:1px solid #dddddd;">{{ $customer->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Booking Amount</strong></td>
                        <td style="border:1px solid #dddddd;">
                            ₹{{ number_format((float) $booking->amount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Payment Status</strong></td>
                        <td style="border:1px solid #dddddd; color:green;">
                            {{ ucfirst($booking->payment_status ?? 'Paid') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #dddddd;"><strong>Payment ID</strong></td>
                        <td style="border:1px solid #dddddd;">
                            {{ $booking->razorpay_payment_id ?? 'N/A' }}
                        </td>
                    </tr>
                </table>
                <h3 style="color:#ed741f; margin-top:25px;">Your Booking OTP</h3>
                <div style="background:#fff4e8; padding:18px; text-align:center; border:1px dashed #ed741f;">
                    <h1 style="color:#ed741f; letter-spacing:8px; margin:0;">
                        {{ $booking->sanatan_otp ?? 'N/A' }}
                    </h1>
                    <p style="margin-bottom:0;">Keep this OTP safe for your booking.</p>
                </div>
                <p style="margin-top:25px;">
                    Thank you for choosing Sanatan Events.
                </p>
                <p>
                    Regards,<br>
                    <strong>Sanatan Events Team</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td style="background:#f5f5f5; padding:15px; text-align:center; color:#777777; font-size:12px;">
                This is an automated booking confirmation email.
            </td>
        </tr>
    </table>
</body>
</html>
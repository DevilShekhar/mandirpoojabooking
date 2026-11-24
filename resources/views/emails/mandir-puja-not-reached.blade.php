
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandir Puja - Not Reached</title>
</head>

<body style="margin:0; padding:20px; background-color:#f4f4f4; font-family:Arial, sans-serif;">

    <div style="max-width:650px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden;">

        <div style="background:#ed741f; padding:25px; text-align:center; color:#ffffff;">
            <h2 style="margin:0;">Mandir Puja Booking Update</h2>
        </div>

        <div style="padding:30px; color:#333333;">

            <p>Dear <strong>{{ $booking->customer_name ?? 'Devotee' }}</strong>,</p>

            <p>
                We noticed that the verification process for your Mandir Puja
                booking has not been completed.
            </p>

            <p>
                As the required OTP verification is still pending, your booking
                has been marked as <strong>Not Reached</strong>.
            </p>

            <h3 style="color:#ed741f; border-bottom:1px solid #eeeeee; padding-bottom:10px;">
                Booking Details
            </h3>

            <table style="width:100%; border-collapse:collapse;">

                <tr>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        <strong>Booking Number</strong>
                    </td>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        {{ $booking->booking_number ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        <strong>Customer Name</strong>
                    </td>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        {{ $booking->customer_name ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        <strong>Puja Name</strong>
                    </td>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        {{ $booking->puja?->title ?? $booking->puja?->name ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        <strong>Temple</strong>
                    </td>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        {{ $booking->temple?->name ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        <strong>Schedule Date</strong>
                    </td>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        {{ $booking->schedule_date ? $booking->schedule_date->format('d-m-Y') : 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        <strong>Schedule Time</strong>
                    </td>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        {{ $booking->schedule_time ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        <strong>Booking Status</strong>
                    </td>
                    <td style="padding:10px; border-bottom:1px solid #eeeeee;">
                        <span style="color:#dc3545; font-weight:bold;">
                            Not Reached
                        </span>
                    </td>
                </tr>

            </table>

            <p style="margin-top:25px;">
                If you believe this status has been updated incorrectly, please
                contact our support team.
            </p>

            <p>
                Thank you,<br>
                <strong>All Sanatan</strong>
            </p>

        </div>

        <div style="background:#f8f8f8; padding:15px; text-align:center; color:#777777; font-size:12px;">
            This is an automated email. Please do not reply directly to this email.
        </div>

    </div>

</body>
</html>

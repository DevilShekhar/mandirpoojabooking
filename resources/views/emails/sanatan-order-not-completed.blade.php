<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanatan Puja Not Completed</title>
</head>

<body style="margin:0;padding:20px 10px;background:#f5f5f5;font-family:Arial,Helvetica,sans-serif;color:#333;">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" border="0"
                style="max-width:600px;width:100%;background:#ffffff;border-radius:10px;overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td align="center" style="background:#e87522;padding:25px;">
                        <h1 style="margin:0;color:#ffffff;">Sanatan Puja</h1>
                        <p style="margin:8px 0 0;color:#ffffff;font-size:14px;">
                            Booking Notification
                        </p>
                    </td>
                </tr>

                <!-- Email Content -->
                <tr>
                    <td style="padding:25px;">

                        <h2 style="color:#e87522;">
                            Dear {{ $order->customer_name ?? 'Devotee' }},
                        </h2>

                        <p style="font-size:14px;line-height:24px;color:#555;">
                            Your Sanatan Puja booking was successfully confirmed.
                            However, our records indicate that the scheduled puja
                            completion verification has not yet been recorded.
                        </p>

                        <!-- Important Notification -->
                        <table width="100%" cellpadding="12" cellspacing="0"
                            style="background:#fff4e8;border-left:4px solid #e87522;">
                            <tr>
                                <td style="font-size:14px;line-height:22px;color:#9a4d12;">
                                    <strong>Important Notification</strong><br>
                                    Your scheduled puja date has passed, but completion
                                    verification is still pending in our system.
                                </td>
                            </tr>
                        </table>

                        <!-- Event Details -->
                        <h3 style="color:#e87522;margin-top:30px;border-bottom:1px solid #eee;padding-bottom:10px;">
                            Sanatan Event Details
                        </h3>

                        <table width="100%" cellpadding="10" cellspacing="0"
                            style="border-collapse:collapse;font-size:14px;">

                            <tr style="background:#fafafa;">
                                <td width="40%"><strong>Event Name</strong></td>
                                <td>{{ $order->event_name ?? 'N/A' }}</td>
                            </tr>

                            <tr>
                                <td><strong>Created By</strong></td>
                                <td>{{ $order->creator_name ?? 'N/A' }}</td>
                            </tr>

                            <tr style="background:#fafafa;">
                                <td><strong>Event Date</strong></td>
                                <td>
                                    {{ $order->event_date
                                        ? \Carbon\Carbon::parse($order->event_date)->format('d M Y')
                                        : 'N/A' }}
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Event Time</strong></td>
                                <td>{{ $order->event_time ?? 'N/A' }}</td>
                            </tr>

                        </table>

                        <!-- Booking Details -->
                        <h3 style="color:#e87522;margin-top:30px;border-bottom:1px solid #eee;padding-bottom:10px;">
                            Booking Details
                        </h3>

                        <table width="100%" cellpadding="10" cellspacing="0"
                            style="border-collapse:collapse;font-size:14px;">

                            <tr style="background:#fafafa;">
                                <td width="40%"><strong>Booking ID</strong></td>
                                <td>#{{ $order->id }}</td>
                            </tr>

                            <tr>
                                <td><strong>Booking Date</strong></td>
                                <td>
                                    {{ $order->created_at
                                        ? \Carbon\Carbon::parse($order->created_at)->format('d M Y h:i A')
                                        : 'N/A' }}
                                </td>
                            </tr>

                            <tr style="background:#fafafa;">
                                <td><strong>Booking Amount</strong></td>
                                <td>
                                    {{ $order->currency ?? 'INR' }}
                                    {{ number_format((float) $order->amount, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Payment Status</strong></td>
                                <td>{{ ucfirst($order->payment_status ?? 'N/A') }}</td>
                            </tr>

                            <tr style="background:#fafafa;">
                                <td><strong>Booking Status</strong></td>
                                <td style="color:#dc3545;font-weight:bold;">
                                    Not Completed
                                </td>
                            </tr>

                        </table>

                        <!-- Customer Details -->
                        <h3 style="color:#e87522;margin-top:30px;border-bottom:1px solid #eee;padding-bottom:10px;">
                            Customer Details
                        </h3>

                        <table width="100%" cellpadding="10" cellspacing="0"
                            style="border-collapse:collapse;font-size:14px;">

                            <tr style="background:#fafafa;">
                                <td width="40%"><strong>Customer Name</strong></td>
                                <td>{{ $order->customer_name ?? 'N/A' }}</td>
                            </tr>

                            <tr>
                                <td><strong>Email Address</strong></td>
                                <td>{{ $order->customer_email ?? 'N/A' }}</td>
                            </tr>

                        </table>

                        <!-- Completion Message -->
                        <h3 style="color:#dc3545;margin-top:30px;">
                            Puja Completion Pending
                        </h3>

                        <p style="font-size:14px;line-height:24px;color:#555;">
                            Since the scheduled puja date has passed and OTP
                            verification has not been recorded, your booking
                            status has been updated to
                            <strong>Not Completed</strong>.
                        </p>

                        <p style="font-size:14px;line-height:24px;color:#555;">
                            If you have already completed your puja, please contact
                            our support team so that we can verify and update your booking.
                        </p>

                        <p style="font-size:14px;line-height:24px;">
                            Thank you,<br>
                            <strong style="color:#e87522;">All Sanatan</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center"
                        style="background:#f7f7f7;padding:20px;border-top:1px solid #eee;">

                        <p style="margin:0;font-size:12px;color:#777;">
                            This is an automated notification regarding your Sanatan Puja booking.
                        </p>

                        <p style="margin:8px 0 0;font-size:12px;color:#999;">
                            &copy; {{ date('Y') }} Sanatan Puja. All Rights Reserved.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
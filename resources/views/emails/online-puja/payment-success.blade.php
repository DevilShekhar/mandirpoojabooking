<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Puja Payment Successful</title>
</head>

<body style="margin:0; padding:0; background:#f4f5f7; font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0"
       style="background:#f4f5f7; padding:30px 10px;">

    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                   style="max-width:600px; width:100%; background:#ffffff; border-radius:10px; overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td align="center"
                        style="background:#ed741f; padding:25px; color:#ffffff;">

                        <h1 style="margin:0; font-size:25px;">
                            All Sanatan
                        </h1>

                        <p style="margin:8px 0 0;">
                            Online Puja Booking
                        </p>

                    </td>
                </tr>

                <!-- Main Content -->
                <tr>
                    <td style="padding:30px;">

                        <h2 style="color:#28a745; text-align:center; margin-top:0;">
                            Payment Successful!
                        </h2>

                        <p style="font-size:15px; color:#444;">
                            Dear {{ $customer->name ?? 'Devotee' }},
                        </p>

                        <p style="font-size:15px; line-height:1.7; color:#555;">
                            Thank you for booking an Online Puja with All Sanatan.
                            Your payment has been successfully received.
                        </p>

                        <!-- Booking Details -->
                        <h3 style="color:#333; border-bottom:1px solid #eeeeee; padding-bottom:10px;">
                            Booking Details
                        </h3>

                        <table width="100%" cellpadding="10"
                               cellspacing="0"
                               style="font-size:14px; border-collapse:collapse;">

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Order Number
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $order->order_number ?? 'N/A' }}
                                </td>
                            </tr>

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Puja Name
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $puja->title ?? 'Online Puja' }}
                                </td>
                            </tr>

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Package
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $package->package_name ?? 'N/A' }}
                                </td>
                            </tr>

                        </table>

                        <!-- Puja Schedule -->
                        <h3 style="color:#333; border-bottom:1px solid #eeeeee; padding-bottom:10px; margin-top:25px;">
                            Puja Schedule Details
                        </h3>

                        <table width="100%" cellpadding="10"
                               cellspacing="0"
                               style="font-size:14px; border-collapse:collapse;">

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Puja Date
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $pujaDate ?? 'N/A' }}
                                </td>
                            </tr>

                            @if(!empty($pujaTime))
                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Puja Time
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $pujaTime }}
                                </td>
                            </tr>
                            @endif

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Puja Day
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $puja->puja_day ?? 'N/A' }}
                                </td>
                            </tr>

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Tithi
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $puja->tithi ?? 'N/A' }}
                                </td>
                            </tr>

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Muhurat
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $puja->muhurat ?? 'N/A' }}
                                </td>
                            </tr>

                            @if(!empty($muhuratEnd))
                            <tr>
                                <td style="color:#666;">
                                    Muhurat End Time
                                </td>

                                <td align="right" style="font-weight:bold;">
                                    {{ $muhuratEnd }}
                                </td>
                            </tr>
                            @endif

                        </table>

                        <!-- Payment Details -->
                        <h3 style="color:#333; border-bottom:1px solid #eeeeee; padding-bottom:10px; margin-top:25px;">
                            Payment Details
                        </h3>

                        <table width="100%" cellpadding="10"
                               cellspacing="0"
                               style="font-size:14px; border-collapse:collapse;">

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Amount Paid
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee;">
                                    ₹{{ number_format((float) $order->amount, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Payment ID
                                </td>

                                <td align="right"
                                    style="font-weight:bold; border-bottom:1px solid #eee; word-break:break-all;">
                                    {{ $paymentId }}
                                </td>
                            </tr>

                            <tr>
                                <td style="color:#666; border-bottom:1px solid #eee;">
                                    Payment Status
                                </td>

                                <td align="right"
                                    style="color:#28a745; font-weight:bold; border-bottom:1px solid #eee;">
                                    Successful
                                </td>
                            </tr>

                            <tr>
                                <td style="color:#666;">
                                    Booking Status
                                </td>

                                <td align="right"
                                    style="font-weight:bold; color:{{ $bookingStatus === 'confirmed' ? '#28a745' : '#e69500' }};">
                                    {{ ucfirst(str_replace('_', ' ', $bookingStatus)) }}
                                </td>
                            </tr>

                        </table>

                        <!-- Booking Confirmation Message -->
                        @if($bookingStatus === 'confirmed')

                            <p style="background:#eaf8ef; padding:15px; color:#237a3b; border-radius:5px; line-height:1.7; margin-top:25px;">
                                <strong>Booking Confirmed!</strong><br>
                                Your puja booking has been confirmed successfully.
                                Thank you for your devotion.
                            </p>

                        @else

                            <p style="background:#fff7e6; padding:15px; color:#946200; border-radius:5px; line-height:1.7; margin-top:25px;">
                                <strong>Booking Under Review</strong><br>
                                Your payment has been received successfully.
                                Your booking is currently under review.
                                Our team will contact you shortly.
                            </p>

                        @endif

                        <!-- Thank You -->
                        <p style="font-size:14px; color:#555; line-height:1.7; margin-top:25px;">
                            Thank you for choosing All Sanatan.
                            May divine blessings always be with you and your family.
                        </p>

                        <p style="margin-top:25px; color:#555; line-height:1.7;">
                            Regards,<br>
                            <strong>Team All Sanatan</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center"
                        style="background:#f8f8f8; padding:20px; color:#888; font-size:12px; line-height:1.7;">

                        This is an automated payment confirmation email.
                        Please do not reply to this email.

                        <br><br>

                        © {{ date('Y') }} All Sanatan. All rights reserved.

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>
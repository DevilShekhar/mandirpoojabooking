<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puja Booking Confirmation</title>
</head>
<body style="margin:0;padding:0;background:#fff8ed;color:#38251b;font-family:Arial,Helvetica,sans-serif;">
@php
    $includedServices = [];
    if (!empty($details['includes_sankalp'])) $includedServices[] = ['label' => 'Sankalp', 'icon' => '🪔'];
    if (!empty($details['includes_prasad'])) $includedServices[] = ['label' => 'Prasad', 'icon' => '🍚'];
    if (!empty($details['includes_video'])) $includedServices[] = ['label' => 'Puja Video', 'icon' => '▶'];
    if (!empty($details['includes_certificate'])) $includedServices[] = ['label' => 'Certificate', 'icon' => '▤'];

    $samagri = $details['package_samagri'] ?? [];
    if (is_string($samagri)) {
        $decodedSamagri = json_decode($samagri, true);
        $samagri = is_array($decodedSamagri) ? $decodedSamagri : array_filter(array_map('trim', explode(',', $samagri)));
    }
    $samagriItems = is_array($samagri)
        ? array_values(array_filter(array_map(fn ($item) => is_scalar($item) ? trim((string) $item) : json_encode($item), $samagri)))
        : [];

    $selectedProducts = $details['selected_products'] ?? [];
    if (is_string($selectedProducts)) {
        $decodedProducts = json_decode($selectedProducts, true);
        $selectedProducts = is_array($decodedProducts) ? $decodedProducts : [];
    }
    $selectedProducts = is_array($selectedProducts) ? $selectedProducts : [];

    $bookingNumber = $details['booking_number'] ?? 'N/A';
    $bookingDate = $details['schedule_date'] ?? $details['booking_date'] ?? 'N/A';
    $bookingTime = $details['schedule_time'] ?? $details['booking_time'] ?? 'N/A';
    $amountPaid = $details['amount'] ?? $details['total_amount'] ?? 0;
    $paymentId = $details['razorpay_payment_id'] ?? $details['payment_id'] ?? null;
    $orderId = $details['razorpay_order_id'] ?? $details['order_id'] ?? null;
@endphp

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#fff8ed;padding:16px 8px;">
<tr><td align="center">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="800" style="width:100%;max-width:800px;background:#ffffff;border:1px solid #f0d9b7;border-radius:14px;overflow:hidden;">

    <!-- Decorative hero. For email compatibility, the design uses a warm background and text rather than requiring an external image URL. -->
    <tr>
        <td style="padding:26px 34px 24px;background-color:#fff0d9;background-image:linear-gradient(115deg,#fff7e9 0%,#ffe6bd 56%,#f7c985 100%);">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="middle" style="padding-bottom:20px;">
                        <div style="font-size:12px;line-height:1.5;font-weight:700;letter-spacing:2px;color:#7d3718;text-transform:uppercase;">🪷 {{ config('app.name', 'Sanatan Seva') }}</div>
                        <div style="margin-top:3px;font-size:9px;letter-spacing:3px;color:#655047;">CONNECTING DEVOTION</div>
                    </td>
                    <td align="right" valign="middle" style="padding-bottom:20px;color:#a94a17;font-size:27px;">ॐ</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h1 style="margin:0 0 8px;color:#963b12;font-family:Georgia,'Times New Roman',serif;font-size:31px;line-height:1.15;">Puja Booking Confirmed</h1>
                        <p style="margin:0;color:#5d4234;font-size:14px;line-height:1.65;">Your devotion brings blessings. Thank you for choosing<br>us for your sacred puja.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Confirmation message -->
    <tr>
        <td style="padding:12px 16px 10px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #f0d3a6;border-radius:12px;background:#fffdf8;">
                <tr>
                    <td width="58" valign="middle" align="center" style="padding:15px 8px 15px 14px;font-size:27px;">🙏</td>
                    <td valign="middle" style="padding:15px 10px;">
                        <p style="margin:0 0 5px;font-family:Georgia,'Times New Roman',serif;font-size:15px;font-weight:700;color:#26314b;">Namaste {{ $details['customer_name'] ?? 'Devotee' }},</p>
                        <p style="margin:0;color:#5e6170;font-size:12px;line-height:1.6;">Your payment has been received successfully and your puja booking is confirmed. Please keep this email for your records.</p>
                    </td>
                    <td width="190" align="center" valign="middle" style="padding:12px 14px;">
                        <div style="padding:12px 10px;border-radius:10px;background:#e0f4e3;color:#187443;">
                            <div style="font-size:17px;font-weight:700;">✓ <span style="font-size:12px;">PAYMENT SUCCESSFUL</span></div>
                            <div style="margin-top:3px;font-size:10px;">Your booking is confirmed</div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Booking reference, date, time and OTP -->
    <tr>
        <td style="padding:0 16px 12px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:2px solid #ef9e2e;border-radius:12px;background:#fffaf1;">
                <tr>
                    <td width="28%" valign="top" style="padding:17px 12px;border-right:1px solid #f1d8b5;">
                        <div style="font-size:20px;color:#a9440b;">▦ <span style="font-size:12px;color:#352a27;font-weight:700;">Booking Reference</span></div>
                        <div style="margin-top:10px;font-size:12px;font-weight:700;color:#8e3814;word-break:break-word;">{{ $bookingNumber }}</div>
                    </td>
                    <td width="21%" valign="top" style="padding:17px 12px;border-right:1px solid #f1d8b5;">
                        <div style="font-size:20px;color:#a9440b;">▦ <span style="font-size:12px;color:#352a27;font-weight:700;">Puja Date</span></div>
                        <div style="margin-top:10px;font-size:12px;color:#45404b;">{{ $bookingDate }}</div>
                    </td>
                    <td width="21%" valign="top" style="padding:17px 12px;border-right:1px solid #f1d8b5;">
                        <div style="font-size:20px;color:#a9440b;">◷ <span style="font-size:12px;color:#352a27;font-weight:700;">Puja Time</span></div>
                        <div style="margin-top:10px;font-size:12px;color:#45404b;">{{ $bookingTime }}</div>
                    </td>
                    <td width="30%" valign="top" style="padding:17px 12px;">
                        <div style="font-size:12px;color:#352a27;font-weight:700;">▤ &nbsp;Samagri OTP</div>
                        @if(filled($details['samgri_otp'] ?? null))
                            <div style="display:inline-block;margin-top:7px;padding:7px 10px;background:#26854e;border-radius:7px;color:#ffffff;font-size:15px;font-weight:700;letter-spacing:2px;">{{ $details['samgri_otp'] }}</div>
                            <div style="margin-top:6px;font-size:10px;line-height:1.4;color:#61564d;">Share this OTP with the authorised team for samagri delivery/verification.</div>
                        @else
                            <div style="margin-top:8px;font-size:11px;color:#82766b;">OTP will be provided when available.</div>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Puja and customer details -->
    <tr>
        <td style="padding:0 16px 12px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="50%" valign="top" style="padding:0 8px 0 0;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #f2dfc8;border-radius:10px;overflow:hidden;">
                            <tr><td colspan="2" style="padding:11px 13px;background:#fff8ef;color:#913b16;font-family:Georgia,'Times New Roman',serif;font-size:15px;font-weight:700;">🛕 &nbsp;Puja Details</td></tr>
                            @php
                                $pujaRows = [
                                    'Puja Name' => $details['puja_name'] ?? 'N/A',
                                    'Package Name' => $details['package_name'] ?? 'N/A',                                                                 
                                ];
                            @endphp
                            @foreach($pujaRows as $label => $value)
                            <tr>
                                <td width="38%" style="padding:7px 10px;border-top:1px solid #f0ece7;font-size:11px;font-weight:700;color:#3f3936;">{{ $label }}</td>
                                <td style="padding:7px 10px;border-top:1px solid #f0ece7;font-size:11px;color:#4d5061;">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td width="50%" valign="top" style="padding:0 0 0 8px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #f2dfc8;border-radius:10px;overflow:hidden;">
                            <tr><td colspan="2" style="padding:11px 13px;background:#fff8ef;color:#913b16;font-family:Georgia,'Times New Roman',serif;font-size:15px;font-weight:700;">♟ &nbsp;Your Details</td></tr>
                            @php
                                $customerRows = [
                                    'Name' => $details['customer_name'] ?? 'N/A',
                                    'Email' => $details['customer_email'] ?? 'N/A',
                                    'Mobile' => $details['customer_mobile'] ?? 'N/A',                                    
                                ];
                            @endphp
                            @foreach($customerRows as $label => $value)
                            <tr>
                                <td width="32%" style="padding:8px 10px;border-top:1px solid #f0ece7;font-size:11px;font-weight:700;color:#3f3936;">{{ $label }}</td>
                                <td style="padding:8px 10px;border-top:1px solid #f0ece7;font-size:11px;color:#4d5061;word-break:break-word;">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Selected products -->
    @if(count($selectedProducts) > 0)
    <tr>
        <td style="padding:0 16px 12px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #f2dfc8;border-radius:10px;overflow:hidden;">
                <tr><td colspan="4" style="padding:11px 13px;background:#fff8ef;color:#913b16;font-family:Georgia,'Times New Roman',serif;font-size:15px;font-weight:700;">🎁 &nbsp;Selected Puja Products (Samagri)</td></tr>
                <tr style="background:#ffebcf;">
                    <th align="left" style="padding:9px 8px;font-size:10px;color:#40352e;">#</th>
                    <th align="left" style="padding:9px 8px;font-size:10px;color:#40352e;">Product Name</th>
                    <th align="center" style="padding:9px 8px;font-size:10px;color:#40352e;">Quantity</th>
                    <th align="right" style="padding:9px 8px;font-size:10px;color:#40352e;">Unit Price / Total</th>
                </tr>
                @foreach($selectedProducts as $index => $product)
                    @php
                        $product = is_array($product) ? $product : [];
                        $quantity = max(1, (int) ($product['quantity'] ?? 1));
                        $unitPrice = max(0, (float) ($product['price'] ?? 0));
                        $lineTotal = max(0, (float) ($product['line_total'] ?? ($unitPrice * $quantity)));
                    @endphp
                    <tr>
                        <td style="padding:9px 8px;border-top:1px solid #f1e7dc;font-size:11px;color:#4d5061;">{{ $index + 1 }}</td>
                        <td style="padding:9px 8px;border-top:1px solid #f1e7dc;font-size:11px;color:#4d5061;">{{ $product['name'] ?? 'Puja Samagri' }}@if(filled($product['unit'] ?? null)) ({{ $product['unit'] }})@endif</td>
                        <td align="center" style="padding:9px 8px;border-top:1px solid #f1e7dc;font-size:11px;color:#4d5061;">{{ $quantity }}</td>
                        <td align="right" style="padding:9px 8px;border-top:1px solid #f1e7dc;font-size:11px;color:#4d5061;">₹{{ number_format($unitPrice, 2) }} / <strong>₹{{ number_format($lineTotal, 2) }}</strong></td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
    @endif

    <!-- Package and payment information -->
    <tr>
        <td style="padding:0 16px 14px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>                  
                    <td width="100%" valign="top" style="padding:0;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                            style="border:1px solid #f2dfc8;border-radius:10px;overflow:hidden;">
                            <tr>
                                <td colspan="2"
                                    style="padding:11px 13px;background:#fff8ef;color:#913b16;
                                        font-family:Georgia,'Times New Roman',serif;
                                        font-size:15px;font-weight:700;">
                                    ▤ &nbsp;Payment Details
                                </td>
                            </tr>

                            @php
                                $paymentRows = [
                                    'Payment ID' => $paymentId,
                                    'Order ID' => $orderId,
                                    'Amount Paid' => '₹' . number_format((float) $amountPaid, 2),
                                    'Payment Date' => $details['payment_date'] ?? null,
                                    'Payment Method' => $details['payment_method'] ?? 'Razorpay (Online Payment)',
                                ];
                            @endphp

                            @foreach($paymentRows as $label => $value)
                                @if(filled($value))
                                    <tr>
                                        <td width="35%"
                                            style="padding:9px 12px;border-top:1px solid #f0ece7;
                                                font-size:12px;font-weight:700;color:#3f3936;">
                                            {{ $label }}
                                        </td>
                                        <td
                                            style="padding:9px 12px;border-top:1px solid #f0ece7;
                                                font-size:12px;color:#4d5061;word-break:break-word;">
                                            {{ $value }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td align="center" style="padding:18px 18px 16px;background:#8d3d17;background-image:linear-gradient(100deg,#7b3214,#a74b1c);color:#fff6e9;">
            <div style="font-size:19px;color:#ffc75e;">✺</div>
            <p style="margin:4px 0 3px;font-family:Georgia,'Times New Roman',serif;font-size:14px;font-weight:700;">Thank you for being a part of this sacred journey.</p>
            <p style="margin:0;font-size:10px;line-height:1.6;color:#ffe2c4;">May the blessings of the Divine be with you and your family always.</p>
        </td>
    </tr>
    <tr>
        <td style="padding:14px 18px;background:#fffaf3;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="33%" valign="top" align="center" style="padding:5px;font-size:10px;line-height:1.6;color:#4f443e;">
                        <strong style="color:#873a18;">Need Help?</strong><br>Our support team is here for you.
                    </td>
                    <td width="34%" valign="top" align="center" style="padding:5px;border-left:1px solid #ecd7bf;border-right:1px solid #ecd7bf;font-size:10px;line-height:1.6;color:#4f443e;">
                        <strong style="color:#873a18;">Email Support</strong><br>{{ config('mail.from.address', 'support@yourdomain.com') }}
                    </td>
                    <td width="33%" valign="top" align="center" style="padding:5px;font-size:10px;line-height:1.6;color:#4f443e;">
                        <strong style="color:#873a18;">Phone Support</strong><br> +91 9767555737
                    </td>
                </tr>
            </table>
            <p style="margin:12px 0 0;text-align:center;font-size:9px;line-height:1.5;color:#9a887a;">This is an automated confirmation email. Please retain your booking reference for future communication.</p>
        </td>
    </tr>
</table>
</td></tr>
</table>
</body>
</html>

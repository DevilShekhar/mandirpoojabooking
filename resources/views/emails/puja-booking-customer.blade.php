<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puja Booking Confirmation</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f6f8; font-family:Arial, Helvetica, sans-serif; color:#263238;">

    @php
        $includedServices = [];

        if (!empty($details['includes_sankalp'])) {
            $includedServices[] = 'Sankalp';
        }

        if (!empty($details['includes_prasad'])) {
            $includedServices[] = 'Prasad';
        }

        if (!empty($details['includes_video'])) {
            $includedServices[] = 'Puja video';
        }

        if (!empty($details['includes_certificate'])) {
            $includedServices[] = 'Certificate';
        }

        $samagri = $details['package_samagri'] ?? [];
        if (is_string($samagri)) {
            $decodedSamagri = json_decode($samagri, true);
            $samagri = is_array($decodedSamagri) ? $decodedSamagri : array_filter(array_map('trim', explode(',', $samagri)));
        }

        $samagriItems = is_array($samagri)
            ? array_values(array_filter(array_map(
                fn ($item) => is_scalar($item) ? trim((string) $item) : json_encode($item),
                $samagri
            )))
            : [];

        $hasDiscountPrice = isset($details['discount_price'])
            && $details['discount_price'] !== ''
            && is_numeric($details['discount_price'])
            && (float) $details['discount_price'] > 0;

        $hasPackageDescription = filled($details['package_short_description'] ?? null)
            || filled($details['package_description'] ?? null);
    @endphp

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f5f6f8; padding:24px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="width:100%; max-width:600px; background-color:#ffffff; border:1px solid #e7e9ed; border-radius:10px; overflow:hidden;">

                    <!-- Brand header -->
                    <tr>
                        <td style="background-color:#8b3f18; padding:26px 30px; text-align:center;">
                            <p style="margin:0 0 6px; color:#ffe5c7; font-size:12px; letter-spacing:2px; text-transform:uppercase;">
                                {{ config('app.name', 'Puja Booking') }}
                            </p>
                            <h1 style="margin:0; color:#ffffff; font-size:25px; line-height:1.3; font-weight:700;">
                                Puja Booking Confirmed
                            </h1>
                        </td>
                    </tr>

                    <!-- Confirmation message -->
                    <tr>
                        <td style="padding:28px 30px 18px;">
                            <p style="margin:0 0 12px; font-size:17px; color:#263238;">
                                Namaste {{ $details['customer_name'] ?? 'Devotee' }},
                            </p>
                            <p style="margin:0; font-size:14px; line-height:1.8; color:#59636e;">
                                Your payment has been received successfully and your puja booking is confirmed. Please keep this email for your records.
                            </p>
                        </td>
                    </tr>

                    <!-- Booking reference -->
                    <tr>
                        <td style="padding:0 30px 22px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#fff8f1; border:1px solid #f3dfcc; border-radius:8px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <p style="margin:0 0 5px; color:#80502e; font-size:11px; text-transform:uppercase; letter-spacing:1px;">
                                            Booking reference
                                        </p>
                                        <p style="margin:0; color:#8b3f18; font-size:20px; font-weight:700; word-break:break-word;">
                                            {{ $details['booking_number'] ?? 'N/A' }}
                                        </p>
                                    </td>
                                    <td align="right" valign="middle" style="padding:16px 18px;">
                                        <span style="display:inline-block; background-color:#e6f5eb; color:#236b3b; padding:6px 10px; border-radius:20px; font-size:12px; font-weight:700;">
                                            PAYMENT SUCCESSFUL
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Booking details -->
                    <tr>
                        <td style="padding:0 30px 24px;">
                            <h2 style="margin:0 0 12px; padding-bottom:9px; border-bottom:1px solid #e8eaed; color:#263238; font-size:16px;">
                                Booking details
                            </h2>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td style="padding:9px 0; color:#69737d; font-size:13px; width:42%;">Puja</td>
                                    <td style="padding:9px 0; color:#263238; font-size:13px; font-weight:600;">{{ $details['puja_name'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:9px 0; border-top:1px solid #f0f1f3; color:#69737d; font-size:13px;">Package</td>
                                    <td style="padding:9px 0; border-top:1px solid #f0f1f3; color:#263238; font-size:13px; font-weight:600;">{{ $details['package_name'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:9px 0; border-top:1px solid #f0f1f3; color:#69737d; font-size:13px;">Date</td>
                                    <td style="padding:9px 0; border-top:1px solid #f0f1f3; color:#263238; font-size:13px;">{{ $details['booking_date'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:9px 0; border-top:1px solid #f0f1f3; color:#69737d; font-size:13px;">Time</td>
                                    <td style="padding:9px 0; border-top:1px solid #f0f1f3; color:#263238; font-size:13px;">{{ $details['booking_time'] ?? 'N/A' }}</td>
                                </tr>
                                @if(filled($details['person_count'] ?? null))
                                    <tr>
                                        <td style="padding:9px 0; border-top:1px solid #f0f1f3; color:#69737d; font-size:13px;">Persons covered</td>
                                        <td style="padding:9px 0; border-top:1px solid #f0f1f3; color:#263238; font-size:13px;">{{ $details['person_count'] }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- Package information -->
                    <tr>
                        <td style="padding:0 30px 24px;">
                            <h2 style="margin:0 0 12px; padding-bottom:9px; border-bottom:1px solid #e8eaed; color:#263238; font-size:16px;">
                                Your package
                            </h2>

                            @if(filled($details['package_short_description'] ?? null))
                                <p style="margin:0 0 10px; color:#59636e; font-size:13px; line-height:1.7;">
                                    {{ $details['package_short_description'] }}
                                </p>
                            @endif

                            @if(filled($details['package_description'] ?? null))
                                <p style="margin:0 0 14px; color:#59636e; font-size:13px; line-height:1.7;">
                                    {{ $details['package_description'] }}
                                </p>
                            @endif

                            @if(count($includedServices) > 0)
                                <p style="margin:0 0 8px; color:#263238; font-size:13px; font-weight:700;">
                                    Included services
                                </p>
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                    @foreach($includedServices as $service)
                                        <tr>
                                            <td style="padding:5px 0; color:#3f5d49; font-size:13px;">
                                                <span style="color:#27834b; font-weight:700;">&#10003;</span>
                                                &nbsp;{{ $service }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            @if(count($samagriItems) > 0)
                                <p style="margin:15px 0 7px; color:#263238; font-size:13px; font-weight:700;">
                                    Puja samagri
                                </p>
                                <p style="margin:0; color:#59636e; font-size:13px; line-height:1.8;">
                                    {{ implode(', ', $samagriItems) }}
                                </p>
                            @endif

                            @if(!empty($details['package_features']) && is_array($details['package_features']))
                                @php
                                    $visibleFeatures = collect($details['package_features'])->filter(function ($feature) {
                                        if (is_array($feature)) {
                                            return filled($feature['name'] ?? $feature['title'] ?? null)
                                                || filled($feature['description'] ?? null);
                                        }
                                        return filled($feature);
                                    });
                                @endphp

                                @if($visibleFeatures->isNotEmpty())
                                    <p style="margin:15px 0 7px; color:#263238; font-size:13px; font-weight:700;">
                                        Package features
                                    </p>
                                    <ul style="margin:0; padding-left:19px; color:#59636e; font-size:13px; line-height:1.8;">
                                        @foreach($visibleFeatures as $feature)
                                            <li>
                                                @if(is_array($feature))
                                                    {{ $feature['name'] ?? $feature['title'] ?? '' }}
                                                    @if(filled($feature['description'] ?? null))
                                                        — {{ $feature['description'] }}
                                                    @endif
                                                @else
                                                    {{ $feature }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </td>
                    </tr>

                    <!-- Payment summary -->
                    <tr>
                        <td style="padding:0 30px 28px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f8f9fb; border:1px solid #e8eaed; border-radius:8px;">
                                <tr>
                                    <td style="padding:18px;">
                                        <p style="margin:0 0 5px; color:#69737d; font-size:12px;">Amount paid</p>
                                        <p style="margin:0; color:#263238; font-size:26px; font-weight:700;">
                                            ₹{{ number_format((float) ($details['amount'] ?? 0), 2) }}
                                        </p>
                                        @if(filled($details['payment_id'] ?? null))
                                            <p style="margin:10px 0 0; color:#69737d; font-size:12px; word-break:break-word;">
                                                Payment ID: {{ $details['payment_id'] }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:22px 30px; background-color:#f8f9fb; border-top:1px solid #e8eaed; text-align:center;">
                            <p style="margin:0 0 8px; color:#59636e; font-size:13px; line-height:1.7;">
                                Thank you for choosing {{ config('app.name', 'Puja Booking') }}.
                            </p>
                            <p style="margin:0 0 12px; color:#59636e; font-size:13px;">
                                May God bless you and your family.
                            </p>
                            <p style="margin:0; color:#8a929b; font-size:11px; line-height:1.6;">
                                This is an automated booking confirmation email. Please retain your booking reference for future communication.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>

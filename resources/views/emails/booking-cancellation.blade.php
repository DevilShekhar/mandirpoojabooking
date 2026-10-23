
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subject }}</title>
</head>
<body style="margin:0; padding:20px; font-family:Arial,sans-serif; color:#333; background:#f5f5f5;">

    <div style="max-width:650px; margin:auto; padding:25px; background:#fff; border-radius:8px;">

        <h2 style="color:#b42318;">{{ $subject }}</h2>

        <p>Hello {{ $recipientName }},</p>

        <p>{{ $messageText }}</p>

        <table width="100%" cellpadding="10" cellspacing="0"
               style="border-collapse:collapse; margin-top:20px;">

            <tr>
                <td style="border:1px solid #ddd;"><strong>Booking Number</strong></td>
                <td style="border:1px solid #ddd;">{{ $bookingNumber }}</td>
            </tr>

            <tr>
                <td style="border:1px solid #ddd;"><strong>Customer</strong></td>
                <td style="border:1px solid #ddd;">{{ $customerName }}</td>
            </tr>

            <tr>
                <td style="border:1px solid #ddd;"><strong>Pandit</strong></td>
                <td style="border:1px solid #ddd;">{{ $panditName }}</td>
            </tr>

            <tr>
                <td style="border:1px solid #ddd;"><strong>Puja</strong></td>
                <td style="border:1px solid #ddd;">{{ $pujaName }}</td>
            </tr>

            <tr>
                <td style="border:1px solid #ddd;"><strong>Request Status</strong></td>
                <td style="border:1px solid #ddd;">{{ ucfirst($status) }}</td>
            </tr>

            <tr>
                <td style="border:1px solid #ddd;"><strong>Cancellation Reason</strong></td>
                <td style="border:1px solid #ddd;">{{ $reason }}</td>
            </tr>

            @if($reviewNotes)
                <tr>
                    <td style="border:1px solid #ddd;"><strong>Admin Notes</strong></td>
                    <td style="border:1px solid #ddd;">{{ $reviewNotes }}</td>
                </tr>
            @endif
        </table>

        <p style="margin-top:25px;">
            Thank you,<br>
            <strong>{{ config('app.name') }} Team</strong>
        </p>

    </div>

</body>
</html>

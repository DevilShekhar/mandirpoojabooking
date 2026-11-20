
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thank You for Visiting Us</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">

    <div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:10px;">

        <h2 style="color:#ed741f; text-align:center;">
            Thank You for Visiting Us!
        </h2>

        <p>Hello {{ $booking->user->name ?? 'Devotee' }},</p>

        <p>
            Thank you for visiting
            <strong>{{ $booking->temple->name ?? 'our temple' }}</strong>.
        </p>

        <p>
            We hope you had a peaceful and wonderful Darshan experience.
            Your ticket booking has been successfully completed.
        </p>

        <p>
            We sincerely appreciate your visit and look forward
            to welcoming you and your family again.
        </p>

        <p>
            May God bless you and your family.
        </p>

        <br>

        <p>
            Warm Regards,<br>
            <strong>Temple Management Team</strong>
        </p>

    </div>

</body>
</html>
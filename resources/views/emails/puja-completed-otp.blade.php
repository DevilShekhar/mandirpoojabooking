
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Puja Completion OTP</title>
</head>

<body style="font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px;">

<div style="max-width: 650px; margin: auto; background: #ffffff; padding: 25px; border-radius: 8px;">

    <h2 style="color: #ed741f;">
        Puja Samagri Delivery Confirmation
    </h2>

    <p>Hello {{ $bookingDetails['customer_name'] }},</p>

    <p>
        Your Samagri booking has been successfully verified.
        Please keep the following OTP ready for delivery completion.
    </p>

    <div style="background: #fff4e9; padding: 20px; text-align: center; border-radius: 8px;">

        <p>Your Puja Completion OTP</p>

        <h1 style="color: #ed741f; letter-spacing: 8px;">
            {{ $bookingDetails['otp'] }}
        </h1>

    </div>

    <h3>Booking Details</h3>

    <table cellpadding="9" cellspacing="0" width="100%" border="1"
           style="border-collapse: collapse; border-color: #eeeeee;">

        <tr>
            <td><strong>Booking ID</strong></td>
            <td>{{ $bookingDetails['booking_id'] }}</td>
        </tr>

        <tr>
            <td><strong>Customer Name</strong></td>
            <td>{{ $bookingDetails['customer_name'] }}</td>
        </tr>

        <tr>
            <td><strong>Mobile</strong></td>
            <td>{{ $bookingDetails['customer_mobile'] }}</td>
        </tr>

        <tr>
            <td><strong>Puja Name</strong></td>
            <td>{{ $bookingDetails['puja_name'] }}</td>
        </tr>

        <tr>
            <td><strong>Temple Name</strong></td>
            <td>{{ $bookingDetails['temple_name'] }}</td>
        </tr>

    </table>

    <h3 style="margin-top: 25px;">Selected Samagri Products</h3>

    <table cellpadding="9" cellspacing="0" width="100%" border="1"
           style="border-collapse: collapse; border-color: #eeeeee;">

        <thead style="background: #fff4e9;">
            <tr>
                <th align="left">Product</th>
                <th align="right">Unit Price</th>
                <th align="center">Quantity</th>
                <th align="right">Amount</th>
            </tr>
        </thead>

        <tbody>

            @forelse($bookingDetails['products'] as $product)

                <tr>
                    <td>
                        {{ $product['name'] }}
                    </td>

                    <td align="right">
                        ₹{{ number_format($product['price'], 2) }}
                    </td>

                    <td align="center">
                        {{ $product['quantity'] }}
                        {{ $product['unit'] ?? '' }}
                    </td>

                    <td align="right">
                        ₹{{ number_format($product['amount'], 2) }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" align="center">
                        No Samagri products found.
                    </td>
                </tr>

            @endforelse

        </tbody>

        <tfoot>
            <tr style="background: #fff4e9;">
                <td colspan="3" align="right">
                    <strong>Total Samagri Amount</strong>
                </td>

                <td align="right">
                    <strong>
                        ₹{{ number_format($bookingDetails['products_total'], 2) }}
                    </strong>
                </td>
            </tr>
        </tfoot>

    </table>

    <p style="margin-top: 25px;">
        Please share this OTP with the authorized delivery officer
        only after your Samagri delivery is completed.
    </p>

    <p style="color: #777;">
        Thank you for choosing our Puja services.
    </p>

</div>

</body>
</html>
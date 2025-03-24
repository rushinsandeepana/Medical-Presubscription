<!DOCTYPE html>
<html>

<head>
    <title>Quotation Details</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">

    <div style="max-width: 800px; margin: 0 auto;">
        <div style="border: none; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <div
                style="background-color: #007bff; color: white; padding: 20px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <h2 style="margin: 0;">Quotation Details</h2>
            </div>
            <div
                style="background-color: #fff; padding: 20px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                <p style="font-size: 1.25rem;">Hi <strong>{{ $user->name }}</strong>,</p>
                <p>Here are the details of your prescription:</p>

                <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Drug Name</th>
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Quantity</th>
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Price</th>
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drugs as $drug)
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $drug->drug_name }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $drug->quantity }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ number_format($drug->price, 2) }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ number_format($drug->amount, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f1f1f1;">
                            <td colspan="2" style="padding: 10px; border: 1px solid #ddd;"></td>
                            <td style="font-weight: bold; padding: 10px; border: 1px solid #ddd;">Total</td>
                            <td style="font-weight: bold; padding: 10px; border: 1px solid #ddd;">
                                {{ number_format($drugs->sum('amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div style="text-align: center; margin-top: 20px;">
                    <p style="font-size: 1rem; color: #333;">Thank you for choosing our service. If you have any
                        questions, feel free to reach out.</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
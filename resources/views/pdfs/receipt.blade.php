<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; padding: 20px; }
        .receipt-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { border-bottom: 2px solid #4a90e2; padding-bottom: 20px; margin-bottom: 20px; clear: both; }
        .school-info { float: left; }
        .school-info h1 { margin: 0; color: #4a90e2; font-size: 24px; }
        .receipt-title { float: right; text-align: right; }
        .receipt-title h2 { margin: 0; color: #666; text-transform: uppercase; }
        .details-section { margin-top: 20px; margin-bottom: 30px; clear: both; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 8px 0; border-bottom: 1px solid #f9f9f9; }
        .label { font-weight: bold; color: #777; width: 150px; }
        .payment-summary { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .summary-row { margin-bottom: 10px; font-size: 18px; clear: both; }
        .summary-label { float: left; }
        .summary-value { float: right; }
        .total-paid { font-weight: bold; color: #2ecc71; border-top: 2px solid #ddd; padding-top: 10px; margin-top: 10px; }
        .footer { margin-top: 50px; clear: both; }
        .signature-area { float: right; border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 5px; }
        .stamp-area { float: left; width: 100px; height: 100px; border: 2px dashed #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px; transform: rotate(-15deg); text-align: center; line-height: 100px; }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="header">
            <div class="school-info">
                <h1>{{ $receipt->school->name }}</h1>
                <p>{{ $receipt->school->address ?? 'School Address' }}</p>
            </div>
            <div class="receipt-title">
                <h2>Fee Receipt</h2>
                <p>Receipt #: {{ $receipt->receipt_number }}</p>
                <p>Date: {{ $receipt->generated_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="details-section">
            <table class="details-table">
                <tr>
                    <td class="label">Student Name:</td>
                    <td>{{ $receipt->invoice->student->name }}</td>
                </tr>
                <tr>
                    <td class="label">Roll Number:</td>
                    <td>{{ $receipt->invoice->student->roll_number }}</td>
                </tr>
                <tr>
                    <td class="label">Class:</td>
                    <td>{{ $receipt->invoice->student->class }}</td>
                </tr>
                <tr>
                    <td class="label">Fee Month:</td>
                    <td>{{ $receipt->invoice->month }} {{ $receipt->invoice->year }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Method:</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                </tr>
            </table>
        </div>

        <div class="payment-summary">
            <div class="summary-row">
                <span class="summary-label">Monthly Fee:</span>
                <span class="summary-value">{{ number_format($receipt->invoice->base_amount, 2) }} PKR</span>
            </div>
            @if($receipt->invoice->late_fee > 0)
            <div class="summary-row">
                <span class="summary-label">Late Fee:</span>
                <span class="summary-value">{{ number_format($receipt->invoice->late_fee, 2) }} PKR</span>
            </div>
            @endif
            <div class="summary-row total-paid">
                <span class="summary-label">Total Amount Paid:</span>
                <span class="summary-value">{{ number_format($payment->amount, 2) }} PKR</span>
            </div>
        </div>

        <div class="footer">
            <div class="stamp-area">STAMP</div>
            <div class="signature-area">Authorized Signature</div>
        </div>
    </div>
</body>
</html>

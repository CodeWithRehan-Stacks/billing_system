<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        .school-name { font-size: 24px; font-weight: bold; }
        .details-table { width: 100%; margin-bottom: 20px; }
        .details-table td { padding: 5px; }
        .amount-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .amount-table th, .amount-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .amount-table th { background-color: #f2f2f2; }
        .footer { text-align: right; margin-top: 50px; }
        .signature { border-top: 1px solid #000; display: inline-block; padding-top: 5px; width: 200px; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <div class="school-name">Global International School</div>
        <div>123 Education Street, Knowledge City</div>
        <div>Email: info@globalschool.edu | Phone: +123 456 7890</div>
        <h2>Payment Receipt</h2>
    </div>

    <table class="details-table">
        <tr>
            <td><strong>Receipt No:</strong> RCPT-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</td>
            <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</td>
            <td><strong>Roll Number:</strong> {{ $student->roll_number }}</td>
        </tr>
        <tr>
            <td><strong>Class:</strong> {{ $student->class }} ({{ $student->section }})</td>
            <td><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</td>
        </tr>
    </table>

    <table class="amount-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Payment Amount ({{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }})</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
            </tr>
            @if($payment->transaction_id)
            <tr>
                <td>Transaction ID</td>
                <td>{{ $payment->transaction_id }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <table class="amount-table" style="width: 50%; float: right;">
        <tr>
            <th>Total Invoice Amount</th>
            <td>${{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
        <tr>
            <th>Total Paid</th>
            <td>${{ number_format($invoice->paid_amount, 2) }}</td>
        </tr>
        <tr>
            <th>Remaining Balance</th>
            <td>${{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
        </tr>
    </table>
    <div style="clear: both;"></div>

    <div class="footer">
        <br><br><br>
        <div class="signature">
            Authorized Signature
        </div>
    </div>

</body>
</html>

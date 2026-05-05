<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .school-name { font-size: 24px; font-weight: bold; color: #1a56db; }
        .receipt-title { font-size: 18px; margin-top: 10px; text-transform: uppercase; letter-spacing: 2px; }
        .content { margin-top: 30px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .label { font-weight: bold; width: 150px; display: inline-block; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .table th { background-color: #f8f9fa; }
        .footer { margin-top: 50px; text-align: right; }
        .signature { margin-top: 40px; border-top: 1px solid #333; display: inline-block; width: 200px; text-align: center; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school->name }}</div>
        <div>{{ $school->address }}</div>
        <div class="receipt-title">Payment Receipt</div>
    </div>

    <div class="content">
        <div class="row">
            <div><span class="label">Receipt No:</span> {{ $invoice->receipt->receipt_number ?? 'N/A' }}</div>
            <div style="text-align: right;"><span class="label">Date:</span> {{ now()->format('d M, Y') }}</div>
        </div>
        <div class="row">
            <div><span class="label">Student Name:</span> {{ $student->name }}</div>
            <div><span class="label">Roll Number:</span> {{ $student->roll_number }}</div>
        </div>
        <div class="row">
            <div><span class="label">Class:</span> {{ $student->class }} ({{ $student->section }})</div>
            <div><span class="label">Father Name:</span> {{ $student->father_name }}</div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Month/Year</th>
                    <th>Amount (PKR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tuition Fee</td>
                    <td>{{ $invoice->month }} {{ $invoice->year }}</td>
                    <td>{{ number_format($invoice->base_amount, 2) }}</td>
                </tr>
                @if($invoice->late_fee > 0)
                <tr>
                    <td>Late Fee Penalty</td>
                    <td>Overdue Charge</td>
                    <td>{{ number_format($invoice->late_fee, 2) }}</td>
                </tr>
                @endif
                <tr style="font-weight: bold;">
                    <td colspan="2" style="text-align: right;">Total Paid</td>
                    <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin-top: 20px;">
            <div><span class="label">Payment Method:</span> {{ ucfirst($payment->payment_method ?? 'N/A') }}</div>
            <div><span class="label">Status:</span> PAID</div>
        </div>
    </div>

    <div class="footer">
        <div class="signature">Authorized Signature</div>
    </div>
</body>
</html>

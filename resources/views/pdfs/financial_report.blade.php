<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report - {{ $school->name }} - {{ $month }} {{ $year }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary-box { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .summary-box td { padding: 10px; border: 1px solid #ddd; }
        .summary-label { font-weight: bold; background-color: #f9f9f9; }
        .report-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .report-table th { background-color: #4a90e2; color: white; padding: 8px; text-align: left; }
        .report-table td { padding: 8px; border-bottom: 1px solid #eee; }
        .status-paid { color: green; font-weight: bold; }
        .status-overdue { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $school->name }}</h1>
        <h2>Monthly Financial Report: {{ $month }} {{ $year }}</h2>
    </div>

    <h3>Income Summary</h3>
    <table class="summary-box">
        <tr>
            <td class="summary-label">Total Invoices Generated:</td>
            <td>{{ $summary['total_invoices'] }}</td>
            <td class="summary-label">Total Revenue Goal:</td>
            <td>{{ number_format($summary['total_amount'], 2) }} PKR</td>
        </tr>
        <tr>
            <td class="summary-label">Total Collected:</td>
            <td class="status-paid">{{ number_format($summary['total_paid'], 2) }} PKR</td>
            <td class="summary-label">Total Pending:</td>
            <td class="status-overdue">{{ number_format($summary['total_unpaid'], 2) }} PKR</td>
        </tr>
        <tr>
            <td class="summary-label">Overdue Invoices:</td>
            <td>{{ $summary['overdue_count'] }}</td>
            <td class="summary-label">Collection Rate:</td>
            <td>{{ $summary['total_amount'] > 0 ? round(($summary['total_paid'] / $summary['total_amount']) * 100, 2) : 0 }}%</td>
        </tr>
    </table>

    <h3>Student-wise Breakdown</h3>
    <table class="report-table">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Student</th>
                <th>Roll #</th>
                <th>Amount</th>
                <th>Paid</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->student->name }}</td>
                <td>{{ $invoice->student->roll_number }}</td>
                <td>{{ number_format($invoice->total_amount, 2) }}</td>
                <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                <td class="status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

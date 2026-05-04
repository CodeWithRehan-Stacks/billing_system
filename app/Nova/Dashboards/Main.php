<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\ActiveStudents;
use App\Nova\Metrics\InvoiceStatusPartition;
use App\Nova\Metrics\OverdueInvoices;
use App\Nova\Metrics\PendingInvoices;
use App\Nova\Metrics\RevenueByMonth;
use App\Nova\Metrics\TotalInvoices;
use App\Nova\Metrics\TotalRevenue;
use App\Nova\Metrics\TotalStudents;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    public function name(): string
    {
        return 'School Fee Dashboard';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(): array
    {
        return [
            // Row 1 — Student KPIs
            TotalStudents::make()->width('1/4'),
            ActiveStudents::make()->width('1/4'),

            // Row 1 — Invoice KPIs
            TotalInvoices::make()->width('1/4'),
            TotalRevenue::make()->width('1/4'),

            // Row 2 — Status KPIs
            PendingInvoices::make()->width('1/4'),
            OverdueInvoices::make()->width('1/4'),

            // Row 2 — Charts
            InvoiceStatusPartition::make()->width('1/2'),

            // Row 3 — Trend
            RevenueByMonth::make()->width('full'),
        ];
    }
}

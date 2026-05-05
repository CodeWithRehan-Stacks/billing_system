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
            // Row 1 — Student & Revenue KPIs
            TotalStudents::make()->width('1/3'),
            TotalRevenue::make()->width('1/3'),
            \App\Nova\Metrics\RevenueExpected::make()->width('1/3'),

            // Row 2 — Collections & Status
            PendingInvoices::make()->width('1/4'),
            OverdueInvoices::make()->width('1/4'),
            InvoiceStatusPartition::make()->width('1/2'),

            // Row 3 — Trends
            RevenueByMonth::make()->width('2/3'),
            TotalInvoices::make()->width('1/3'),
        ];
    }
}

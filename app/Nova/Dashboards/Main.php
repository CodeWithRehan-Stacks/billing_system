<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\ActiveStudents;
use App\Nova\Metrics\PendingInvoices;
use App\Nova\Metrics\RevenueByMonth;
use App\Nova\Metrics\RevenueExpected;
use App\Nova\Metrics\TotalInvoices;
use App\Nova\Metrics\TotalRevenue;
use App\Nova\Metrics\TotalStudents;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    public function name(): string
    {
        return 'Dashboard';
    }

    /**
     * Get the cards for the dashboard.
     */
    public function cards(): array
    {
        $user = request()->user();

        return [

            TotalStudents::make()->width('1/3'),
            TotalRevenue::make()->width('1/3'),
            RevenueExpected::make()->width('1/3'),

            TotalInvoices::make()->width('1/2'),
            ...$this->roleCards($user),
            RevenueByMonth::make()->width('full'),


        ];
    }

    /**
     * Role-based cards
     */
    protected function roleCards($user): array
    {
        if (! $user) {
            return [];
        }
        if ($user->role === 'superadmin') {
            return [
                ActiveStudents::make()->width('1/3'),
            ];
        }

        if ($user->role === 'school_admin') {
            return [
                ActiveStudents::make()->width('1/3'),
            ];
        }

        return [
            PendingInvoices::make()->width('1/2'),
        ];
    }
}

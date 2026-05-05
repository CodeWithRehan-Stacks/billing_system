<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get dashboard summary for the authenticated school admin.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user->school_id) {
            return $this->error('Access denied. No school associated with this account.', 403);
        }

        $month = now()->format('F');
        $year = now()->format('Y');

        $summary = $this->reportService->getMonthlySummary($user->school_id, $month, $year);

        return $this->success($summary, 'Dashboard summary retrieved successfully');
    }
}

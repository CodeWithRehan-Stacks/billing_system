<?php

namespace App\Nova\Actions;

use App\Services\ReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateFinancialReport extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $reportService = app(ReportService::class);
        $school = $models->first();

        try {
            $url = $reportService->generateMonthlyReport(
                $school->id,
                $fields->month,
                $fields->year,
                $fields->format
            );

            // Return relative path for download if possible, or direct URL
            return Action::openInNewTab($url);
        } catch (\Exception $e) {
            return Action::danger('Failed to generate report: ' . $e->getMessage());
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Month')->options([
                'January' => 'January', 'February' => 'February', 'March' => 'March',
                'April' => 'April', 'May' => 'May', 'June' => 'June',
                'July' => 'July', 'August' => 'August', 'September' => 'September',
                'October' => 'October', 'November' => 'November', 'December' => 'December'
            ])->default(now()->format('F'))->rules('required'),

            Text::make('Year')->default(now()->format('Y'))->rules('required'),

            Select::make('Format')->options([
                'excel' => 'Excel',
                'pdf' => 'PDF'
            ])->default('excel')->rules('required'),
        ];
    }
}

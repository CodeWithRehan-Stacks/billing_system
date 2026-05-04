<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class DeactivateStudent extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Deactivate Student';

    public function handle(ActionFields $fields, Collection $models): void
    {
        foreach ($models as $model) {
            $model->update(['status' => 'inactive']);
        }
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}

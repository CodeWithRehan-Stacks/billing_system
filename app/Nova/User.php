<?php

namespace App\Nova;

use App\Nova\Actions\ActivateUser;
use App\Nova\Actions\DeactivateUser;
use App\Nova\Filters\UserRoleFilter;
use App\Nova\Metrics\TotalUsers;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;

class User extends Resource
{
    public static $model = \App\Models\User::class;

    public static $title = 'user_name';

    public static $search = [
        'id', 'first_name', 'last_name', 'email', 'user_name'
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('School', 'school', School::class)
                ->sortable()
                ->rules('required')
                ->canSee(fn ($request) => $request->user()->isSuperAdmin()),

            Text::make('First Name', 'first_name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Last Name', 'last_name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('User Name', 'user_name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Date::make('Date of Birth', 'date_of_birth')
                ->sortable()
                ->nullable(),

            Select::make('Role')->options([
                'super_admin' => 'Super Admin',
                'school_admin' => 'School Admin',
                'worker' => 'School Worker',
            ])->sortable()->rules('required'),
        ];
    }

    public static function indexQuery(NovaRequest $request, Builder $query): Builder
    {
        if ($request->user()->isSuperAdmin()) {
            return $query;
        }

        return $query->where('school_id', $request->user()->school_id)
                     ->where('role', 'worker');
    }

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->isSuperAdmin() || $request->user()->isSchoolAdmin();
    }

    public function cards(NovaRequest $request): array
    {
        return [new TotalUsers];
    }

    public function filters(NovaRequest $request): array
    {
        return [new UserRoleFilter];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [new ActivateUser, new DeactivateUser];
    }
}

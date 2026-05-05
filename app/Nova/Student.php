<?php

namespace App\Nova;

use App\Nova\Actions\ActivateStudent;
use App\Nova\Actions\DeactivateStudent;
use App\Nova\Filters\StudentClassFilter;
use App\Nova\Filters\StudentStatusFilter;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Student extends Resource
{
    public static $model = \App\Models\Student::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'roll_number', 'phone',
    ];

    public static function label(): string
    {
        return 'Students';
    }

    public static function singularLabel(): string
    {
        return 'Student';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('School', 'school', School::class)
                ->sortable()
                ->rules('required'),

            Panel::make('Personal Information', [
                Text::make('Name', 'name')
                    ->sortable()
                    ->rules('required', 'string', 'max:255'),

                Text::make('Father Name', 'father_name')
                    ->sortable()
                    ->rules('nullable', 'string', 'max:255'),

                Text::make('Mother Name', 'mother_name')
                    ->sortable()
                    ->rules('nullable', 'string', 'max:255'),

                Text::make('Phone')
                    ->nullable()
                    ->rules('nullable', 'string', 'max:20'),
                
                Text::make('Student WhatsApp', 'student_whatsapp')
                    ->nullable(),
                
                Text::make('Father WhatsApp', 'father_whatsapp')
                    ->nullable(),
                
                Text::make('Mother WhatsApp', 'mother_whatsapp')
                    ->nullable(),

                Textarea::make('Address')
                    ->nullable()
                    ->hideFromIndex(),
            ]),

            Panel::make('Academic & Fee Information', [
                Select::make('Class')
                    ->options(function () {
                        $classes = [];
                        foreach (range(1, 12) as $cls) {
                            $classes["Class $cls"] = "Class $cls";
                        }
                        $classes['KG']  = 'KG';
                        $classes['Pre-KG'] = 'Pre-KG';
                        return $classes;
                    })
                    ->nullable()
                    ->sortable()
                    ->displayUsingLabels(),

                Select::make('Section')
                    ->options([
                        'A' => 'Section A',
                        'B' => 'Section B',
                        'C' => 'Section C',
                        'D' => 'Section D',
                    ])
                    ->nullable()
                    ->displayUsingLabels(),

                Text::make('Roll Number', 'roll_number')
                    ->nullable()
                    ->sortable()
                    ->creationRules('nullable', 'string', 'unique:students,roll_number')
                    ->updateRules('nullable', 'string', 'unique:students,roll_number,{{resourceId}}')
                    ->help('Must be unique.'),

                Currency::make('Monthly Fee', 'monthly_fee')
                    ->currency('PKR')
                    ->rules('required', 'numeric')
                    ->sortable(),

                Date::make('Admission Date', 'admission_date')
                    ->nullable()
                    ->sortable(),
            ]),

            Panel::make('Status', [
                Select::make('Status')
                    ->options([
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->rules('required')
                    ->displayUsingLabels()
                    ->sortable(),

                Badge::make('Status')->map([
                    'active'   => 'success',
                    'inactive' => 'danger',
                ])->onlyOnIndex(),
            ]),

            HasMany::make('Invoices', 'invoices', FeeInvoice::class),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new StudentClassFilter,
            new StudentStatusFilter,
        ];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new ActivateStudent,
            new DeactivateStudent,
        ];
    }
}

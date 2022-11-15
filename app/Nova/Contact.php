<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Metrics\NewContacts;
use App\Nova\Metrics\ContactsPerDay;
use App\Nova\Metrics\ContactsPerMonth;

class Contact extends Resource
{
    // 限制当前用户的
    public static function indexQuery(NovaRequest $request, $query)
    {
        //$request->user()->id
        // 必须是church owner
        $id = \App\Models\Organization::where('user_id', $request->user()->id)->firstOrFail()->id;
        return $query->where('organization_id', $id);
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Contact::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('name_en')->rules('required', 'string', 'max:255'),
            Text::make('name_last')->rules('required', 'string', 'max:255'),
            Text::make('name_first')->rules('required', 'string', 'max:255'),
            Text::make('sex')->rules('required', 'string', 'max:255'),
            DateTime::make('birthday')->rules('required', 'string', 'max:255'),
            Text::make('telephone')->rules('required', 'string', 'max:255'),
            Text::make('email')->rules('required', 'string', 'max:255'),
            Text::make('address')->rules('required', 'string', 'max:255')->hideFromIndex(),
            DateTime::make('date_join')->rules('required', 'string', 'max:255'),
            Text::make('reference_id')->rules('required', 'string', 'max:255'),
            Text::make('remark')->rules('required', 'string', 'max:255'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            new NewContacts, 
            new ContactsPerDay,
            new ContactsPerMonth
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}

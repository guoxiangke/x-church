<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;


class Organization extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Organization::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
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
            Text::make('user_id')->rules('required', 'string', 'max:255'),
            Text::make('system_name')->rules('required', 'string', 'max:255'),
            Text::make('name')->rules('required', 'string', 'max:255'),
            Text::make('name_en')->rules('required', 'string', 'max:255'),
            Text::make('name_abbr')->rules('required', 'string', 'max:255'),
            Text::make('name_en_abbr')->rules('required', 'string', 'max:255'),
            Text::make('telephone')->rules('required', 'string', 'max:255'),
            Text::make('email')->rules('required', 'string', 'max:255'),
            Text::make('address')->rules('required', 'string', 'max:255'),
            Text::make('website_url')->rules('required', 'string', 'max:255'),
            Text::make('logo_url')->rules('required', 'string', 'max:255'),
            DateTime::make('birthday')->rules('required', 'string', 'max:255'),
            Text::make('introduce')->rules('required', 'string', 'max:255'),
            Text::make('contact_fields')->rules('required', 'string', 'max:255'),
            Text::make('wechat_qr_url'),
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
        return [];
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

<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;


class Organization extends Resource
{
    
    // 限制当前用户的
    public static function indexQuery(NovaRequest $request, $query)
    {
        $userId = $request->user()->id;
        if($userId === 1) return $query;
        return $query->where('user_id', $userId);
    }

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
            // ID::make()->sortable(),
            Text::make('Name', function () {
                return "<a class='link-default' href='organizations/{$this->id}'>{$this->name}</a>";
            })->asHtml()->onlyOnIndex(),
            Text::make('Name')->rules('required', 'string', 'max:255')->onlyOnForms(),
            Text::make('name_en')->nullable(),
            Text::make('name_abbr')->nullable(),
            Text::make('name_en_abbr')->nullable(),
            Text::make('telephone')->rules('required', 'string', 'max:255'),
            Text::make('email')->nullable(),
            Text::make('address')->nullable()->hideFromIndex(),
            Text::make('website_url')->nullable()->hideFromIndex(),
            Text::make('logo_url')->nullable()->hideFromIndex(),
            Date::make('birthday')->nullable(),
            Text::make('introduce')->nullable()->hideFromIndex(),
            Text::make('contact_fields')->hideFromIndex(),
            Text::make('system_name')->rules('required', 'string', 'max:255'),
            Text::make('wechat_ai_title')->nullable(),
            Text::make('wechat_ai_token')->nullable(),
            Text::make('wechat_qr_url')->hideFromIndex(),
            BelongsTo::make('user')->rules('required'),

            HasMany::make('Contacts'),
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

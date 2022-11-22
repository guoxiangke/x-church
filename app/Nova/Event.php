<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Event extends Resource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Event::class;

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
            BelongsTo::make('organization')->rules('required'),
            BelongsTo::make('service')->nullable(),
            Text::make('Name')->rules('required', 'string', 'max:255'),
            Text::make('description')->hideFromIndex(),
            DateTime::make('begin_at'),
            Number::make('check_in_ahead')->help('单位：分钟，默认180分钟'),
            Number::make('活动时长','duration_hours')->help('单位：小时'),
            Boolean::make('签出功能','is_need_check_out'),
            Text::make('活动地址','address')->hideFromIndex(),

            Boolean::make('统计成人儿童','is_multi_enroll'),
            Boolean::make('取消报名','cancel_ahead_hours'),
            Boolean::make('报名留言','is_need_remark'),
            Text::make('直播链接','live_url')->nullable(),
            Text::make('活动周期计划','rrule')->nullable()->hideFromIndex()->help('<a href="https://jakubroztocil.github.io/rrule/">Gen RRULE</a> copy rule.toString() from second line, include RRULE'),
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

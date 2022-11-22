<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;

class EventEnroll extends Resource
{
    // 限制 拥有者
    public static function indexQuery(NovaRequest $request, $query)
    {
        $userId = $request->user()->id;
        if($userId === 1) return $query;

        // 找到组织里的所有event
        $orgIds = $request->user()->organizations()->pluck('id');
        $eventIds = \App\Models\Event::whereIn('organization_id', $orgIds)->pluck('id');
        return $query->whereIn('event_id', $eventIds);
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\EventEnroll::class;

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
            BelongsTo::make('user')->rules('required', 'string', 'max:255'),
            BelongsTo::make('event')->rules('required', 'string', 'max:255'),
            BelongsTo::make('service')->rules('required', 'string', 'max:255'),
            DateTime::make('enrolled_at'),
            DateTime::make('double_checked_at'),
            DateTime::make('checked_in_at'),
            DateTime::make('checked_out_at'),
            DateTime::make('canceled_at'),
            Text::make('remark')->hideFromIndex(),
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

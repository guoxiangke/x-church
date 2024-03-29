<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Metrics\NewContacts;
use App\Nova\Metrics\ContactsPerDay;
use App\Nova\Metrics\ContactsPerMonth;

class Contact extends Resource
{
    // public static $displayInNavigation = false;
    
    // 限制 owner
    public static function indexQuery(NovaRequest $request, $query)
    {
        $userId = $request->user()->id;
        if($userId === 1) return $query;

        return $query->whereIn('organization_id', $request->user()->organizations()->pluck('id'));
    }

    public function filterByOrg($orgId=1)
    {
        // Log::error(__CLASS__,[$orgId]);
        // return $this->where('organization_id', $orgId);
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
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name','name_en','telephone','email'
    ];

    public static $with = ['user.social'];
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
            BelongsTo::make('organization')->rules('required')->onlyOnForms(),
            Text::make('Name')->onlyOnForms(),
            Text::make('Name', function () {
                return "<a class='link-default' href='contacts/{$this->id}'>{$this->name}</a>";
            })->asHtml()->onlyOnIndex(),
            Text::make('name_en'),
            Select::make('Sex')->options([
                '0' => 'Male',
                '1' => 'Female',
            ]),
            Date::make('birthday'),
            Text::make('telephone'),
            Text::make('email'),
            Text::make('address')->hideFromIndex(),
            Date::make('date_join'),
            Text::make('reference_id'),
            Text::make('remark'),
            Text::make('Avatar', function (){
                if(!$this->user) return '';
                $avatar = $this->user->social?$this->user->social->avatar:$this->user->profile_photo_url;
                return '<img style="max-width:45px;" src="'.$avatar.'"></img>';
            })->asHtml()->onlyOnIndex(),
            BelongsTo::make('user')->exceptOnForms(),
            BelongsTo::make('organization')->rules('required')->exceptOnForms(),
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

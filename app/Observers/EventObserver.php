<?php

namespace App\Observers;

use App\Models\Event;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        $organization = $event->organization;
        $path = $event->qrpath;
        if(!Storage::exists($path)){
            $avatar =  QrCode::size(2000)
                ->format('png')
                ->eye('square')
                ->style('dot')
                ->merge($organization->logo_url??'https://res.wx.qq.com/a/wx_fed/assets/res/OTE0YTAw.png', .3, true)
                ->generate(route('service.checkin', $event->hashid), Storage::path($path));
        }
    }

    /**
     * Handle the Event "updated" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        //
    }

    /**
     * Handle the Event "deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function restored(Event $event)
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Models\EventEnroll;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class EventEnrollObserver
{
    /**
     * Handle the Event "created" event.
     *
     * @param  \App\Models\EventEnroll  $event
     * @return void
     */
    public function created(EventEnroll $eventEnroll)
    {
        $organization = $eventEnroll->service->organization;
        // LCC 信义会十架堂
        if($organization->id == 7){
            $eventEnrolls = EventEnroll::where(['event_id' => $eventEnroll->event->id])
                ->orderBy('updated_at','desc')
                ->get();

            Http::withHeaders([
                'x-api-key' => config('services.textbee.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.textbee.dev/api/v1/gateway/devices/'.config('services.textbee.devices_id').'/sendSMS', [
                'recipients' => [$organization->telephone],
                'message' => $eventEnroll->event->name."\n新增1人报名：".$eventEnroll->user->name."\n当前成人：".$eventEnrolls->sum('count_adult')."\n当前儿童：".$eventEnrolls->sum('count_child') ."\n当前时间：".now('America/Los_Angeles')->format('m/d H:i'),
            ]);
        }
    }

    /**
     * Handle the EventEnroll "updated" event.
     *
     * @param  \App\Models\EventEnroll  $event
     * @return void
     */
    public function updated(EventEnroll $eventEnroll)
    {
        // 检查 count_child 或 count_adult 是否发生变化
        if ($eventEnroll->isDirty('count_child') || $eventEnroll->isDirty('count_adult')) {
            $organization = $eventEnroll->service->organization;
            // LCC 信义会十架堂
            if($organization->id == 7){
                $eventEnrolls = EventEnroll::where(['event_id' => $eventEnroll->event->id])
                    ->orderBy('updated_at','desc')
                    ->get();

                Http::withHeaders([
                    'x-api-key' => config('services.textbee.api_key'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.textbee.dev/api/v1/gateway/devices/'.config('services.textbee.devices_id').'/sendSMS', [
                    'recipients' => [$organization->telephone],
                    'message' => $eventEnroll->event->name."\n报名人数更新：".$eventEnroll->user->name."\n当前成人：".$eventEnrolls->sum('count_adult')."\n当前儿童：".$eventEnrolls->sum('count_child') ."\n当前时间：".now('America/Los_Angeles')->format('m/d H:i'),
                ]);
            }
            
        }
    }

    /**
     * Handle the EventEnroll "deleted" event.
     *
     * @param  \App\Models\EventEnroll  $event
     * @return void
     */
    public function deleted(EventEnroll $event)
    {
        //
    }

    /**
     * Handle the EventEnroll "restored" event.
     *
     * @param  \App\Models\EventEnroll  $event
     * @return void
     */
    public function restored(EventEnroll $event)
    {
        //
    }

    /**
     * Handle the EventEnroll "force deleted" event.
     *
     * @param  \App\Models\EventEnroll  $event
     * @return void
     */
    public function forceDeleted(EventEnroll $event)
    {
        //
    }
}

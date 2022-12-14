<?php

namespace App\Observers;

use App\Models\Service;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function created(Service $service)
    {
        $organization = $service->organization;
        $path = $service->qrpath;
        if(!Storage::exists($path)){
            $avatar =  QrCode::size(1000)
                ->format('png')
                ->eye('square')
                ->style('dot')
                ->merge($organization->logo_url??'https://www.tudingyy.com/wp-content/uploads/2022/06/WechatIMG1064.jpeg', .3, true)
                ->errorCorrection('H')
                ->generate(route('service.checkin', $service->hashid), Storage::path($path));
        }
    }

    /**
     * Handle the Service "updated" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function updated(Service $service)
    {
        //
    }

    /**
     * Handle the Service "deleted" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function deleted(Service $service)
    {
        //
    }

    /**
     * Handle the Service "restored" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function restored(Service $service)
    {
        //
    }

    /**
     * Handle the Service "force deleted" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function forceDeleted(Service $service)
    {
        //
    }
}

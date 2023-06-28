<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class RegenServiceQR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $services = Service::all();
        $services->each(function($service){
            // echo $item->name;
            $organization = $service->organization;
            $path = $service->qrpath;

            QrCode::size(1000)
                ->format('png')
                ->eye('square')
                ->style('dot')
                ->merge($organization->logo_url??'https://www.tudingyy.com/wp-content/uploads/2022/06/WechatIMG1064.jpeg', .3, true)
                ->errorCorrection('H')
                ->generate(route('service.checkin', $service->hashid), Storage::path($path));
        });
        return Command::SUCCESS;
    }
}

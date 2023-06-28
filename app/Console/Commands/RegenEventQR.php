<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class RegenEventQR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:event';

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
        $events = Event::all();
        $events->each(function($event){
            $organization = $event->organization;
            $path = $event->qrpath;

            QrCode::size(1000)
                ->format('png')
                ->eye('square')
                ->style('dot')
                ->merge($organization->logo_url??'https://www.tudingyy.com/wp-content/uploads/2022/06/WechatIMG1064.jpeg', .3, true)
                ->errorCorrection('H')
                ->generate(route('event.checkin', $event->hashid), Storage::path($path));
        });
        return Command::SUCCESS;
    }
}

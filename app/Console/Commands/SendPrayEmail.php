<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Organization;
use App\Mail\PrayEmail;
use App\Jobs\SendPrayEmailQueue;

class SendPrayEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:prayemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Pray Email for ccac Cantonese';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $organization = Organization::find(8);
        foreach ($organization->contacts as $key => $contact) {
            // 会员状态：1：active，0：unsubscribe
            if($contact->email && $contact->status!=0)  {
                SendPrayEmailQueue::dispatch($contact);
            }
        }
        return Command::SUCCESS;
    }
}

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
            if($contact->email)  {
                SendPrayEmailQueue::dispatch($contact);
            }
        }
        return Command::SUCCESS;
    }
}

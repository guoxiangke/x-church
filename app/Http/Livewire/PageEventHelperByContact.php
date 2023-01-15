<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Contact;
use App\Models\EventEnroll;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

// 辅助报名：录入的联系人Contact，没有微信，如何为他们签到？
class PageEventHelperByContact extends Component
{
    public Event $event;
    public $eventEnrollWithContacts;
    public $count=0;

    protected $rules = [
        'contacts.*.checked_in_at'
    ];

    public function render()
    {
        return view('livewire.event.helper-by-contacts')->layout('layouts.wechat');;
    }

    public function updated($name, $value)
    {
        // get id from eventEnrollWithContacts.1.eventEnroll.checked_in_at
        $id = filter_var($name, FILTER_SANITIZE_NUMBER_INT);
        $eventEnroll = $this->eventEnrollWithContacts[$id]['eventEnroll']; //array
        if(isset($eventEnroll['id'])){
            $eventEnroll = EventEnroll::find($eventEnroll['id']);
            $eventEnroll->checked_in_at = $value?now():null;
        }else{
            $eventEnroll['checked_in_at'] = $value?now():null;
            $eventEnroll = new EventEnroll($eventEnroll); // first or new!
        }
        $eventEnroll->timestamps = false;
        $eventEnroll->saveQuietly();
        if($value){
            $this->count++;
        }else{
            $this->count--;
        }
        $this->eventEnrollWithContacts = $this->getEventEnrollWithContacts();
    }

    public function mount(Event $event)
    {
        $this->events = $event;
        $this->eventEnrollWithContacts = $this->getEventEnrollWithContacts();
        $this->count = $this->getCount();
    }

    public function getCount(){
        $count = 0;
        foreach ($this->eventEnrollWithContacts as $key => $value) {
            if( $value['eventEnroll']->checked_in_at) $count++;
        }
        return $count;
    }

    public function getEventEnrollWithContacts()
    {
        $event = $this->events;

        // 报名的用户，来签到 + 潜在的成员（以前的联系人/报名的） $potentialContacts1
        $eventEnrollsAll = EventEnroll::with('user.social')
            ->whereBelongsTo($event)
            ->get();
        // 手动登记的 用户 辅助签到 $potentialContacts
        $enrolledContacts = $eventEnrollsAll->where('user_id','=', 0);
        $customContacts = Contact::whereBelongsTo($event->organization)//with('events')->
            ->where('user_id', NULL) //只显示手动登记的
            ->get();

        // if($enrolledContactIds->contains($customContact->id)) 则已报名
        $extra = [];
        $customContacts->each(function ($contact, $key) use($enrolledContacts, $event, &$extra) {
            $eventEnroll = $enrolledContacts->firstWhere('contact_id', $contact->id);
            if(!$eventEnroll){
                $eventEnroll = new EventEnroll([
                    'event_id' => $event->id,
                    'user_id' => 0,
                    'contact_id' => $contact->id,
                    'enrolled_at' => now(),
                    'checked_in_at' => null
                ]);
            }
            $extra[] = compact('contact','eventEnroll');
        });
        return $extra;
    }
}

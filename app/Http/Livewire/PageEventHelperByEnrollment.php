<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Contact;
use App\Models\EventEnroll;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

// 辅助报名：录入的联系人Contact，没有微信，如何为他们签到？
class PageEventHelperByEnrollment extends Component
{
	public Event $event;
    public $eventEnrolls;
    public $isBelongsToService = false;
    public $potentialContacts;
    
    public $count = 0;

    protected $rules = [
        "eventEnrolls.*.is_checked_in" => 'required',
    ];

    public function getCount(){
        return $this->eventEnrolls->whereNotNull('checked_in_at')->count();
    }

    public function updated($field, $value){
    	// "eventEnrolls.0.is_checked_in"
        // get id from $field
        $id = filter_var($field, FILTER_SANITIZE_NUMBER_INT);
        $eventEnroll = $this->eventEnrolls[$id]; //array
		$eventEnroll->checked_in_at = $value?now():null;
        $eventEnroll->timestamps = false;
        unset($eventEnroll->is_checked_in);
        $eventEnroll->saveQuietly();
        if($value){
            $this->count++;
        }else{
            $this->count--;
        }
        // $this->eventEnrolls = $this->geteventEnrolls();
    }

    public function mount(Event $event){
        // return view('pages.event.eventEnrollList', compact('eventEnrolls', 'event', 'customContacts'));

        $this->event = $event;
        $this->eventEnrolls = $this->geteventEnrolls();
        $this->isBelongsToService = $event->service_id?true:false;
        // dd($event->service_id,$this->isBelongsToService);

    	// // 潜在的成员（以前的联系人/报名的） $potentialContacts
    	// // 1. 先找到 该 service 下所有的 放在前面 
    	// // 2. 再找到 所有改 organization 下所有的 merge一下
    	// $serviceIds = $event->organization->services->pluck('id');
    	// $allOfAllService = EventEnroll::with('user.social')->whereIn('service_id', $serviceIds)->get();

    	// // $this->potentialContacts = $allOfAllService->diffAssoc($this->eventEnrolls);
    	// $ids = $this->eventEnrolls->pluck('id');
    	// $this->potentialContacts = $allOfAllService->filter(function ($item, $key) use($ids) {
    	// 	return !$ids->contains($item->id);

		// });
    }

    public function geteventEnrolls(){
        return EventEnroll::with('user.social')
            ->whereBelongsTo($this->event)
            ->where('user_id','<>', 0)
            ->get();
        // Str::contains($eventEnroll->user->social->name, $search)||Str::contains($eventEnroll->user->name, $search)||Str::contains($eventEnroll->user->social->telephone, $search);
    }
    

    public function render()
    {
        return view('livewire.event.helper-by-enrollment')->layout('layouts.wechat');;
    }
}
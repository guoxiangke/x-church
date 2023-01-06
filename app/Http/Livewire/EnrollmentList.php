<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Contact;
use App\Models\EventEnroll;

class EnrollmentList extends Component
{
    public Event $event;


    public function render()
    {
        return view('livewire.enrollment.list')->layout('layouts.wechat');;
    }

    public $contacts;
    public $potentialContacts;

    public function mount()
    {
        // 1.把所有的组织联系人都拿出来，有的有user/有的是手动录入的，没有关联user
        // 2.和 已报名的User 求差，得到没有 报名的人 让同工来 帮忙checkIN

        $allContacts = Contact::whereBelongsTo($this->event->organization)->get()->mapWithKeys(function ($item, $key) {
            // 没有关联user的
            $user = $item->user;
            if(!$user){
                return [
                    [
                        'enrollment_id' => -1,
                        'user_id' => 0,
                        'name' => $item->name,
                        'avatar' => '', 
                    ]
                ];
            }
            $social = $user->social;
            // $social->name //公众号获取的微信昵称
            // $social->nickname //微信姓名
            return [
                $user->id => [
                    'enrollment_id' => 0,
                    'user_id' => $user->id,
                    'name' => $social?($social->nickname?:$social->name):$user->name,
                    'avatar' => $social?$social->avatar:$user->profile_photo_url,
                ]
            ];
        });

        // dd($allContacts->toArray());

        // dd($this->event->toArray());
        // dd($this->event->organization->toArray());
        // dd(Contact::whereBelongsTo($this->event->organization)->get());
        // dd(EventEnroll::with('user.social')->whereBelongsTo($this->event)->get()->toArray());

        $collection = EventEnroll::with('user.social')
            ->whereBelongsTo($this->event)
            ->get();
        $enrolledContacts = $collection->mapWithKeys(function ($item, $key) {
            $social = $item->user->social;
            // $social->name //公众号获取的微信昵称
            // $social->nickname //微信姓名
            return [
                $item->user->id => [
                    'enrollment_id' => $item->id,
                    'enrolled_at' => $item->enrolled_at,
                    'checked_in_at' => $item->checked_in_at,
                    'double_checked_at' => $item->checked_in_at,
                    'user_id' => $item->user->id,
                    'name' => $social?($social->nickname?:$social->name):$item->user->name,
                    'avatar' => $social?$social->avatar:$item->user->profile_photo_url,
                ]
            ];
        });

        $this->contacts = $enrolledContacts;
        // $diff = $enrolledContacts->diffKeys($allContacts);
        $this->potentialContacts = $allContacts->diffKeys($enrolledContacts);
        // dd($diff->toArray());
        // dd($enrolledContacts->count(),$allContacts->count(),$diff->count());
        
         // https://laravel.com/docs/9.x/collections#method-maptogroups
        // user_id of contacts
    }

    public function add()
    {
    }

    public function remove()
    {
    }

    public function rename()
    {
    }
}

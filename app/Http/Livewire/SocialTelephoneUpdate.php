<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SocialTelephoneUpdate extends Component
{
    public $social;
    public $event;

    public function render()
    {
        return view('livewire.social.telephone-update');
    }

    protected $rules = [
        'social.telephone' => 'required|digits|min:10|regex:/^([0-9\s\-\+\(\)]*)$/',
        'social.nickname' => 'string,max:16',
    ];

    public function updated($name, $value)
    {
        $this->social->update([$name=>$value]);
    }
}

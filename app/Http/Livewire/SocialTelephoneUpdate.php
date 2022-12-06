<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SocialTelephoneUpdate extends Component
{
    public $social;

    public function render()
    {
        return view('livewire.social.telephone-update');
    }

    protected $rules = [
        'social.telephone' => 'numeric,max:10',
    ];

    public function updated($name, $value)
    {
        $this->social->update([$name=>$value]);
    }
}

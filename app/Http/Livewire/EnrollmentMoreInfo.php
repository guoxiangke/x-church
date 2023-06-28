<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventEnroll;

class EnrollmentMoreInfo extends Component
{
    public EventEnroll $eventEnroll;
    protected $rules = [
        'eventEnroll.count_adult' =>['integer','min:1','max:5'],
        'eventEnroll.count_child' =>['integer','min:0','max:10'],
        'eventEnroll.remark' => ['string',"max:255"],

    ];
    public function render()
    {
        return view('livewire.enrollment.moreinfo');
    }


    public function updated($name, $value)
    {
        $this->validate();
        $this->eventEnroll->update([$name=>$value]);
    }

    public function submit()
    {
        
    }
}

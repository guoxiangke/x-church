<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EnrollmentMoreInfo extends Component
{
    public $eventEnroll;
    protected $rules = [
        'eventEnroll.count_adult' => 'numeric,max:5',
        'eventEnroll.count_child' => 'numeric,max:10',
        'eventEnroll.remark' => 'string,max:255',

    ];
    public function render()
    {
        return view('livewire.enrollment.moreinfo');
    }


    public function updated($name, $value)
    {
        $this->eventEnroll->update([$name=>$value]);
    }
}

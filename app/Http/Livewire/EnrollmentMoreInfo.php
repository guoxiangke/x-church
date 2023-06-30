<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventEnroll;

class EnrollmentMoreInfo extends Component
{
    public EventEnroll $eventEnroll;
    public function mount(){
        // set default value of nullable field
        if(!$this->eventEnroll->count_adult) $this->eventEnroll->count_adult=1;
        if(!$this->eventEnroll->count_child) $this->eventEnroll->count_child=0;
    }
    protected $rules = [
        'eventEnroll.count_adult' =>['integer','min:1','max:5'],
        'eventEnroll.count_child' =>['integer','min:0','max:10'],
        'eventEnroll.remark' => ['nullable','string',"max:255"],

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

<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EnrollmentCancel extends Component
{
    public $eventEnroll;

    public function render()
    {
        return view('livewire.enrollment.cancel');
    }

    public function cancel()
    {
        $this->eventEnroll->cancel();
        // $this->eventEnroll->delete();
        return redirect()->to('/weui/success');
    }
}

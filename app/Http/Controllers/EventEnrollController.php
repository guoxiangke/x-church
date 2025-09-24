<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventEnrollRequest;
use App\Http\Requests\UpdateEventEnrollRequest;
use App\Models\EventEnroll;

class EventEnrollController extends Controller
{
    
    // show counts form to update counts
    public function counts(EventEnroll $eventEnroll)
    {
         return view('event-counts-update', compact('eventEnroll'));
    }
    public function cancel(EventEnroll $eventEnroll)
    {
         return view('cancel-double-check', compact('eventEnroll'));
    }

    public function status(EventEnroll $eventEnroll)
    {
        $userId = $eventEnroll->user_id;
        $serviceId = $eventEnroll->event->service_id;

        $results = EventEnroll::join('events', 'event_enrolls.event_id', '=', 'events.id')
            ->where('event_enrolls.user_id', $userId)
            ->where('events.service_id', $serviceId)
            ->whereNotNull('event_enrolls.checked_in_at')
            ->select('event_enrolls.*', 'events.name as event_name', 'events.begin_at as event_begin_at')
            ->orderBy('events.begin_at', 'desc')
            ->get();

        $userName = $eventEnroll->user->name;
        $attendanceCount = $results->count();
        $eventNames = $results->pluck('event_name')->toArray();

        $output = "用户名：{$userName}\n";
        $output .= "出勤次数：{$attendanceCount}\n";
        $output .= "分别是：\n";
        
        foreach ($eventNames as $eventName) {
            $output .= "  \"{$eventName}\",\n";
        }

        return response($output, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventEnrollRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventEnrollRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventEnroll  $eventEnroll
     * @return \Illuminate\Http\Response
     */
    public function show(EventEnroll $eventEnroll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EventEnroll  $eventEnroll
     * @return \Illuminate\Http\Response
     */
    public function edit(EventEnroll $eventEnroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventEnrollRequest  $request
     * @param  \App\Models\EventEnroll  $eventEnroll
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventEnrollRequest $request, EventEnroll $eventEnroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventEnroll  $eventEnroll
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventEnroll $eventEnroll)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventEnrollRequest;
use App\Http\Requests\UpdateEventEnrollRequest;
use App\Models\EventEnroll;

class EventEnrollController extends Controller
{
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

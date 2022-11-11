<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChurchRequest;
use App\Http\Requests\UpdateChurchRequest;
use App\Models\Church;

class ChurchController extends Controller
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
     * @param  \App\Http\Requests\StoreChurchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChurchRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function show(Church $church)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function edit(Church $church)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChurchRequest  $request
     * @param  \App\Models\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChurchRequest $request, Church $church)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function destroy(Church $church)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChurchContactRequest;
use App\Http\Requests\UpdateChurchContactRequest;
use App\Models\ChurchContact;

class ChurchContactController extends Controller
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
     * @param  \App\Http\Requests\StoreChurchContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChurchContactRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChurchContact  $churchContact
     * @return \Illuminate\Http\Response
     */
    public function show(ChurchContact $churchContact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChurchContact  $churchContact
     * @return \Illuminate\Http\Response
     */
    public function edit(ChurchContact $churchContact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChurchContactRequest  $request
     * @param  \App\Models\ChurchContact  $churchContact
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChurchContactRequest $request, ChurchContact $churchContact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChurchContact  $churchContact
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChurchContact $churchContact)
    {
        //
    }
}

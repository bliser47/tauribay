<?php

namespace TauriBay\Http\Controllers;

use TauriBay\Battleground;
use Illuminate\Http\Request;

class BattlegroundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('battleground');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \TauriBay\Battleground  $battleground
     * @return \Illuminate\Http\Response
     */
    public function show(Battleground $battleground)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \TauriBay\Battleground  $battleground
     * @return \Illuminate\Http\Response
     */
    public function edit(Battleground $battleground)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \TauriBay\Battleground  $battleground
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Battleground $battleground)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \TauriBay\Battleground  $battleground
     * @return \Illuminate\Http\Response
     */
    public function destroy(Battleground $battleground)
    {
        //
    }
}

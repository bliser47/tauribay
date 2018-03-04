<?php

namespace App\Http\Controllers;

use App\TopItemLevels;
use Illuminate\Http\Request;

class TopItemLevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $_request)
    {
        $characters = TopItemLevels::GetTopItemLevels($_request)->paginate(16);


        return view('top_item_levels')->with(compact('characters'));
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
     * @param  \App\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function show(TopItemLevels $topItemLevels)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function edit(TopItemLevels $topItemLevels)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TopItemLevels $topItemLevels)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TopItemLevels  $topItemLevels
     * @return \Illuminate\Http\Response
     */
    public function destroy(TopItemLevels $topItemLevels)
    {
        //
    }
}

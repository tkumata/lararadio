<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channels;
use Symfony\Component\Process\Process;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $channels = Channels::where('public', '1')->get();
        return view('index', compact('channels'));
    }

    public function play(Request $request)
    {
        //
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'Linux') {
            $process = new Process('rtmpdump --live -r ' . $request->channel_url .' | mplayer -novideo -af volnorm=2:0.10 -');
            $process->setTimeout(3600);
            $process->setPty(true);
    
            $process->run();
        }

        return response()->json([
            'channel_id' => $request->channel_id,
            'channel_name' => $request->channel_name,
        ]);
    }

    public function stop(Request $request)
    {
        //
        $process = new Process('killall rtmpdump mplayer');
        $process->setTimeout(3600);
        $process->setPty(true);

        $process->run();

        return response()->json([
            'channel_id' => $request->channel_id,
        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

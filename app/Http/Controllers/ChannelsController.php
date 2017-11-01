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
        $channels = Channels::where('public', '1')->paginate(5);
        return view('index', compact('channels'));
    }

    /**
     * Play sound Controller
     * @return json
     */
    public function play(Request $request)
    {
        //
        $ch = Channels::find($request->channel_id);
        $ch->play = '1';
        $result = $ch->save();

        if (strtoupper(PHP_OS) === 'LINUX') {
            if (preg_match("/^rtmp/", $request->channel_url)) {
                // $live = " live=1";
                $live = "";
            } else {
                $live = "";
            }

            if (!empty($request->channel_url)) {
                // $cmd = 'mplayer -really-quiet -novideo -af volnorm=2:0.25 "'.$request->channel_url.$live.'"';
                $cmd = 'omxplayer "'.$request->channel_url.$live.'"';
            } else {
                $cmd = '/home/pi/bin/led_fire/led_fire.py';
            }

            /**
             * @todo Laravel 的に mplayer がデバイスを掴むのが気に入らないのかプロセスが裏に回らない。
             */
            // $process = new Process('nohup ' . $cmd . ' < /dev/null > /dev/null 2>&1 &');
            // $process->disableOutput();
            // $process->start(function(){
            //     return response()->json([
            //         'channel_name' => $ch->channel_name,
            //         'channel_id' => $request->channel_id,
            //     ]);
            // });
            $tmp = exec('nohup ' . $cmd . ' < /dev/null > /dev/null 2>&1 &');
        }

        return response()->json([
            'channel_name' => $ch->channel_name,
            'channel_id' => $request->channel_id,
        ]);
    }

    public function stop(Request $request)
    {
        //
        $ch = Channels::find($request->channel_id);
        $ch->play = '0';
        $result = $ch->save();

        // $process = new Process('/usr/bin/killall rtmpdump');
        // $process->disableOutput();
        // $process->start();

        if (empty($request->channel_url)) {
            $mplayerProcess = new Process('/usr/bin/killall python');
            $mplayerProcess->disableOutput();
            $mplayerProcess->start();
        } else {
            $mplayerProcess = new Process('/usr/bin/killall mplayer');
            $mplayerProcess->disableOutput();
            $mplayerProcess->start();
        }

        return response()->json([
            'channel_name' => $ch->channel_name,
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

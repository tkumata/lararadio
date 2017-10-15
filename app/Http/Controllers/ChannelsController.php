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

    public function play(Request $request)
    {
        //
        $ch = Channels::find($request->channel_id);
        $ch->play = '1';
        $result = $ch->save();

        if (strtoupper(PHP_OS) === 'LINUX') {
            if (preg_match("/^rtmp/", $request->channel_url)) {
                $live = " live=1";
            } else {
                $live = "";
            }

            if (!empty($request->channnel_url)) {
                /**
                 * @todo Laravel 的に mplayer がデバイスを掴むのが気に入らないのかプロセスが裏に回らない。
                 * 別途 artisan command でも作成して、それをキックするようにしよう。
                 */
                // $cmd = 'nohup sh -c "rtmpdump -q --live -r '.$request->channel_url.' -o - | mplayer -really-quiet -novideo -af volnorm=2:0.20 - >/dev/null 2>&1 &" >/dev/null 2>&1 &';
                $cmd = 'nohup mplayer -really-quiet -novideo -af volnorm=2:0.15 "'.$request->channel_url.$live.'" > /dev/null 2>&1 &';
            } else {
                $cmd = 'nohup /home/pi/bin/led_fire/led_fire.py > /dev/null 2>&1 &';
            }

            $process = new Process($cmd);
            $process->disableOutput();
            $process->start();
    }

        return response()->json([
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
        $mplayerProcess = new Process('/usr/bin/killall mplayer');
        $mplayerProcess->disableOutput();
        $mplayerProcess->start();
        $mplayerProcess = new Process('/usr/bin/killall python');
        $mplayerProcess->disableOutput();
        $mplayerProcess->start();

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

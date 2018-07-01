<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channels;
use Log;
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
        $channels = Channels::where('public', '1')->paginate(10);
        return view('index', compact('channels'));
    }

    /**
     * Play sound Controller
     *
     * @return json
     * @todo api に入れよう。
     */
    public function play(Request $request)
    {
        //
        $ch = Channels::find($request->channel_id);
        $ch->play = '1';
        $result = $ch->save();

        if (preg_match("/aandg22$/", $request->channel_url)) {
            $live = " live=1";
        } else {
            $live = "";
        }

        $osName = strtoupper(PHP_OS);

        if ($osName === 'LINUX') {
            if (!empty($request->channel_url)) {
                $cmd = 'nohup mplayer -really-quiet -vo null -af volnorm=2:0.25 "'.
                    $request->channel_url.$live.
                    '" < /dev/null > /dev/null 2>&1 &';
                // $cmd = 'omxplayer "'.$request->channel_url.'"';
                // $cmd = storage_path('/').'play.sh ' . $request->channel_url.$live;
            } else {
                $cmd = 'nohup /home/pi/bin/led_fire/led_fire.py > /dev/null 2>&1 &';
            }
        } elseif ($osName === 'DARWIN') {
                $cmd = '/usr/bin/open -a "QuickTime Player" '.$request->channel_url.$live;
        }

        /**
         * @todo Laravel 的に mplayer がデバイスを掴むのが気に入らないのか、プロセスが裏に回らないため return しない。
         */
        // $process = new Process($cmd.' > /dev/null 2>&1 &');
        // $process->disableOutput();
        // $process->start();
        exec($cmd);

        return [
            'channel_name' => $ch->channel_name,
            'channel_id' => $request->channel_id
        ];
    }

    /**
     * Stop sound Controller
     *
     * @return json
     * @todo api に入れよう。
     */
    public function stop(Request $request)
    {
        //
        $ch = Channels::find($request->channel_id);
        $ch->play = '0';
        $result = $ch->save();

        // $process = new Process('/usr/bin/killall rtmpdump');
        // $process->disableOutput();
        // $process->start();

        $osName = strtoupper(PHP_OS);

        if ($osName === 'LINUX') {
            if (empty($request->channel_url)) {
                $mplayerProcess = new Process('/usr/bin/killall python');
            } else {
                $mplayerProcess = new Process('/usr/bin/killall mplayer');
            }

            $mplayerProcess->disableOutput();
            $mplayerProcess->start();
        } elseif ($osName === 'DARWIN') {
            $cmd = '/usr/bin/killall "QuickTime Player"';
            exec($cmd);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channels;
use Log;
use Symfony\Component\Process\Process;

class ApiController extends Controller
{
    /**
     * Top
     */
    public function topindex()
    {
        $channels = Channels::where('public', '1')->get();
        $playing = Channels::where('play', '1')->first();
        $name = empty($playing) ? null : $playing->channel_name;

        return response()->json([
            'channels' => $channels,
            'name' => $name
        ]);
    }

    /**
     * Play sound Controller
     *
     * @return json
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
                $cmd = '/usr/bin/nohup /usr/bin/mplayer -really-quiet -vo null -af volnorm=2:0.25 "'
                    . $request->channel_url.$live
                    . '" < /dev/null > /dev/null 2>&1 &';
                // $cmd = storage_path('/').'play.sh ' . $request->channel_url.$live;
            } else {
                /**
                 * Check gpio, spi, i2c in /etc/group on your Raspberry Pi.
                 */
                $cmd = '/usr/bin/nohup /home/pi/bin/led_fire/led_fire.py > /dev/null 2>&1 &';
            }
        } elseif ($osName === 'DARWIN') {
            $cmd = '/usr/bin/open -a "QuickTime Player" '
                . $request->channel_url.$live;
        }

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
}

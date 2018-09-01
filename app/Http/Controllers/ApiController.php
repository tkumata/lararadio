<?php
/**
 * API Controller.
 *
 * @access public
 * @package Controller
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ApiController extends Controller
{
    /**
     * Top
     *
     * Get parts for top page of angularJS.
     *
     * @return json
     */
    public function topindex()
    {
        $channels = Channels::where('public', '1')->get();
        $playing = Channels::where('play', '1')->first();
        $name = empty($playing) ? null : $playing->channel_name;

        if (empty($playing->id)) {
            $ch = 1;
        } else {
            $ch = $playing->id;
        }

        return response()->json([
            'channels' => $channels,
            'name' => $name,
            'ch' => $ch
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
                 * Check /etc/group of gpio, spi, i2c on your Raspberry Pi (RaspbianOS).
                 * Because www-data does not have permission of devices.
                 */
                $cmd = '/usr/bin/nohup /home/pi/bin/led_fire/led_fire.py > /dev/null 2>&1 &';
            }
        } elseif ($osName === 'DARWIN') {
            $cmd = '/usr/bin/open -a "QuickTime Player" '
                . $request->channel_url.$live;
        }

        // Execute $cmd. But macOS cannot execute below Symfony.
        // So I use exec().
        // $process = new Process($cmd.' > /dev/null 2>&1 &');
        // $process->disableOutput();
        // $process->start();
        exec($cmd);

        return response()->json([
            'channel_name' => $ch->channel_name,
            'channel_id' => $request->channel_id,
        ]);
    }

    public function play2(Request $request)
    {
        $ch = Channels::find($request->channel_id);
        $ch->play = '1';
        $result = $ch->save();

        if (preg_match("/aandg22$/", $ch->channel_url)) {
            $live = " live=1";
        } else {
            $live = "";
        }

        $osName = strtoupper(PHP_OS);

        if ($osName === 'LINUX') {
            if (!empty($ch->channel_url)) {
                $cmd = '/usr/bin/nohup /usr/bin/mplayer -really-quiet -vo null -af volnorm=2:0.25 "'
                    . $ch->channel_url.$live
                    . '" < /dev/null > /dev/null 2>&1 &';
            } else {
                /**
                 * Check /etc/group of gpio, spi, i2c on your Raspberry Pi (RaspbianOS).
                 * Because www-data does not have permission of devices.
                 */
                $cmd = '/usr/bin/nohup /home/pi/bin/led_fire/led_fire.py > /dev/null 2>&1 &';
            }
        } elseif ($osName === 'DARWIN') {
            $cmd = '/usr/bin/open -a "QuickTime Player" '
                . $ch->channel_url.$live;
        }

        exec($cmd);

        return response()->json([
            'channel_name' => $ch->channel_name,
            'channel_id' => $request->channel_id,
        ]);
    }
    public function stop2(Request $request)
    {
        $ch = Channels::find($request->channel_id);
        $ch->play = '0';
        $result = $ch->save();

        $osName = strtoupper(PHP_OS);

        if ($osName === 'LINUX') {
            if (empty($ch->channel_url)) {
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
            'channel_name' => "----",
            'channel_id' => "",
        ]);
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
            'channel_name' => "----",
            'channel_id' => "",
        ]);
    }
}

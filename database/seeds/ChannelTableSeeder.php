<?php

use Illuminate\Database\Seeder;

class ChannelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'LED Fire, It makes a LED illumine like fire.',
            'channel_url' => '',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'A & G, sound only',
            'channel_url' => 'rtmp://fms-base1.mitene.ad.jp/agqr/aandg22',
            'play' => '0',
        ]);

        $url_977hits = 'http://19353.live.streamtheworld.com/977_HITSAAC_SC';
        $url_977hits .= '?ua=RadioTime&ttag=RadioTime&DIST=TuneIn&TGT=TuneIn&maxServers=2';
        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => '977 Hits',
            'channel_url' => $url_977hits,
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => '181 FM Rock',
            'channel_url' => 'http://listen.181fm.com/181-rock_128k.mp3',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => '181 FM Beatles',
            'channel_url' => 'http://listen.181fm.com/181-beatles_128k.mp3',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => '181 FM UK Top 40',
            'channel_url' => 'http://listen.181fm.com/181-uktop40_128k.mp3',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => '181 FM Breeze',
            'channel_url' => 'http://listen.181fm.com/181-breeze_128k.mp3',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'AFN FAN',
            'channel_url' => 'http://15113.live.streamtheworld.com/AFN_FANAAC.aac',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'AFN JOE',
            'channel_url' => 'http://19393.live.streamtheworld.com/AFN_JOEP.mp3',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'AFN Tokyo',
            'channel_url' => 'http://15363.live.streamtheworld.com/AFNP_TKO.mp3',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'AFN PowerTalk',
            'channel_url' => 'http://18813.live.streamtheworld.com/AFN_PTK.mp3',
            'play' => '0',
        ]);

        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'AFN Voice',
            'channel_url' => 'http://16603.live.streamtheworld.com/AFN_VCE.mp3',
            'play' => '0',
        ]);
    }
}

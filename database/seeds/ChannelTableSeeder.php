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
            'channel_name' => 'A & G Sound Only',
            'channel_url' => 'rtmp://fms-base1.mitene.ad.jp/agqr/aandg22',
        ]);
        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'AFN FAN',
            'channel_url' => 'http://15113.live.streamtheworld.com/AFN_FANAAC.aac',
        ]);
        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'AFN JOE',
            'channel_url' => 'http://19393.live.streamtheworld.com/AFN_JOEP.mp3',
        ]);
        DB::table('channels')->insert([
            'public' => '1',
            'channel_name' => 'AFN Tokyo',
            'channel_url' => 'http://15363.live.streamtheworld.com/AFNP_TKO.mp3',
        ]);
    }
}

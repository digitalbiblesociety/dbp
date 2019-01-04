<?php

use Illuminate\Database\Seeder;

use \App\Models\User\Study\HighlightColor;

class UserHighlightColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HighlightColor::create([
            'color'   => 'green',
            'hex'     => 'addd79',
            'red'     => '173',
            'green'   => '221',
            'blue'    => '121',
            'opacity' => 0.7
        ]);
        HighlightColor::create([
            'color'   => 'blue',
            'hex'     => '87adcc',
            'red'     => '135',
            'green'   => '173',
            'blue'    => '204',
            'opacity' => 0.7
        ]);
        HighlightColor::create([
            'color'   => 'pink',
            'hex'     => 'ea9dcf',
            'red'     => '234',
            'green'   => '157',
            'blue'    => '207',
            'opacity' => 0.7
        ]);
        HighlightColor::create([
            'color'   => 'yellow',
            'hex'     => 'e9de7f',
            'red'     => '223',
            'green'   => '222',
            'blue'    => '127',
            'opacity' => 0.7
        ]);
        HighlightColor::create([
            'color'   => 'orange',
            'hex'     => 'ddad6d',
            'red'     => '221',
            'green'   => '173',
            'blue'    => '109',
            'opacity' => 0.7
        ]);
    }
}

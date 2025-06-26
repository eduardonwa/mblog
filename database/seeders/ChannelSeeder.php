<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channel = Channel::firstOrCreate(
            ['slug' => 'lists'],
            [
                'name' => 'Lists',
                'description' => "Top 10's of your favorite bangers and such.",
                'is_active' => true,
            ]
        );

        $path = public_path('images/groups/g-metal.png');

        if (file_exists($path) && ! $channel->getFirstMedia('channel_sticker')) {
            $channel
                ->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection('channel_sticker');
        }
    }
}

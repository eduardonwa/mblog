<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Channel;

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
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Channel;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            [
                'slug' => 'lists',
                'name' => 'Lists',
                'description' => "Top 10's and your favorite bangers/albums whatever.",
                'is_active' => true,
            ],
            [
                'slug' => 'off-topic',
                'name' => 'Off-topic',
                'description' => "Off topic talk.",
                'is_active' => true,
            ],
            [
                'slug' => 'metal-discussion',
                'name' => 'Metal discussion',
                'description' => "Anything metal related.",
                'is_active' => true,
            ],
        ];

        foreach ($channels as $data) {
            Channel::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}

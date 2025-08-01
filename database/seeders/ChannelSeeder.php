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

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
        $path = public_path('images/groups/g-metal.png');

        if (!file_exists($path)) {
            if ($this->command) {
                $this->command->info('Saltando ChannelSeeder porque no existe la imagen requerida.');
            }
            return; // Salta el seeder
        }

        $channel = Channel::firstOrCreate(
            ['slug' => 'lists'],
            [
                'name' => 'Lists',
                'description' => "Top 10's of your favorite bangers and such.",
                'is_active' => true,
            ]
        );

        if (! $channel->getFirstMedia('channel_sticker')) {
            $channel
                ->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection('channel_sticker');
        }
    }
}

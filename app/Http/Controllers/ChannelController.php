<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Channel;

class ChannelController extends Controller
{
    public function index()
    {
        return Inertia::render('channels/index', [
            'channels' => $this->getActiveChannels()
        ]);
    }

    public function show($slug)
    {
        return Inertia::render('channels/show', [
            'channel' => $this->getChannelBySlug($slug)
        ]);
    }

    private function getActiveChannels()
    {
        return Channel::with('media')
            ->where('is_active', true)
            ->get();
    }

    private function getChannelBySlug(string $slug)
    {
        return Channel::with('media')->where('slug', $slug)->firstOrFail();
    }
}

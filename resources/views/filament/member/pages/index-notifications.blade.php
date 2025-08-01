<x-filament::page>
    
    @if($notifications->whereNull('read_at')->count())
        <form action="{{ route('member.notifications.markAllAsRead') }}" method="POST" class="flex justify-end">
            @csrf
            <button type="submit" class="px-3 py-1 rounded bg-morado-600 clr-morado-100 hover:text-white transition-colors text-sm font-medium">
                Mark all as read
            </button>
        </form>
    @endif

    @foreach ($notifications as $notification)
        <div class="p-2 hover:bg-black/40 transition duration-200">
            @if (!empty($notification->data['url']))
                <a
                    target="_blank"
                    class="font-semibold clr-morado-100"
                    href="{{ $notification->data['url']}}"
                >
                    {{ $notification->data['message'] ?? 'N\A' }}
                </a>
            @else
                <span class="font-semibold clr-morado-100">
                    {{ $notification->data['message'] ?? 'N\A' }}
                </span>
            @endif
            <div class="text-sm text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
            
            <div class="flex gap-2 mt-2">
                @if(!$notification->read_at)
                    <form action="{{ route('member.notifications.markAsRead', $notification) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm clr-morado-100 hover:underline">Mark as read</button>
                    </form>
                @endif
                <form action="{{ route('member.notifications.destroy', $notification) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm clr-error-100 hover:underline">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
</x-filament::page>

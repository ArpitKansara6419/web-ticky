@forelse ($allNotifications as $notification)
<li class="flex p-3 justify-between items-center border-b border-gray-200 rounded-lg hover:bg-gray-100 transition-all duration-200">
    <div class="flex justify-center items-center gap-3">
        <img src="/assets/ticky-logo-new.png" class="w-10 h-10 rounded-full ring-2 ring-teal-500" alt="">
        <div class="text-sm">
            <div class="font-semibold text-gray-800">{{ $notification->title }}</div>
            <div class="text-xs text-gray-500">{{ $notification->message }}</div>
        </div>
    </div>
    <div class="flex flex-col items-end gap-2">
        <span class="text-xs  font-medium text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
        {{-- Mark as Read button --}}
        @if(!$notification->is_read)
        <a data-href="{{ route('notifications.markAsRead', $notification->id) }}" class=" text-teal-500 hover:text-teal-700 text-xs cursor-pointer mark_as_read">
            <i class=" fas fa-check-circle"></i> Mark as Read
        </a>
        @endif
    </div>
</li>
@empty
<li class="text-sm text-gray-500">No notifications found.</li>
@endforelse
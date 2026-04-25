@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Notifications</h1>
            <p class="mt-2 text-gray-600">Stay updated with your order status and important updates</p>
        </div>

        @if($notifications->count() > 0)
            <div class="bg-white rounded-xl shadow-sm">
                @foreach($notifications as $notification)
                    <div class="flex items-start justify-between p-6 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-50">
                                    <i class="text-blue-600 fas fa-bell"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $notification->data['title'] ?? 'Notification' }}</h3>
                                    <p class="mt-2 text-gray-600">{{ $notification->data['message'] ?? '' }}</p>
                                    @if(isset($notification->data['order_id']))
                                        <a href="{{ route('orders.show', $notification->data['order_id']) }}"
                                           class="inline-flex items-center mt-3 text-sm font-medium text-blue-600 hover:text-blue-700">
                                            View Order <i class="ml-1 fas fa-arrow-right text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500 whitespace-nowrap">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center bg-white rounded-xl shadow-sm py-12">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100">
                    <i class="text-2xl text-gray-400 fas fa-bell-slash"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No notifications</h3>
                <p class="mt-2 text-gray-500 max-w-sm mx-auto">You don't have any notifications at the moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection

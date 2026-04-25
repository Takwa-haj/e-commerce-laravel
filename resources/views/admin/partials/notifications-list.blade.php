@if(count($notifications) > 0)
    @foreach($notifications as $notification)
        <li class="notification-item {{ $notification['read'] ? 'read' : 'unread' }}">
            <a href="{{ $notification['link'] }}" class="dropdown-item"
               @if(!$notification['read'])
               onclick="markNotificationAsRead('{{ $notification['id'] }}')"
               @endif>
                <div class="notification-content">
                    <div class="notification-title">{{ $notification['title'] }}</div>
                    <div class="notification-message">{{ $notification['message'] }}</div>
                    <div class="notification-time">{{ $notification['time'] }}</div>
                </div>
            </a>
        </li>
    @endforeach
@else
    <li class="dropdown-item">
        <div class="text-center py-3">
            <div class="text-muted">Aucune notification</div>
        </div>
    </li>
@endif

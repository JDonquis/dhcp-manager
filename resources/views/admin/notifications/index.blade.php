@extends('admin.layout.dashboard')

@section('title', 'Mis Notificaciones')

@section('style')
<style>
    .notification-card {
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .notification-card.unread {
        border-left: 4px solid #0d6efd;
        background-color: rgba(13, 110, 253, 0.03);
    }
    
    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #dc3545;
        color: white;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .notification-time {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .mark-all-btn {
        border-radius: 20px;
        padding: 0.25rem 1rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="content-header d-flex justify-content-between align-items-center">
    <h1 class="text-azul mb-0">Mis Notificaciones</h1>
    @if($notifications->whereNull('read_at')->count() > 0)
        <form id="markAllReadForm" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary mark-all-btn">
                <i class="fas fa-check-circle me-1"></i> Marcar todas como leídas
            </button>
        </form>
    @endif
</div>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4">
            @if($notifications->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <a href="{{ $notification->data['action_url'] ?? '#' }}" 
                           class="list-group-item list-group-item-action p-3 notification-card {{ $notification->read_at ? '' : 'unread' }}"
                           data-notification-id="{{ $notification->id }}">
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    <div class="notification-icon bg-{{ $notification->data['type'] ?? 'primary' }}">
                                        <i class="fas fa-{{ $notification->data['icon'] ?? 'bell' }}"></i>
                                    </div>
                                    @if(!$notification->read_at)
                                        <div class="notification-badge"></div>
                                    @endif
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">{{ $notification->data['message'] }}</h6>
                                        <small class="notification-time">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if(isset($notification->data['details']))
                                        <p class="mb-0 text-muted small">{{ $notification->data['details'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <div class="mt-4 d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="far fa-bell"></i>
                    </div>
                    <h4 class="text-muted">No tienes notificaciones</h4>
                    <p class="text-muted">Cuando tengas notificaciones, aparecerán aquí</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marcar notificación como leída al hacer clic
    document.querySelectorAll('.notification-card.unread').forEach(item => {
        item.addEventListener('click', function(e) {
            const notificationId = this.getAttribute('data-notification-id');
            if (notificationId) {
                fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        this.classList.remove('unread');
                    }
                });
            }
        });
    });

     const markAllReadForm = document.getElementById('markAllReadForm');
    if (markAllReadForm) {
        markAllReadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch("{{ route('notifications.mark-all-read') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover la clase 'unread' de todas las notificaciones
                    document.querySelectorAll('.notification-card.unread').forEach(card => {
                        card.classList.remove('unread');
                    });
                    
                    
                    // Opcional: ocultar el botón después de marcar todas
                    markAllReadForm.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al marcar las notificaciones como leídas');
            });
        });
    }

});
</script>
@endsection
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | DHCP Manager</title>

      <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/favicon/favicon.ico') }}" type="image/x-icon">

    <link rel="preload" href="{{ asset('fonts/Figtree/Figtree-VariableFont_wght.ttf') }}" as="font" type="font/ttf" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/icons/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}">
    @yield('style') 


</head>
<body>
    <div class="dashboard-container">
        <!-- Top Navigation -->
        <nav class="top-navbar">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="logo">
                <span class="app-name">DHCP Manager</span>
            </a>
            
            <ul class="nav-menu">
                <li class="nav-item @if(Request::is('hosts*')) active @endif">
                    <a href="{{ route('hosts.index') }}">Hosts</a>
                </li>
                <li class="nav-item @if(Request::is('departamentos*')) active @endif">
                    <a href="{{ route('departments.index') }}">Departamentos</a>
                </li>
                <li class="nav-item @if(Request::is('grupos*')) active @endif">
                    <a href="{{ route('groups.index') }}">Grupos</a>
                </li>
                <li class="nav-item @if(Request::is('usuarios*')) active @endif">
                    <a href="{{ route('users.index') }}">Usuarios</a>
                </li>
                <li class="nav-item @if(Request::is('configuracion*')) active @endif">
                    <a href="{{ route('configuracion.index') }}">Configuración</a>
                </li>
            </ul>
            
            <div class="user-section">
                <div class="dropdown notification-dropdown me-3">
                    <a class="btn btn-notification position-relative text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end notification-menu">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0">Notificaciones</h6>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <a href="#" class="mark-all-read text-primary small">Marcar todas como leídas</a>
                            @endif
                        </li>
                        
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            <li>
                                <a class="dropdown-item notification-item unread" href="{{ $notification->data['action_url'] ?? '#' }}" data-id="{{ $notification->id }}">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon text-primary bg-{{ $notification->data['type'] ?? 'primary' }} rounded-circle">
                                            <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }}"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="mb-0 notification-text">{{ $notification->data['message'] }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="text-center py-3">
                                <p class="text-muted m-0">No hay notificaciones nuevas</p>
                            </li>
                        @endforelse
                        
                        <li class="dropdown-footer">
                            <a href="{{ route('notifications.index') }}" class="text-center d-block">Ver todas las notificaciones</a>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <button class="btn btn-danger" id="generateDhcpBtn">
                        <i class="fa-solid fa-file"></i>
                        Generar archivo DHCP
                    </button>

                    <button class="btn btn-secondary" id="reloadDhcpBtn">
                        <i class="fa-solid fa-rotate"></i>
                        Reiniciar DHCP
                    </button>
                </div>

                <span class="username">{{ Auth::user()->name }}</span>
                <a href="{{ route('logout') }}" class="logout-btn btn text-white d-flex justify-content-center fs-5">
                   <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </nav>
        
        <!-- Contenido Principal -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/popper.min.js') }}" ></script>
    <script src="{{ asset('assets/bootstrap/sweetalert.min.js') }}" ></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}" ></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}" ></script>
    <script>
    
    document.addEventListener('DOMContentLoaded', function() {


    @if(session('success'))
        showToast('success', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showToast('error', '{{ session('error') }}');
    @endif

    @if($errors->any())
        showToast('error', `{!! implode('<br>', $errors->all()) !!}`);
    @endif

    // Función para mostrar toast
    function showToast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        Toast.fire({
            icon: icon,
            title: message,
            showCloseButton: true
        });
    }




    const generateBtn = document.getElementById('generateDhcpBtn');
    const reloadDhcpBtn = document.getElementById('reloadDhcpBtn');
    
    if (generateBtn) {
        generateBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Generar Archivo DHCP',
                text: 'Seleccione una opción para continuar',
                icon: 'question',
                showCancelButton: false,
                showDenyButton: true,
                confirmButtonText: '<i class="fas fa-file-export"></i> Solo Generar',
                denyButtonText: '<i class="fas fa-play"></i> Generar y Ejecutar',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    denyButton: 'btn btn-success me-2',
                    cancelButton: 'btn btn-secondary me-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    generateDhcpFile('generate');
                } else if (result.isDenied) {
                    generateDhcpFile('generate_and_execute');
                }
            });
        });
    }

    if (reloadDhcpBtn) {
        reloadDhcpBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Reiniciar archivo DHCP',
                text: 'Seleccione una opción para continuar',
                icon: 'question',
                confirmButtonText: '<i class="fa-solid fa-rotate"></i> Reiniciar',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-secondary me-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    reloadDhcpFile();
                } 
            });
        });
    }

    function generateDhcpFile(action) {
        // Mostrar loader
        Swal.fire({
            title: 'Procesando...',
            html: 'Generando archivo DHCP, por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Hacer la petición AJAX
        fetch('{{ route("generate-dhcp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ action: action })
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: data.success ? 'Éxito' : 'Error',
                text: data.message,
                icon: data.success ? 'success' : 'error',
                confirmButtonText: 'Aceptar'
            });
            
            if (data.download_url) {
                // Si hay URL de descarga, mostramos botón adicional
                Swal.fire({
                    title: 'Archivo Generado',
                    text: data.message,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Descargar Archivo',
                    cancelButtonText: 'Cerrar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.download_url;
                    }
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al generar el archivo DHCP',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            console.error('Error:', error);
        });
    }

    function reloadDhcpFile(action) {
        // Mostrar loader
        Swal.fire({
            title: 'Procesando...',
            html: 'Reiniciando archivo DHCP, por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Hacer la petición AJAX
        fetch('{{ route("reload-dhcp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: data.success ? 'Éxito' : 'Error',
                text: data.message,
                icon: data.success ? 'success' : 'error',
                confirmButtonText: 'Aceptar'
            });
            
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al reiniciar el archivo DHCP',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            console.error('Error:', error);
        });
    }

    // Marcar notificación como leída al hacer clic
    document.querySelectorAll('.notification-item.unread').forEach(item => {
        item.addEventListener('click', function(e) {
            const notificationId = this.getAttribute('data-id');
            if (notificationId) {
                fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
            }
        });
    });

    // Marcar todas como leídas
    const markAllRead = document.querySelector('.mark-all-read');
    if (markAllRead) {
        markAllRead.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        });
    }
});
    </script>
    @yield('scripts')
</body>
</html>
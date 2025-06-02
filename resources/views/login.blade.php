<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <!-- Precarga de fuentes y ahora también los SVG -->
    <link rel="preload" href="{{ asset('fonts/figtree/Figtree-VariableFont_wght.ttf') }}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{{ asset('fonts/figtree/Figtree-Italic-VariableFont_wght.ttf') }}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{{ asset('assets/svg/eye-open.svg') }}" as="image" type="image/svg+xml">
    <link rel="preload" href="{{ asset('assets/svg/eye-closed.svg') }}" as="image" type="image/svg+xml">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1 class="italic">Bienvenido</h1>
        </div>
        
        <form class="login-form" method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Ingresa tu usuario">
                
                @error('username')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group password-container">
                <label for="password">Contraseña</label>
                <div class="password-input-wrapper">
                    <input id="password" type="password" name="password" required placeholder="Ingresa tu contraseña">
                    <button type="button" class="toggle-password" aria-label="Mostrar contraseña">
                        <img src="{{ asset('assets/svg/eye-open.svg') }}" alt="Mostrar contraseña" class="eye-icon eye-open">
                        <img src="{{ asset('assets/svg/eye-closed.svg') }}" alt="Ocultar contraseña" class="eye-icon eye-closed" style="display: none;">
                    </button>
                </div>
                
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="login-btn">Iniciar Sesión</button>
            
            @if($errors->any())
                <div class="form-error italic">
                    Usuario o contraseña incorrectos. Inténtalo de nuevo.
                </div>
            @endif
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const password = document.querySelector('#password');
            const eyeOpen = document.querySelector('.eye-open');
            const eyeClosed = document.querySelector('.eye-closed');
            
            togglePassword.addEventListener('click', function() {
                // Cambiar el tipo de input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Alternar visibilidad de los íconos
                if (type === 'password') {
                    eyeOpen.style.display = 'block';
                    eyeClosed.style.display = 'none';
                } else {
                    eyeOpen.style.display = 'none';
                    eyeClosed.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>
@extends('admin.layout.dashboard')

@section('title', 'Crear Usuario')

@section('style')
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
        padding: 12px 16px;
        height: auto;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    .btn-azul {
        background-color: #0d6efd;
        color: white;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s;
        padding: 12px 24px;
    }
    
    .btn-azul:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }
    
    .form-floating>label {
        padding: 0.8rem 1rem;
    }
    
    .invalid-feedback {
        font-size: 0.85rem;
    }
    
    .card-body {
        padding: 2.5rem;
    }
    
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="content-header d-flex align-items-center justify-content-between">
    <h1 class="text-azul mb-0">
        <i class="fa-solid fa-user-plus me-2"></i> Crear Usuario
    </h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-5">
            <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Nombre Completo -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Nombre completo" required
                                   pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]{3,50}">
                            <label for="name">Nombre Completo</label>
                            <div class="invalid-feedback">
                                Por favor ingrese un nombre válido (3-50 caracteres).
                            </div>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Nombre de usuario" required
                                   pattern="[A-Za-z0-9_]{4,20}">
                            <label for="username">Nombre de Usuario</label>
                            <div class="invalid-feedback">
                                Por favor ingrese un username válido (4-20 caracteres, solo letras, números y guión bajo).
                            </div>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="col-md-12 position-relative">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Contraseña" required
                                   pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$">
                            <label for="password">Contraseña</label>
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                            <div class="invalid-feedback">
                                La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.
                            </div>
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="col-md-12 position-relative">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" placeholder="Confirmar contraseña" required>
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <i class="fas fa-eye password-toggle" id="togglePasswordConfirmation"></i>
                            <div class="invalid-feedback">
                                Las contraseñas deben coincidir.
                            </div>
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <div class="col-12 mt-4">
                        <button class="btn btn-azul px-4 py-3" type="submit">
                            <i class="fas fa-save me-2"></i>Guardar Usuario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario
    (function () {
        'use strict'
        
        const forms = document.querySelectorAll('.needs-validation')
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                // Validar que las contraseñas coincidan
                const password = document.getElementById('password');
                const passwordConfirm = document.getElementById('password_confirmation');
                
                if (password.value !== passwordConfirm.value) {
                    passwordConfirm.setCustomValidity('Las contraseñas no coinciden');
                    passwordConfirm.classList.add('is-invalid');
                } else {
                    passwordConfirm.setCustomValidity('');
                }
                
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })();

    // Toggle para mostrar/ocultar contraseña
    const togglePassword = document.querySelector('#togglePassword');
    const togglePasswordConfirmation = document.querySelector('#togglePasswordConfirmation');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const password = document.querySelector('#password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    }
    
    if (togglePasswordConfirmation) {
        togglePasswordConfirmation.addEventListener('click', function() {
            const password = document.querySelector('#password_confirmation');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    }

    // Validación en tiempo real para confirmación de contraseña
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    
    if (password && passwordConfirm) {
        passwordConfirm.addEventListener('input', function() {
            if (password.value !== this.value) {
                this.setCustomValidity('Las contraseñas no coinciden');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
    }
});
</script>
@endsection
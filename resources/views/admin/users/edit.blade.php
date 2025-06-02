@extends('admin.layout.dashboard')

@section('title', 'Editar Usuario')

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
    
    .btn-danger-link {
        background: none;
        border: none;
        color: #dc3545;
        padding: 0;
        text-decoration: underline;
        cursor: pointer;
        font-size: inherit;
    }
    
    .btn-danger-link:hover {
        color: #bb2d3b;
        text-decoration: none;
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
    
    .password-optional {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    .readonly-field {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    
    .view-only-message {
        background-color: #fff3cd;
        color: #856404;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid #ffeeba;
    }
</style>
@endsection

@section('content')
@php
    $isCurrentUser = auth()->id() == $user->id;
@endphp

<div class="content-header d-flex align-items-center justify-content-between">
    <h1 class="text-azul mb-0">
        <i class="fa-solid fa-user-edit me-2"></i> Editar Usuario: {{ $user->name }}
    </h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-5">
            @if(!$isCurrentUser)
            <div class="view-only-message">
                <i class="fas fa-info-circle me-2"></i>
                Solo puedes ver la información de este usuario. Para realizar cambios, debes ser el propio usuario.
            </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Nombre Completo -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control {{ !$isCurrentUser ? 'readonly-field' : '' }}" 
                                   id="name" name="name" 
                                   placeholder="Nombre completo" 
                                   required
                                   pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]{3,50}"
                                   value="{{ old('name', $user->name) }}"
                                   {{ !$isCurrentUser ? 'readonly' : '' }}>
                            <label for="name">Nombre Completo</label>
                            <div class="invalid-feedback">
                                Por favor ingrese un nombre válido (3-50 caracteres).
                            </div>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control {{ !$isCurrentUser ? 'readonly-field' : '' }}" 
                                   id="username" name="username" 
                                   placeholder="Nombre de usuario" 
                                   required
                                   pattern="[A-Za-z0-9_]{4,20}"
                                   value="{{ old('username', $user->username) }}"
                                   {{ !$isCurrentUser ? 'readonly' : '' }}>
                            <label for="username">Nombre de Usuario</label>
                            <div class="invalid-feedback">
                                Por favor ingrese un username válido (4-20 caracteres, solo letras, números y guión bajo).
                            </div>
                        </div>
                    </div>

                    <!-- Contraseña (solo visible para el propio usuario) -->
                    @if($isCurrentUser)
                    <div class="col-md-12 position-relative">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Contraseña"
                                   pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$">
                            <label for="password">Nueva Contraseña</label>
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                            <div class="password-optional">(Dejar en blanco para mantener la actual)</div>
                            <div class="invalid-feedback">
                                La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.
                            </div>
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="col-md-12 position-relative">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" placeholder="Confirmar contraseña">
                            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                            <i class="fas fa-eye password-toggle" id="togglePasswordConfirmation"></i>
                            <div class="invalid-feedback">
                                Las contraseñas deben coincidir.
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Botones -->
                    <div class="col-12 mt-4 d-flex justify-content-between align-items-center">
                        @if($isCurrentUser)
                        <button class="btn btn-azul px-4 py-3" type="submit">
                            <i class="fas fa-save me-2"></i>Actualizar Usuario
                        </button>
                        @else
                        <div></div> <!-- Espacio vacío para mantener el layout -->
                        @endif
                        
                        <button type="button" class="btn btn-danger-link" id="deleteUserBtn">
                            <i class="fas fa-trash me-1"></i>Eliminar Usuario
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Formulario oculto para eliminar -->
            <form id="deleteForm" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Función para confirmar eliminación (versión mejorada)
function setupDeleteConfirmation() {
    const deleteBtn = document.getElementById('deleteUserBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto! El usuario y todos sus datos asociados serán eliminados permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        });
    }
}

// Toggle para mostrar/ocultar contraseña
function setupPasswordToggles() {
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
}

// Validación del formulario
function setupFormValidation() {
    'use strict';
    
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            // Validar que las contraseñas coincidan solo si se ingresó una nueva
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            
            if (password && password.value && password.value !== passwordConfirm.value) {
                passwordConfirm.setCustomValidity('Las contraseñas no coinciden');
                passwordConfirm.classList.add('is-invalid');
            } else {
                if(passwordConfirm) passwordConfirm.setCustomValidity('');
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    setupDeleteConfirmation();
    setupPasswordToggles();
    
    @if($isCurrentUser)
    setupFormValidation();
    @endif
});
</script>
@endsection
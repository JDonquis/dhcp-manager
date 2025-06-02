@extends('admin.layout.dashboard')

@section('title', 'Editar Departamento')

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
    
    .ip-range-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .ip-range-separator {
        font-size: 1.2rem;
        color: #6c757d;
        margin-top: 8px;
    }
    
    .invalid-feedback {
        font-size: 0.85rem;
    }
    
    .card-body {
        padding: 2.5rem;
    }
</style>
@endsection

@section('content')
<div class="content-header d-flex align-items-center justify-content-between">
    <h1 class="text-azul mb-0">
        <i class="fa-solid fa-building me-2"></i> Editar Departamento: {{ $department->name }}
    </h1>
    <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-5">
            <form action="{{ route('departments.update', $department->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Nombre del Departamento -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Nombre del departamento" required
                                   pattern="[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s\-]{3,50}"
                                   value="{{ old('name', $department->name) }}">
                            <label for="name">Nombre del Departamento</label>
                            <div class="invalid-feedback">
                                Por favor ingrese un nombre válido (3-50 caracteres).
                            </div>
                        </div>
                    </div>

                    <!-- Rango de IP -->
                    <div class="col-md-12">
                        <label class="form-label">Rango de IP</label>
                        <div class="ip-range-container">
                            <div class="form-floating flex-grow-1">
                                <input type="text" class="form-control ip-input" id="ip_range_start" 
                                       name="ip_range_start" placeholder="192.168.1.1" required
                                       pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                       value="{{ old('ip_range_start', $department->ip_range_start) }}">
                                <label for="ip_range_start">IP Inicial</label>
                                <div class="invalid-feedback">
                                    Ingrese una dirección IP válida (ej. 192.168.1.1)
                                </div>
                            </div>
                            
                            <span class="ip-range-separator">-</span>
                            
                            <div class="form-floating flex-grow-1">
                                <input type="text" class="form-control ip-input" id="ip_range_end" 
                                       name="ip_range_end" placeholder="192.168.1.254" required
                                       pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                       value="{{ old('ip_range_end', $department->ip_range_end) }}">
                                <label for="ip_range_end">IP Final</label>
                                <div class="invalid-feedback">
                                    Ingrese una dirección IP válida (ej. 192.168.1.254)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 mt-4 d-flex justify-content-between align-items-center">
                        <button class="btn btn-azul px-4 py-3" type="submit">
                            <i class="fas fa-save me-2"></i>Actualizar Departamento
                        </button>
                        
                        <button type="button" class="btn btn-danger-link" onclick="confirmDelete()">
                            <i class="fas fa-trash me-1"></i>Eliminar Departamento
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Formulario oculto para eliminar -->
            <form id="deleteForm" action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Validación del formulario
(function () {
    'use strict'
    
    const forms = document.querySelectorAll('.needs-validation')
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
    })
})();

// Función para confirmar eliminación
function confirmDelete() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto! El departamento y todos sus hosts asociados serán eliminados.",
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
}

// Validación de IPs en tiempo real
const ipInputs = document.querySelectorAll('.ip-input');
    
ipInputs.forEach(input => {
    input.addEventListener('input', function() {
        const ipPattern = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        
        if (this.value && !ipPattern.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    input.addEventListener('blur', function() {
        const ipPattern = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        
        if (!ipPattern.test(this.value)) {
            this.setCustomValidity('Ingrese una dirección IP válida');
        } else {
            this.setCustomValidity('');
        }
    });
});

// Validar que la IP final sea mayor que la inicial
const ipStart = document.getElementById('ip_range_start');
const ipEnd = document.getElementById('ip_range_end');

function validateIpRange() {
    if (ipStart.value && ipEnd.value) {
        const startParts = ipStart.value.split('.').map(Number);
        const endParts = ipEnd.value.split('.').map(Number);
        
        for (let i = 0; i < 4; i++) {
            if (endParts[i] < startParts[i]) {
                ipEnd.setCustomValidity('La IP final debe ser mayor o igual que la IP inicial');
                ipEnd.classList.add('is-invalid');
                return;
            } else if (endParts[i] > startParts[i]) {
                break;
            }
        }
        
        ipEnd.setCustomValidity('');
        ipEnd.classList.remove('is-invalid');
    }
}

// Ejecutar validación al cargar la página
validateIpRange();

ipStart.addEventListener('change', validateIpRange);
ipEnd.addEventListener('change', validateIpRange);
</script>
@endsection
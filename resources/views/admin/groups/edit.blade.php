@extends('admin.layout.dashboard')

@section('title', 'Editar Grupo')

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
</style>
@endsection

@section('content')
<div class="content-header d-flex align-items-center justify-content-between">
    <h1 class="text-azul mb-0">
        <i class="fa-solid fa-users me-2"></i> Editar Grupo: {{ $group->name }}
    </h1>
    <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-5">
            <form action="{{ route('groups.update', $group->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Nombre del Grupo -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Nombre del grupo" required
                                   pattern="[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s\-]{3,50}"
                                   value="{{ old('name', $group->name) }}">
                            <label for="name">Nombre del Grupo</label>
                            <div class="invalid-feedback">
                                Por favor ingrese un nombre válido (3-50 caracteres).
                            </div>
                        </div>
                    </div>

                    <!-- Departamento -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <select class="form-select" id="department_id" name="department_id" required>
                                <option value="" disabled>Seleccione un departamento</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $group->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="department_id">Departamento</label>
                            <div class="invalid-feedback">
                                Seleccione un departamento válido.
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 mt-4 d-flex justify-content-between align-items-center">
                        <button class="btn btn-azul px-4 py-3" type="submit">
                            <i class="fas fa-save me-2"></i>Actualizar Grupo
                        </button>
                        
                        <button type="button" class="btn btn-danger-link" onclick="confirmDelete()">
                            <i class="fas fa-trash me-1"></i>Eliminar Grupo
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Formulario oculto para eliminar -->
            <form id="deleteForm" action="{{ route('groups.destroy', $group->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
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
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })();

    // Validación en tiempo real para el nombre
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function() {
        const pattern = /^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s\-]{3,50}$/;
        if (!pattern.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Validación para el select de departamento
    const departmentSelect = document.getElementById('department_id');
    departmentSelect.addEventListener('change', function() {
        if (this.value === "") {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Validar campos al cargar si ya tienen valores
    if (nameInput.value) {
        nameInput.dispatchEvent(new Event('input'));
    }
    if (departmentSelect.value) {
        departmentSelect.dispatchEvent(new Event('change'));
    }
});

// Función para confirmar eliminación
function confirmDelete() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto! El grupo y todos sus hosts asociados serán eliminados.",
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
</script>
@endsection
@extends('admin.layout.dashboard')

@section('title', 'Editar Host')

@section('style')
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .btn-azul {
        background-color: #0d6efd;
        color: white;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .btn-azul:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
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
</style>
@endsection

@section('content')
<div class="content-header d-flex align-items-center justify-content-between">
    <h1 class="text-azul mb-0">
        <i class="fa-solid fa-server me-2"></i> Editar Host: {{ $host->name }}
    </h1>
    <a href="{{ route('hosts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-5">
            <form action="{{ route('hosts.update', $host->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Departamento -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="department_id" name="department_id" required>
                                <option value="" disabled>Seleccione un departamento</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" 
                                            data-groups='@json($department->groups)'
                                            data-ips='@json($department->available_ips)'
                                            {{ $host->department_id == $department->id ? 'selected' : '' }}>
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

                    <!-- Nombre -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Nombre del host" required value="{{ $host->name }}">
                            <label for="name">Nombre del Host</label>
                            <div class="invalid-feedback">
                                Por favor ingrese un nombre válido.
                            </div>
                        </div>
                    </div>

                    <!-- Grupo -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="group_id" name="group_id" required>
                                <option value="" disabled>Seleccione un grupo</option>
                                @foreach($host->department->groups as $group)
                                    <option value="{{ $group->id }}" 
                                            {{ $host->group_id == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="group_id">Grupo | Grupo Actual: {{ $host->group->name ?? 'Sin grupo' }}</label>
                            <div class="invalid-feedback">
                                Seleccione un grupo válido.
                            </div>
                        </div>
                    </div>

                    <!-- Usuario -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="user" name="user" 
                                   placeholder="Nombre del host" value="{{ $host->user }}">
                            <label for="name">Usuario</label>
                            <div class="invalid-feedback">
                                Por favor ingrese un nombre válido.
                            </div>
                        </div>
                    </div>

                    <!-- Dirección IP -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="ip" name="ip" required>
                                <option value="" disabled>Seleccione una IP</option>
                                @foreach($host->department->availableIps() as $ip)
                                    <option value="{{ $ip }}" 
                                            {{ $host->ip == $ip ? 'selected' : '' }}>
                                        {{ $ip }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="ip">Dirección IP | IP Actual: {{ $host->ip }}</label>
                            <div class="invalid-feedback">
                                Seleccione una dirección IP válida.
                            </div>
                        </div>
                    </div>

                    <!-- Dirección MAC -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="mac" name="mac" 
                                   placeholder="00:1A:2B:3C:4D:5E" required
                                   pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"
                                   value="{{ $host->mac }}">
                            <label for="mac">Dirección MAC</label>
                            <div class="invalid-feedback">
                                Ingrese una MAC válida (ej. 00:1A:2B:3C:4D:5E)
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 mt-4 d-flex justify-content-between align-items-center">
                        <button class="btn btn-azul px-4 py-3" type="submit">
                            <i class="fas fa-save me-2"></i>Actualizar
                        </button>
                        
                        <button type="button" class="btn btn-danger-link" onclick="confirmDelete()">
                            <i class="fas fa-trash me-1"></i>Eliminar Host
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Formulario oculto para eliminar -->
            <form id="deleteForm" action="{{ route('hosts.destroy', ['host' => $host->id]) }}" method="POST" class="d-none">
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
        text: "¡No podrás revertir esto! El host será eliminado permanentemente.",
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

document.addEventListener('DOMContentLoaded', function() {
    // Máscara para MAC
    const macInput = document.getElementById('mac');
    let lastLength = 0;

    macInput.addEventListener('keydown', function(e) {
        lastLength = e.target.value.length;
    });

    macInput.addEventListener('input', function(e) {
        const position = e.target.selectionStart;
        let value = e.target.value.replace(/[^0-9A-Fa-f]/g, '');
        
        if (value.length > 12) value = value.substr(0, 12);
        
        let formatted = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 2 === 0) formatted += ':';
            formatted += value[i];
        }
        
        e.target.value = formatted.toUpperCase();
        
        if (e.target.value.length < lastLength && position > 0) {
            if (e.target.value[position - 1] === ':') {
                e.target.setSelectionRange(position - 1, position - 1);
            }
        }
    });

    macInput.addEventListener('blur', function(e) {
        const isValid = /^([0-9A-F]{2}:){5}[0-9A-F]{2}$/.test(e.target.value);
        if (!isValid) {
            e.target.setCustomValidity('Ingrese una dirección MAC válida (ej: 00:1A:2B:3C:4D:5E)');
            e.target.classList.add('is-invalid');
        } else {
            e.target.setCustomValidity('');
            e.target.classList.remove('is-invalid');
        }
    });

    // Elementos del formulario
    const departmentSelect = document.getElementById('department_id');
    const groupSelect = document.getElementById('group_id');
    const ipSelect = document.getElementById('ip');
    const nameInput = document.getElementById('name');

    // Manejar cambio de departamento
    departmentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        // Cargar grupos
        const groups = JSON.parse(selectedOption.getAttribute('data-groups')) || [];
        groupSelect.innerHTML = '<option value="" disabled>Seleccione un grupo</option>';
        
        if (groups.length > 0) {
            groups.forEach(group => {
                const option = document.createElement('option');
                option.value = group.id;
                option.textContent = group.name;
                groupSelect.appendChild(option);
            });
            groupSelect.disabled = false;
            
            // Seleccionar el grupo actual si existe en los nuevos grupos
            const currentGroupId = "{{ $host->group_id }}";
            if (currentGroupId && groups.some(g => g.id == currentGroupId)) {
                groupSelect.value = currentGroupId;
            }
        } else {
            groupSelect.innerHTML = '<option value="" disabled>No hay grupos disponibles</option>';
            groupSelect.disabled = true;
        }
        
        // Cargar IPs disponibles
        const availableIps = JSON.parse(selectedOption.getAttribute('data-ips')) || [];
        ipSelect.innerHTML = '<option value="" disabled>Seleccione una IP</option>';
        
        if (availableIps.length > 0) {
            availableIps.forEach(ip => {
                const option = document.createElement('option');
                option.value = ip;
                option.textContent = ip;
                ipSelect.appendChild(option);
            });
            ipSelect.disabled = false;
            
            // Seleccionar la IP actual si existe en las nuevas IPs
            const currentIp = "{{ $host->ip }}";
            if (currentIp && availableIps.includes(currentIp)) {
                ipSelect.value = currentIp;
            }
        } else {
            ipSelect.innerHTML = '<option value="" disabled>No hay IPs disponibles</option>';
            ipSelect.disabled = true;
        }
        
        // Sugerir nombre si está vacío
        if (!nameInput.value) {
            const selectedDept = selectedOption.text;
            nameInput.value = `${selectedDept.toUpperCase().substr(0, 3)}-${Math.floor(100 + Math.random() * 900)}`;
        }
    });

    // Disparar evento change al cargar si ya hay un departamento seleccionado
    if (departmentSelect.value) {
        departmentSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
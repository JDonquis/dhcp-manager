@extends('admin.layout.dashboard')

@section('title', 'Configuración de Red')

@section('style')
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .form-control, .form-select, .form-input {
        border-radius: 8px;
        border: 1px solid #e0e6ed;
        transition: all 0.3s;
        padding: 12px 15px;
        font-size: 14px;
    }
    
    .form-control:focus, .form-select:focus, .form-input:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }
    
    .btn-primary {
        background-color: #4361ee;
        color: white;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s;
        padding: 12px 24px;
        border: none;
    }
    
    .btn-primary:hover {
        background-color: #3a56d4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
    }
    
    .config-section {
        background-color: #f8fafc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .section-title {
        color: #4361ee;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    
    .json-editor {
        min-height: 150px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
    }
    
    .form-label {
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 8px;
    }
    
    .info-text {
        font-size: 13px;
        color: #718096;
        margin-top: 5px;
    }
</style>
@endsection

@section('content')
<div class="content-header d-flex align-items-center justify-content-between">
    <h1 class="text-azul mb-0">
        <i class="fas fa-network-wired me-2"></i>Configuración de Red
    </h1>
</div>

<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-5">
            <form action="{{ route('configuracion.update') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="config-section">
                    <h4 class="section-title">
                        <i class="fas fa-server"></i> Configuración Básica
                    </h4>
                    
                    <div class="row g-4">
                        <!-- Subred -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subnet" class="form-label">Subred</label>
                                <input type="text" class="form-control" id="subnet" name="subnet" 
                                       placeholder="Ej: 192.168.1.0" required
                                       pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                       value="{{ $network->subnet ?? '' }}">
                                <div class="invalid-feedback">
                                    Ingrese una dirección de subred válida.
                                </div>
                                <small class="info-text">Dirección de red principal (ej. 192.168.1.0)</small>
                            </div>
                        </div>

                        <!-- Máscara de Red -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="netmask" class="form-label">Máscara de Red</label>
                                <input type="text" class="form-control" id="netmask" name="netmask" 
                                       placeholder="Ej: 255.255.255.0" required
                                       pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                       value="{{ $network->netmask ?? '' }}">
                                <div class="invalid-feedback">
                                    Ingrese una máscara de red válida.
                                </div>
                                <small class="info-text">Máscara de subred (ej. 255.255.255.0)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="config-section">
                    <h4 class="section-title">
                        <i class="fas fa-cog"></i> Opciones de Red
                    </h4>
                    
                    <div class="form-group">
                        <label for="options" class="form-label">Opciones (JSON)</label>
                        <textarea class="form-control json-editor" id="options" name="options" 
                                  rows="5" placeholder='{"dns": ["8.8.8.8"], "gateway": "192.168.1.1"}'>{{ json_encode($network->options ?? [], JSON_PRETTY_PRINT) }}</textarea>
                        <div class="invalid-feedback">
                            Ingrese un JSON válido para las opciones.
                        </div>
                        <small class="info-text">Configuraciones adicionales en formato JSON</small>
                    </div>
                </div>

                <div class="config-section">
                    <h4 class="section-title">
                        <i class="fas fa-tools"></i> Parámetros Avanzados
                    </h4>
                    
                    <div class="form-group">
                        <label for="params" class="form-label">Parámetros (JSON)</label>
                        <textarea class="form-control json-editor" id="params" name="params" 
                                  rows="5" placeholder='{"dhcp_range": ["192.168.1.100", "192.168.1.200"]}'>{{ json_encode($network->params ?? [], JSON_PRETTY_PRINT) }}</textarea>
                        <div class="invalid-feedback">
                            Ingrese un JSON válido para los parámetros.
                        </div>
                        <small class="info-text">Parámetros avanzados de la red en formato JSON</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-primary px-4 py-3" type="submit">
                        <i class="fas fa-save me-2"></i>Actualizar
                    </button>
                </div>
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
            // Validar campos JSON
            try {
                if (document.getElementById('options').value) {
                    JSON.parse(document.getElementById('options').value);
                }
                if (document.getElementById('params').value) {
                    JSON.parse(document.getElementById('params').value);
                }
            } catch (e) {
                event.preventDefault();
                alert('Error en formato JSON: ' + e.message);
                return;
            }
            
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
    })
})();

// Mejorar la edición de JSON
document.addEventListener('DOMContentLoaded', function() {
    // Opcional: Podrías integrar un editor JSON más avanzado como:
    // https://github.com/josdejong/jsoneditor
    
    // Validación en tiempo real para campos JSON
    const jsonFields = document.querySelectorAll('.json-editor');
    jsonFields.forEach(field => {
        field.addEventListener('input', function() {
            try {
                if (this.value) {
                    JSON.parse(this.value);
                    this.classList.remove('is-invalid');
                }
            } catch (e) {
                this.classList.add('is-invalid');
            }
        });
    });
    
    // Validación de direcciones IP
    const ipFields = document.querySelectorAll('input[type="text"][pattern]');
    ipFields.forEach(field => {
        field.addEventListener('input', function() {
            const pattern = new RegExp(this.pattern);
            if (this.value && !pattern.test(this.value)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endsection
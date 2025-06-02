@extends('admin.layout.dashboard')

@section('title', 'Usuarios')

@section('content')
<div class="content-header d-flex align-items-center">
    <h1 class="text-azul">Usuarios</h1>
    <div class="ms-auto pe-5">
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            Nuevo Usuario
        </a>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="table-card">
        <div class="table-header">
            <h5 class="mb-0">Listado de Usuarios</h5>
        </div>
        <div class="table-responsive">
            <table id="users-table" class="table table-hover align-middle mb-0 w-100">
                <thead class="table-header">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Nombre</th>
                        <th>Username</th>
                        <th>Fecha Creación</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    <!-- Datos cargados via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery) {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.data') }}",
            columns: [
                { 
                    data: 'id', 
                    name: 'id',
                    className: 'text-center'
                },
                { 
                    data: 'name', 
                    name: 'name',
                    render: function(data) {
                        return '<span class="fw-semibold">'+data+'</span>';
                    }
                },
                { 
                    data: 'username', 
                    name: 'username',
                    render: function(data) {
                        return '<span class="text-muted">@'+data+'</span>';
                    }
                },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleDateString('es-ES', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                    }
                },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center',
                }
            ],
            order: [[3, 'desc']] // Ordenar por fecha de creación descendente
        });
    }
});
</script>
@endsection
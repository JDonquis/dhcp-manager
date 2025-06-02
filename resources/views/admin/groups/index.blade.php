@extends('admin.layout.dashboard')

@section('title', 'Grupos')

@section('content')
<div class="content-header d-flex align-items-center">
    <h1 class="text-azul">Grupos</h1>
    <div class="ms-auto pe-5">
        <a href="{{ route('groups.create') }}" class="btn btn-primary">
            Nuevo Grupo
        </a>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="table-card">
        <div class="table-header">
            <h5 class="mb-0">Listado de Grupos</h5>
        </div>
        <div class="table-responsive">
            <table id="groups-table" class="table table-hover align-middle mb-0 w-100">
                <thead class="table-header">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Nombre</th>
                        <th>Departamento</th>
                        <th>Nro de Hosts</th>
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
        $('#groups-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('groups.data') }}",
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
                    data: 'department.name', 
                    name: 'department.name',
                    render: function(data, type, row) {
                        if (!data) return '<span class="text-muted fw-bold fs-6">Sin departamento</span>';
                        
                        return `
                                    <span class="badge rounded-pill me-1 mb-1 fw-normal fs-6" 
                                        style="background-color: #f8f9fa; color: #495057; border: 1px solid #dee2e6;">
                                        ${data}
                                    </span>
                                `;
                    }
                },
                { 
                    data: 'hosts_count', 
                    name: 'hosts_count',
                    className: 'text-center',
                    render: function(data) {
                        return `<span class="badge bg-primary rounded-pill">${data}</span>`;
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
        });
    }
});
</script>
@endsection
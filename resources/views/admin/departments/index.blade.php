@extends('admin.layout.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="content-header d-flex align-items-center">
    <h1 class="text-azul">Departamentos</h1>
    <div class="ms-auto pe-5">
        
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            Nuevo departamento
        </a>
    </div>
</div>


<div class="container-fluid px-4">
    <div class="table-card">
        <div class="table-header">
            <h5 class="mb-0">Listado de departamentos</h5>
        </div>
        <div class="table-responsive">
            <table id="mi-tabla" class="table table-hover align-middle mb-0 w-100">
                <thead class="table-header">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Nombre</th>
                        <th>Rango IP Inicial</th>
                        <th>Rango IP Final</th>
                        <th>Grupos</th>
                        <th>Nro hosts</th>

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
            $('#mi-tabla').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('departments.data') }}",
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
                        data: 'ip_range_start', 
                        name: 'ip_range_start',
                        orderable: false, 
                    },
                         
                    {
                        data: 'ip_range_end',
                        name: 'ip_range_end',  
                        orderable: false,
                    },
                    {
                        data: 'groups',
                        name: 'groups.name',
                        orderable: false,
                        render: function(data, type, row) {
                            if (!data || data.length === 0) return 'Sin Grupo';

                            return data.map(groupName => {
                                return `
                                    <span class="badge rounded-pill me-1 mb-1 fw-normal fs-6" 
                                        style="background-color: #f8f9fa; color: #495057; border: 1px solid #dee2e6;">
                                        ${groupName}
                                    </span>
                                `;
                            }).join('');
                        }
                    },
                    { 
                        data: 'hosts_count', 
                        name: 'hosts_count',
                        className: 'text-center',
                        orderable: false, 
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
                ]
            });
        }
    });
</script>
@endsection
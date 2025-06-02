@extends('admin.layout.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="content-header d-flex align-items-center">
    <h1 class="text-azul">Hosts</h1>
    <div class="ms-auto pe-5">
        
        <a href="{{ route('hosts.create') }}" class="btn btn-primary">
            Nuevo host
        </a>
    </div>
</div>


<div class="container-fluid px-4">
    <div class="table-card">
        <div class="table-header">
            <h5 class="mb-0">Listado de Hosts</h5>
        </div>
        <div class="table-responsive">
            <table id="mi-tabla" class="table table-hover align-middle mb-0 w-100">
                <thead class="table-header">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Host</th>
                        <th>Usuario</th>
                        <th>Dirección IP</th>
                        <th>Dirección MAC</th>
                        <th>Departamento</th>
                        <th>Grupo</th>

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
                ajax: "{{ route('hosts.data') }}",
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
                        data: 'user', 
                        name: 'user',
                        render: function(data) {
                            return '<span class="text-muted">@'+data+'</span>';
                        }
                    },
                    { 
                        data: 'ip', 
                        name: 'ip',
                        orderable: false, 
                    },
                         
                    {
                        data: 'mac',
                        name: 'mac',  
                        orderable: false,
                    },
                    { 
                        data: 'department.name',
                        name: 'department.name',
                         render: function(data, type, row) {
                            if (!data) return '';
                            
                            const stringToColor = (str) => {
                                let hash = 0;
                                for (let i = 0; i < str.length; i++) {
                                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                                }
                                let color = 'hsl(';
                                color += (hash % 360) + ', '; // Hue
                                color += '70%, '; // Saturation
                                color += '60%)'; // Lightness
                                return color;
                            };
                            
                            const bgColor = stringToColor(data);
                            return `<span class="badge rounded-pill fw-bold fs-6" style="background-color: ${bgColor}; color: #fff">${data}</span>`;
                        }
                     },
                    { 
                        data: 'group.name', 
                        name: 'group.name',
                        orderable: false, 

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
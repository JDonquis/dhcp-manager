@extends('admin.layout.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="content-header">
    <h1>Panel de Control</h1>
    <p>Bienvenido</p>
</div>

<div class="dashboard-cards">
    <div class="card">
        <h3>Hosts Activos</h3>
        <p class="count">{{ $hosts }}</p>
    </div>
    <div class="card">
        <h3>Usuarios</h3>
        <p class="count">{{ $users }}</p>
    </div>
    <div class="card">
        <h3>Departamentos</h3>
        <p class="count">{{ $departments }}</p>
    </div>
    <div class="card">
        <h3>Grupos</h3>
        <p class="count">{{ $groups }}</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Scripts específicos para esta vista
    console.log('Dashboard cargado');
</script>
@endsection
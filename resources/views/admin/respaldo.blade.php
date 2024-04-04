<x-app-layout>
    <x-slot name="header">
        <div class="layout-container">
            <div id="app">
                <respaldo-vue></respaldo-vue>
                <br>
                <!--Star Contenido Blade-->
                <h1>Descargar Respaldo de base de datos</h1><br>
                <a href="{{ route('respaldofile.all.data') }}" class="btn btn-primary">Exportar Datos</a>


                {{-- <a href="{{ route('backup.create') }}">Crear Respaldo</a>
                <a href="{{ route('backup.download', ['fileName' => 'nombre_del_archivo.sql']) }}">Descargar Respaldo</a> --}}

                
                <!--End Contenido Blade-->
            </div>
        </div>

        @section('titulo', 'Respaldo :: Sistema de Votación COEPCI')
    </x-slot>
</x-app-layout>
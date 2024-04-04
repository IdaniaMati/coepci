<x-app-layout>
    <x-slot name="header">
        <div class="layout-container">
            <div id="app">
                <respaldo-vue></respaldo-vue>
                <a href="{{ route('respaldofile.all.data') }}" class="btn btn-primary">Exportar Datos</a>
            </div>
        </div>

        @section('titulo', 'Respaldo :: Sistema de Votación COEPCI')
    </x-slot>
</x-app-layout>
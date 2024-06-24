<x-app-layout>
    <x-slot name="header">
        <div class="layout-container">
            <div id="app">
                <permisos-vue></permisos-vue>
            </div>
        </div>

        @section('titulo', 'Permisos :: Sistema de Votación COEPCI')
    </x-slot>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="layout-container">
            <div id="app">
                <datos-vue></datos-vue>
            </div>
        </div>

        @section('titulo', 'Datos :: Sistema de Votación COEPCI')
    </x-slot>
</x-app-layout>

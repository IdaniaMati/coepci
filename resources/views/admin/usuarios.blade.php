<x-app-layout>
    <x-slot name="header">
        <div class="layout-container">
            <div id="app">
                <usuarios-vue></usuarios-vue>
            </div>
        </div>

        @section('titulo', 'Usuarios :: Sistema de Votación COEPCI')
    </x-slot>
</x-app-layout>
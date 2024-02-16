<x-app-layout>
    <x-slot name="header">
        <div class="layout-container">
            <div id="app">
                <dashboard-vue></dashboard-vue>
            </div>
        </div>

        @section('titulo', 'Home :: Sistema de Votación COEPCI')
    </x-slot>
</x-app-layout>

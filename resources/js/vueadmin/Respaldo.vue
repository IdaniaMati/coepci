<template>
    <div>
        <h1>Descargar Respaldo de base de datos</h1>
        <button @click="exportData" class="btn btn-primary">Exportar Datos</button>
    </div>
</template>

<script>
export default {
    methods: {
        exportData() {
            axios.get("{{ route('respaldofile.all.data') }}")
                .then(response => {
                })
                .catch(error => {
                });
        }
    }
}
</script>
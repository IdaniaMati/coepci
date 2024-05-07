<template>
        <div class="text-center mb-4">
                <h2><strong>CONTROL DE ACTIVIDADES</strong></h2>
        </div>
                <div class="nav-item d-flex align-items-center">
                        <h5 class="card-header"><strong>Buscar</strong></h5>
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                        <button class="btn btn-success ms-2" @click="exportarExcel">Exportar Excel</button>

                </div>
</template>

<style scoped>
    .table-container {
    }

    .info-container {
      display: flex;
      justify-content: space-between;
      padding: 10px;
      background-color: #f0f0f0;
      border-top: 1px solid #ccc;
    }

    .info-item {
      flex: 1;
      text-align: center;
    }

</style>



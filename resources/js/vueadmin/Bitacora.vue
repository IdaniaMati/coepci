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

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Dependencia</th>
                                <th>Fecha y hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="bitacora in bitacoras" :key="bitacora.id">
                            <td>{{ bitacora.id }}</td>
                            <td>{{ bitacora.id_user}}</td>
                            <td>{{ bitacora.action }}</td>
                            <td>{{ descripcionDepen(bitacora.id_depen) }}</td>
                            <td>{{ obtenerFechaFormateada(bitacora.created_at) }}</td>
                        </tr>
                        </tbody>
                    </table>
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

<script>
  import axios from 'axios';
  import { utils as XLSXUtils, writeFile } from 'xlsx';



  export default {

    data() {
      return {
        bitacoras: [],
        dependencias: [],
        id: "",
        id_user: "",
        id_depen: "",
        action: "",
        created_at: ""
      };
    },

    mounted() {
        this.obtenerActividades();
        this.obtenerDependencias();
    },

    methods: {

         async obtenerActividades() {
             try {
             const response = await axios.get('/obtenerBitacora');
             this.bitacoras = response.data;
             } catch (error) {
             console.error('Error al obtener las actividades:', error);
             }
         },


         obtenerDependencias(){
            axios.get('/obtenerDependencias')
                .then((response) => {
                    this.dependencias = response.data.user;
                })
                .catch((error) => {
                    console.error(error);
                });
        },


        descripcionDepen(idDepen) {
            const dependencia = this.dependencias.find(dep => dep.id === idDepen);
            return dependencia ? dependencia.descripcion : 'Sin descripción';
        },

        obtenerFechaFormateada(fechaOriginal) {

            const fecha = new Date(fechaOriginal);
            const fechaFormateada = fecha.toLocaleString();

            return fechaFormateada;
        },

        exportarExcel() {
      const bitacorasParaExportar = this.bitacoras.map(bitacora => {
        return {
          ID: bitacora.id,
          Usuario: bitacora.id_user,
          Acción: bitacora.action,
          Dependencia: this.descripcionDepen(bitacora.id_depen),
          'Fecha y hora': this.obtenerFechaFormateada(bitacora.created_at),
        };
      });

        const workbook = XLSXUtils.book_new();
        const worksheet = XLSXUtils.json_to_sheet(bitacorasParaExportar);
        XLSXUtils.book_append_sheet(workbook, worksheet, 'Bitacoras');

        const blob = writeFile(workbook, 'Bitacoras.xlsx', { bookType: 'xlsx', type: 'blob' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');

        a.href = url;
        a.download = 'Bitacoras.xlsx';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        },

    }
  };
</script>



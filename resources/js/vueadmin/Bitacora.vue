<template>
    <div class="text-center mb-4">
         <h2><strong>CONTROL DE ACTIVIDADES</strong></h2>
    </div>
                <div class="nav-item d-flex align-items-center">
                        <h5 class="card-header"><strong>Buscar</strong></h5>
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                        <h5 class="card-header"><strong>Filtrar Fecha</strong></h5>
                        <input v-model="filtroFecha1" class="form-control" type="date" @input="filtrarPorFechas" required />&nbsp;&nbsp;
                        <input v-model="filtroFecha2" class="form-control" type="date" @input="filtrarPorFechas" required />
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
                    <tr v-for="bitacora in paginatedBitacoras" :key="bitacora.id">
                        <td>{{ bitacora.id }}</td>
                        <td>{{ bitacora.id_user }}</td>
                        <td>{{ bitacora.action }}</td>
                        <td>{{ descripcionDepen(bitacora.id_depen) }}</td>
                        <td>{{ obtenerFechaFormateada(bitacora.created_at) }}</td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-auto">
                        <button @click="paginaAnterior" :disabled="pagina === 1" class="btn btn-primary mr-2">
                        Anterior
                        </button>
                    </div>
                <div class="col-md-auto">
                    <ul class="pagination">
                        <li v-for="numero in totalPaginas" :key="numero" class="page-item" :class="{ active: numero === pagina }">
                            <a class="page-link" @click="cambiarPagina(numero)">{{ numero }}</a>
                        </li>
                    </ul>
                </div>
                    <div class="col-md-auto">
                        <button @click="paginaSiguiente" :disabled="pagina === totalPaginas" class="btn btn-primary ml-2">
                        Siguiente
                        </button>
                    </div>
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
      filtro: '',
      filtroFecha1: '',
      filtroFecha2: '',
      pagina: 1,
      totalPaginas: 0,
      registrosPorPagina: 7,
    };
  },

  computed: {
    bitacoraFiltrados() {
      const filtroMinusculas = this.filtro.toLowerCase();
      return this.bitacoras.filter((bitacora) => {
        const nombreCompleto = `${bitacora.id_user}`;
        const nombreSinAcentos = nombreCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        const descripcionDependencia = this.descripcionDepen(bitacora.id_depen).toLowerCase();
        const dependenciaSinAcentos = descripcionDependencia.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        const fechaBitacora = new Date(bitacora.created_at);
        const filtroFechaInicio = this.filtroFecha1 ? new Date(this.filtroFecha1) : null;
        const filtroFechaFin = this.filtroFecha2 ? new Date(this.filtroFecha2) : null;

        const cumpleConFecha =
          (!filtroFechaInicio || fechaBitacora >= filtroFechaInicio) &&
          (!filtroFechaFin || fechaBitacora <= filtroFechaFin);

        return (
          cumpleConFecha &&
          (nombreCompleto.toLowerCase().includes(filtroMinusculas) ||
            nombreSinAcentos.includes(filtroMinusculas) ||
            bitacora.action.toLowerCase().includes(filtroMinusculas) ||
            dependenciaSinAcentos.includes(filtroMinusculas) ||
            bitacora.created_at.toString().includes(filtroMinusculas))
        );
      });
    },

    totalPages() {
      return Math.ceil(this.bitacoraFiltrados.length / this.registrosPorPagina);
    },

    paginatedBitacoras() {
      const startIndex = (this.pagina - 1) * this.registrosPorPagina;
      const endIndex = startIndex + this.registrosPorPagina;
      return this.bitacoraFiltrados.slice(startIndex, endIndex);
    },
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
            this.calcularTotalPaginas();
        } catch (error) {
            console.error('Error al obtener las actividades:', error);
        }
        },

        async obtenerDependencias() {
        try {
            const response = await axios.get('/obtenerDependencias');
            this.dependencias = response.data.user;
        } catch (error) {
            console.error(error);
        }
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
        const bitacorasParaExportar = this.bitacoraFiltrados.map(bitacora => {
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

        filtrarPorFechas() {
        this.calcularTotalPaginas();
        this.pagina = 1;
        },

        calcularTotalPaginas() {
        this.totalPaginas = Math.ceil(this.bitacoraFiltrados.length / this.registrosPorPagina);
        },

        paginaAnterior() {
        if (this.pagina > 1) {
            this.pagina--;
        }
        },

        paginaSiguiente() {
        if (this.pagina < this.totalPaginas) {
            this.pagina++;
        }
        },

        cambiarPagina(numero) {
        this.pagina = numero;
        },
    },
};
</script>



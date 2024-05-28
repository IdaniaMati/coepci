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
                        <input v-model="filtroFecha2" class="form-control" type="date" @input="filtrarPorFechas" required />&nbsp;&nbsp;
                        <button class="btn btn-refresh ms-2"  title="Limpiar fechas" @click="limpiarFiltro">
                            <i class="bi bi-arrow-clockwise"  style="font-size: 20px;"></i>
                        </button>
                        <button class="btn btn-download ms-3" title="Exportar Excel" @click="exportarExcel">
                           <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" viewBox="0 0 512 512"><path fill="#ffffff" d="M453.547 273.449H372.12v-40.714h81.427zm0 23.264H372.12v40.714h81.427zm0-191.934H372.12v40.713h81.427zm0 63.978H372.12v40.713h81.427zm0 191.934H372.12v40.714h81.427zm56.242 80.264c-2.326 12.098-16.867 12.388-26.58 12.796H302.326v52.345h-36.119L0 459.566V52.492L267.778 5.904h34.548v46.355h174.66c9.83.407 20.648-.291 29.197 5.583c5.991 8.608 5.41 19.543 5.817 29.43l-.233 302.791c-.29 16.925 1.57 34.2-1.978 50.892m-296.51-91.256c-16.052-32.57-32.395-64.909-48.39-97.48c15.82-31.698 31.408-63.512 46.937-95.327c-13.203.64-26.406 1.454-39.55 2.385c-9.83 23.904-21.288 47.169-28.965 71.888c-7.154-23.323-16.634-45.774-25.3-68.515c-12.796.698-25.592 1.454-38.387 2.21c13.493 29.78 27.86 59.15 40.946 89.104c-15.413 29.081-29.837 58.57-44.785 87.825c12.737.523 25.475 1.047 38.212 1.221c9.074-23.148 20.357-45.424 28.267-69.038c7.096 25.359 19.135 48.798 29.023 73.051c14.017.99 27.976 1.862 41.993 2.676M484.26 79.882H302.326v24.897h46.53v40.713h-46.53v23.265h46.53v40.713h-46.53v23.265h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v26.897H484.26z"/></svg>
                        </button>
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

        this.pagina = 1;

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

        limpiarFiltro() {
        this.filtroFecha1 = '';
        this.filtroFecha2 = '';
        this.calcularTotalPaginas();
        this.pagina = 1;
    },
    },
};
</script>





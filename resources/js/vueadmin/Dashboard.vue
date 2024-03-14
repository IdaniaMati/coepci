<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>PANEL PRINCIPAL EMPLEADOS</strong></h2>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="info-container">
                <div class="info-item">
                    <strong>Total Empleados:</strong> {{ empleados.length }}
                </div>
                <div class="info-item">
                    <strong>Ronda 1 - Votaron:</strong> {{ votosRonda1 }} | <strong> Sin Votar:</strong> {{ empleados.length - votosRonda1 }}
                </div>
                <div class="info-item">
                    <strong>Ronda 2 - Votaron:</strong> {{ votosRonda2 }} | <strong> Sin Votar:</strong> {{ empleados.length - votosRonda2 }}
                </div>
                </div>

                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Empleados</strong></h5>
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                    <button class="btn btn-success ms-2" @click="exportarExcel">Exportar Excel</button>
                </div>

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;">ID</th>
                            <th style="width: 20%;">Nombre</th>
                            <th style="width: 15%;">Curp</th>
                            <th style="width: 25%;">Cargo</th>
                            <th style="width: 1%;">Grupo</th>
                            <th style="width: 5%;">Voto 1</th>
                            <th style="width: 5%;">Voto 2</th>
                        </tr>
                        </thead>
                        <tbody v-if="paginatedEmpleados.length > 0">
                            <tr v-for="empleado in paginatedEmpleados" :key="empleado.id">
                                <td>{{ empleado.id }}</td>
                                <td>{{ empleado.nombre + ' ' + empleado.apellido_paterno + ' ' + empleado.apellido_materno }}</td>
                                <td>{{ empleado.curp }}</td>
                                <td>{{ empleado.cargo }}</td>
                                <td>{{ empleado.id_grup }}</td>
                                <td>
                                    <span v-if="empleado.votoRonda1" class="badge bg-label-primary me-1">Sí</span>
                                    <span v-else class="badge bg-label-info me-1">No</span>
                                </td>
                                <td>
                                    <span v-if="empleado.votoRonda2" class="badge bg-label-primary me-1">Sí</span>
                                    <span v-else class="badge bg-label-info me-1">No</span>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="5">No hay empleados que coincidan con la búsqueda.</td>
                            </tr>
                        </tbody>
                    </table>

                    <br>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                <a class="page-link" href="#" @click="prevPage">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in totalPages" :key="page" :class="{ 'active': currentPage === page }">
                                <a class="page-link" href="#" @click="gotoPage(page)">{{ page }}</a>
                            </li>
                            <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                <a class="page-link" href="#" @click="nextPage">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                    <br>

                </div>

            </div>
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
import { utils as XLSXUtils, writeFile } from 'xlsx';

    export default {
        data() {
            return {
            empleados: [],
            filtro: '',
            votosRonda1: 0,
            votosRonda2: 0,
            currentPage: 1,
            perPage: 10,
            };
        },

    computed: {
        empleadosFiltrados() {
            const filtroMinusculas = this.filtro.toLowerCase();
            return this.empleados.filter((empleado) => {
                const nombreCompleto = `${empleado.nombre} ${empleado.apellido_paterno} ${empleado.apellido_materno}`;
                const nombreSinAcentos = nombreCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();

                return (
                    nombreCompleto.toLowerCase().includes(filtroMinusculas) ||
                    nombreSinAcentos.includes(filtroMinusculas) ||
                    empleado.curp.toLowerCase().includes(filtroMinusculas) ||
                    empleado.cargo.toLowerCase().includes(filtroMinusculas) ||
                    empleado.id_grup.toString().includes(filtroMinusculas)
                );
            });
        },

        totalPages() {
            return Math.ceil(this.empleadosFiltrados.length / this.perPage);
        },

        paginatedEmpleados() {
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            return this.empleadosFiltrados.slice(startIndex, endIndex);
        },
    },

    mounted() {
        this.obtenerEmpleados();
        this.obtenerRegistrosVotos();
     },

    methods: {

        obtenerEmpleados() {
            axios.get('/obtenerEmpleados')
                .then((response) => {
                    if (response.data.empleados) {
                        this.empleados = response.data.empleados;

                        axios.get('/obtenerVotosRondas')
                            .then((responseVotos) => {
                                const votosRondas = responseVotos.data.resultados;

                                // Asignar los votos a cada empleado
                                this.empleados.forEach(empleado => {
                                    const votoRonda = votosRondas.find(voto => voto.id_empleado === empleado.id);

                                    if (votoRonda) {
                                        empleado.votoRonda1 = votoRonda.voto_ronda1;
                                        empleado.votoRonda2 = votoRonda.voto_ronda2;
                                    } else {
                                        empleado.votoRonda1 = false;
                                        empleado.votoRonda2 = false;
                                    }
                                });
                            })
                            .catch((errorVotos) => {
                                //console.error("Error al obtener los votos en ambas rondas", errorVotos);
                            });
                    } else {
                        //console.log(response.data.message);
                    }
                })
                .catch((error) => {
                    //console.error(error);
                });
        },

        async obtenerRegistrosVotos() {
            try {
                const response = await axios.get('/obtenerRegistrosVotos');
                this.votosRonda1 = response.data.votosRonda1;
                this.votosRonda2 = response.data.votosRonda2;
                } catch (error) {
                    //console.error(error);
            }
        },

        exportarExcel() {

            const empleadosParaExportar = this.empleadosFiltrados.map(empleado => {
                return {
                    id: empleado.id,
                    nombre: empleado.nombre + ' ' + empleado.apellido_paterno + ' ' + empleado.apellido_materno,
                    curp: empleado.curp,
                    cargo: empleado.cargo,
                    id_grup: empleado.id_grup,
                    Ronda1: empleado.votoRonda1 ? 'Sí' : 'No',
                    Ronda2: empleado.votoRonda2 ? 'Sí' : 'No'
                };
            });

            const workbook = XLSXUtils.book_new();
            const worksheet = XLSXUtils.json_to_sheet(empleadosParaExportar);
            XLSXUtils.book_append_sheet(workbook, worksheet, 'Empleados');

            const blob = writeFile(workbook, 'Empleados.xlsx', { bookType: 'xlsx', type: 'blob' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');

            a.href = url;
            a.download = 'Empleados.xlsx';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        },


        gotoPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },

        prevPage() {
            this.gotoPage(this.currentPage - 1);
        },

        nextPage() {
            this.gotoPage(this.currentPage + 1);
        },

    },
};

</script>

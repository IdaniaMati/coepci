<template>
         <!-- Filtrados dependencia admin -->
        <div>
            <div class="mb-3" v-if="showDependenciaSelect === true">
            <label for="defaultSelect" class="form-label">Seleccione una Dependencia o Institución</label>
            <select id="showDependenciaSelect" class="form-control" v-model="dependenciaSeleccionada">
                <option disabled selected>Seleccionar dependencia</option>
                <option v-for="dependencia in dependencias" :value="dependencia.id">{{ dependencia.descripcion }}</option>
            </select>
        </div>
        <!-- Fin filtrados dependencia admin -->

        <div class="text-center mb-4">
            <h2><strong>PANEL PRINCIPAL EMPLEADOS</strong></h2>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="info-container">
                    <div class="nav-item d-flex align-items-center">
                        <button v-if="hab_permisos('Crear_empleados')" class="btn btn-add" @click="nuevo" title="Agregar">
                            <i class="bi bi-person-fill-add" style="font-size: 20px;"></i>
                            &nbsp;<p> Agregar</p>
                        </button>
                    </div>
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

                <!-- Filtrados generales -->
                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Empleados</strong></h5>
                    <i class="bx bx-search fs-4 lh-0"></i>
                        <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                    <h5 class="card-header"><strong>Grupo</strong></h5>
                        <select v-model="filtroGrupo" class="form-control" id="grupo" required>
                            <option value="">Seleccionar Grupo</option>
                            <option v-for="grupo in grupos" :key="grupo.id" :value="grupo.id">{{ grupo.grupo }}</option>
                        </select>
                    <h5 class="card-header"><strong>Votos</strong></h5>
                        <select v-model="ronda" class="form-control" id="votos" required>
                            <option value="">Seleccionar Ronda</option>
                            <option value="1">Ronda 1</option>
                            <option value="2">Ronda 2</option>
                        </select>
                    <button class="btn btn-refresh ms-2" title="Limpiar filtros" @click="limpiarFiltro">
                        <i class="bi bi-arrow-clockwise"  style="font-size: 20px;"></i>
                    </button>
                    <button class="btn btn-download ms-2" title="Exportar Excel" @click="exportarExcel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" viewBox="0 0 512 512"><path fill="#ffffff" d="M453.547 273.449H372.12v-40.714h81.427zm0 23.264H372.12v40.714h81.427zm0-191.934H372.12v40.713h81.427zm0 63.978H372.12v40.713h81.427zm0 191.934H372.12v40.714h81.427zm56.242 80.264c-2.326 12.098-16.867 12.388-26.58 12.796H302.326v52.345h-36.119L0 459.566V52.492L267.778 5.904h34.548v46.355h174.66c9.83.407 20.648-.291 29.197 5.583c5.991 8.608 5.41 19.543 5.817 29.43l-.233 302.791c-.29 16.925 1.57 34.2-1.978 50.892m-296.51-91.256c-16.052-32.57-32.395-64.909-48.39-97.48c15.82-31.698 31.408-63.512 46.937-95.327c-13.203.64-26.406 1.454-39.55 2.385c-9.83 23.904-21.288 47.169-28.965 71.888c-7.154-23.323-16.634-45.774-25.3-68.515c-12.796.698-25.592 1.454-38.387 2.21c13.493 29.78 27.86 59.15 40.946 89.104c-15.413 29.081-29.837 58.57-44.785 87.825c12.737.523 25.475 1.047 38.212 1.221c9.074-23.148 20.357-45.424 28.267-69.038c7.096 25.359 19.135 48.798 29.023 73.051c14.017.99 27.976 1.862 41.993 2.676M484.26 79.882H302.326v24.897h46.53v40.713h-46.53v23.265h46.53v40.713h-46.53v23.265h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v26.897H484.26z"/></svg>
                    </button>
                </div>
                <!-- Fin Filtrados generales -->

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;">ID</th>
                            <th style="width: 15%;">Nombre</th>
                            <th style="width: 15%;">Curp</th>
                            <th style="width: 20%;">Cargo</th>
                            <th style="width: 1%;">Grupo</th>
                            <th style="width: 3%;">Voto 1</th>
                            <th style="width: 3%;">Voto 2</th>
                            <th style="width: 15%;">Dependencia</th>
                            <th style="width: 10%;">Opciones</th>
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
                                <td>{{ descripcionDepen (empleado.id_depen) }}</td>
                                <td>
                                    <button v-if="hab_permisos('Editar_empleados')" class="btn btn-edit btn-sm" title="Editar" @click="detalleEmpleado(empleado.id)">
                                        <i class="bi bi-pencil-fill" style="font-size: 15px;"></i>
                                    </button>
                                    <button v-if="hab_permisos('Eliminar_empleados')" class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarEmpleado(empleado.id)">
                                        <i class="bi bi-trash3-fill" style="font-size: 15px;"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="5">No hay empleados que coincidan con la búsqueda.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Inicio Modal Agregar Emeplado -->
                <div class="container">
                    <div class="modal fade" id="largeModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><strong>Empleado</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Grupo</label>
                                                    <select v-model="id_grup" class="form-control" id="grupo" required>
                                                        <option v-for="grupo in grupos" :key="grupo.id" :value="grupo.id">{{ grupo.grupo }}</option>
                                                    </select>
                                                <div v-if="!id_grup" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Curp</label>
                                                <input v-model="curp" class="form-control" placeholder="CURP" maxlength="18" required/>
                                                <div v-if="!curp" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Nombres</label>
                                                <input v-model="nombre" class="form-control" placeholder="Nombres" required/>
                                                <div v-if="!nombre" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Apellido Paterno</label>
                                                <input v-model="apellido_paterno" class="form-control" placeholder="Apellido paterno" required/>
                                                <div v-if="!apellido_paterno" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Apellido materno</label>
                                                <input v-model="apellido_materno" class="form-control" placeholder="Apellido materno" required/>
                                                <div v-if="!apellido_materno" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Cargo</label>
                                                <input v-model="cargo" class="form-control" placeholder="Cargo" required/>
                                                <div v-if="!cargo" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 0" class="btn btn-primary" @click="agregarEmpleado">Guardar</button>
                                    <button v-if="bandera === 1" class="btn btn-primary" @click="editarEmpleado">Editar</button>
                                    <button class="btn btn-cerrar" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Final Modal Agregar Empleado -->

                <!-- Paginador -->
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
                <!-- Fin paginador -->
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
import permisos from "../permisos/permisos.vue";

export default {

    components: {

    },extends:permisos,

    data() {
        return {
            empleados: [],
            dependencias: [],
            dependenciaSeleccionada: null,
            showDependenciaSelect: false,
            filtro: '',
            filtroGrupo: '',
            ronda: '',
            votosRonda1: 0,
            votosRonda2: 0,
            currentPage: 1,
            perPage: 10,
            idEmp: "",
            idEmpleado: "",
            bandera: "",
            nombre: "",
            apellido_paterno: "",
            apellido_materno: "",
            curp: "",
            cargo: "",
            id_grup: "",
            id_depen: "",
            grupos: [],
            lista_permisos:[],
            showDependenciaSelect: false,
        };
    },

    computed: {

        empleadosFiltrados() {

            const filtroMinusculas = this.filtro.toLowerCase();
            let empleados = this.empleados.filter((empleado) => {
                const nombreCompleto = `${empleado.nombre} ${empleado.apellido_paterno} ${empleado.apellido_materno}`;
                const cargoCompleto = `${empleado.cargo}`;
                const descripcionDependencia = this.descripcionDepen(empleado.id_depen).toLowerCase();
                const nombreSinAcentos = nombreCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                const cargoSinAcentos = cargoCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                const dependenciaSinAcentos = descripcionDependencia.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();

                this.currentPage = 1;

                return (
                    nombreCompleto.toLowerCase().includes(filtroMinusculas) ||
                    nombreSinAcentos.includes(filtroMinusculas) ||
                    (empleado.curp && empleado.curp.toLowerCase().includes(filtroMinusculas)) ||
                    cargoCompleto.toLowerCase().includes(filtroMinusculas) ||
                    cargoSinAcentos.includes(filtroMinusculas) ||
                    (empleado.id_grup && empleado.id_grup.toString().includes(filtroMinusculas)) ||
                    dependenciaSinAcentos.includes(filtroMinusculas)
                );
            });

            if (this.dependenciaSeleccionada) {
            empleados = empleados.filter(empleado => empleado.id_depen === this.dependenciaSeleccionada);
            }

            if (this.filtroGrupo) {
                empleados = empleados.filter(empleado => empleado.id_grup == this.filtroGrupo);
            }

            if (this.ronda) {
                empleados = empleados.filter(empleado => {
                    if (this.ronda === '1') {
                        return empleado.votoRonda1;
                    } else if (this.ronda === '2') {
                        return empleado.votoRonda2;
                    } else {
                        return true;
                    }
                });
            }
            return empleados;
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
        this.obtenerGrupos();
        this.obtenerPermisos();
        this.obtenerDependencias();
        this.cargarDependencias();
     },

    methods: {

        cargarDependencias() {
            axios.get('/dashboardWithDependencia')
                .then(response => {
                    this.showDependenciaSelect = response.data.showDependenciaSelect;
                    console.log("Valor de showDependenciaSelect:", this.showDependenciaSelect);
                    console.log("Dependencias obtenidas:", this.dependencias);
                })
                .catch(error => {
                    console.error('Error al obtener los datos:', error);
                });
        },

        obtenerPermisos(){
            axios
                .get("/Obtenerpermisos")
                .then((response) => {
                    this.lista_permisos  = response.data;

                })
                .catch((error) => {
                    console.error(error);

                });
        },

        obtenerEmpleados() {
            axios.get('/obtenerEmpleados')
                .then((response) => {
                    if (response.data.empleados) {
                        this.empleados = response.data.empleados;

                        axios.get('/obtenerVotosRondas')
                            .then((responseVotos) => {
                                const votosRondas = responseVotos.data.resultados;

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
                                this.filtrarEmpleados();
                            })
                            .catch((errorVotos) => {
                            });
                    } else {
                    }
                })
                .catch((error) => {
                });
        },

        async obtenerGrupos() {
             try {
                 const response = await axios.get('/obtenerGrupos');
                 if (response.data.success) {
                 this.grupos = response.data.grupos;
                 } else {
                 console.error('Error al obtener los grupos');
                 }
             } catch (error) {
                 console.error('Error al obtener los grupos:', error);
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

        async agregarEmpleado() {

        const curp = this.curp.toUpperCase().substring(0, 18);

            if (curp.length !== 18) {
                Swal.fire('Error', 'La CURP debe tener exactamente 18 caracteres', 'error');
                return;
            }

            const nuevoEmpleado = {
                id_grup: this.id_grup,
                curp: this.curp,
                nombre: this.nombre,
                apellido_paterno: this.apellido_paterno,
                apellido_materno: this.apellido_materno,
                cargo: this.cargo,
            };
                try {
                        const response = await axios.post('/agregarEmpleado', nuevoEmpleado);

                        if (response.data.success) {
                            this.cerrarModal();
                            this.obtenerEmpleados();

                            Swal.fire('Éxito', response.data.message, 'success');
                        } else {
                            Swal.fire('Error', response.data.error, 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire('Error', 'La CURP ya se encuentra registrada.', 'error');
                    }
        },

        async editarEmpleado() {

            const curp = this.curp.toUpperCase().substring(0, 18);

                if (curp.length !== 18) {
                    Swal.fire('Error', 'La CURP debe tener exactamente 18 caracteres', 'error');
                    return;
                }

                const empleadoActualizado = {
                id: this.idEmpleado,
                nombre: this.nombre,
                apellido_paterno: this.apellido_paterno,
                apellido_materno: this.apellido_materno,
                curp: this.curp,
                cargo: this.cargo,
                id_grup: this.id_grup
                };

                try {
                const response = await axios.post('/editarEmpleado', empleadoActualizado);

                    if (response.data.success) {
                        this.cerrarModal();
                        this.obtenerEmpleados();
                        Swal.fire('Éxito', response.data.message, 'success');
                    } else {
                        Swal.fire('Error', response.data.message, 'error');
                    }
                    } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Hubo un error al actualizar el empleado.', 'error');
                    }
        },

        detalleEmpleado(idEmp) {

            this.idEmpleado = idEmp;
            this.bandera = 1;
            this.abrirModal();

            axios
                .get("/detalleEmpleado/" + idEmp)
                .then((response) => {
                    const empleado = response.data[0];
                    this.nombre = empleado.nombre;
                    this.apellido_paterno = empleado.apellido_paterno;
                    this.apellido_materno = empleado.apellido_materno;
                    this.curp = empleado.curp;
                    this.cargo = empleado.cargo;
                    this.curp = empleado.curp;
                    this.id_grup = empleado.id_grup;
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        async eliminarEmpleado(idEmpleado) {

            try {
                const confirmed = await Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esto eliminará al empleado seleccionado.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí'
                });

                if (confirmed.isConfirmed) {
                    const response = await axios.delete(`/eliminarEmpleado/${idEmpleado}`);

                    if (response.data.success) {
                        Swal.fire('Éxito', response.data.message, 'success');
                        this.obtenerEmpleados();
                    } else {
                        Swal.fire('Error', response.data.error, 'error');
                    }
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Hubo un error al eliminar al empleado.', 'error');
            }
        },

        async obtenerRegistrosVotos() {
            try {
                const response = await axios.get('/obtenerRegistrosVotos');
                this.votosRonda1 = response.data.votosRonda1;
                this.votosRonda2 = response.data.votosRonda2;
                } catch (error) {
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

        nuevo() {
            this.limpiarvar();
            this.bandera = 0;
            this.abrirModal();
        },

        abrirModal() {
            $("#largeModal").modal({ backdrop: "static", keyboard: false });
            $("#largeModal").modal("toggle");
        },
        cerrarModal() {
            $("#largeModal").modal("hide");
        },

        limpiarvar() {
            this.nombre = null;
            this.apellido_paterno = null;
            this.apellido_materno = null;
            this.curp = null;
            this.cargo = null;
            this.id_grup = null;
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

        limpiarFiltro() {
            this.filtroGrupo = '';
            this.ronda = '';
            this.dependenciaSeleccionada = '';
            this.currentPage = 1;
        },
    },
};
</script>

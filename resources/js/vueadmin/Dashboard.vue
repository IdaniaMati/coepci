<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>PANEL PRINCIPAL EMPLEADOS</strong></h2>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="info-container">
                <div class="nav-item d-flex align-items-center">
                    <button class="btn btn-info mb-3" @click="nuevo">Agregar Nuevo Empleado</button>
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
                                <td>
                                    <button class="btn btn-primary btn-sm" @click="detalleEmpleado(empleado.id)">Editar</button>
                                    <button class="btn btn-danger btn-sm" @click="eliminarEmpleado(empleado.id)">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="5">No hay empleados que coincidan con la búsqueda.</td>
                            </tr>
                        </tbody>
                    </table>

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
                                    <button class="btn btn-secondary" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                     </div>
                  </div>
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
            grupos: []
            };
        },

    computed: {

        empleadosFiltrados() {
            const filtroMinusculas = this.filtro.toLowerCase();
            let empleados = this.empleados.filter((empleado) => {
                const nombreCompleto = `${empleado.nombre} ${empleado.apellido_paterno} ${empleado.apellido_materno}`;
                const cargoCompleto = `${empleado.cargo}`;
                const nombreSinAcentos = nombreCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                const cargoSinAcentos = cargoCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();

                return (
                    nombreCompleto.toLowerCase().includes(filtroMinusculas) ||
                    nombreSinAcentos.includes(filtroMinusculas) ||
                    (empleado.curp && empleado.curp.toLowerCase().includes(filtroMinusculas)) ||
                    cargoCompleto.toLowerCase().includes(filtroMinusculas) ||
                    cargoSinAcentos.includes(filtroMinusculas) ||
                    (empleado.id_grup && empleado.id_grup.toString().includes(filtroMinusculas))
                );
            });

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

    },
};

</script>

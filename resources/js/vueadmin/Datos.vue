<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>CARGA MASIVA</strong></h2>
        </div>

        <!-- Sección para importr/exportar empleados -->
        <div class="card-container">
            <div class="card">
                <div class="nav-item d-flex align-items-center">
                    <button class="btn btn-download" title="Descargar Formato" @click="descargarFormato">
                        <i class="bi bi-file-earmark-arrow-down" style="font-size: 20px;"></i>
                    </button>
                    <h5 class="card-header"><strong>Importar Personal</strong></h5>
                    <input type="file" @change="handleFileUpload" accept=".xlsx, .xls" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                    <button class="btn btn-upload" title="Subir Formato" @click="importarEmpleados">
                        <i class="bi bi-file-arrow-up" style="font-size: 20px;"></i>
                    </button>
                </div>
                <button class="btn btn-outline-dark" @click="vaciarBaseDatos">Eliminar Personal</button>
            </div>
        </div>

        <!-- Configuración de eventos -->
        <div class="card-container">
            <div class="card">
                <h5 class="card-header" style="text-align: center;"><strong>CONFIGURACIÓN VOTACIONES</strong></h5>
                <div class="nav-item d-flex align-items-center">
                        <div class="nav-item d-flex align-items-center">
                            <h5 class="card-header"><strong>Buscar</strong></h5>
                                <i class="bx bx-search fs-4 lh-0"></i>
                            <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                        </div>
                        <button v-if="hab_permisos('Crear_eventos')" class="btn btn-add mb-3" style="margin-left:700px;" title="Agregar" @click="nuevo">
                            <i class="bi bi-calendar-check" style="font-size: 17px;" ></i>
                            &nbsp;<p> Agregar</p>
                        </button>
                </div>

                <!-- Tabla de datos -->
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descripción Evento</th>
                                <th>Fecha Primera Ronda</th>
                                <th>Fecha Segunda Ronda</th>
                                <th>Fecha Fin Evento</th>
                                <th>Dependencia</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="evento in paginatedEventos" :key="evento.id">
                                <td>{{ evento.id }}</td>
                                <td>{{ evento.descripcion }}</td>
                                <td>{{ evento.fechaIni1ronda }}</td>
                                <td>{{ evento.fechaIni2ronda }}</td>
                                <td>{{ evento.fechaFin }}</td>
                                <td>{{ descripcionDepen(evento.id_depen) }}</td>
                                <td>
                                    <button v-if="hab_permisos('Editar_eventos')" class="btn btn-edit btn-sm" title="Editar" @click="datalleEvento(evento.id)">
                                        <i class="bi bi-pencil-fill" style="font-size: 15px;"></i>
                                    </button>&nbsp;
                                    <button v-if="hab_permisos('Eliminar_eventos')" class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarEvento(evento.id)">
                                        <i class="bi bi-trash3-fill" style="font-size: 15px;"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Fin Tabla de datos -->

                <!-- Modal Agregar Evento -->
                <div class="container">
                    <div class="modal fade" id="largeModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><strong>Eventos</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Descripción</label>
                                                <input v-model="descripcion" class="form-control" placeholder="Descripción del evento" required/>
                                                <div v-if="!descripcion" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label>Fecha Primera Ronda</label>
                                                <input v-model="fechaIni1ronda" class="form-control" type="date" @input="validarFechaInicial" required/>
                                                <div v-if="!fechaIni1ronda" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Fecha Segunda Ronda</label>
                                                <input v-model="fechaIni2ronda" :disabled="!fechaIni1ronda" class="form-control" type="date" @input="validarSegundaFecha" required/>
                                                <div v-if="!fechaIni2ronda" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Fecha Fin de Evento</label>
                                                <input v-model="fechaFin" :disabled="!fechaIni2ronda" class="form-control" type="date" @input="validarTerceraFecha" required/>
                                                <div v-if="!fechaFin" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 0" class="btn btn-primary" @click="agregarEvento">Guardar</button>
                                    <button v-if="bandera === 1" class="btn btn-primary" @click="editarEvento">Editar</button>
                                    <button class="btn btn-cerrar" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <!-- Fin Modal Agregar Evento -->

                <!-- Agregamos el paginador -->
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
                <!-- Fin del paginador -->
                <br>
            </div>
        </div>
    </div>
</template>

<style>
  body.modal-open .modal-backdrop {
    opacity: 0.5;
  }
  .custom-input {
    width: 400px;
    height: 100px;
    resize: none;
    white-space: pre-line;
  }
  .swal2-container.swal2-backdrop-show, .swal2-container.swal2-noanimation {
    z-index: 11000;
  }
</style>

<script>
import { utils as XLSXUtils } from 'xlsx';
import { writeFile } from 'xlsx';
import permisos from "../permisos/permisos.vue";

export default {
    components: {

    },extends:permisos,

    data() {
        return {
            eventos: [],
            dependencias: [],
            evento: {},
            filtro: '',
            pagina: 1,
            totalPaginas: 0,
            registrosPorPagina: 7,
            bandera: "",
            id_depen: "",
            ideve: "",
            descripcion: "",
            fechaIni1ronda: "",
            fechaIni2ronda: "",
            fechaFin: "",
            numeroDependencia: null,
            lista_permisos:[],
        };
    },

    computed: {

        eventosFiltrados() {
            const filtroMinusculas = this.filtro.toLowerCase();
            let eventos = this.eventos.filter((evento) => {
                const descripcionCompleto = `${evento.descripcion}`;
                const descripcionSinAcentos = descripcionCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                const descripcionDependencia = this.descripcionDepen(evento.id_depen).toLowerCase();
                const dependenciaSinAcentos = descripcionDependencia.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();

                this.pagina = 1;
                return (
                    descripcionCompleto.toLowerCase().includes(filtroMinusculas) ||
                    descripcionSinAcentos.includes(filtroMinusculas) ||
                    dependenciaSinAcentos.includes(filtroMinusculas)
                );
            });
            return eventos;
        },

        totalPages() {
            return Math.ceil(this.eventosFiltrados.length / this.perPage);
        },

        paginatedEventos() {
            const startIndex = (this.pagina - 1) * this.registrosPorPagina;
            const endIndex = startIndex + this.registrosPorPagina;
            return this.eventosFiltrados.slice(startIndex, endIndex);
        },
    },

    mounted() {
        this.obtenerEvento();
        this.calcularTotalPaginas();
        this.obtenerPermisos();
        this.obtenerDependencias();
    },

    methods: {

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

        handleFileUpload(event) {
            this.archivo = event.target.files[0];
        },

        descargarFormato() {
            const wb = XLSXUtils.book_new();
            const ws = XLSXUtils.aoa_to_sheet([['nombre', 'apellido_paterno', 'apellido_materno', 'curp', 'cargo', 'id_grup', 'id_depen']]);
            XLSXUtils.book_append_sheet(wb, ws, 'Formato Empleados');
            writeFile(wb, 'Formato_Empleados.xlsx');
        },

        async importarEmpleados() {

            if (!this.archivo || this.archivo.length === 0) {
                Swal.fire('Advertencia', 'Por favor, seleccione un archivo antes de intentar importar.', 'warning');
                return;
            }

            const hayRegistros = await this.verificarDatosEnTablas();

            if (hayRegistros) {
                Swal.fire('Advertencia', 'No puedes importar empleados mientras haya registros en la tabla.', 'warning');
                return;
            }

            const formData = new FormData();
            formData.append('archivo', this.archivo);

            try {
                const response = await axios.post('/importar-empleados', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (response.data.success) {
                    Swal.fire('Éxito', response.data.message, 'success');
                } else {
                    Swal.fire('Error', response.data.error, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Hubo un error al importar empleados.', 'error');
            }
        },

        async vaciarBaseDatos() {
            const confirmacion = await Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminara los registros de empleados y registros.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            });

            if (confirmacion.isConfirmed) {
                try {
                    const response = await axios.post('/vaciarBaseDatos');
                    if (response.data.success) {
                        Swal.fire('Éxito', response.data.message, 'success');
                    } else {
                        if (response.data.message) {
                            Swal.fire('Info', response.data.message, 'info');
                        } else {
                            Swal.fire('Error', response.data.error, 'error');
                        }
                    }
                } catch (error) {
                    Swal.fire('Error', 'Hubo un error al vaciar la base de datos.', 'error');
                }
            }
        },

        obtenerEvento() {
            axios.get('/obtenerEvento')
                .then((response) => {
                    if (response.data.eventos) {
                        this.eventos = response.data.eventos;
                        this.calcularTotalPaginas();
                    } else {
                    }
                })
                .catch((error) => {
                });
        },

        validarFechaInicial() {
            const fechaActual = new Date();
            const fechaIni1 = new Date(this.fechaIni1ronda);

            if (fechaIni1 < fechaActual) {
                Swal.fire('Error', 'La fecha de inicio de la primera ronda no puede ser anterior a la fecha actual.', 'error');
                return false;
            }
            return true;
        },

        validarSegundaFecha() {
            if (this.fechaIni1ronda && this.fechaIni2ronda) {
                const fechaIni1 = new Date(this.fechaIni1ronda);
                const fechaIni2 = new Date(this.fechaIni2ronda);

                if (fechaIni2 < fechaIni1) {
                    Swal.fire('Error', 'La fecha de la segunda ronda no puede ser menor que la fecha de la primera ronda.', 'error');
                    return false;
                }
            }
            return true;
        },

        validarTerceraFecha() {
            if (this.fechaIni2ronda && this.fechaFin) {
                const fechaIni2 = new Date(this.fechaIni2ronda);
                const fechaFin = new Date(this.fechaFin);

                if (fechaFin < fechaIni2) {
                    Swal.fire('Error', 'La fecha Final no puede ser menor que la fecha de la segunda ronda.', 'error');
                    return false;
                }
            }
            return true;
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

        descripcionDepen(id_depen) {
            const dependencia = this.dependencias.find(dep => dep.id === id_depen);
            return dependencia ? dependencia.descripcion : 'Sin descripción';
        },

        async agregarEvento() {

            if (!this.descripcion || !this.fechaIni1ronda || !this.fechaIni2ronda || !this.fechaFin) {
                Swal.fire('Error', 'Todos los campos son obligatorios.', 'error');
                return;
            }

            if (!this.validarFechaInicial() || !this.validarSegundaFecha() || !this.validarTerceraFecha()) {
                return;
            }

            const nuevoEvento = {
                descripcion: this.descripcion,
                fechaIni1ronda: this.fechaIni1ronda,
                fechaIni2ronda: this.fechaIni2ronda,
                fechaFin: this.fechaFin,
            };

            try {
                const response = await axios.post('/agregarEvento', nuevoEvento);

                if (response.data.success) {
                    this.limpiarvar();
                    this.cerrarModal();
                    this.obtenerEvento();

                    Swal.fire('Éxito', response.data.message, 'success');
                } else {
                    Swal.fire('Error', response.data.error, 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Hubo un error al guardar el evento.', 'error');
            }
        },

        editarEvento() {

            var idEvento = this.ideve;

            if (!this.descripcion || !this.fechaIni1ronda || !this.fechaIni2ronda || !this.fechaFin) {
                Swal.fire('Error', 'Todos los campos son obligatorios.', 'error');
                return;
            }

            if (!this.validarFechaInicial() || !this.validarSegundaFecha() || !this.validarTerceraFecha()) {
                return;
            }

            this.cerrarModal();

            Swal.fire({
                title: '¿Estás seguro de que quieres editar este evento?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, editar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Por favor, ingresa una justificación para la edición:',
                        input: 'text',
                        inputPlaceholder: 'Justificación...',
                        customClass: {
                            input: 'custom-input'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Guardar',
                        cancelButtonText: 'Cancelar',
                        inputValidator: (value) => {
                            return !value && 'Debes proporcionar una justificación para editar el evento.';
                        }

                    }).then((result) => {
                        if (result.isConfirmed) {
                            let data = {
                                id: idEvento,
                                descripcion: this.descripcion,
                                fechaIni1ronda: this.fechaIni1ronda,
                                fechaIni2ronda: this.fechaIni2ronda,
                                fechaFin: this.fechaFin,
                                comentario: result.value,
                            };

                            axios.post(`/editarEvento`, data)
                                .then((response) => {
                                    if (response.data.success) {
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: response.data.message,
                                            showConfirmButton: false,
                                            timer: 1800
                                        });
                                        this.limpiarvar();
                                        this.obtenerEvento();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: response.data.message,
                                        });
                                    }
                                })
                                .catch((error) => {
                                });
                        }
                    });
                }
            });
        },

        datalleEvento(idEvento) {

        this.ideve = idEvento;
        this.bandera = 1;
        this.abrirModal();

        axios
            .get("/detalleEvento/" + idEvento)
            .then((response) => {
                const evento = response.data[0];
                this.descripcion = evento.descripcion;
                this.fechaIni1ronda = evento.fechaIni1ronda;
                this.fechaIni2ronda = evento.fechaIni2ronda;
                this.fechaFin = evento.fechaFin;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        async eliminarEvento(idEvento) {

            try {
                const tieneGanadores = await this.verificarGanadores(idEvento);

                if (tieneGanadores) {
                    Swal.fire('Advertencia', 'No puedes eliminar un evento con ganadores registrados.', 'warning');
                    return;
                }

                const fechaFinEvento = this.eventos.find(evento => evento.id === idEvento).fechaFin;

                if (this.fechaActualEsMayor(fechaFinEvento)) {
                    Swal.fire('Advertencia', 'No puedes eliminar un evento que no ha terminado.', 'warning');
                    return;
                }

                const confirmed = await Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esto eliminará el evento seleccionado.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí'
                });

                if (confirmed.isConfirmed) {
                    const response = await axios.delete(`/eliminarEvento/${idEvento}`);

                    if (response.data.success) {
                        Swal.fire('Éxito', response.data.message, 'success');
                        this.obtenerEvento();
                    } else {
                        Swal.fire('Error', response.data.error, 'error');
                    }
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Hubo un error al eliminar el evento.', 'error');
            }
        },

        async verificarGanadores(idEvento) {

            try {
                const response = await axios.get(`/verificarGanadores/${idEvento}`);
                return response.data.tieneGanadores;
            } catch (error) {
                Swal.fire('Error', 'Hubo un error al verificar la existencia de ganadores.', 'error');
                return true;
            }
        },

        fechaActualEsMayor(fecha) {
            const fechaFinEvento = new Date(fecha + 'T23:59:59');
            const fechaActual = new Date();

            return fechaActual < fechaFinEvento;
        },

        nuevo() {
            this.verificarEvento();
        },

        verificarEvento() {
            axios.get('/verificarEventos')
            .then(response => {
                if (response.data.hayRegistros) {
                    Swal.fire('Advertencia', 'No puedes agregar un nuevo evento mientras haya registros en la tabla.', 'warning');
                } else {
                    this.limpiarvar();
                    this.bandera = 0;
                    this.abrirModal();
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire('Error', 'Hubo un error al verificar la existencia de registros.', 'error');
            });
        },

        async verificarDatosEnTablas() {
            try {
                const response = await axios.get('/verificarDatosEnTablas');
                return response.data.hayRegistros;
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Hubo un error al verificar la existencia de registros.', 'error');
                return true;
            }
        },

        abrirModal() {
            $("#largeModal").modal({ backdrop: "static", keyboard: false });
            $("#largeModal").modal("toggle");
        },

        cerrarModal() {
            $("#largeModal").modal("hide");
        },

        limpiarvar() {
            this.descripcion = null;
            this.fechaIni1ronda = null;
            this.fechaIni2ronda = null;
            this.fechaFin = null;
        },

        calcularTotalPaginas() {
            this.totalPaginas = Math.ceil(this.eventosFiltrados.length / this.registrosPorPagina);
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
    }
};
</script>

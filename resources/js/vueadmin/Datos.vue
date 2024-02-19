<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>PANEL PRINCIPAL EMPLEADOS</strong></h2>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Importar Empleados</strong></h5>
                    <input type="file" @change="handleFileUpload" accept=".xlsx, .xls" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                    <button class="btn btn-success" @click="importarEmpleados">Importar Empleados</button>

                </div>
                <button class="btn btn-outline-dark" @click="vaciarBaseDatos">Eliminar Empleados</button>
            </div>
        </div>

        <br>

        <div class="card-container">
            <div class="card">

                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Configuración de Evento</strong></h5>
                    <button class="btn btn-info mb-3" @click="nuevo">Agregar Nuevo Evento</button>
                </div>

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descripción Evento</th>
                                <th>Fecha Primera Ronda</th>
                                <th>Fecha Segunda Ronda</th>
                                <th>Fecha Fin Evento</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="evento in eventos" :key="evento.id">
                                <td>{{ evento.id }}</td>
                                <td>{{ evento.descripcion }}</td>
                                <td>{{ evento.fechaIni1ronda }}</td>
                                <td>{{ evento.fechaIni2ronda }}</td>
                                <td>{{ evento.fechaFin }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" @click="datalleEvento(evento.id)">Editar</button>&nbsp;
                                    <button class="btn btn-danger btn-sm" @click="eliminarEvento(evento.id)">Eliminar</button>
                                </td>
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
                                                <input v-model="fechaIni1ronda" class="form-control" type="date" required/>
                                                <div v-if="!fechaIni1ronda" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Fecha Segunda Ronda</label>
                                                <input v-model="fechaIni2ronda" class="form-control" type="date" required/>
                                                <div v-if="!fechaIni2ronda" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Fecha Fin de Evento</label>
                                                <input v-model="fechaFin" class="form-control" type="date" required/>
                                                <div v-if="!fechaFin" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 0" class="btn btn-primary" @click="agregarEvento">Guardar</button>
                                    <button v-if="bandera === 1" class="btn btn-primary" @click="editarEvento">Editar</button>
                                    <button class="btn btn-secondary" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



</template>

<style>
  body.modal-open .modal-backdrop {
    opacity: 0.5;
  }
</style>

<script>


export default {

    data() {
        return {
            eventos: [],
            currentPage: 1,
            perPage: 10,
            evento: {},
            bandera: "",
            ideve: "",
            descripcion: "",
            fechaIni1ronda: "",
            fechaIni2ronda: "",
            fechaFin: "",
        };
    },

    mounted() {
        this.obtenerEvento();
    },

    methods: {
        handleFileUpload(event) {
            this.archivo = event.target.files[0];
        },

        async importarEmpleados() {
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
                    if (response.data.concurso) {
                        this.eventos = response.data.concurso;
                    } else {
                        console.log(response.data.message);
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        async  agregarEvento() {

            if (!this.descripcion || !this.fechaIni1ronda || !this.fechaIni2ronda || !this.fechaFin) {
                Swal.fire('Error', 'Todos los campos son obligatorios.', 'error');
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

            let data = {
                id: idEvento,
                descripcion: this.descripcion,
                fechaIni1ronda: this.fechaIni1ronda,
                fechaIni2ronda: this.fechaIni2ronda,
                fechaFin: this.fechaFin,
            };

            axios
                .post(`/editarEvento`, data) 
                .then((response) => {

                    if (response.data.success) {
                    // Operación exitosa, muestra un mensaje de éxito en la vista
                        Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1800
                    });
                    this.limpiarvar();
                    this.cerrarModal();
                    this.obtenerEvento();
                } else {
                    // Operación con error, muestra un mensaje de error en la vista
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.data.message,
                });
                }
                })
                .catch((error) => {

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


        async  eliminarEvento(idEvento) {
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
                try {
                    const response = await axios.delete(`/eliminarEvento/${idEvento}`);

                    if (response.data.success) {
                        Swal.fire('Éxito', response.data.message, 'success');
                        this.obtenerEvento();
                    } else {
                        Swal.fire('Error', response.data.error, 'error');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Hubo un error al eliminar el evento.', 'error');
                }
            }
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
            this.descripcion = null;
            this.fechaIni1ronda = null;
            this.fechaIni2ronda = null;
            this.fechaFin = null;
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
    }
};
</script>

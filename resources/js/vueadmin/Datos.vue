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
                    <button class="btn btn-info mb-3" @click="agregarEvento">Agregar Nuevo Evento</button>
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
                                    <button class="btn btn-primary btn-sm" @click="editarEvento(evento.id)">Editar</button>&nbsp;
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

            </div>
        </div>
    </div>

    <div class="modal" id="eventoModal" tabindex="-1" role="dialog" :class="{ 'show': mostrarModal, 'd-block': mostrarModal }">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventoModalLabel">{{ modalTitle }}</h5>
                    <button type="button" class="close" @click="cerrarModal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input class="form-control" id="descripcion" placeholder="Nombre del Evento" v-model="evento.descripcion">
                        </div>

                        <button class="btn btn-success" @click="guardarEvento">Guardar</button>
                        <button class="btn btn-secondary" @click="cerrarModal">Cancelar</button>
                    </form>
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
            modalTitle: '',
            mostrarModal: false,
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

        agregarEvento() {
            this.evento = {};
            this.modalTitle = 'Agregar Nuevo Evento';
            this.mostrarModal = true;
        },

        editarEvento(idEvento) {
            const eventoSeleccionado = this.eventos.find(evento => evento.id === idEvento);

            if (eventoSeleccionado) {
                this.evento = { ...eventoSeleccionado };
                this.modalTitle = 'Editar Evento';
                this.mostrarModal = true;
            }
        },

        guardarEvento() {
            if (this.modalTitle === 'Agregar Nuevo Evento') {

            } else if (this.modalTitle === 'Editar Evento') {

            }

            $('#eventoModal').modal('hide');
        },

        cerrarModal() {
            this.mostrarModal = false;
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

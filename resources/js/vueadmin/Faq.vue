<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>Lista de Manuales</strong></h2>
        </div>
        <div class="card-container">
            <div class="card" v-if="hab_permisos('importar_manual')">
                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Importar Manual</strong></h5>
                    <input type="file" @change="handleFileUpload" ref="fileInput" accept=".pdf" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                    <button class="btn btn-upload" title="Subir Formato" @click="importarManuales">
                        <i class="bi bi-file-arrow-up" style="font-size: 20px;"></i>
                    </button>
                </div>
            </div>
        </div>
      <br>

        <div class="card-container">
            <div class="card">
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>Nombre de manual</th>
                        <th>Fecha de actualización</th>
                        <th v-if="hab_permisos('ver_opciones')">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="manual in manuales" :key="manual.id">
                            <td>{{ manual.nombre }}</td>
                            <td>{{ formatFecha(manual.updated_at) }}</td>
                            <td v-if="hab_permisos('ver_opciones')">
                                <button v-if="hab_permisos('editar_manual')" class="btn btn-edit btn-sm" title="Editar" @click="openEditModal(manual)">
                                <i class="bi bi-pencil-fill" style="font-size: 15px;"></i>
                                </button>
                                <button v-if="hab_permisos('eliminar_manual')" class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarManual(manual.id)">
                                    <i class="bi bi-trash3-fill" style="font-size: 15px;"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>
            </div>
        </div>

        <!-- Start Modal Editar Manual -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title"><strong>Editar Manual</strong></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="editModalLabel">Archivo seleccionado: {{ nombreArchivoSeleccionado }}</h5>
                        <input type="file" @change="handleEditFileUpload" accept=".pdf" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="editarManual">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End  Modal Editar Manual -->
    </div>

</template>

<script>
    import permisos from "../permisos/permisos.vue";
    import axios from 'axios';
    import Swal from 'sweetalert2';

    export default {
        components: {
            permisos,
        },
        extends: permisos,

        data() {
            return {
                bandera: "",
                lista_permisos: [],
                archivo: null,
                editArchivo: null,
                editManualId: null,
                manuales: [],
                nombreArchivoSeleccionado: '',
            };
        },

        mounted() {
            this.obtenerPermisos();
            this.obtenerManuales();
        },

        methods: {
            formatFecha(fecha) {
                const fechaObj = new Date(fecha);
                const opcionesFecha = { year: 'numeric', month: '2-digit', day: '2-digit' };
                const opcionesHora = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
                const fechaFormateada = fechaObj.toLocaleDateString('es-ES', opcionesFecha) + ' ' + fechaObj.toLocaleTimeString('es-ES', opcionesHora);
                return fechaFormateada;
            },

            async importarManuales() {
                this.$refs.fileInput.value = '';
                if (this.manuales.length >= 3) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Alerta',
                        text: 'Ya existen 3 archivos. Antes de agregar uno nuevo, elimine uno de la lista si es necesario.',
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('file', this.archivo);

                try {
                    const response = await axios.post('/importar-manual', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    console.log(response.data.message);
                    this.obtenerManuales();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'El archivo ha sido importado exitosamente',
                    });
                } catch (error) {
                    console.error('Hubo un error al subir el archivo:', error);
                }
            },

            async eliminarManual(id) {
                const result = await Swal.fire({
                    title: '¿Está seguro de que desea eliminar este manual?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo',
                    cancelButtonText: 'Cancelar'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await axios.delete(`/eliminar-manual/${id}`);
                        console.log(response.data.message);
                        this.obtenerManuales();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'El registro ha sido eliminado exitosamente',
                        });
                    } catch (error) {
                        console.error('Error al eliminar el manual:', error);
                    }
                }
            },

            obtenerManuales() {
                axios
                .get("/lista-manuales")
                .then((response) => {
                    this.manuales = response.data;
                })
                .catch((error) => {
                    console.error('Error al obtener la lista de manuales:', error);
                });
            },

            openEditModal(manual) {
                this.editManualId = manual.id;
                $("#editModal").modal("show");
                this.nombreArchivoSeleccionado = manual.nombre;
            },

            obtenerPermisos() {
                axios
                .get("/Obtenerpermisos")
                .then((response) => {
                    this.lista_permisos = response.data;
                })
                .catch((error) => {
                    console.error(error);
                });
            },

            handleEditFileUpload(event) {
                this.editArchivo = event.target.files[0];
            },

            async editarManual() {
                if (!this.editArchivo) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Seleccione un archivo para subir',
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('file', this.editArchivo);

                try {
                    const response = await axios.post(`/editar-manual/${this.editManualId}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    console.log(response.data.message);
                    $("#editModal").modal("hide");
                    this.obtenerManuales();


                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'El manual ha sido editado exitosamente',
                    });
                } catch (error) {
                    console.error('Error al editar el archivo:', error);
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
            },
        }
    };
</script>

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

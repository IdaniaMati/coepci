    <template>
        <div>
            <div>
                <div class="text-center mb-4">
                    <h2><strong>Lista de Manuales</strong></h2>
                </div>
            <div class="card-container" v-if="hab_permisos('importar_manual')">
                <div class="card">
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

            <!--Tabla de manuales-->
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
                                        <button v-if="hab_permisos('descargar_manual')" class="btn btn-download btn-sm" title="Descargar" @click="descargarManual(manual.id)">
                                            <i class="bi bi-download" style="font-size: 15px;"></i>
                                        </button>&nbsp;
                                        <button class="btn btn-edit btn-sm" title="Editar" @click="openEditModal(manual)" v-if="hab_permisos('editar_manual')">
                                            <i class="bi bi-pencil-fill" style="font-size: 15px;"></i>
                                        </button>&nbsp;
                                        <button class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarManual(manual.id)" v-if="hab_permisos('eliminar_manual')">
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
            <!-- Fin Tabla de manuales-->

            <!--Modal de manuales-->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Editar Manual</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="file" @change="handleEditFileUpload" accept=".pdf" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" @click="editarManual">Guardar cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin todo de manuales-->

        <!--Start todo de preguntas-->
        <div>
            <div>
                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Preguntas</strong></h5>
                        <i class="bx bx-search fs-4 lh-0"></i>
                    <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                </div>

                <div class="card-container">
                    <div class="card">
                        <div class="nav-item d-flex align-items-center">
                            <button v-if="hab_permisos('Crear_faq')" class="btn btn-add mb-3" title="Agregar" @click="nuevo">
                                <i class="bi bi-question-square" ></i>
                                &nbsp;<p> Agregar</p>
                            </button>
                        </div>

                            <!-- Espacio para el collapse FAQ-->
                            <div>
                                <div v-for="faq in paginatedFaqs" :key="faq.id" class="mb-4 border border-gray-300 rounded-lg">
                                    <div class="p-4 cursor-pointer bg-gray-100 hover:bg-gray-200 d-flex justify-content-between align-items-center" @click="toggleCollapse(faq.id)">
                                        <strong>{{ faq.pregunta }}</strong>
                                        <i :class="isExpanded(faq.id) ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                                    </div>

                                    <div v-show="isExpanded(faq.id)" class="p-4 bg-white">
                                        <p>{{ faq.respuesta }}</p>

                                        <div class="mt-2 flex space-x-2">
                                            <button v-if="hab_permisos('Editar_faq')" class="btn btn-edit btn-sm" title="Editar" @click="datalleFaq(faq.id)">
                                                <i class="bi bi-pencil-fill" style="font-size: 15px;"></i> Editar
                                            </button>
                                            <button v-if="hab_permisos('Eliminar_faq')" class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarFaq(faq.id)">
                                                <i class="bi bi-trash3-fill" style="font-size: 15px;"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!-- Fin del Collapse FAQ -->

                            <!-- Modal agregar FAQ -->
                            <div class="container">
                                <div class="modal fade" id="largeModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><strong>FAQ</strong></h5>
                                                <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label>Pregunta</label>
                                                            <input v-model="pregunta" class="form-control" placeholder="Pregunta" required/>
                                                            <div v-if="!pregunta" class="text-danger">Este campo es obligatorio.</div>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label>Respuesta</label>
                                                            <input v-model="respuesta" class="form-control" placeholder="Respuesta" required/>
                                                            <div v-if="!respuesta" class="text-danger">Este campo es obligatorio.</div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="modal-footer">
                                                <button v-if="bandera === 0" class="btn btn-primary" @click="agregarFaq">Guardar</button>
                                                <button v-if="bandera === 1" class="btn btn-primary" @click="editarFaq">Editar</button>
                                                <button class="btn btn-cerrar" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Modal agregar FAQ -->

                            <!-- Paginador FAQ -->
                            <br>
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
                            </br>
                            <!-- Final Paginador FAQ -->
                        </div>
                    </div>
                </div>
            </div>
            <!--Start todo de preguntas-->
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

                filtro: '',
                faqs: [],
                pagina: 1,
                totalPaginas: 0,
                registrosPorPagina: 7,
                idfaq:"",
                pregunta: "",
                respuesta: "",
                expandedId: null
            };
        },

        mounted() {
            this.obtenerPermisos();
            this.obtenerManuales();
            this.obtenerFaq();
            this.calcularTotalPaginas();
        },

        computed: {
            faqFiltrados() {
                const filtroMinusculas = this.filtro.toLowerCase();
                return this.faqs.filter((faq) => {
                    const preguntaCompleto = `${faq.pregunta}`;
                    const preguntaSinAcentos = preguntaCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                    const respuestaCompleto = `${faq.respuesta}`;
                    const respuestaSinAcentos = respuestaCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                    this.pagina = 1;

                    return (
                        preguntaCompleto.toLowerCase().includes(filtroMinusculas) ||
                        preguntaSinAcentos.includes(filtroMinusculas) ||
                        respuestaCompleto.toLowerCase().includes(filtroMinusculas) ||
                        respuestaSinAcentos.includes(filtroMinusculas)
                    );
                });
            },

            totalPages() {
                return Math.ceil(this.faqFiltrados.length / this.perPage);
            },

            paginatedFaqs() {
                const startIndex = (this.pagina - 1) * this.registrosPorPagina;
                const endIndex = startIndex + this.registrosPorPagina;
                return this.faqFiltrados.slice(startIndex, endIndex);
            },
        },

        methods: {

            //Start métodos de manuales
            formatFecha(fecha) {
                const fechaObj = new Date(fecha);
                const opcionesFecha = { year: 'numeric', month: '2-digit', day: '2-digit' };
                const opcionesHora = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
                const fechaFormateada = fechaObj.toLocaleDateString('es-ES', opcionesFecha) + ' ' + fechaObj.toLocaleTimeString('es-ES', opcionesHora);
                return fechaFormateada;
            },

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
                    //console.log(response.data.message);
                    this.obtenerManuales();
                    Swal.fire({
                        icon: 'success',
                        title: 'Manual importado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
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
                        //console.log(response.data.message);
                        this.obtenerManuales();
                        Swal.fire({
                            icon: 'success',
                            title: 'Manual eliminado exitosamente',
                            showConfirmButton: false,
                            timer: 1500
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
            },

            handleFileUpload(event) {
                this.archivo = event.target.files[0];
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
                    //console.log(response.data.message);
                    $("#editModal").modal("hide");
                    this.obtenerManuales();
                    Swal.fire({
                        icon: 'success',
                        title: 'Manual editado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });

                } catch (error) {
                    console.error('Error al editar el archivo:', error);
                }
            },

            descargarManual(id) {
                window.location.href = `/descargar-manual/${id}`;
            },
            //End métodos de manuales

            //Start métodos de preguntas
            toggleCollapse(id) {
                if (this.expandedId === id) {
                    this.expandedId = null;
                } else {
                    this.expandedId = id;
                }
            },

            isExpanded(id) {
                return this.expandedId === id;
            },

            async obtenerFaq() {
                try {
                    const response = await axios.get('/obtenerFaq');
                    this.faqs = response.data;
                    this.calcularTotalPaginas();
                } catch (error) {
                    console.error('Error al obtener las actividades:', error);
                }
            },

            agregarFaq() {

                const nuevoFaq = {
                    pregunta: this.pregunta,
                    respuesta: this.respuesta,
                };

                axios.post('/agregarFaq', nuevoFaq)
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire('Éxito', 'FAQ agregada exitosamente.', 'success');
                            this.cerrarModal();
                            this.obtenerFaq();
                        } else {
                            Swal.fire('Error', response.data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        this.cerrarModal();
                        Swal.fire('Error', 'Se produjo un error al agregar la FAQ.', 'error');
                    });
            },

            datalleFaq(idFaq) {

            this.idfaq = idFaq;
            this.bandera = 1;
            this.abrirModal();

                axios.get("/detalleFaq/" + idFaq)
                    .then((response) => {
                        const faq = response.data;
                        this.pregunta = faq.pregunta;
                        this.respuesta = faq.respuesta;
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            },

            editarFaq() {
                var idFaq = this.idfaq;
                this.cerrarModal();

                let data = {
                    id: idFaq,
                    pregunta: this.pregunta,
                    respuesta: this.respuesta,
                };

                axios.post(`/editarFaq`, data)
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
                            this.cerrarModal();
                            this.obtenerFaq();
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

            },

            eliminarFaq(idFaq) {
                Swal.fire({
                    title: '¿Estás seguro de que deseas eliminar esta pregunta?',
                    text: "No se podrá revertir dicha acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete("/eliminarFaq/" + idFaq)
                            .then(response => {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 1800
                                });
                                this.obtenerFaq();
                            })
                            .catch((error) => {
                                console.error(error);

                                if (error.response.data.error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        text: error.response.data.error,
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Error al eliminar la FAQ',
                                    });
                                }
                            });
                    }
                });
            },

            calcularTotalPaginas() {
                this.totalPaginas = Math.ceil(this.faqFiltrados.length / this.registrosPorPagina);
            },

            paginaAnterior() {
                if (this.pagina > 1) {
                    this.pagina--;
                }
            },

            abrirModal(){
                $("#largeModal").modal({ backdrop: "static", keyboard: false });
                $("#largeModal").modal("toggle");
            },

            paginaSiguiente() {
                if (this.pagina < this.totalPaginas) {
                    this.pagina++;
                }
            },

            cambiarPagina(numero) {
                this.pagina = numero;
            },

            limpiarvar() {
                this.respuesta = null;
                this.pregunta = null;
                this.descripcion = null;
            },

            nuevo() {
                this.limpiarvar();
                this.bandera = 0;
                this.abrirModal();
            },
            //End métodos de preguntas

            //Inicio métodos generales
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

            cerrarModal() {
                $("#largeModal").modal("hide");
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

<!-- <template>
     <div>
        <div class="text-center mb-4">
            <h2><strong>MANUALES Y FAQ</strong></h2>
        </div>

        <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Preguntas</strong></h5>
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                </div>

        <div class="card-container">
            <div class="card">

                <div class="nav-item d-flex align-items-center">
                    <button  class="btn btn-add mb-3" title="Agregar" @click="nuevo">
                        <i class="bi bi-building-add" ></i>
                       Agregar</button>
                </div>

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pregunta</th>
                                <th>Respuesta</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="faq in paginatedFaqs" :key="faq.id">
                                <td>{{ faq.id }}</td>
                                <td>{{ faq.pregunta }}</td>
                                <td>{{ faq.respuesta }}</td>
                                <td>
                                    <button class="btn btn-edit btn-sm" title="Editar" @click="datalleFaq(faq.id)">
                                        <i class="bi bi-pencil-fill" style="font-size: 15px;"></i>
                                        </button>&nbsp;
                                    <button class="btn btn-delete btn-sm"  title="Eliminar" @click="eliminarFaq(faq.id)">
                                        <i class="bi bi-trash3-fill" style="font-size: 15px;"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

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
                <br>
                Agregamos el paginador -->
                <!-- <div class="row justify-content-center">
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
                Fin del paginador -->
                <!-- <br>
            </div>
        </div>
    </div>
</template> -->

<template>
    <div class="container mt-4">
        <div class="text-center mb-4">
            <h2><strong>MANUALES Y FAQ</strong></h2>
        </div>

        <div class="d-flex justify-content-between mb-3 mx-auto">
            <div class="d-flex align-items-center">
                <h5 class="card-header"><strong>Preguntas</strong></h5>
                <i class="bx bx-search fs-4 lh-0 ml-2"></i>
                <input v-model="filtro" type="text" class="form-control border-0 shadow-none ml-2" placeholder="Buscar..." aria-label="Buscar..." />
            </div>
            <button v-if="hab_permisos('Crear_faq')" class="btn btn-add" title="Agregar" @click="nuevo">
                <i class="bi bi-building-add"></i> Agregar
            </button>
        </div>

        <!-- <div class="accordion" id="faqAccordion">
            <div class="card mb-3" v-for="faq in paginatedFaqs" :key="faq.id">
                <div class="card-header" :id="'heading' + faq.id">
                    <h2 class="mb-0">
                        <button class="btn btn-link w-100 text-left" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse' + faq.id" aria-expanded="false" :aria-controls="'collapse' + faq.id">
                            {{ faq.pregunta }}
                        </button>
                    </h2>
                </div>
                <div :id="'collapse' + faq.id" class="collapse"  :aria-labelledby="'heading' + faq.id" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        {{ faq.respuesta }}
                        <div class="mt-3 d-flex justify-content-end">
                            <button v-if="hab_permisos('Editar_faq')" class="btn btn-edit btn-sm mr-2" title="Editar" @click="datalleFaq(faq.id)">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </button>
                            <button v-if="hab_permisos('Eliminar_faq')" class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarFaq(faq.id)">
                                <i class="bi bi-trash3-fill"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="accordion" id="faqAccordion">
            <div class="card mb-3" v-for="faq in paginatedFaqs" :key="faq.id">
                <div class="card-header" :id="'heading' + faq.id">
                    <h2 class="mb-0">
                        <button class="btn btn-link w-100 text-center" type="button" @click="toggleCollapse(faq.id)" :aria-expanded="isExpanded(faq.id)" :aria-controls="'collapse' + faq.id">
                            {{ faq.pregunta }}
                        </button>
                    </h2>
                </div>
                <div :id="'collapse' + faq.id" class="collapse" :class="{ 'show': isExpanded(faq.id) }" :aria-labelledby="'heading' + faq.id" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        {{ faq.respuesta }}
                        <div class="mt-3 d-flex justify-content-end">
                            <button v-if="hab_permisos('Editar_faq')" class="btn btn-edit btn-sm mr-2" title="Editar" @click="datalleFaq(faq.id)">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </button>
                            <button v-if="hab_permisos('Eliminar_faq')" class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarFaq(faq.id)">
                                <i class="bi bi-trash3-fill"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
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
.card-header {
    cursor: pointer;
}
.btn-link {
    text-decoration: none;
    color: inherit;
}
</style>
<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import permisos from "../permisos/permisos.vue";

export default {

    components: {

    },extends:permisos,

    data() {
        return {
            filtro: '',
            faqs: [],
            pagina: 1,
            totalPaginas: 0,
            registrosPorPagina: 7,
            bandera: "",
            idfaq:"",
            pregunta: "",
            respuesta: "",
            lista_permisos:[],
            expandedId: null
        };
    },

    mounted() {
        this.obtenerFaq();
        this.calcularTotalPaginas();
        this.obtenerPermisos_user();

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

        obtenerPermisos_user(){
            axios
                .get("/Obtenerpermisos")
                .then((response) => {
                    this.lista_permisos  = response.data;

                })
                .catch((error) => {
                    console.error(error);

                });
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

        nuevo() {
            this.limpiarvar();
            this.bandera = 0;
            this.abrirModal();
        },

        abrirModal(){
            $("#largeModal").modal({ backdrop: "static", keyboard: false });
            $("#largeModal").modal("toggle");
        },

        cerrarModal() {
            $("#largeModal").modal("hide");
        },

        limpiarvar() {
            this.respuesta = null;
            this.pregunta = null;
        },

        calcularTotalPaginas() {
            this.totalPaginas = Math.ceil(this.faqFiltrados.length / this.registrosPorPagina);
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

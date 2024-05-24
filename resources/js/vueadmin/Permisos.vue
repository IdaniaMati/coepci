<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>GESTIÓN DE PERMISOS</strong></h2>
        </div>


        <div class="card-container">
            <div class="card">
                <h5 class="card-header"><strong>Buscar</strong></h5>
                <i class="bx bx-search fs-4 lh-0"></i>
                <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                <div class="nav-item d-flex align-items-center">
                    <button v-if="hab_permisos('Crear_permisos')" class="btn btn-add mb-3" title="Agregar" @click="nuevo">
                        <i class="bi bi-file-earmark-plus"></i>
                    Agregar</button>
                </div>

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="permiso in paginatedPermisos" :key="permiso.id">
                                <td>{{ permiso.id }}</td>
                                <td>{{ permiso.name }}</td>
                                <td>
                                    <button v-if="hab_permisos('Editar_permisos')" class="btn btn-edit btn-sm" title="Editar" @click="datallePermiso(permiso.id)">
                                        <i class="bi bi-pencil-fill" style="font-size: 15px;"></i>
                                    </button>&nbsp;
                                    <button v-if="hab_permisos('Eliminar_permisos')" class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarPermiso(permiso.id)">
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
                                    <h5 class="modal-title"><strong>Permisos</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Descripción del Permiso</label>
                                                <input v-model="name" class="form-control" placeholder="Descripción" required/>
                                                <div v-if="!name" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 0" class="btn btn-primary" @click="agregarPermiso">Guardar</button>
                                    <button v-if="bandera === 1" class="btn btn-primary" @click="editarPermiso">Editar</button>
                                    <button class="btn btn-cerrar" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
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
</style>

<script>
import Swal from 'sweetalert2';
import permisos from "../permisos/permisos.vue";

export default {

    components: {

    },extends:permisos,

    data() {
        return {
            permisos: [],
            filtro: '',
            pagina: 1,
            totalPaginas: 0,
            registrosPorPagina: 7,
            bandera: "",
            name: "",
            idper: "",
            lista_permisos:[],
        };
    },

    mounted() {
        this.obtenerPermisos();
        this.calcularTotalPaginas();
        this.obtenerPermisos_user();
    },

    computed: {

    permisosFiltrados() {
            const filtroMinusculas = this.filtro.toLowerCase();
            return this.permisos.filter((permissions) => {
                const nameCompleto = `${permissions.name}`;
                const nameSinAcentos = nameCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                this.pagina = 1;

                return (
                    nameCompleto.toLowerCase().includes(filtroMinusculas) ||
                    nameSinAcentos.includes(filtroMinusculas)
                );
            });
        },

    totalPages() {
            return Math.ceil(this.permisosFiltrados.length / this.perPage);
        },

    paginatedPermisos() {
            const startIndex = (this.pagina - 1) * this.registrosPorPagina;
            const endIndex = startIndex + this.registrosPorPagina;
            return this.permisosFiltrados.slice(startIndex, endIndex);
        },
    },

    methods: {

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

        obtenerPermisos() {
            axios.get('/obtenerPermisos')
                .then((response) => {
                    if (response.data.permiso) {
                        this.permisos = response.data.permiso;
                        this.calcularTotalPaginas();
                    } else {
                        //console.log(response.data.message);
                    }
                })
                .catch((error) => {
                    //console.error(error);
                });
        },

        agregarPermiso() {
            axios.post('/agregarPermisos', { name: this.name })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire('Éxito', 'Rol agregado exitosamente.', 'success');
                        this.cerrarModal();
                        this.obtenerPermisos();
                    } else {
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    this.cerrarModal();
                    Swal.fire('Error', 'Se produjo un error al agregar el rol.', 'error');
                });
        },

        datallePermiso(idPerm) {

        this.idper = idPerm;
        this.bandera = 1;
        this.abrirModal();

        axios.get("/detallePermiso/" + idPerm)
            .then((response) => {
                const permiso = response.data[0];
                this.name = permiso.name;

            })
            .catch((error) => {
                console.error(error);
            });
        },

        editarPermiso() {
            var idPerm = this.idper;
            this.cerrarModal();

            let data = {
                id: idPerm,
                name: this.name,
            };

            axios.post(`/editarPermiso`, data)
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
                        this.obtenerPermisos();
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

        eliminarPermiso(idPerm){
            Swal.fire({
                title: '¿Estás seguro de que deseas eliminar este Permiso?',
                text: "No se podra revertir dicha acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'

                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete("/eliminarPermiso/" + idPerm)
                        .then(response => {

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1800
                            });
                            this.obtenerPermisos();
                        })
                        .catch((error) => {
                            console.error(error);

                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.data.message,
                        });
                    });
                }
            })
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
            this.name = null;
        },

        calcularTotalPaginas() {
            this.totalPaginas = Math.ceil(this.permisos.length / this.registrosPorPagina);
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

<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>GESTIÓN DE ROLES</strong></h2>
        </div>


        <div class="card-container">
            <div class="card">

                <div class="nav-item d-flex align-items-center">
                    <button class="btn btn-info mb-3" @click="nuevo">Agregar Nuevo Rol</button>
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
                            <tr v-for="role in paginatedRoles" :key="role.id">
                                <td>{{ role.id }}</td>
                                <td>{{ role.name }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" @click="datalleRol(role.id)">Editar</button>&nbsp;
                                    <button class="btn btn-danger btn-sm" @click="eliminarRol(role.id)">Eliminar</button>&nbsp;
                                    <button class="btn btn-secondary btn-sm" @click="detallePermiso(role.id)">Asignar Permisos</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal agregar roles -->
                <div class="container">
                    <div class="modal fade" id="modalroles" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><strong>Roles</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModalRol" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Descripción del rol</label>
                                                <input v-model="name" class="form-control" placeholder="Descripción" required/>
                                                <div v-if="!name" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 0" class="btn btn-primary" @click="agregarRol">Guardar</button>
                                    <button v-if="bandera === 1" class="btn btn-primary" @click="editarRol">Editar</button>
                                    <button class="btn btn-secondary" @click="cerrarModalRol" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal agregar permisos -->
                <div class="container">
                    <div class="modal fade" id="modalpermisos" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><strong>Permisos</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModalPermiso" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <label>Selecciona los permisos:</label>
                                    <div v-for="permiso in permisos" :key="permiso.id" class="form-check">
                                        <input type="checkbox" class="form-check-input" :id="'permiso-' + permiso.id" v-model="selectedPermisos" :value="permiso.id"/>
                                        <label class="form-check-label" :for="'permiso-' + permiso.id">{{ permiso.name }}</label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 3" class="btn btn-primary" @click="asignarPermiso">Guardar Permisos</button>
                                    <button class="btn btn-secondary" @click="cerrarModalPermiso" data-bs-dismiss="modal">Cerrar</button>
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
import { ref, reactive } from 'vue';

export default {

    data() {
        return {
            roles: [],
            role: [],
            permisos: [],
            selectedPermisos: [],
            userPermissions: {},
            pagina: 1,
            totalPaginas: 0,
            registrosPorPagina: 7,
            bandera: "",
            name: "",
            idro: "",
            idpe: "",
        };
    },

    mounted() {
        this.obtenerRoles();
        this.calcularTotalPaginas();
    },

    computed: {
        paginatedRoles() {
            const startIndex = (this.pagina - 1) * this.registrosPorPagina;
            const endIndex = startIndex + this.registrosPorPagina;
            return this.roles.slice(startIndex, endIndex);
        },
    },

    methods: {

        /* Metodos de Roles */
        obtenerRoles() {
            axios.get('/obtenerRoles')
                .then((response) => {
                    if (response.data.role) {
                        this.roles = response.data.role;
                        this.calcularTotalPaginas();
                    } else {
                        console.log(response.data.message);
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        agregarRol() {
            axios.post('/agregarRol', { name: this.name })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire('Éxito', 'Rol agregado exitosamente.', 'success');
                        this.cerrarModalRol();
                        this.obtenerRoles();
                    } else {
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    this.cerrarModalRol();
                    Swal.fire('Error', 'Se produjo un error al agregar el rol.', 'error');
                });
        },

        datalleRol(idRol) {

        this.idro = idRol;
        this.bandera = 1;
        this.abrirModalRol();

        axios.get("/detalleRol/" + idRol)
            .then((response) => {
                const evento = response.data[0];
                this.name = evento.name;

            })
            .catch((error) => {
                console.error(error);
            });
        },

        editarRol() {
            var idRol = this.idro;
            this.cerrarModalRol();

            let data = {
                id: idRol,
                name: this.name,
            };

            axios.post(`/editarRol`, data)
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
                        this.cerrarModalRol();
                        this.obtenerRoles();
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

        eliminarRol(idRol){
            Swal.fire({
                title: '¿Estás seguro de que deseas eliminar este Rol?',
                text: "No se podra revertir dicha acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'

                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete("/eliminarRol/" + idRol)
                        .then(response => {

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1800
                            });
                            this.obtenerRoles();
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

        abrirModalRol() {
            $("#modalroles").modal({ backdrop: "static", keyboard: false });
            $("#modalroles").modal("toggle");
        },

        cerrarModalRol() {
            $("#modalroles").modal("hide");
        },


        /* Metodos de Permisos */
        detallePermiso(idPerm) {

        this.idpe = idPerm;
        this.bandera = 3;
        this.idRolSeleccionado = idPerm;
        this.abrirModalPermiso();

        axios.get(`/obtenerPermisosRol/${this.idpe}`)
                .then((response) => {
                const permisosAsignados = response.data.permisos;

                const permisosArray = Array.isArray(permisosAsignados)
                    ? permisosAsignados
                    : Object.values(permisosAsignados);

                if (!this.userPermissions[this.idpe]) {
                    this.userPermissions[this.idpe] = ref(reactive([]));
                }

                this.userPermissions[this.idpe].value = permisosArray;
                this.selectedPermisos = permisosArray.map(permiso => permiso.id);
                })
                .catch((error) => {
                console.error(error);
                });


        axios.get('/obtenerPermisos')
            .then((response) => {
                    if (response.data.permiso) {
                        this.permisos = response.data.permiso;

                    } else {
                        console.log(response.data.message);
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        asignarPermiso() {
            const idRol = this.idRolSeleccionado;

            axios.post('/asignarpermisos', { idRol, selectedPermisos: this.selectedPermisos })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire('Éxito', 'Permisos asignados correctamente.', 'success');
                        this.cerrarModalPermiso();
                    } else {
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Se produjo un error al asignar permisos.', 'error');
                });
        },

        abrirModalPermiso() {
            this.selectedPermisos = [];
            $("#modalpermisos").modal({ backdrop: "static", keyboard: false });
            $("#modalpermisos").modal("toggle");
        },

        cerrarModalPermiso() {
            $("#modalpermisos").modal("hide");
        },


        /* General */
        nuevo() {
            this.limpiarvar();
            this.bandera = 0;
            this.abrirModalRol();
        },

        limpiarvar() {
            this.name = null;
        },

        calcularTotalPaginas() {
            this.totalPaginas = Math.ceil(this.roles.length / this.registrosPorPagina);
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

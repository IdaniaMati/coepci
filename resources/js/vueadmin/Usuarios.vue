<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>GESTIÓN DE USUARIOS</strong></h2>
        </div>


        <div class="card-container">
            <div class="card">
                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Usuarios</strong></h5>
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input v-model="filtro" type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                </div>

                <div class="nav-item d-flex align-items-center">
                    <button v-if="hab_permisos('Crear_usuarios')" class="btn btn-add mb-3" title="Agregar" @click="nuevo">
                        <i class="bi bi-person-fill-add" style="font-size: 20px;"></i>
                        &nbsp;<p> Agregar</p>
                    </button>
                </div>
                <!-- Tabla de lista de Usuarios -->
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Correo Electrónico</th>
                                <th>Institución</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in paginatedUsuarios" :key="user.id">

                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ descripcionDepen(user.id_depen) }}</td>
                                <td>
                                    <button v-if="hab_permisos('Editar_usuarios')" class="btn btn-edit btn-sm" title="Editar" @click="datalleUsuario(user.id)">
                                        <i class="bi bi-pencil-fill" style="font-size: 15px;"></i>
                                    </button>&nbsp;
                                    <button v-if="hab_permisos('Eliminar_usuarios')" class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarUsuario(user.id)">
                                        <i class="bi bi-trash3-fill" style="font-size: 15px;"></i>
                                    </button>&nbsp;
                                    <button v-if="hab_permisos('Asignar_roles_usuarios')" class="btn btn-roles btn-sm" title="Asignar roles" @click="detalleRol(user.id)">
                                        <i class="bi bi-person-rolodex" style="font-size: 15px;"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Fin tabla de lista de Usuarios -->

                <!-- Modal Agregar Usuario -->
                <div class="container">
                    <div class="modal fade" id="modalusuarios" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><strong>Usuarios</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModalUsuario" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Nombre Completo</label>
                                                <input v-model="name" class="form-control" placeholder="Nombre completo" required/>
                                                <div v-if="!name" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Correo Electrónico</label>
                                                <input v-model="email" class="form-control" placeholder="@" required/>
                                                <div v-if="!email" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Dependencia</label>
                                                <select v-model="id_depen" class="form-select">
                                                    <option disabled selected>Seleccionar</option>
                                                    <option v-for="dependencia in dependencias" :value="dependencia.id">{{ dependencia.descripcion }}</option>
                                                </select>
                                                <div v-if="!id_depen" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Contraseña</label>
                                                <input v-model="password" type="password" class="form-control" placeholder="***" required/>
                                                <div v-if="!password" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 0" class="btn btn-primary" @click="agregarUsuario">Guardar</button>
                                    <button v-if="bandera === 1" class="btn btn-primary" @click="editarUsuario">Editar</button>
                                    <button class="btn btn-cerrar" @click="cerrarModalUsuario" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal Agregar Usuario -->

                <!-- Modal agregar Roles -->
                <div class="container">
                    <div class="modal fade" id="modalroles" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><strong>Roles</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModalRoles" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <label>Selecciona los roles:</label>
                                    <div v-for="role in roles" :key="role.id" class="form-check">
                                        <input
                                        name="default-radio-1" class="form-check-input" type="checkbox" :id="'role-' + role.id" v-model="selectedRoles" :value="role.id"/>
                                        <label class="form-check-label" :for="'role-' + role.id">{{ role.name }}</label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 3" class="btn btn-primary" @click="asignarRoles">Guardar Roles</button>
                                    <button class="btn btn-cerrar" @click="cerrarModalRoles" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal agregar Roles -->

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
import permisos from "../permisos/permisos.vue";

export default {

    components: {

    },extends:permisos,

    data() {
        return {
            filtro: '',
            users: [],
            dependencias: [],
            roles: [],
            selectedRoles: [],
            userRoles: {},
            pagina: 1,
            totalPaginas: 0,
            registrosPorPagina: 7,
            bandera: "",
            name: "",
            email: "",
            id_depen: "",
            password: "",
            iduse: "",
            idro: "",
            lista_permisos:[],

        };
    },

    mounted() {
        this.obtenerUsuarios();
        this.obtenerDependencias();
        this.calcularTotalPaginas();
        this.obtenerPermisos();
    },

    computed: {

        usuariosFiltrados() {
            const filtroMinusculas = this.filtro.toLowerCase();
            return this.users.filter((usuarios) => {
                const nombreCompleto = `${usuarios.name}`;
                const nombreSinAcentos = nombreCompleto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
                const nombreDependencia = this.dependencias.find(dep => dep.id === usuarios.id_depen)?.descripcion || '';
                const dependenciaSinAcentos = nombreDependencia.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();

                this.pagina = 1;

                return (
                    nombreCompleto.toLowerCase().includes(filtroMinusculas) ||
                    nombreSinAcentos.includes(filtroMinusculas) ||
                    usuarios.email.toLowerCase().includes(filtroMinusculas) ||
                    dependenciaSinAcentos.toLowerCase().includes(filtroMinusculas)
                );
            });
        },

        totalPages() {
            return Math.ceil(this.usuariosFiltrados.length / this.perPage);
        },

        paginatedUsuarios() {
            const startIndex = (this.pagina - 1) * this.registrosPorPagina;
            const endIndex = startIndex + this.registrosPorPagina;
            return this.usuariosFiltrados.slice(startIndex, endIndex);
        },
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

        /* Metodos de Usuario */
        obtenerUsuarios() {
        axios.get('/obtenerUsers')
            .then((response) => {
                if (response.data.user) {
                    this.users = response.data.user;
                    this.calcularTotalPaginas();
                } else {
                }
            })
            .catch((error) => {
            });
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

        descripcionDepen(idDepen) {
            const dependencia = this.dependencias.find(dep => dep.id === idDepen);
            return dependencia ? dependencia.descripcion : 'Sin descripción';
        },

        agregarUsuario() {

            const nuevoUsuario = {
                name: this.name,
                email: this.email,
                id_depen: this.id_depen,
                password: this.password,
            };

            axios.post('/agregarUsuario', nuevoUsuario)
                .then(response => {
                    if (response.data.success) {
                        Swal.fire('Éxito', 'Usuario agregado exitosamente.', 'success');
                        this.cerrarModalUsuario();
                        this.obtenerUsuarios();
                    } else {
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    this.cerrarModalUsuario();
                    Swal.fire('Error', 'Se produjo un error al agregar el usuario.', 'error');
                });
        },

        datalleUsuario(idUser) {

        this.iduse = idUser;
        this.bandera = 1;
        this.abrirModalUsuario();

        axios.get("/detalleUsuario/" + idUser)
            .then((response) => {
                const usuario = response.data;
                this.name = usuario.name;
                this.email = usuario.email;
                this.id_depen = usuario.id_depen;
                this.password = usuario.password;

            })
            .catch((error) => {
                console.error(error);
            });
        },

        editarUsuario() {
            var idUser = this.iduse;
            this.cerrarModalUsuario();

            let data = {
                id: idUser,
                name: this.name,
                email: this.email,
                id_depen: this.id_depen,
                password: this.password,

            };

            axios.post(`/editarUsuario`, data)
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
                        this.cerrarModalUsuario();
                        this.obtenerUsuarios();
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

        eliminarUsuario(idUser) {
            Swal.fire({
                title: '¿Estás seguro de que deseas eliminar este Usuario?',
                text: "No se podrá revertir dicha acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete("/eliminarUsuario/" + idUser)
                        .then(response => {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1800
                            });
                            this.obtenerUsuarios();
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
                                    text: 'Error al eliminar el usuario',
                                });
                            }
                        });
                }
            });
        },


        abrirModalUsuario() {
            $("#modalusuarios").modal({ backdrop: "static", keyboard: false });
            $("#modalusuarios").modal("toggle");
        },

        cerrarModalUsuario() {
            $("#modalusuarios").modal("hide");
        },

        /* Metodos de Roles */
        detalleRol(idRol) {

        this.idro = idRol;
        this.bandera = 3;
        this.idRolesSeleccionado = idRol;
        this.abrirModalRoles();

        axios.get(`/obtenerRolesUsuario/${this.idro}`)
                .then((response) => {
                const rolesAsignados = response.data.roles;

                const rolesArray = Array.isArray(rolesAsignados)
                    ? rolesAsignados
                    : Object.values(rolesAsignados);

                if (!this.userRoles[this.idro]) {
                    this.userRoles[this.idro] = ref(reactive([]));
                }

                this.userRoles[this.idro].value = rolesArray;
                this.selectedRoles = rolesArray.map(role => role.id);
                })
                .catch((error) => {
                console.error(error);
                });


        axios.get('/obtenerRoles')
            .then((response) => {
                    if (response.data.role) {
                        this.roles = response.data.role;

                    } else {
                    }
                })
                .catch((error) => {
                });
        },

        asignarRoles() {
            const idUser = this.idRolesSeleccionado;

            axios.post('/asignarRoles', { idUser, selectedRoles: this.selectedRoles })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire('Éxito', 'Roles asignados correctamente.', 'success');
                        this.cerrarModalRoles();
                    } else {
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Se produjo un error al asignar roles.', 'error');
                });
        },

        abrirModalRoles() {
            $("#modalroles").modal({ backdrop: "static", keyboard: false });
            $("#modalroles").modal("toggle");
        },

        cerrarModalRoles() {
            $("#modalroles").modal("hide");
        },

        /* General */
        nuevo() {
            this.limpiarvar();
            this.bandera = 0;
            this.abrirModalUsuario();
        },

        limpiarvar() {
            this.name = null;
            this.email = null;
            this.id_depen = null;
            this.password = null;
        },

        calcularTotalPaginas() {
            this.totalPaginas = Math.ceil(this.users.length / this.registrosPorPagina);
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

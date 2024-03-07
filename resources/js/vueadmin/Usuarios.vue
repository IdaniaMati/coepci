<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>GESTIÓN DE USUARIOS</strong></h2>
        </div>


        <div class="card-container">
            <div class="card">

                <div class="nav-item d-flex align-items-center">
                    <button class="btn btn-info mb-3" @click="nuevo">Agregar Nuevo Usuario</button>
                </div>

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Correo Electrónico</th>
                                <th>Dependencia</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in paginatedUsuarios" :key="user.id">

                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.id_depen }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" @click="datalleEvento(user.id)">Editar</button>&nbsp;
                                    <button class="btn btn-danger btn-sm" @click="eliminarEvento(user.id)">Eliminar</button>
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
                                    <h5 class="modal-title"><strong>Usuarios</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModal" aria-label="Close"></button>
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
                                                    <option value="91">91</option>
                                                    <option value="92">92</option>
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
                                    <button v-if="bandera === 1" class="btn btn-primary" @click="editarEvento">Editar</button>
                                    <button class="btn btn-secondary" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
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

export default {

    data() {
        return {
            users: [],
            user: [],
            pagina: 1,
            totalPaginas: 0,
            registrosPorPagina: 7,
            bandera: "",
            name: "",
            email: "",
            id_depen: "",
            password: "",

        };
    },

    mounted() {
        this.obtenerUsuarios();
        this.calcularTotalPaginas();
    },

    computed: {
        paginatedUsuarios() {
            const startIndex = (this.pagina - 1) * this.registrosPorPagina;
            const endIndex = startIndex + this.registrosPorPagina;
            return this.users.slice(startIndex, endIndex);
        },
    },

    methods: {

        obtenerUsuarios() {
            axios.get('/obtenerUsers')
                .then((response) => {
                    if (response.data.user) {
                        this.users = response.data.user;
                        this.calcularTotalPaginas();
                    } else {
                        console.log(response.data.message);
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
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
                        this.cerrarModal();
                        this.obtenerUsuarios();
                    } else {
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    this.cerrarModal();
                    Swal.fire('Error', 'Se produjo un error al agregar el usuario.', 'error');
                });
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

<template>
    <div>
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Formulario de votación</h5>
                </div>

                <div>

                    <button @click="cerrarSesion" class="btn btn-secondary">Cerrar Sesión</button>
                </div>

                <div class="card-body">
                    <form >
                        <div class="col-xxl" >
                            <div class="card mb-4">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">Grupo</h5>
                                </div>
                                <div class="card-body col-xxl">
                                    <div class="col-sm-10">
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name">Candidato 1</label>
                                            <div class="col-sm-10">
                                                <select v-model="nombre" class="form-select" aria-label="Default select example" >
                                                    <option disabled selected>Seleccionar</option>
                                                    <option v-for="opcion in nombre" :value="opcion.id">{{ opcion.nombre }}</option>
                                                </select>
                                                <div v-if="!nombre" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class= "row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name">Candidato 2</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" aria-label="Default select example" >
                                                    <option value=""></option>
                                                    <option >
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class= "row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name">Candidato 3</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" aria-label="Default select example" >
                                                    <option value=""></option>
                                                    <option >
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class= "row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary">Enviar votación</button> &nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type="submit" class="btn btn-primary">Limpiar campos</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import axios from "axios";
  import Swal from 'sweetalert2'
  import 'sweetalert2/dist/sweetalert2.min.css'

export default {
    data() {
        return {

        };
    },
    created() {
        /* this.obtenerEmpleados(); */
    },
    methods: {

        cerrarSesion() {
            axios.post("/CerrarSesion").then(() => {
                window.location.href = '/';
            }).catch((error) => {
                console.error(error);
            });
        },
    },
};

</script>

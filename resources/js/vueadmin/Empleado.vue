<template>
    <div class="container">
      <form>
        <div class="mb-3">
          <label class="form-label">CURP</label>
          <input v-model="curp" type="text" class="form-control" required />
          <div v-if="!curp" class="text-danger">Este campo es obligatorio.</div>
        </div>

        <button type="button" class="btn btn-inline btn-primary" @click="ingresar">Login</button>
        
      </form>
    </div>
  </template>

<script>
  import axios from "axios";
  import Swal from 'sweetalert2'
  import 'sweetalert2/dist/sweetalert2.min.css'

  export default {

    components: {

    },

    data() {
      return {
        curp: "",
      };
    },

    created() {

    },

    computed: {

    },

    methods: {


        ingresar() {
            if (!this.curp) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, completa todos los campos obligatorios.',
                });
            return;
            }

            let dataarray = {
                curp: this.curp,
            };

        axios
            .post("/EmpleadoLogin", dataarray)
            .then((response) => {

                if (response.data.success) {

                    window.location.href = response.data.redirect;

                } else {

                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'CURP no encontrada',
                    });
                }

                }) .catch((error) => {
            });
        },
    },

  };

  </script>

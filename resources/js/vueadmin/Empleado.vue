<template>
    <div class="container">
      <div class="center-container">
        <div class="image-container center">
          <img src="assets/img/logo-2.png" alt="" height="250px" width="450px">
        </div>
        <br>
        <div class="sign-title">
          <h1 class="title-text">Sistema de Votación para el Comité de Ética y Prevención de Conflicto de Interés</h1>
          <div class="sign-note">
            <p class="note-text">Para iniciar la votación es necesario ingresar su CURP</p>
          </div>
        </div>
      </div>

      <form>
        <div class="mb-3">
          <label class="note-text">CURP</label>
          <input v-model="curp" type="text" class="form-control" required />
          <div v-if="!curp" class="text-danger">Este campo es obligatorio.</div>
        </div>

        <button type="button" class="btn btn-inline btn-primary" @click="ingresar">Login</button>
      </form>

      <div class="announcement-box">
        <p class="announcement-text">El sistema de votación se encuentra desactivado. <br>Favor de verificar la fecha en la
            cual el concurso inicie. Si ya se encuentra activo la convocatoria, favor de recargar la página.
        </p>
      </div>

    </div>
  </template>

  <style scoped>
  .container {
    /* display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh; */
  }

  .center-container {
    text-align: center;
  }

  .title-text {
    font-size: 24px;
  }

  .note-text {
    font-size: 16px;
  }

  .image-container {

  }

  .sign-title {

  }

  .sign-note {

  }

  .announcement-box {
  background-color: #f0f0f0;
  padding: 10px;
  margin-top: 20px;
}

.announcement-text {
  color: #555;
  font-size: 14px;
  text-align: center;
}
  </style>

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

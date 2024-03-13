<template>
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">

      <div class="card">
        <div class="card-body">

          <div class="app-brand justify-content-center">

              <img src="assets/img/logo-2.png" alt="" height="150px" />

          </div>
          <div class="justify-content-center">
            <h2 class="app-brand-text fw-bolder">COEPCI</h2>
          </div>

          <h4 class="mb-2">Sistema de Votación para el Comité de Ética y Prevención de Conflicto de Interés</h4>
          <p class="mb-4">Para iniciar la votación es necesario ingresar su CURP</p>

          <form>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="curp">CURP</label>
              </div>
              <div class="input-group input-group-merge">
                <input v-model="curp" type="text" id="curp" class="form-control" name="curp" maxlength="18" required/>


              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="button" @click="ingresar"> Ingresar </button>
            </div>

          </form>
        </div>
      </div>



    </div>
  </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

export default {

  data() {
    return {
      curp: "",
      isVotacionDesactivada: false,
      isVotacionFin: false,
    };
  },

  computed: {

  },

  created() {

  },

  methods: {

    ingresar() {
        if (!this.curp) {
            this.showErrorAlert("Por favor, ingresa una CURP para continuar.");
            return;
        }

        if (this.curp.length !== 18) {
            this.showErrorAlert("La CURP debe contener 18 caracteres.");
            return;
        }

        const dataarray = {
            curp: this.curp,
        };

        axios.post("/EmpleadoLogin", dataarray)
            .then((response) => {
                if (response.data.success) {
                    window.location.href = response.data.redirect;
                } else {
                    this.showErrorAlert(response.data.error || "Error desconocido al intentar iniciar sesión.");
                }
            })
            .catch((error) => {
                console.error(error);
                this.showErrorAlert("Hubo un error al intentar iniciar sesión.");
            });
    },

    showErrorAlert(message) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: message,
      });
    },

  },
};
</script>

<style scoped>
/* Add your scoped styles here if needed */
</style>

<template>
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
              <!-- Replace the image source with the correct path for your Vue project -->
              <img src="assets/img/logo-2.png" alt="" height="150px" />

          </div>
          <div class="justify-content-center">
            <h2 class="app-brand-text fw-bolder">COEPCI</h2>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">Sistema de Votación para el Comité de Ética y Prevención de Conflicto de Interés</h4>
          <p class="mb-4">Para iniciar la votación es necesario ingresar su CURP</p>

          <form>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="curp">CURP</label>
              </div>
              <div class="input-group input-group-merge">
                <input
                  v-model="curp"
                  type="text"
                  id="curp"
                  class="form-control"
                  name="curp"
                  :disabled="isVotacionDesactivada || isVotacionFin"
                  maxlength="18"
                  required
                />
                <!-- You may need to replace this icon with the Vue equivalent -->
                
              </div>
            </div>
            <div class="mb-3">
              <button
                class="btn btn-primary d-grid w-100"
                type="button"
                @click="ingresar"
                :disabled="isVotacionDesactivada || isVotacionFin"
              >
                Ingresar
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- /Register -->

      <div v-if="isVotacionDesactivada || isVotacionFin" class="announcement-box">
        <p class="announcement-text">
          {{ isVotacionDesactivada ? 'El sistema de votación se encuentra desactivado.' : 'Las votaciones han finalizado. Gracias por Participar' }}
          <br />
          Favor de verificar la fecha en la cual el concurso inicie. Si ya se encuentra activa la convocatoria, favor de recargar la página.
        </p>
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

  created() {
    this.verificarFechaVotacion();
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

      axios
        .post("/EmpleadoLogin", dataarray)
        .then((response) => {
          if (response.data.success) {
            window.location.href = response.data.redirect;
          } else {
            this.showErrorAlert("CURP no encontrada");
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },

    verificarFechaVotacion() {
      axios
        .get("/obtenerFechaInicioConcurso")
        .then((response) => {
          if (response.data.fechaInicio && response.data.fechaFin) {
            const fechaInicioConcurso = new Date(response.data.fechaInicio);
            const fechaFinConcurso = new Date(response.data.fechaFin);

            this.isVotacionDesactivada = new Date() < fechaInicioConcurso;
            this.isVotacionFin = new Date() > fechaFinConcurso;
          } else {
            console.error("No se pudo obtener la fecha de inicio o fin del concurso.");
          }
        })
        .catch((error) => {
          console.error(error);
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
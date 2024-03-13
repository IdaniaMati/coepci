<template>
    <div>
      <div class="text-center mb-4">
        <h2><strong>VEDA ELECTORAL</strong></h2>
      </div>

      <div class="card-container">
        <div class="card">
          <div class="card-body">
            <div class="form-check form-switch mb-2">
              <input v-model="vedaActiva" v-if="hab_permisos('Activar_Veda')" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
              <label class="form-check-label" for="flexSwitchCheckDefault">Veda Electoral</label>
            </div>
          </div>

          <div class="table-container">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Imagen Actual</th>
                  <th>Nueva Imagen</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in imagenes" :key="index">
                  <td><img :src="item.imagenActual" alt="Imagen Actual" class="imagen-preview"></td>
                  <td><img :src="item.nuevaImagen" alt="Nueva Imagen" class="imagen-preview"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </template>

<style>

</style>

<script>
import Swal from 'sweetalert2';
import permisos from "../permisos/permisos.vue";

export default {

    components: {

    },extends:permisos,

    data() {
        return {
            lista_permisos:[],
            vedaActiva: false,
            imagenes: [
                { imagenActual: 'assets/img/logo-2.png',
                    nuevaImagen: 'assets/img/veda/Escudo.png'
                },
            ],
        };
    },

    mounted() {
        this.obtenerPermisos_user();
    },

    computed: {

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

    }
};
</script>

<style>
  .imagen-preview {
    width: 50%;
    height: auto;
  }
</style>

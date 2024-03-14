<template>
    <div>
      <div class="text-center mb-4">
        <h2><strong>VEDA ELECTORAL</strong></h2>
      </div>

      <div class="card-container">
        <div class="card">
          <div class="card-body">
            <div class="form-check form-switch mb-2">
              <input v-model="vedaActiva" @change="cambiarEstadoVeda" v-if="hab_permisos('Activar_Veda')" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
              <label class="form-check-label" for="flexSwitchCheckDefault">Veda Electoral</label>
            </div>
          </div>

          <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th>Imágenes Activas</th>
                    <th>Imágenes Inactivas</th>
                    </tr>
                </thead>

                <tbody>
                    <template v-if="imagenesActivas.length > 0 || imagenesInactivas.length > 0">
                        <tr v-for="(active, index) in imagenesActivas" :key="'row-' + index">
                        <td><img :src="active.ruta" alt="Imagen Actual" class="imagen-preview"></td>
                        <td v-if="index < imagenesInactivas.length">
                            <img :src="imagenesInactivas[index].ruta" alt="Imagen Actual" class="imagen-preview">
                        </td>

                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                        <td colspan="4">No hay imágenes para mostrar</td>
                        </tr>
                    </template>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </template>

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
        imagenesActivas: [],
        imagenesInactivas: [],
      };
    },

    mounted() {
      this.obtenerPermisos_user();
      this.obtenerEstadoVeda();
      this.obtenerImagenes();
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

        obtenerEstadoVeda() {
            axios
            .get("/obtenerEstadoVeda")
            .then((response) => {
                this.vedaActiva = response.data.estado === 1;
            })
            .catch((error) => {
                console.error(error);
                Swal.fire('Error al obtener el estado de la veda', '', 'error');
            });
        },

        cambiarEstadoVeda() {
            const nuevoEstadoVeda = this.vedaActiva ? 1 : 0;

            axios
            .post("/cambiarEstadoVeda", { estado: nuevoEstadoVeda })
            .then((response) => {
                this.cambiarEstadoImagen(nuevoEstadoVeda);
                this.obtenerImagenes();
            })
            .catch((error) => {
                console.error(error);
                Swal.fire('Error al cambiar el estado de la veda', '', 'error');
            });
        },

        cambiarEstadoImagen(nuevoEstado) {
            axios
            .post("/cambiarEstadoImagen", { estado: nuevoEstado })
            .then((response) => {
                Swal.fire('Estado de la veda y las imágenes actualizado correctamente', '', 'success');
            })
            .catch((error) => {
                console.error(error);
                Swal.fire('Error al cambiar el estado de las imágenes', '', 'error');
            });
        },

        obtenerImagenes() {
        axios
            .get("/Obtenertodasimagenes")
            .then((response) => {
                const activas = response.data.activas || [];
                const inactivas = response.data.inactivas || [];
                this.imagenesActivas = activas.map((item) => ({
                    ruta: item.ruta,
                    nuevaImagen: item.nuevaImagen
                }));
                this.imagenesInactivas = inactivas.map((item) => ({
                    ruta: item.ruta,
                    nuevaImagen: item.nuevaImagen
                }));
            })
            .catch((error) => {
                console.error(error);
                Swal.fire('Error al obtener las imágenes', '', 'error');
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

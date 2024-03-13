<template>
  <div>
    <div class="container-xl">
      <div class="card mb-4 shadow"><br>
        <h2 class="mb-0 text-center fs-4">¡Gracias por votar!</h2>
        <div class="card-body">
          <p class="fs-6">
            Aquí encontrarás los resultados de las votaciones de la primera y segunda ronda del año {{ currentYear }},
            así como los resultados históricos. Estas votaciones buscan elegir representantes del <strong>COEPCI</strong>
            (Comité de Ética y Prevención de Conflictos de Interés), un cuerpo colegiado compuesto por servidores
            públicos de diversos niveles jerárquicos. Su objetivo principal es promover la ética y la integridad pública
            para mejorar constantemente el clima y la cultura organizacional. El COEPCI aborda señalamientos por
            desviaciones al Código de Ética, de Conducta, Reglas de Integridad y otros lineamientos, resolviendo consultas
            sobre posibles conflictos de interés y promoviendo la integridad de los servidores públicos.
          </p>
        </div>
        <div class="row mb-5">
          <div class="col-sm-10 offset-sm-1 d-flex justify-content-center">
            <button type="button" class="btn btn-primary mx-auto fs-5" @click="resultados">
              Ver Resultados {{ currentYear }}
            </button>
            <button type="button" class="btn btn-primary mx-auto fs-5" @click="resultadosHistoricos">
              Ver Resultados Históricos
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        currentYear: new Date().getFullYear(),
      };
    },

    created() {
      this.calcularYGuardarGanadores();
  },

    methods: {
      resultados() {
        window.location.href = '/resultado';
      },

      resultadosHistoricos() {
        window.location.href = '/historico';
      },

      calcularYGuardarGanadores() {
      axios
          .get("/calcular-y-guardar-ganadores")
          .then((response) => {
          console.log(response.data.message);
          })
          .catch((error) => {
          console.error("Error al calcular y guardar ganadores", error);
          });
      },

    },
  };
</script>

<style scoped>

.fs-4 {
/* Tamaño de fuente 1.5rem */
font-size: 1.5rem;
}

.fs-5 {
/* Tamaño de fuente 1.25rem */
font-size: 1.25rem;
}

.fs-6 {
/* Tamaño de fuente 1rem */
font-size: 12px;
text-align: justify;
}
</style>
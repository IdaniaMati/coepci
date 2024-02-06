<template>
    <div>
      <div class="col-xxl">
        <div class="card mb-4">
          
          <h3 class="mb-0 text-center fs-4">Bienvenido</h3>
          <div class="card-body">
            <p class="fs-6">
              A continuación, se presentan los resultados de las votaciones correspondientes 
              a la primera y segunda ronda del año {{ currentYear }}, así como los resultados 
              históricos. Estas votaciones están destinadas a elegir representantes del <strong>COEPCI</strong> 
              (Comité de Ética y Prevención de Conflictos de Interés), que se configura como un cuerpo 
              colegiado compuesto por servidores públicos de diversos niveles jerárquicos dentro de las 
              instituciones. Su principal objetivo es fomentar la ética y la integridad pública para 
              lograr una mejora constante en el clima y la cultura organizacional. Además, el COEPCI 
              aborda señalamientos por desviaciones al Código de Ética, de Conducta, Reglas de Integridad 
              y demás lineamientos o protocolos. También se encarga de resolver consultas sobre posibles 
              conflictos de interés, promoviendo la integridad de los servidores públicos e implementando 
              acciones permanentes para fortalecer su comportamiento ético.
            </p>
        </div>
        <div class="row mb-5">
          <label class="col-sm-1 col-form-label"></label>
          <div class="col-sm-10 d-flex justify-content-center">
            <button type="button" class="btn btn-primary mx-auto fs-5" @click="resultados">
              Resultados
              {{ currentYear }}
            </button>

            <button type="button" class="btn btn-primary mx-auto fs-5" @click="resultadosHistoricos">
              Resultados Históricos
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
        currentYear: new Date().getFullYear()
      };
    },
    methods: {
      resultados() {
        window.location.href = '/resultado';
      },
      resultadosHistoricos() {
        window.location.href = '/historico';
      },
    }
  };
</script>


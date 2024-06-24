<template>
    <div>
      <div class="col-xxl">
        <div class="card mb-4">
          <br><br>
            <div class="sign-title">
                <h1 class="title-text text-center">Historico de Integrantes de COEPCI</h1>
            </div>

          <div class="card-body">
            <div>
              <ul class="nav nav-tabs" role="tablist">
                <li v-for="(concursoPorAnio, anio) in historico" :key="anio" class="nav-item">
                  <a :id="`tab-${anio}`" :href="`#pane-${anio}`" class="nav-link" :aria-controls="`pane-${anio}`" data-bs-toggle="tab" role="tab" @click="cambiarPestana(anio)">
                    {{ anio }} <!-- Año de concurso -->
                  </a>
                </li>
              </ul>
              <div class="tab-content">
                <div v-for="(concursoPorAnio, anio) in historico" :key="anio" :id="`pane-${anio}`" class="tab-pane" role="tabpanel">
                    <div v-for="(concurso, concursoKey) in concursoPorAnio" :key="concursoKey" class="row">
                        <h6 class="mb-3"><strong>{{ concurso.descripcion }}</strong></h6>
                        <div v-for="(grupo, grupoIndex) in concurso.grupos" :key="grupoIndex" class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">{{ `Grupo ${grupoIndex}` }}</h6>
                                    <ul class="list-group">
                                        <li v-for="(ganadores, ganadorIndex) in grupo" :key="ganadorIndex" class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ ganadores }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div>
                            <button type="button" class="btn btn-primary mx-auto fs-5" @click="obtenerVotosTodosEmpleados(concurso.id_conc)">Ver Empleados Votados</button>
                            </div> <br>

                            <div v-if="mostrarInfoEmpleados && votosTodosEmpleados && votosTodosEmpleados.length > 0">
                                <div v-for="(grupoInfo, grupoIndex) in votosTodosEmpleados" :key="grupoIndex" class="mb-3">
                                    <strong>Ronda {{ grupoInfo.ronda }}:</strong>
                                    <div class="row">
                                        <div v-for="(grupo, grupoKey) in grupoInfo.empleadosPorRonda" :key="grupoKey" class="col-md-6 col-lg-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ `Grupo ${grupo.grupo}` }}</h6>
                                                    <ul class="list-group">
                                                        <li v-for="(empleado, empleadoIndex) in grupo.empleados" :key="empleadoIndex" class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span>{{ empleado.nombre }}</span>
                                                            <span class="badge bg-primeros">{{ empleado.novotos }} votos</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-5">
            <label class="col-sm-1 col-form-label"></label>
            <div class="col-sm-10 d-flex justify-content-center">
              <button type="button" class="btn btn-primary mx-auto fs-5" @click="regresar">Regresar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<style>
.concurso-card {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
}
.card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-title {
    color: #B68400;
    font-weight: bold;
}

.list-group-item {
    border: 1px solid #ddd;
    margin-bottom: 5px;
}

.card-body {
    padding: 20px;
}

.fs-4 {
    margin-bottom: 20px;
}

.btn-primary {
    background-color: #AB0A3D;
    border: 1px solid #AB0A3D;
}

.btn-primary:hover {
    background-color: #440412;
    border: 1px solid #440412;
}

.bg-primeros{
  background-color: #ab0a3d;
}

.bg-segundos{
  background-color: #9c9312;
}
</style>

<script>
import axios from 'axios';

export default {
  data() {
    return {
        historico: {},
        votosTodosEmpleados: {},
        mostrarInfoEmpleados: false,
        pestañaActual: null,
        dependencias: [],
        id_depen: null,
    };
  },

  created() {

    this.obtenerDependecias();
    this.obtenerHistorico();
    this.cambiarDependencia();

    const urlParams = new URLSearchParams(window.location.search);
    const idDependencia = urlParams.get('idDependencia');

    if (idDependencia) {
        this.id_depen = idDependencia;
        this.obtenerHistorico(idDependencia);
    }
  },

  methods: {

    obtenerDependecias(){
        axios.get('/obtenerDependencias')
            .then((response) => {
                this.dependencias = response.data.user;
            })
            .catch((error) => {
                console.error(error);
            });
        },

    cambiarDependencia() {
        this.obtenerHistorico(this.id_depen);
    },

    cambiarPestana(anio) {
        this.pestañaActual = anio;
        this.mostrarInfoEmpleados = false;
    },

    regresar() {
        window.location.href = '/nominaciones';
    },

    obtenerHistorico(idDependencia) {
      axios.get(`/obtenerHistorico?idDependencia=${idDependencia}`)
        .then(response => {
          this.historico = response.data.historico;
        })
        .catch(error => {
        });
    },

    obtenerVotosTodosEmpleados(idConcurso) {
        axios.get(`/obtenerVotosTodosEmpleados/${idConcurso}`)
        .then(response => {
            if (response.data && typeof response.data === 'object') {
            const votosPorRondaYGrupo = response.data.votosPorRondaYGrupo;
            const votosEmpleados = [];

            for (const ronda in votosPorRondaYGrupo) {
                if (Object.prototype.hasOwnProperty.call(votosPorRondaYGrupo, ronda)) {
                const grupos = votosPorRondaYGrupo[ronda];

                const empleadosPorRonda = [];
                for (const grupo in grupos) {
                    if (Object.prototype.hasOwnProperty.call(grupos, grupo)) {
                    const empleados = grupos[grupo].map(empleado => ({
                        nombre: empleado.nombre,
                        novotos: empleado.novotos
                    }));

                    empleadosPorRonda.push({
                        grupo: Number(grupo),
                        empleados
                    });
                    }
                }
                votosEmpleados.push({
                    ronda: Number(ronda),
                    empleadosPorRonda
                });
                }
            }
            this.mostrarInfoEmpleados = !this.mostrarInfoEmpleados;

            this.votosTodosEmpleados = votosEmpleados.sort((a, b) => a.ronda - b.ronda);
            } else {
            }
        })
        .catch(error => {
        });
    },
  },
};
</script>

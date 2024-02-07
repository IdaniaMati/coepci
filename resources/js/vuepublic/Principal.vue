<template>
    <div>
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-header d-flex align-items-center justify-content-between">
            <button @click="cerrarSesion" class="btn btn-secondary ms-auto">Cerrar Sesión</button>
          </div> <br>
          <h3 class="mb-0 text-center fs-4">Bienvenido</h3>
          <div class="card-body">
            <p class="fs-6">
                Que con motivo de las reformas Constitucionales en materia de combate a la corrupción,
                publicadas en el Diario Oficial de la Federación el veintisiete de mayo del año dos mil
                quince, llevaron a la expedición de la Ley General del Sistema Nacional Anticorrupción,
                publicada en el citado medio oficial con fecha dieciocho de julio del año dos mil dieciséis;
                la cual, entre otros objetivos, tiene la de establecer las acciones permanentes que aseguren
                la integridad y el comportamiento ético de los servidores públicos, así como crear las bases
                mínimas para que todo Órgano del Estado mexicano establezca políticas eficaces de ética
                pública y responsabilidad en el servicio público. <br><br>

                Que de conformidad con los artículos 109, fracción III, de la Constitución Política de los
                Estados Unidos Mexicanos; 5 de la Ley General del Sistema Nacional Anticorrupción; 7 de
                la Ley General de Responsabilidades Administrativas; y 5 de la Ley del Sistema
                Anticorrupción del Estado de Quintana Roo, todos los servidores públicos en el desempeño
                de sus empleos, cargos o comisiones deberán observar los principios de legalidad,
                objetividad, profesionalismo, honradez, lealtad, imparcialidad, eficiencia, equidad,
                transparencia, economía, integridad, disciplina, transparencia, eficacia y competencia por
                mérito, que rigen su actuar en la Administración Pública. <br><br>

                EI <strong>COEPCI</strong> se constituye como un cuerpo colegiado de servidores públicos de
                los distintos niveles jerárquicos dentro de las instituciones, cuya finalidad es promover la
                ética y la integridad pública, para lograr una mejora constante del clima y cultura
                organizacional, dar tratamiento a los señalamientos por desviaciones al Código de Ética,
                de Conducta, Reglas de Integridad y demás lineamientos o protocolos y, resolver respecto
                a las consultas por posibles conflictos de interés, impulsando la integridad de los servidores
                públicos e implementando acciones permanentes que fortalezcan su comportamiento ético.
            </p>
        </div>
        <div class="row mb-5">
          <label class="col-sm-1 col-form-label"></label>
          <div class="col-sm-10 d-flex justify-content-center">
            <button type="button" class="btn btn-primary mx-auto fs-5" @click="votarprimera">Primera Ronda</button>
            <button type="button" class="btn btn-primary mx-auto fs-5" @click="votarsegunda" :disabled="isVotacionDesactivada">Segunda Ronda</button>
          </div>
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
            isVotacionDesactivada: false,
        };
    },

    created() {
        this.verificarFechaVotacion();
    },

    methods: {

      cerrarSesion() {
        axios.post("/CerrarSesion").then(() => {

          window.location.href = '/';

            }).catch((error) => {
                console.error(error);
            });
      },


      votarprimera() {
            this.verificarVotoUsuario('1');
        },

      votarsegunda() {
            this.verificarVotoUsuario('2');
        },

      verificarVotoUsuario(ronda) {
        axios.get(`/verificarVotoUsuarioActual/${ronda}`)
            .then((response) => {
                if (response.data.yaVoto) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Ya has realizado tu voto',
                        text: 'No puedes votar más de una vez.',
                    });
                } else {
                    window.location.href = `/Votacion?ronda=${ronda}`;
                }
            })
            .catch((error) => {
                console.error(error);
            });
      },


      verificarFechaVotacion() {
            axios.get("/obtenerSegundaFechaConcurso")
                .then((response) => {
                    if (response.data.fechaSegundo) {
                        const fechaSegundoConcurso = new Date(response.data.fechaSegundo);
                        this.isVotacionDesactivada = new Date() < fechaSegundoConcurso;
                    } else {
                        console.error('No se pudo obtener la fecha de inicio del concurso.');
                    }
                })
                .catch((error) => {
                    console.error(error);
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

<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>RESPALDO DE SISTEMA</strong></h2>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="info-container">
                    <div class="info-container">
                        <button v-if="hab_permisos('Crear_Respaldo')" class="btn btn-download" title="Generar respaldo" @click="checkAndExport">
                           <i class="bi bi-floppy" style="font-size: 20px;"></i>
                        </button>
                    </div>
                </div>

                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Respaldos Base de Datos</strong></h5>
                </div>

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th style="width: 10%;">Id</th>
                            <th style="width: 0%;">Nombre de archivo</th>
                            <th style="width: 25%;">Fecha</th>
                            <th style="width: 15%;">Tamaño (MB)</th>
                            <th style="width: 10%;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="backup in backups" :key="backup.id">
                                <td>{{ backup.Id }}</td>
                                <td>{{ backup.filename }}</td>
                                <td>{{ backup.creation_date }}</td>
                                <td>{{ backup.size_mb }}</td>
                                <td>
                                    <button v-if="hab_permisos('Descargar_Respaldo')" type="button" class="nav-link active" @click="mostrarModal(backup.filename)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5M8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                    <!-- Modal agregar roles -->
                    <div class="container">
                        <div class="modal fade" id="confirmarpass" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><strong>Confirmar contraseña</strong></h5>
                                        <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Ingrese contraseña para confirmar la descarga</label>
                                                <input type="password" v-model="password"  class="block mt-1 w-full" placeholder="Ingrese contraseña" required/>
                                                <div v-if="!password"  class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button v-if="bandera === 1" class="btn btn-primary" @click="confirmarContrasena">Descargar</button>
                                        <button v-if="bandera === 2" class="btn btn-primary" @click="realizarRespaldo">Realizar Respaldo</button>
                                        <button v-if="bandera === 3" class="btn btn-primary" @click="realizarRespaldoNuevo">Realizar Respaldo</button>
                                        <button class="btn btn-cerrar" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>

        </div>

    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import permisos from "../permisos/permisos.vue";


export default {

    components: {

    },extends:permisos,

    data() {
        return {
            backups: [],
            backupFileName: "",
            password: "",
            bandera: "",
            lista_permisos:[],
        };
    },

    mounted() {
        this.getBackups();
        this.obtenerPermisos_user();
    },

    methods: {

        obtenerPermisos_user(){
            axios
                .get("/Obtenerpermisos")
                .then((response) => {
                    this.lista_permisos  = response.data;

                })
                .catch((error) => {
                    //console.error(error);

                });

        },

        realizarRespaldo(){
            axios.post('/confirmpassword', { password: this.password })
                .then(response => {
                    if (response.data.success) {
                        this.cerrarModal();
                        this.confirmExport();
                    } else {
                        this.cerrarModal();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'La contraseña es incorrecta. Inténtalo de nuevo.'
                        });
                    }
                })
                .catch(error => {
                    this.cerrarModal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la solicitud. Inténtalo de nuevo.'
                    });
                });
        },

        realizarRespaldoNuevo(){
            axios.post('/confirmpassword', { password: this.password })
                .then(response => {
                    if (response.data.success) {
                        this.cerrarModal();
                        this.respaldofile();
                    } else {
                        this.cerrarModal();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'La contraseña es incorrecta. Inténtalo de nuevo.'
                        });
                    }
                })
                .catch(error => {
                    this.cerrarModal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la solicitud. Inténtalo de nuevo.'
                    });
                });
        },

        confirmarContrasena() {
            axios.post('/confirmpassword', { password: this.password })
                .then(response => {
                    if (response.data.success) {
                        this.cerrarModal();
                        this.downloadBackup(this.backupFileName);
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto!',
                            text: 'Archivo descargado correctamente.'
                        });
                    } else {
                        this.cerrarModal();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'La contraseña es incorrecta. Inténtalo de nuevo.'
                        });
                    }
                })
                .catch(error => {
                    this.cerrarModal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la solicitud. Inténtalo de nuevo.'
                    });
                });
        },

        mostrarModal(id) {
            this.backupFileName = id;
            this.bandera = 1;
            this.abrirModal();
            this.limpiarvar();
        },

        mostrarModalRespaldoNuevo() {
            this.bandera = 3;
            this.abrirModal();
            this.limpiarvar();
        },

        mostrarModalRespaldo() {
            this.bandera = 2;
            this.abrirModal();
            this.limpiarvar();
        },

        importarRespaldo() {
            this.bandera = 3;
            this.abrirModal();
            this.limpiarvar();
        },

        limpiarvar() {
            this.password = null;
        },

        abrirModal() {
            $("#confirmarpass").modal({ backdrop: "static", keyboard: false });
            $("#confirmarpass").modal("toggle");
        },

        cerrarModal() {
            $("#confirmarpass").modal("hide");
        },

        checkAndExport() {
            let today = new Date();
            let todayDate = `${today.getDate()}/${today.getMonth() + 1}/${today.getFullYear()}`;
            let existsToday = this.backups.some(backup => {
                let backupDate = new Date(backup.creation_date);
                let backupDateString = `${backupDate.getDate()}/${backupDate.getMonth() + 1}/${backupDate.getFullYear()}`;
                return backupDateString === todayDate;
            });
            if (existsToday) {
                this.mostrarModalRespaldo();
            } else {
                this.mostrarModalRespaldoNuevo();
            }
        },

        downloadBackup(filename) {
        axios
            .get("/downloadBackup/" + filename, { responseType: "blob" })
            .then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", filename);
                document.body.appendChild(link);
                link.click();
            })
            .catch((error) => {
                //console.error("Error al descargar el archivo de respaldo:", error);
            });
        },

        confirmExport() {
            let oldestBackupDate = '';
            let oldestBackupSize = '';
            if (this.backups.length > 0) {
                let oldestBackup = this.backups[0];
                let oldestBackupDateObj = new Date(oldestBackup.creation_date);
                oldestBackupDate = `${oldestBackupDateObj.getDate()}/${oldestBackupDateObj.getMonth() + 1}/${oldestBackupDateObj.getFullYear()} ${oldestBackupDateObj.getHours()}:${oldestBackupDateObj.getMinutes() < 10 ? '0' : ''}${oldestBackupDateObj.getMinutes()}`;
                oldestBackupSize = oldestBackup.size_mb;
            }

            let latestBackup = this.backups.length > 0 ? this.backups[this.backups.length - 1] : null;

            let latestBackupDate = '';
            let latestBackupSize = '';
            if (latestBackup) {
                let latestBackupDateObj = new Date(latestBackup.creation_date);
                latestBackupDate = `${latestBackupDateObj.getDate()}/${latestBackupDateObj.getMonth() + 1}/${latestBackupDateObj.getFullYear()} ${latestBackupDateObj.getHours()}:${latestBackupDateObj.getMinutes() < 10 ? '0' : ''}${latestBackupDateObj.getMinutes()}`;
                latestBackupSize = latestBackup.size_mb;
            }

            let message = `Actualmente existe un respaldo con fecha ${latestBackupDate} horas, con un tamaño de ${latestBackupSize} MB. `;
            if (oldestBackupDate) {
                message += `Si continua se eliminará el respaldo más antiguo con fecha: ${oldestBackupDate} horas, con un tamaño de ${oldestBackupSize} MB`;
            }

            Swal.fire({
                title: '¿Está seguro de agregar un nuevo registro?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.respaldofile();
                }
            });
        },

        respaldofile() {
            axios.get('/respaldofile', { responseType: 'blob' })
                .then(response => {
                    //console.log(response.data.message);
                    Swal.fire({
                        icon: 'success',
                        title: 'Respaldo realizado',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    this.getBackupFileInfo();
                    this.getBackups();
                })
                .catch(error => {
                    //console.error('Error al exportar los datos:', error);
                });
        },

        getBackupFileInfo() {
            axios.get('/getBackupFileInfo')
                .then(response => {
                    this.backups = response.data.backups;
                })
                .catch(error => {
                    //console.error('Error al descargar los archivos en el sistema:', error);
                });
        },

        getBackups() {
            axios.get('/getBackupList')
                .then(response => {
                    this.backups = response.data.backups;
                })
                .catch(error => {
                    //console.error('Error al obtener la lista de respaldos:', error);
                });
        },


    },
};
</script>

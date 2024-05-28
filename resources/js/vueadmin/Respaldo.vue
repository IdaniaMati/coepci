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
                                        <button v-if="bandera === 2" class="btn btn-primary" @click="realizarRespaldo">Realizar Respaldo2</button>
                                        <button v-if="bandera === 3" class="btn btn-primary" @click="realizarRespaldoNuevo">Realizar Respaldo3</button>
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
                console.log("Si existe respaldo");
                this.mostrarModalRespaldo();
            } else {
                console.log("No existe respaldo");
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
            if (this.backups.length > 0) {
                this.backups.sort((a, b) => new Date(a.creation_date) - new Date(b.creation_date));

                let oldestBackup = this.backups[0];
                let latestBackup = this.backups[this.backups.length - 1];


                let oldestBackupDateObj = new Date(oldestBackup.creation_date);
                let latestBackupDateObj = new Date(latestBackup.creation_date);

                let oldestBackupDate = `${oldestBackupDateObj.getDate()}/${oldestBackupDateObj.getMonth() + 1}/${oldestBackupDateObj.getFullYear()} ${oldestBackupDateObj.getHours()}:${oldestBackupDateObj.getMinutes() < 10 ? '0' : ''}${oldestBackupDateObj.getMinutes()}`;
                let latestBackupDate = `${latestBackupDateObj.getDate()}/${latestBackupDateObj.getMonth() + 1}/${latestBackupDateObj.getFullYear()} ${latestBackupDateObj.getHours()}:${latestBackupDateObj.getMinutes() < 10 ? '0' : ''}${latestBackupDateObj.getMinutes()}`;


                let message = `Actualmente existe un respaldo con fecha ${latestBackupDate} horas, con un tamaño de ${latestBackup.size_mb} MB. `;
                if (this.backups.length > 1) {
                    message += `Si continúa, ¿cuál desea eliminar?`;
                    message += `<br><br><b>Respaldos disponibles:</b>`;
                    message += `<br><br><b>1.</b> Más antiguo: ${oldestBackupDate} horas, ${oldestBackup.size_mb} MB`;
                    message += `<br><b>2.</b> Más reciente: ${latestBackupDate} horas, ${latestBackup.size_mb} MB`;

                    Swal.fire({
                        title: '¿Está seguro de agregar un nuevo registro?',
                        html: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                        focusConfirm: false,
                        focusCancel: true,
                        reverseButtons: true,
                        showCloseButton: true,
                        customClass: {
                            closeButton: 'swal2-close',
                            confirmButton: 'swal2-confirm',
                            cancelButton: 'swal2-cancel'
                        },
                        showLoaderOnConfirm: true,

                        preConfirm: () => {
                            const choice = document.querySelector('input[name="swal2-radio"]:checked');
                            return choice ? choice.value : null;
                        },
                        input: 'radio',
                        inputOptions: {
                            '1': 'Eliminar el respaldo más antiguo',
                            '2': 'Eliminar el respaldo más reciente'
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Debe seleccionar una opción';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value === '1') {
                                this.eliminarRespaldo(oldestBackup);
                            } else if (result.value === '2') {
                                this.eliminarRespaldo(latestBackup);
                            }
                        }
                        console.log("3");
                        this.respaldofile();
                    });
                } else {

                }
            } else {

            }
        },

        eliminarRespaldo(respaldo) {
            const filename = respaldo.filename;

            axios.delete(`/deleteBackup/${filename}`)
                .then(response => {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'El respaldo ha sido eliminado correctamente.',
                        icon: 'success',
                        onClose: () => {
                            this.crearNuevoRespaldo();
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al eliminar el respaldo:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al eliminar el respaldo. Por favor, inténtalo de nuevo más tarde.',
                        icon: 'error'
                    });
                });
        },


        // crearNuevoRespaldo() {//antes crearNuevoRespaldo
        //     // Realizar una solicitud al servidor para crear un nuevo respaldo
        //     axios.get('/respaldofile')
        //         .then(response => {
        //             // Manejar la respuesta del servidor
        //             Swal.fire({
        //                 title: 'Nuevo respaldo creado',
        //                 text: 'Se ha creado un nuevo respaldo correctamente.',
        //                 icon: 'success'
        //             });
        //         })
        //         .catch(error => {
        //             // Manejar errores en caso de que la creación del respaldo falle
        //             console.error('Error al crear un nuevo respaldo:', error);
        //             Swal.fire({
        //                 title: 'Error',
        //                 text: 'Hubo un problema al crear un nuevo respaldo. Por favor, inténtalo de nuevo más tarde.',
        //                 icon: 'error'
        //             });
        //         });
        // },

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
                    console.error('Error al descargar los archivos en el sistema:', error);
                });
        },

        getBackups() {
            axios.get('/getBackupList')
                .then(response => {
                    this.backups = response.data.backups;
                })
                .catch(error => {
                    console.error('Error al obtener la lista de respaldos:', error);
                });
        },


    },
};
</script>

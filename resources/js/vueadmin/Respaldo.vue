<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>Historial de respaldos de bases de datos</strong></h2>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="info-container">
                    <div class="info-container">
                        <button class="btn btn-success" @click="checkAndExport">Exportar Datos</button>
                    </div>
                </div>

                <div class="nav-item d-flex align-items-center">
                    <h5 class="card-header"><strong>Respaldos BD</strong></h5>
                    <i class="bx bx-search fs-4 lh-0"></i>
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
                                <i @click="backup.filename" class="bi bi-arrow-bar-down"></i>
                            </td>
                            </tr>
                        </tbody>
                    </table>



                </div>

            </div>
        </div>
        <div v-if="showModal" class="modal">
      <div class="modal-content">
        <input type="password" v-model="password" placeholder="Contraseña">
        <button @click="validatePassword()">Confirmar</button>
      </div>
    </div>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';



export default {
    data() {
        return {
            showModal: false,
            password: "",
            backupFileName: "",
            backups: [],
        };
    },

    mounted() {
        this.getBackups();
    },

    methods: {

        showPasswordModal() {
            this.showModal = true;
        },
        validatePassword() {
            const correctPassword = "contraseña_correcta";

            if (this.password === correctPassword) {
                this.downloadBackup(this.backupFileName);
                this.showModal = false;
                this.password = "";
            } else {
                alert("Contraseña incorrecta. Por favor, inténtalo de nuevo.");
            }
        },

        checkAndExport() {
            let today = new Date();
            let todayDate = `${today.getDate()}/${today.getMonth() + 1}/${today.getFullYear()}`;

            // Verifica si existe un registro con la fecha de hoy
            let existsToday = this.backups.some(backup => {
                let backupDate = new Date(backup.creation_date);
                let backupDateString = `${backupDate.getDate()}/${backupDate.getMonth() + 1}/${backupDate.getFullYear()}`;
                return backupDateString === todayDate;
            });
            if (existsToday) {
                this.confirmExport();
            } else {
                this.respaldofile();
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
            console.error("Error al descargar el archivo de respaldo:", error);
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
                    console.log(response.data.message);
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
                    console.error('Error al exportar los datos:', error);
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
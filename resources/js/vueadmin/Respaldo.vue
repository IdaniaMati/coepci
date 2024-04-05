<template>
    <div>
        <div class="text-center mb-4">
            <h2><strong>Historial de respaldos de bases de datos</strong></h2>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="info-container">
                    <div class="info-container">
                        <!-- Aquí puedes colocar el botón de exportación -->
                        <button class="btn btn-success" @click="respaldofile">Exportar Datos</button>
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
                            <th style="width: 20%;">Archivo</th>
                            <th style="width: 15%;">Tamaño (MB)</th>
                            <th style="width: 25%;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="backup in backups" :key="backup.id">
                            <td>{{ backup.Id }}</td>
                            <td>{{ backup.filename }}</td>
                            <td>{{ backup.size_mb }}</td>
                            <td>
                                <!-- Aquí puedes agregar cualquier acción relacionada con el respaldo, como descargar el archivo -->
                                <button @click="downloadBackup(backup.filename)">Descargar</button>
                            </td>
                            </tr>
                        </tbody>
                    </table>



                </div>

            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';


export default {
    data() {
        return {
            backups: []
        };
    },

    mounted() {
        this.getBackups();
    },

    methods: {
        getBackups() {
            axios.get('/getBackupList')
                .then(response => {
                    this.backups = response.data.backups;
                })
                .catch(error => {
                    console.error('Error al obtener la lista de respaldos:', error);
                });
        },

        downloadBackup(filename) {
            axios.get('/downloadBackup/' + filename, { responseType: 'blob' })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(error => {
                    console.error('Error al descargar el archivo de respaldo:', error);
                });
        },

        respaldofile() {
            axios.get('/respaldofile', { responseType: 'blob' })
                .then(response => {
                    console.log(response.data.message);
                    // Luego, hacer una segunda solicitud para obtener la información del respaldo
                    this.getBackups(); // Actualiza la lista de respaldos después de exportar los datos
                })
                .catch(error => {
                    console.error('Error al exportar los datos:', error);
                });
        },
    },
};
</script>
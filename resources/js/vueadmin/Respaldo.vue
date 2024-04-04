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
                            <th style="width: 15%;">Tamaño</th>
                            <th style="width: 25%;"> </th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td>
                                    
                                </td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                
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
            
        };
    },

    methods: {
        respaldofile() {
            axios.get('/respaldofile', { responseType: 'blob' })
                .then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/sql' }));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'coepci_' + new Date().toISOString().slice(0, 19).replace(/[-:]/g, '') + '.sql');
                    document.body.appendChild(link);
                    link.click();
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>
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
                    <input type="text" class="form-control border-0 shadow-none" placeholder="Buscar..." aria-label="Buscar..." />
                    <button class="btn btn-success ms-2" @click="respaldofile">Exportar Base de datos</button>
                </div>

                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;">ID</th>
                            <th style="width: 20%;">Archivo</th>
                            <th style="width: 15%;">MB</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
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
            // Puedes definir tus datos aquí si los necesitas
        };
    },

    methods: {
        respaldofile() {
            // Realiza una solicitud GET a la ruta correspondiente
            axios.get('/respaldofile.all.data')
                .then((response) => {
                    // Maneja la respuesta si es necesario
                })
                .catch((error) => {
                    // Maneja los errores si es necesario
                });
        },
    },
};
</script>
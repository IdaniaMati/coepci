<template>
    <div>
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Formulario de votación</h5>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submitForm">
                    <div class="col-xxl">
                        <h3>Crupo({{ id_grup }})</h3>
                        <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0"></h5>
                        </div>
                        <div class="card-body col-xxl">
                            <div class="row mb-3">
                                <div class="col-sm-10">
                                    <div class="mb-3">
                                        <select v-model="selectedEmployee1" class="form-select" aria-label="Default select example">
                                            <option v-for="empleado in empleados" :key="empleado.id" :value="empleado.id">{{ empleado.nombre }}</option>
                                        </select>
                                        <select v-model="selectedEmployee2" class="form-select" aria-label="Default select example">
                                            <option v-for="empleado in empleados" :key="empleado.id" :value="empleado.id">{{ empleado.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
        
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
    
<script>
    export default {
    data() {
        return {
        id_grup: null,
        empleado1: null,
        empleado2: null,
        empleados: [],
        };
    },
    methods: {
        submitForm() {
        console.log('Formulario enviado!');
        },
    },
    mounted() {
        // Fetch employees based on the id_grup from the API
        // You can use Axios or any other HTTP library for this
        // Update the 'empleados' array with the fetched data

        // For example, using Axios
        axios.get(`/api/empleados/${this.id_grup}`)
        .then(response => {
            this.empleados = response.data;
        })
        .catch(error => {
            console.error('Error fetching employees:', error);
        });
    },
    };
</script>
    
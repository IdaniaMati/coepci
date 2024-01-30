<template>
    <div>
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Formulario de votación</h5>
                </div>
                <div class="card-body">
                    <form >
                        <div class="col-xxl" >
                            <div class="card mb-4">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">Grupo</h5>
                                </div>
                                <div class="card-body col-xxl">
                                    <div class="col-sm-10">
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name">Candidato 1</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" aria-label="Default select example" >
                                                    <option value=""></option>
                                                    <option >
                                                        
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class= "row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name">Candidato 2</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" aria-label="Default select example" >
                                                    <option value=""></option>
                                                    <option >
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class= "row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name">Candidato 3</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" aria-label="Default select example" >
                                                    <option value=""></option>
                                                    <option >
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class= "row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary">Enviar votación</button> &nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type="submit" class="btn btn-primary">Limpiar campos</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            empleados: [],
        };
    },
    created() {
        this.obtenerEmpleados();
    },
    methods: {
        obtenerEmpleados() {
            axios.get('/empleados')
                .then(response => {
                    this.empleados = response.data.empleados;
                })
                .catch(error => {
                    console.error('Error al obtener la lista de empleados', error);
                });
        },
        EnviarVoto() {
            // Tu lógica para enviar el voto
        },
    },
};
    
</script>
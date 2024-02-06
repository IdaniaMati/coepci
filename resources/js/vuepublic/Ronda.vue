<template>
    <div>
        <div class="col-xxl">
            <div class="card mb-4">
                <h3 class="mb-0 text-center fs-4">Integrantes de COEPCI  {{ currentYear }}</h3>
                <div class="card-body">
                    <!-- Examples -->
                    <div class="row mb-5">
                        <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                            <h5 class="card-title">Grupo 1</h5>
                            
                            
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                            <div class="card-body">
                            <h5 class="card-title">Grupo 1</h5>
                            
                            
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                            <div class="card-body">
                            <h5 class="card-title">Grupo 1</h5>
                            
                            
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- Examples -->
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

<script> 
  export default {
    data() {
      return {
        currentYear: new Date().getFullYear()
      };
    },
    methods: {
     
      regresar() {
        window.location.href = '/nominaciones';
      },
    }
  };
</script>


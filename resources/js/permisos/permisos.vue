<script>
export default {
    props :{
        lista_permisos:{
            type:Array,
            default:()=>[]
        }
    },methods:{
        /* hab_permisos(clave){

            let acceso=this.permisos.find(permiso =>permiso.name==clave);
            if(acceso!= undefined){
                return true;
            }
            return false;
        } */
        hab_permisos(clave) {
            return this.lista_permisos.some((permiso) => permiso.name === clave);
        },
    }
}
</script>

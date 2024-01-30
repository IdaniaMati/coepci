<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Document</title>
</head>
<body>
    <form action="{{ route('login_post') }}" method="post" id="login_form">

        {{csrf_field()}}
        <div class="form-group has-feedback" id="form-group-no_id">
            <input type="text" class="form-control" name="no_id" id="no_id"
            placeholder="Usuario" autofocus required>
            <div class='errors help-block _no_id'></div>
        </div>
    
        <div class="form-group has-feedback" id="form-group-contrasena">
            <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Contrasenna"
            minlength="6" required>
            <div class='errors help-block _contrasena'></div>
        </div>
    
        <button type="submit" id="btn_login" value="Login">Login</button>
    
    </form>
</body>
</html>

<script>
    $(document).ready(function(){
        $('#login_form').on('submit',function(){
            var $form = $(this)
                //Limpiar errores
                $('.errors').hide()
                $('.errors').children('message').html('')

                $.ajax({
                    url: $form.attr('action'),
                    type:$form.attr('method'),
                    data:$form.serialize(),
                    beforeSend:function(){
                        $('#no_id,#contrasena').attr('class','form-control disabled').attr('disabled','disabled')
                        $('#btn_login').attr('class','btn btn-primary btn-block btn-flat disabled').attr('disabled','disabled')
                        $('body').attr('style','cursor:wait')
                        $('#login_form').css("opacity",".5");
                    },
                    dataType:'json',
                    success:function($data){
                        //Si el acceso es insatisfactorio
                        if($data.success==false){
                            if($data.errors){
                                //Mostrar los errores en los campos del login
                                $.each($data.errors,function($i,$value){
                                    $('._'+$i).html($value).fadeIn('fast');
                                    $('#form-group-'+$i).attr('class','form-group has-feedback has-error')
                                    setTimeout(function () {
                                        $('._'+$i).fadeOut()
                                        $('#form-group-'+$i).attr('class','form-group has-feedback')
                                    },5000)
                                })
                            }else{
                                //Mensaje de Error general
                                alert($data.message)
                            }
                            $('#no_id,#contrasena').removeAttr('disabled').attr('class','form-control')
                            $('#btn_login').attr('class','btn btn-primary btn-block btn-flat').removeAttr('disabled')
                            $('#btn_login').children('i').attr('class', 'fa fa-sign-in')
                            $('body').attr('style','cursor:default')
                            $('#login_form').css("opacity","");
                        }else{
                            //Mensaje de Acceso satisfactorio
                            alert($data.message)

                            setTimeout(function(){
                                window.location = '{{ url('/home') }}' //aki defines la ruta que deseas ir despues de que hagas login
                            },1500)
                        }
                    }
                });
                return false;
            })
    })
</script>
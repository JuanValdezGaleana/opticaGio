$(function(){

var frmLogin=$('#frmLogin');
var msjLogin=$('#msjLogin');

frmLogin.on('submit',function(){
  event.preventDefault();
  msjLogin.empty();
  
    $.ajax({
      url:'php/is.php',
      data:$(this).serialize(),
      dataType:'JSON',
      type:'POST',
      success:function(data){},
      error:function(error){
        console.error('ERROR:',error);
      }
    }).done(function(data){
      console.log('Datos de sesion iniciado:',data);
      $.each(data,function(ind,val){
        if(data==''){
          msjLogin.html('<div class="alert alert-danger text-center">Las credenciales no coinciden</div>');
        }else{
            if(val.stat==1){
                window.location="./panel.php?ip="+val.id_persona+'&nom='+btoa(val.nombre);
            }else if(val.stat==0){
              msjLogin.html('<div class="alert alert-danger text-center">'+val.msj+'</div>');
            }
        }
        
      });

    }).fail(function(error){
      console.error('FAIL:',error);
    });


});

  


});
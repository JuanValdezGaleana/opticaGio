<?php
include('conexionpdo.php');
$datos=array();
if(isset($_POST['usuario'])){$usuario=$_POST['usuario'];}
if(isset($_POST['usuario'])){$pass=$_POST['contrasenia'];}
date_default_timezone_set("America/Mexico_City");

if(isset($usuario) && isset($pass)){
        $qDat=$con->prepare('SELECT COUNT(ID_USUARIO) AS NUMREGS FROM usuario WHERE USUARIO="'.$usuario.'" AND PASS="'.md5($pass).'";');
        $dDat=$qDat->execute();

        while($rows=$qDat->fetch()){
            if((int) $rows['NUMREGS']==1){
               
                 /* Consultamos los datos de la persona */
                 $qDatPer=$con->prepare('SELECT A.ID_PERSONA,A.NOMBRE FROM persona A INNER JOIN usuario B ON A.ID_PERSONA=B.ID_PERSONA WHERE B.USUARIO="'.$usuario.'" AND B.PASS="'.md5($pass).'" ;');
                 $dDatPer=$qDatPer->execute();
                     while($rows2=$qDatPer->fetch()){
                         /*Iniciamos y creamos las sesiones */
                         session_start();
                         $_SESSION["SESSION_ID_PERSONA"] = $rows2['ID_PERSONA'];
                         $_SESSION["SESSION_NOMBRE"] = $rows2['NOMBRE'];
                         

                         $datos[]=array('stat'=>1,'id_persona'=>$_SESSION["SESSION_ID_PERSONA"],'nombre'=>$_SESSION["SESSION_NOMBRE"]);
                     }
                

                    

            }elseif((int) $rows['NUMREGS']==0){
                $datos[]=array('stat'=>0,'msj'=>'Correo o contraseña no coinciden');
            }
            
        }
    

    /* Mandamos el estatus o el mensaje */
    echo json_encode($datos);


}else{

    //session_start();
    session_regenerate_id();
    
    //echo 'Valor de sesion '.$_SESSION["SESSION_NOMBRE"];
    if($_SESSION["SESSION_ID_PERSONA"]==''){
        
        header("Location:./index.php");
    }elseif($_SESSION["SESSION_NOMBRE"]!=''){
        
    }


    
}


?>
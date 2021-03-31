<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
include('conexionpdo.php');
//session_start();
//session_regenerate_id();
//$id_persona=$_SESSION["SESSION_ID_PERSONA"];

if(isset($_POST['id_persona'])){$id_persona=$_POST['id_persona'];}

$op=$_GET['op'];

$datos=array();

switch($op){
    case 1:

        $qfacts=$con->prepare('SELECT
            A.ID_FACTURA,
            A.NUM_FACTURA,
            A.ID_PROVEEDOR_LAB,
            A.FECHA,
            B.PROVEEDOR
            FROM facturas A
            INNER JOIN cat_proveedor_lab B
            ON A.ID_PROVEEDOR_LAB=B.ID_PROVEEDOR_LAB;');
            $rqfacts=$qfacts->execute();
            while($dqfacts=$qfacts->fetch()){
                $datos[]=array('id_factura'=>$dqfacts['ID_FACTURA'],'id_proveedor_lab'=>$dqfacts['ID_PROVEEDOR_LAB'],'num_factura'=>$dqfacts['NUM_FACTURA'],'fecha'=>$dqfacts['FECHA'],'proveedor'=>$dqfacts['PROVEEDOR']);
            }

    break;
    case 2:
        $num_factura=$_POST['num_factura'];
        $fecha_factura=$_POST['fecha_factura'];
        $id_proveedor_lab=$_POST['id_proveedor_lab'];
        
        $insFact=$con->prepare('INSERT INTO facturas VALUES("","'.$num_factura.'","'.$fecha_factura.' 00:00:00",'.$id_proveedor_lab.');');
        $dinsFact=$insFact->execute();

        if($dinsFact){
            $datos[]=array('stat'=>1,'msj'=>'Factura creado. Ahora puedes agregar productos');
        }else{
            $datos[]=array('stat'=>0,'msj'=>'Ha ocurrido un error al insertar la factura','fact'=>$num_factura,'fecha'=>$fecha_factura,'lab'=>$id_proveedor_lab);
        }
    break;

    case 3:
        $qProv=$con->prepare('SELECT
            ID_PROVEEDOR_LAB,
            PROVEEDOR
            FROM cat_proveedor_lab;');
            $rProv=$qProv->execute();
            while($dProv=$qProv->fetch()){
                $datos[]=array('id_proveedor_lab'=>$dProv['ID_PROVEEDOR_LAB'],'proveedor_lab'=>$dProv['PROVEEDOR']);
            }
    break;

    case 4:
        $id_factura=$_POST['id_factura'];
        $qProv=$con->prepare('SELECT
            B.ID_PROVEEDOR_LAB,
            B.PROVEEDOR,
            A.NUM_FACTURA
            FROM facturas A
            INNER JOIN cat_proveedor_lab B
            ON A.ID_PROVEEDOR_LAB=B.ID_PROVEEDOR_LAB
            WHERE A.ID_FACTURA='.$id_factura.';');
            $rProv=$qProv->execute();
            while($dProv=$qProv->fetch()){
                $datos[]=array('id_factura'=>$id_factura,
                'id_proveedor_lab_add'=>$dProv['ID_PROVEEDOR_LAB'],
                'proveedor_lab'=>$dProv['PROVEEDOR'],
                'num_factura'=>$dProv['NUM_FACTURA']);
            }
    break;

    case 5:
        $id_factura=$_POST['id_factura'];
        $qProv=$con->prepare('SELECT
            A.ID_ENTRADA,
            A.CANTIDAD,
            A.ID_PRODUCTO,
            B.SKU,
            C.TIPO_LENTE,
            D.MATERIAL,
            E.GRADUACION AS GRAD_ESFERA,
            G.GRADUACION AS GRAD_CILINDRO,
            F.TIPO_REFRACCION
            FROM entradas A
            INNER JOIN producto B
            ON A.ID_PRODUCTO=B.ID_PRODUCTO
            LEFT OUTER JOIN cat_tipo_lente C
            ON B.ID_TIPO_LENTE=C.ID_TIPO_LENTE
            LEFT OUTER JOIN cat_material D
            ON B.ID_MATERIAL=D.ID_MATERIAL
            LEFT OUTER JOIN cat_graduacion E
            ON B.ID_GRADUACION_ESFERA=E.ID_GRADUACION
            LEFT OUTER JOIN cat_graduacion G
            ON B.ID_GRADUACION_CILINDRO=G.ID_GRADUACION
            LEFT OUTER JOIN cat_tipo_refraccion F
            ON B.ID_TIPO_REFRACCION=F.ID_TIPO_REFRACCION
            WHERE A.ID_FACTURA='.$id_factura.'
            ORDER BY A.ID_ENTRADA DESC;');
            $rProv=$qProv->execute();

            while($dProv=$qProv->fetch()){
                $datos[]= array('id_entrada'=>$dProv['ID_ENTRADA'],
                                'cantidad'=>$dProv['CANTIDAD'],
                                'id_producto'=>$dProv['ID_PRODUCTO'],
                                'sku'=>$dProv['SKU'],
                                'tipo_lente'=>$dProv['TIPO_LENTE'],
                                'material'=>$dProv['MATERIAL'],
                                'graduacion_esfera'=>$dProv['GRAD_ESFERA'],
                                'graduacion_cilindro'=>$dProv['GRAD_CILINDRO'],
                                'tipo_refraccion'=>$dProv['TIPO_REFRACCION']);
            }
    break;
    
    case 6:
            $sku=trim($_POST['sku']);
            $id_factura=$_POST['id_factura'];
            $cantidad=$_POST['cantidad'];
            $id_proveedor_lab=$_POST['id_proveedor_lab'];

            /* Buscamos el producto con el SKU introducido */

            //hacemos un conteo para ver si existe el producto con SKU
            $qcount=$con->prepare('SELECT COUNT(ID_PRODUCTO) AS NUMPRODS, ID_PRODUCTO FROM producto WHERE SKU="'.$sku.'" AND ID_PROVEEDOR_LAB='.$id_proveedor_lab.';
           ');
            $rcount=$qcount->execute();
            while($dcount=$qcount->fetch()){
                $numprods=$dcount['NUMPRODS'];
                $id_producto=$dcount['ID_PRODUCTO'];
            }
            //$datos[]=array('sku'=>$sku,'id_factura'=>$id_factura,'cantidad'=>$cantidad,'numprods'=>$numprods);

            if($numprods==0){
                $datos[]=array('stat'=>0,'msj'=>'No se encontró ningún producto con SKU '.$sku.' o el producto no pertenece al laboratorio proveedor='.$id_proveedor_lab);
            }else if($nomprods>=2){
                $datos[]=array('stat'=>0,'msj'=>'No se puede agregar el producto porque existen registros de SKU duplicados');
            }else if($numprods==1){





                // Sumamos las cantidades al inventario
                //Verificamos si existe o no el registro del producto en la tabla inventario
                $qbuscarId=$con->prepare('SELECT COUNT(ID_PRODUCTO) AS NUMID FROM inventario WHERE ID_PRODUCTO='.$id_producto.';');
                $rProv=$qbuscarId->execute();
                while($dbuscarId=$qbuscarId->fetch()){
                    if($dbuscarId['NUMID']==0){
                        //No existe el registro y se crea uno
                        $insnewinv=$con->prepare('INSERT INTO inventario VALUES('.$id_producto.','.$cantidad.',1,1);');
                        $dinsnewinv=$insnewinv->execute();
                        $msj2='Se creó un registro nuevo en la tabla INVENTARIO';

                        // Se inserta el producto 
                        //$datos[]=array('id_factura'=>$id_factura,'id_producto'=>$id_producto,'cantidad'=>$cantidad,'id_persona'=>$id_persona);
                        
                        $insprod=$con->prepare('INSERT INTO entradas VALUES("",NOW(),'.$cantidad.','.$id_persona.','.$id_factura.','.$id_producto.');');
                        $dinsprod=$insprod->execute();

                        if($dinsprod){
                            $datos[]=array('stat'=>1,
                            'msj'=>'Producto insertado',
                            'msj2'=>$msj2
                            );
                        }

                    }else if($dbuscarId['NUMID']>=2){
                        //Hay mas de un registro duplicado para este identificador
                        $msj2='Hay registro duplicado en la tabla INVENTARIO con id_producto='.$id_producto;
                        $datos[]=array('stat'=>0,
                            'msj'=>'No se pudo ingresar el producto',
                            'msj2'=>$msj2
                            );

                    }else if($dbuscarId['NUMID']==1){
                        //Consultamos la cantidad actual y hacemos un update sumando la cantidad actual mas la cantidad a ingresar
                        $qbuscarId=$con->prepare('SELECT CANTIDAD_ACTUAL FROM inventario WHERE id_producto='.$id_producto.';');
                        $rProv=$qbuscarId->execute();
                        while($dbuscarId=$qbuscarId->fetch()){
                            $nuevaCantidad=$cantidad+$dbuscarId['CANTIDAD_ACTUAL'];
                            $updCantidad=$con->prepare('UPDATE inventario SET CANTIDAD_ACTUAL='.$nuevaCantidad.' WHERE ID_PRODUCTO='.$id_producto.';');
                            $dupdCantidad=$updCantidad->execute();
                        }
                        
                        $insprod=$con->prepare('INSERT INTO entradas VALUES("",NOW(),'.$cantidad.','.$id_persona.','.$id_factura.','.$id_producto.');');
                        $dinsprod=$insprod->execute();

                        if($dinsprod){
                            $datos[]=array('stat'=>1,
                            'msj'=>'Producto insertado',
                            'msj2'=>'Registros correctos en tabla INVENTARIO'
                            );
                        }
                    }
                }

                


            }





    break;
    case 7:
            $id_entrada=$_POST['id_entrada'];
            $cantidad=$_POST['cantidad'];
            $id_producto=$_POST['id_producto'];

            //Verificamos si existe un registro en la tabla INVENTARIO
            /* Restamos la cantidad al inventario total */
            $qbuscarCant=$con->prepare('SELECT COUNT(ID_PRODUCTO) AS NUMCANTINV FROM inventario WHERE id_producto='.$id_producto.';');
            $rProvCant=$qbuscarCant->execute();
            while($dbuscarCant=$qbuscarCant->fetch()){
                if($dbuscarCant['NUMCANTINV']==0){
                    $datos[]=array('stat'=>0,
                                    'id_entrada'=>$id_entrada,
                                    'msj'=>'Ocurrió un error al borrar porque no existe un registro con id_producto='.$id_producto.' en la tabla INVENTARIO '
                                );
                }elseif($dbuscarCant['NUMCANTINV']==1){

                    /* Restamos la cantidad al inventario total */
                    $qbuscarId=$con->prepare('SELECT CANTIDAD_ACTUAL FROM inventario WHERE id_producto='.$id_producto.';');
                    $rProv=$qbuscarId->execute();
                        while($dbuscarId=$qbuscarId->fetch()){

                            $nuevaCantidad=$dbuscarId['CANTIDAD_ACTUAL']-$cantidad;

                            $updCantidad=$con->prepare('UPDATE inventario SET CANTIDAD_ACTUAL='.$nuevaCantidad.' WHERE ID_PRODUCTO='.$id_producto.';');

                            $dupdCantidad=$updCantidad->execute();

                            $delProdEntrada=$con->prepare('DELETE FROM entradas WHERE ID_ENTRADA='.$id_entrada.';');

                            $ddelProdEntrada=$delProdEntrada->execute();



                            if($dupdCantidad){
                                $datos[]=array('stat'=>1,
                                                'id_entrada'=>$id_entrada,
                                                'msj'=>'Registro borrado'
                                            );

                            }
                            

                        }

                }
            }



            
            
            

    break;
}

echo json_encode($datos);

?>
<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
require('conexionpdo.php');

$op=$_GET['op'];

$datos=array();

switch($op){
    

    case 1:
            //Listamos la lista de inventarios

            
            //$qlinv=$con->prepare('SELECT ID_INVENTARIO,NOMBRE_INV,FECHA_INV,COMENTARIOS_INV FROM inventarios_rapidos ORDER BY ID_INVENTARIO DESC;');           
            $qlinv=$con->prepare('SELECT ID_INVENTARIO,NOMBRE_INV,FECHA_INV,COMENTARIOS_INV,ID_PERSONA FROM inventarios_rapidos ORDER BY ID_INVENTARIO DESC;');
            $rlinv=$qlinv->execute();

            

            //$err=$qlinv->errorInfo();

            //if($rlinv) {
                while($dlinv=$qlinv->fetch()){
                    $datos[]=array('id_inventario'=>$dlinv['ID_INVENTARIO'],'nombre_inv'=>$dlinv['NOMBRE_INV'],'fecha_inv'=>$dlinv['FECHA_INV']);
                }
                
            //}else{
            //    $datos[]=array('Error en php'=>$err);
           // }
    break;

    case 2:
        if(isset($_POST['nombre_inv']))       {$nombre_inv      = $_POST['nombre_inv'];}
        if(isset($_POST['fecha_inv']))        {$fecha_inv       = $_POST['fecha_inv'];}
        //if(isset($_POST['comentarios_inv']))  {$comentarios_inv = $_POST['comentarios_inv'];}
        if(isset($_POST['comentarios_inv']))  {$comentarios_inv = '';}else{$comentarios_inv = '';}
        if(isset($_POST['p']))               { $id_persona     = $_POST['p']; }else{$id_persona='No hay dato';}

        //$id_persona=$_POST['p'];

        /* actualizzamos la cantidad insertada mas la cantidad del inventario */

        /* Se inserta el producto en la tabla de inventario ra´pido */
        $qinsir=$con->prepare('CALL INSERTAR_INVENTARIO_RAPIDO("'.$nombre_inv.'","'.$fecha_inv.'","'.$comentarios_inv.'",'.$id_persona.');');
        $rinsir=$qinsir->execute();
        if($rinsir){
            $datos[]=array('msj'=>'Se creó una nueva lista de inventario','stat'=>1);
        }else{
            $datos[]=array('msj'=>'Ocurrió un error al crear la lista del inventario','stat'=>0);
        }

        $datos[]=array('Dato de sesion'=>$id_persona);
        
    break;

    case 3:
            if(isset($_POST['idInv'])){ $inventario=$_POST['idInv']; }

            //$qlpr=$con->prepare('CALL PRODUCTOS_RAPIDOS('.$inventario.');');
            //$qlpr=$con->prepare('SELECT
            //A.ID_PIR,
            //A.CANTIDAD,
            //A.ID_PRODUCTO,
            //B.SKU,
            //C.TIPO_LENTE,
            //D.MATERIAL,
            //E.GRADUACION AS GRAD_ESFERA,
            //G.GRADUACION AS GRAD_CILINDRO,
            //F.TIPO_REFRACCION
            //FROM productos_inv_rap A
            //INNER JOIN producto B
            //ON A.ID_PRODUCTO=B.ID_PRODUCTO
            //LEFT OUTER JOIN cat_tipo_lente C
            //ON B.ID_TIPO_LENTE=C.ID_TIPO_LENTE
            //LEFT OUTER JOIN cat_material D
            //ON B.ID_MATERIAL=D.ID_MATERIAL
            //
            //LEFT OUTER JOIN cat_graduacion E
            //ON B.ID_GRADUACION_ESFERA=E.ID_GRADUACION
            //LEFT OUTER JOIN cat_graduacion G
            //ON B.ID_GRADUACION_CILINDRO=G.ID_GRADUACION
            //
            //LEFT OUTER JOIN cat_tipo_refraccion F
            //ON B.ID_TIPO_REFRACCION=F.ID_TIPO_REFRACCION
            //WHERE A.ID_INVENTARIO='.$inventario.'
            //ORDER BY A.ID_PIR DESC;');

            $qlpr=$con->prepare('SELECT 
            A.ID_PRODUCTO,
            A.SKU,
            B.PROVEEDOR,
            A.DESCRIPCION,
            C.MATERIAL,
            D.INDICE_REFRACCION,
            E.ESFERICIDAD,
            F.MARCA,
            G.TRATAMIENTO,
            H.FILTRO_UV,
            I.DIAMETRO,
            J.TIPO_LENTE,
            K.GRADUACION AS GRADUACION_ESFERA,
            L.GRADUACION AS GRADUACION_CILINDRO,
            M.TIPO_REFRACCION,
            N.CANTIDAD,
            N.ID_PIR
            FROM producto A
            LEFT OUTER JOIN cat_proveedor_lab B
            ON A.ID_PROVEEDOR_LAB=B.ID_PROVEEDOR_LAB
            LEFT OUTER JOIN cat_material C
            ON A.ID_MATERIAL=C.ID_MATERIAL
            LEFT OUTER JOIN cat_indice_refraccion_n D
            ON A.ID_INDICE_REF=D.ID_INDICE_REF
            LEFT OUTER JOIN cat_esfericidad E
            ON A.ID_ESFERICIDAD=E.ID_ESFERICIDAD
            LEFT OUTER JOIN cat_marca F
            ON A.ID_MARCA=F.ID_MARCA
            LEFT OUTER JOIN cat_tratamiento_ar G
            ON A.ID_TRATAMIENTO=G.ID_TRATAMIENTO
            LEFT OUTER JOIN cat_filtro_uv H
            ON A.ID_FILTRO=H.ID_FILTRO_UV
            LEFT OUTER JOIN cat_diametro I
            ON A.ID_DIAMETRO=I.ID_DIAMETRO
            LEFT OUTER JOIN cat_tipo_lente J
            ON A.ID_TIPO_LENTE=J.ID_TIPO_LENTE
            LEFT OUTER JOIN cat_graduacion K
            ON A.ID_GRADUACION_ESFERA=K.ID_GRADUACION
            LEFT OUTER JOIN cat_graduacion L  
            ON A.ID_GRADUACION_CILINDRO=L.ID_GRADUACION
            LEFT OUTER JOIN cat_tipo_refraccion M
            ON A.ID_TIPO_REFRACCION=M.ID_TIPO_REFRACCION
            LEFT OUTER JOIN productos_inv_rap N
            ON A.ID_PRODUCTO=N.ID_PRODUCTO
            WHERE N.ID_INVENTARIO=2
            ORDER BY 1');


            
            



            $rlpr=$qlpr->execute();

            while($dlpr=$qlpr->fetch()){
                $datos[]=array('id_pir'=>$dlpr['ID_PIR'],
                               'ID_PRODUCTO'=>$dlpr['ID_PRODUCTO'],
                               'SKU'=>$dlpr['SKU'],
                               'PROVEEDOR'=>$dlpr['PROVEEDOR'],
                               'DESCRIPCION'=>$dlpr['DESCRIPCION'],
                               'MATERIAL'=>$dlpr['MATERIAL'],
                               'INDICE_REFRACCION'=>$dlpr['INDICE_REFRACCION'],
                               'ESFERICIDAD'=>$dlpr['ESFERICIDAD'],
                               'MARCA'=>$dlpr['MARCA'],
                               'TRATAMIENTO'=>$dlpr['TRATAMIENTO'],
                               'FILTRO_UV'=>$dlpr['FILTRO_UV'],
                               'DIAMETRO'=>$dlpr['DIAMETRO'],
                               'TIPO_LENTE'=>$dlpr['TIPO_LENTE'],
                               'GRADUACION_ESFERA'=>$dlpr['GRADUACION_ESFERA'],
                               'GRADUACION_CILINDRO'=>$dlpr['GRADUACION_CILINDRO'],
                               'TIPO_REFRACCION'=>$dlpr['TIPO_REFRACCION'],
                               'CANTIDAD'=>$dlpr['CANTIDAD'],
                               );

                               //cREAR EL NOMBRE DEL PRODUCTO CONCATENANDO LOS REGISTROS
            }

    break;
    
    case 4:
            if(isset($_POST['cod'])){ $codigo=trim($_POST['cod']);  }
            if(isset($_POST['id_inventario'])){ $id_inventario=intval(trim($_POST['id_inventario']));  }
            /* Revisamos si existe el código en la tabla productos */
            $qbc=$con->prepare('CALL BUSCAR_SKU("'.$codigo.'");');
            $qbc->execute();
            
            
            while($dbc=$qbc->fetch()){
                $regs=intval($dbc['NUMCOD']);
                $id_producto=intval($dbc['ID_PRODUCTO']);
            }
            
            //
            $qbc->closeCursor(); // opcional en MySQL, dependiendo del controlador de base de datos puede ser obligatorio
            //$qbc = null; // obligado para cerrar la conexión
            //$con = null;

             

                if($regs==0){
                    $datos[]=array('msj'=>'No se encontró ningún producto con SKU/código '.$codigo,'stat'=>2);
                }elseif($regs==1){
                    /* Buscamos si existe un registro en la tabla inventario */
                    $qbti=$con->prepare('SELECT COUNT(ID_PRODUCTO) AS NUMIPINV FROM inventario WHERE ID_PRODUCTO='.$id_producto.'; ');
                    $rbti=$qbti->execute();
                    while($dbti=$qbti->fetch()){
                        $cant_numpinv=$dbti['NUMIPINV'];
                    }
                    $qbti->closeCursor(); // opcional en MySQL, dependiendo del controlador de base de datos puede ser obligatorio

                    if($cant_numpinv==1){}else{
                        /* Insertamos un nuevo registro en la tabla inventario porque no se ha encontrado ninguno */
                        $qini=$con->prepare('INSERT INTO inventario (ID_PRODUCTO,CANTIDAD_ACTUAL,ID_RACK,ID_CAJA)
                        VALUES
                        ('.$id_producto.',0,NULL,NULL);');
                        $rini=$qini->execute();
                        $qini->closeCursor(); // opcional en MySQL, dependiendo del controlador de base de datos puede ser obligatorio
                    }

                     /* Consultamos la cantidad actual del producto en la tabla inventario  */
                    $qbca=$con->prepare('CALL CANT_ACTUAL_INVENTARIO('.$id_producto.');');
                    $rbca=$qbca->execute();
                    while($dbca=$qbca->fetch()){
                        $cantidad_actual=$dbca['CANTIDAD_ACTUAL'];
                    }
                    $qbca->closeCursor(); // opcional en MySQL, dependiendo del controlador de base de datos puede ser obligatorio
                    //$qbc = null; // obligado para cerrar la conexión
                    //$con = null;
                    /* Aumentamos la cantidad actual del producto en 1  */
                    $cant_nueva=$cantidad_actual+1;
                    $quc=$con->prepare('CALL AUMENTAR_CANTIDAD1('.$id_producto.','.$cant_nueva.');');
                    $ruc=$quc->execute();
            

                    $quc->closeCursor(); // opcional en MySQL, dependiendo del controlador de base de datos puede ser obligatorio
                    //$qbc = null; // obligado para cerrar la conexión
                    //$con = null;

                    // Hacemos la inserción del producto y con cantidad en 1 
                    $qip=$con->prepare('CALL INSERTA_PRODUCTO_RAPIDO('.$id_inventario.','.$id_producto.',1);');
                    $drip=$qip->execute();
                    //echo "\nPDOStatement::errorInfo():\n";
                    $arr = $qip->errorInfo();
                    //print_r($arr);
                    $qip->closeCursor(); // opcional en MySQL, dependiendo del controlador de base de datos puede ser obligatorio
                    if($drip){
                        $datos[]=array('msj'=>'Producto registrado.','stat'=>1,'Cantidad en inventario'=>$cantidad_actual,'Nueva cantidad'=>$cant_nueva);
                    }else{
                        $datos[]=array('msj'=>'Hubo un error al insertar el productoError: ','stat'=>0, 'inventario'=>$id_inventario,'producto'=>$id_producto,'skus encontrados'=>$regs,'valor DRIP:'=>$drip,'Error:'=>$arr);
                        }

                }

            

    break;

    case 5:
            $cantActual=$_POST['cantActual'];
            $id_prodir=$_POST['id_prodir'];
            $cantTotal=0;

        /* Consultamos la cantidad actual en el inventario rapido */

            $qcair=$con->prepare('SELECT ID_PRODUCTO,CANTIDAD FROM productos_inv_rap WHERE ID_PIR='.$id_prodir.';');
            $rcair=$qcair->execute();

            while($dcair=$qcair->fetch()){
                $id_prod=$dcair['ID_PRODUCTO'];
                $cantpir=$dcair['CANTIDAD'];
            }

            /* Consultamos la cantidad actual del inventario total */
            $qct=$con->prepare('SELECT CANTIDAD_ACTUAL FROM inventario WHERE ID_PRODUCTO='.$id_prod.';');
            $rct=$qct->execute();

            while($dct=$qct->fetch()){
                $cantTotal=$dct['CANTIDAD_ACTUAL'];
            }

            /* Restamos al inventario total la cantidad actual de inventario rapido */

            $quct=$con->prepare('UPDATE inventario SET CANTIDAD_ACTUAL='.($cantTotal-$cantpir).' WHERE ID_PRODUCTO='.$id_prod.';');
            $ruct=$quct->execute();

            /* Modificamos el registro de cantidad del inventario rapido */
            $qupir=$con->prepare('UPDATE productos_inv_rap SET CANTIDAD='.$cantActual.' WHERE ID_PIR='.$id_prodir.';');
            $rupir=$qupir->execute();

            /* Actualizamos la nueva cantidad del inventario total */
            $quit=$con->prepare('UPDATE inventario SET CANTIDAD_ACTUAL='.(($cantTotal-$cantpir)+$cantActual).' WHERE ID_PRODUCTO='.$id_prod.';');
            $ruit=$quit->execute();

            $datos[]=array('id_pir'=>$id_prodir,'cant'=>$cantActual);

    break;

    case 6:
            $id_pirdel=$_POST['id_pirdel'];
            $cantpir=0;
            $id_producto=0;
            $cantinv=0;

            $qnp=$con->prepare('SELECT CANTIDAD,ID_PRODUCTO FROM productos_inv_rap WHERE ID_PIR='.$id_pirdel.';');
            $rnp=$qnp->execute();
            while($dnp=$qnp->fetch()){
                $cantpir=$dnp['CANTIDAD'];
                $id_producto=$dnp['ID_PRODUCTO'];
            }

            $qni=$con->prepare('SELECT CANTIDAD_ACTUAL FROM inventario WHERE ID_PRODUCTO='.$id_producto.';');
            $rni=$qni->execute();
            while($dni=$qni->fetch()){
                $cantinv=$dni['CANTIDAD_ACTUAL'];
            }
            $nuevaCant=$cantinv-$cantpir;
            $qupd=$con->prepare('UPDATE inventario SET CANTIDAD_ACTUAL='.$nuevaCant.' WHERE ID_PRODUCTO='.$id_producto.' ;');
            $rupd=$qupd->execute();

            $dpir=$con->prepare('DELETE FROM productos_inv_rap WHERE ID_PIR='.$id_pirdel.';');
            $rpir=$dpir->execute();

            
            $datos[]=array('id_pirdel'=>$id_pirdel);


    break;
    case 7:
        //Listamos la lista de inventarios

        
        //$qlinv=$con->prepare('SELECT ID_INVENTARIO,NOMBRE_INV,FECHA_INV,COMENTARIOS_INV FROM inventarios_rapidos ORDER BY ID_INVENTARIO DESC;');           
        $qlinv=$con->prepare('SELECT ID_INVENTARIO,NOMBRE_INV,FECHA_INV,COMENTARIOS_INV,ID_PERSONA FROM inventarios_rapidos ORDER BY ID_INVENTARIO DESC;');
        $rlinv=$qlinv->execute();

        

        //$err=$qlinv->errorInfo();

        //if($rlinv) {
            while($dlinv=$qlinv->fetch()){
                $datos[]=array('id_inventario'=>$dlinv['ID_INVENTARIO'],'nombre_inv'=>$dlinv['NOMBRE_INV'],'fecha_inv'=>$dlinv['FECHA_INV']);
            }
            
        //}else{
        //    $datos[]=array('Error en php'=>$err);
       // }
    break;
    case 8:
            /*Consultamos la informacion del inventario rápido */
            $id_inventario=$_POST['id_inventario'];
            $inf=$con->prepare('SELECT NOMBRE_INV,FECHA_INV,ID_ESTATUS FROM inventarios_rapidos WHERE ID_INVENTARIO='.$id_inventario.';');
            $inf->execute();
            while($dinf=$inf->fetch()){
                $datos[]=array('nom_inv'=>$dinf['NOMBRE_INV'],'fecha_inv'=>$dinf['FECHA_INV'],'estatus'=>$dinf['ID_ESTATUS']);
            }

    break;
    case 9:
        /* Actualizamos el estatus de la facturapara que ya no se puedan cargar datos */
        $iic=$_POST['iic'];
        $updStat=$con->prepare('UPDATE inventarios_rapidos SET ID_ESTATUS=1 WHERE ID_INVENTARIO='.$iic.';');
        $dupdStat=$updStat->execute();
        if($dupdStat){
            $datos[]=array('msj'=>'Se ha cerrado el inventario y ya no se pueden cargar mas datos','id_factura'=>$iic);
        }else{
            $datos[]=array('msj'=>'Ocurrió un error al cerrar el inventario');
        }

    break;


    dafault:
        $datos[]=array('msj'=>'Opcion no encontrada');
    break;

}

echo json_encode($datos);

?>
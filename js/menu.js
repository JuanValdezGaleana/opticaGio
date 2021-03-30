$(function(){

    const menuIzq=$('#menuIzq li');
    const contenedor=$('#contenedor');
    const titulo=$('#titulo');
    const espCarga=$('#espCarga');

    menuIzq.on('click',function(){
        menuIzq.removeClass('active');
        event.preventDefault();
        $(this).addClass('active');
        var opMenu=$(this).attr('id');

        espCarga.html('<div id="animacionCargando"><div class="spinner-border text-primary" role="status">'+
        '<span class="visually-hidden">Cargando datos...</span>'+
        
        '</div> <span class="text-primary h1">Cargando datos...</span></div>');

        switch(opMenu){
            case 'li1':
                titulo.text('Inicio');
                contenedor.empty();
                $('#animacionCargando').remove();/* Quitar animación de carga */
            break;

            case 'li2':

                titulo.text('Registrar entradas');
                contenedor.load('vistas/frmEntradas.html',function(){
                    /* Consultamos las facturas */
                    $.ajax({
                        url:'php/altafacturas.php?op=1',
                        dataType:'JSON',
                        success:function(data){

                        },
                        error:function(error){
                            console.error('ERROR: ',error);
                        }
                    }).done(function(data){
                            
                            $('#espTbFacturas').html('<table id="tbFacturas" class=" table table-sm" style="width:100%">'+
                            '<thead>'+
                                '<tr>'+
                                    '<th>Num de factura</th>'+
                                    '<th>Laboratorio</th>'+
                                    '<th>Fecha de factura</th>'+
                                    '<th>&nbsp;</th>'+
                                '</tr>'+
                            '</thead>'+
                            '<tbody>'+
                            '</tbody>'+
                            '<tfoot>'+
                                '<tr>'+
                                    '<th>Num de factura</th>'+
                                    '<th>Laboratorio</th>'+
                                    '<th>Fecha de factura</th>'+
                                    '<th>&nbsp;</th>'+
                                '</tr>'+
                            '</tfoot>'+
                        '</table>');

                            $.each(data,function(ind,val){
                                $('#tbFacturas tbody').append('<tr>'+
                                '<td>'+val.num_factura+'</td>'+
                                '<td>'+val.proveedor+'</td>'+
                                '<td>'+val.fecha+'</td>'+
                                '<td><a href="" onclick="agregarProducto('+val.id_factura+','+val.id_proveedor_lab+')">Seleccionar</a></td>'+
                            '</tr>');
                            });

                            $('#tbFacturas').DataTable({
                                "language": {
                                    "url": "DataTables/json/idioma_ES.json"
                                  }
                             });

                        }
                    ).fail(function(error){
                            console.error('FAIL: ',error);
                        }
                    );

                     

                     /* Regresar a la lista de facturas */
                     $('#backFacturas').on('click',function(){
                         event.preventDefault();
                        $('#contenedorAgregarProds').hide('fast');
                        $('#contenedorListaFacturas').show('fast');
                        $('#btnaddProd').attr('disabled','disabled');

                        $('#id_factura').val('');
                        $('#id_proveedor_lab_add').val('');
                        $('#lbProveedor').text('');
                        $('#lbFactura').text('');
                        
                     });


 /* --------------------------------------------------------------------------- */
                     /* SUBMIT frmAltaFactura */
                     $('#frmAltaFactura').on('submit',function(){
                        event.preventDefault();
                           $.ajax({
                               url:'php/altafacturas.php?op=2',
                               data:$(this).serialize(),
                               dataType:'JSON',
                               type:'POST',
                               success:function(data){

                               },
                               error:function(error){
                                   console.error('ERROR: ',error);
                               }
                           }).done(
                               function(data){
                                   $.each(data,function(ind,val){
                                       switch(val.stat){
                                           case 0:
                                               var colorAlert="alert-danger";
                                           break;
                                           case 1:
                                               var colorAlert="alert-success";
                                               $('#num_factura').val('');
                                               $('#fecha_factura').val('');
                                               $('#id_proveedor_lab').val('');

                                                /* Consultamos las facturas */
                                                $.ajax({
                                                    url:'php/altafacturas.php?op=1',
                                                    dataType:'JSON',
                                                    success:function(data){

                                                    },
                                                    error:function(error){
                                                        console.error('ERROR: ',error);
                                                    }
                                                }).done(function(data){
                                                        
                                                        $('#espTbFacturas').html('<table id="tbFacturas" class=" table table-sm" style="width:100%">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th>Num de factura</th>'+
                                                                '<th>Laboratorio</th>'+
                                                                '<th>Fecha de factura</th>'+
                                                                '<th>&nbsp;</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                        '<tfoot>'+
                                                            '<tr>'+
                                                                '<th>Num de factura</th>'+
                                                                '<th>Laboratorio</th>'+
                                                                '<th>Fecha de factura</th>'+
                                                                '<th>&nbsp;</th>'+
                                                            '</tr>'+
                                                        '</tfoot>'+
                                                    '</table>');

                                                        $.each(data,function(ind,val){
                                                            $('#tbFacturas tbody').append('<tr>'+
                                                            '<td>'+val.num_factura+'</td>'+
                                                            '<td>'+val.proveedor+'</td>'+
                                                            '<td>'+val.fecha+'</td>'+
                                                            '<td><a href="" onclick="agregarProducto('+val.id_factura+','+val.id_proveedor_lab+')">Seleccionar</a></td>'+
                                                        '</tr>');
                                                        });

                                                        $('#tbFacturas').DataTable({
                                                            "language": {
                                                                "url": "DataTables/json/idioma_ES.json"
                                                            }
                                                        });

                                                    }
                                                ).fail(function(error){
                                                        console.error('FAIL: ',error);
                                                    }
                                                );


                                           break;
                                       }

                                       $('#msjAltaFact').html('<div class="alert '+colorAlert+' col-12 text-center mt-3">'+val.msj+'</div>');
                                       setTimeout(
                                        function(){ 
                                            $('#msjAltaFact').empty();
                                        }, 5000); 
                                   });
                               }
                           ).fail(
                               function(error){
                                   console.error('FAIL: ',error);
                               }
                           );
                       });
                     /* Cargar la lista de proveedores para dar de alta una nueva factura */
                       $.ajax({
                           url:'php/altafacturas.php?op=3',
                           dataType:'JSON',
                           success:function(data){

                           },
                           error:function(error){
                               console.error('ERROR: ',error);
                           }
                       }).done(
                           function(data){
                               $.each(data,function(ind,val){
                                   $('#id_proveedor_lab').append('<option value="'+val.id_proveedor_lab+'">'+val.proveedor_lab+'</option>');
                               });
                           }
                       ).fail(
                           function(error){
                               console.error('FAIL: ',error);
                           }
                       );

                       /* ---------------------------------------------------------------------------------- */


                        /* ------------------------------------------------------------- */

                           /* Insertamos el producto mediante el sku */
                        $('#frmEntradas').on('submit',function(){
                            event.preventDefault();
                            $('#btnaddProd').attr('disabled','disabled');
                            $('#msjinsprod').removeClass('alert alert-danger').empty();

                            var sku=$('#sku').val();
                            var cantidad=$('#cantidad').val();
                            var id_factura=$('#id_factura').val();
                            var id_proveedor_lab_add=$('#id_proveedor_lab_add').val();
                            var id_persona=$('#iper').val();
                            $.ajax({
                                url:'php/altafacturas.php?op=6',
                                data:'sku='+sku+'&id_factura='+id_factura+'&cantidad='+cantidad+'&id_proveedor_lab='+id_proveedor_lab_add+'&id_persona='+id_persona,
                                dataType:'JSON',
                                type:'POST',
                                success:function(data){
                        
                                },
                                error:function(error){
                                    console.error('ERROR: ',error);
                                }
                            }).done(
                                function(data){
                                    console.log('producto ingresado con SKU:',data);

                                    $.each(data,function(ind,val){
                                    console.log('ESTATUS=',val.stat);
                                    if(val.stat==0){
                                        $('#msjinsprod').addClass('alert alert-danger').text(val.msj);

                                    }else if(val.stat==1){
                                        $('#msjinsprod').addClass('alert alert-success').text(val.msj);

                                        cargarListaProds(id_factura);

                                    }

                                    });
                                    $('#sku').val('');
                                    $('#cantidad').val('');
                                    $('#sku').attr('autofocus');
                                    $('#btnaddProd').removeAttr('disabled');

                                }
                                
                            ).fail(
                                function(error){
                                    console.error('FAIL: ',error);
                                }
                            );
                        });

                        /* -------------------------------------------------------------- */



                     $('#animacionCargando').remove();/* Quitar animación de carga */
                     
                     });

                    
                    

            break;

            case 'li3':
                $('#animacionCargando').remove();/* Quitar animación de carga */
                InventarioRapido(contenedor);
            break;

            case 'logout':
                $('#animacionCargando').remove();/* Quitar animación de carga */
                window.location="./";
            break;

            default:
                contenedor.empty();
                $('#animacionCargando').remove();/* Quitar animación de carga */
            break;

        }

    });


});



function agregarProducto(id_factura,id_proveedor_lab){

    event.preventDefault();
    
    $('#contenedorListaFacturas').hide('fast');
    $('#contenedorAgregarProds').show('fast');
    $('#tbProdsFact tbody').empty();
    $('#msjinsprod').removeClass('alert alert-danger').empty();
    $('#sku').val('');
    $('#cantidad').val('');
    $('#id_factura').val('');
    $('#id_proveedor_lab_add').val('');
    $('#btnaddProd').removeAttr('disabled');

    /* Consultamos el laboratorio proveedor y el numero de factura */
    $.ajax({
        url:'php/altafacturas.php?op=4',
        data:'id_factura='+id_factura,
        dataType:'JSON',
        type:'POST',
        success:function(data){

        },
        error:function(error){
            console.error('ERROR: ',error);
        }
    }).done(
        function(data){
            $.each(data,function(ind,val){
                $('#lbProveedor').text(val.proveedor_lab);
                $('#lbFactura').text(val.num_factura);
                $('#id_factura').val(val.id_factura);
                $('#id_proveedor_lab_add').val(val.id_proveedor_lab_add);
            });
        }
    ).fail(
        function(error){
            console.error('FAIL: ',error);
        }
    );

    cargarListaProds(id_factura);

    





}


function cargarListaProds(id_factura){
    /* Consultamos la lista de productos agregados a la factura */
    //console.log('FACTURA: ',id_factura);
    $('#tbProdsFact tbody').empty();
    $.ajax({
        url:'php/altafacturas.php?op=5',
        data:'id_factura='+id_factura,
        dataType:'JSON',
        type:'POST',
        success:function(data){

        },
        error:function(error){
            console.error('ERROR: ',error);
        }
    }).done(
        function(data){
            //console.log('Lista prods:',data);
            $.each(data,function(ind,val){
               $('#tbProdsFact tbody').append('<tr id="trIngreso'+val.id_entrada+'">'+
               '<td><a id="iconTrash'+val.id_entrada+'" onclick="confirmarBorrarEntrada('+val.id_entrada+')" class="fa fa-trash text-danger"></a><div id="msjConfirmacion'+val.id_entrada+'" class="col-12" style="display:none;"><span class="text-info">¿Seguro que quieres borrar este registro?</span><a onclick="confirmarBorrado('+val.id_entrada+','+val.cantidad+','+val.id_producto+')" class="text-success">SI</a> <a id="cancelarConfirmacion'+val.id_entrada+'" onclick="cancelarBorrado('+val.id_entrada+')" class="text-danger">NO</a></div></td>'+
               '<td>'+(ind+1)+'</td>'+
               '<td>'+val.sku+'</td>'+
               '<td>'+val.tipo_lente+'</td>'+
               '<td>'+val.material+'</td>'+
               '<td>'+val.graduacion_esfera+'</td>'+
               '<td>'+val.graduacion_cilindro+'</td>'+
               '<td>'+val.tipo_refraccion+'</td>'+
               '<td>'+val.cantidad+'</td>'+
             '</tr>');
            });
        }
    ).fail(
        function(error){
            console.error('FAIL: ',error);
        }
    );
}

function confirmarBorrarEntrada(id_entrada){
    $('#iconTrash'+id_entrada).hide('fast');
    $('#msjConfirmacion'+id_entrada).show('fast');
}

function cancelarBorrado(id_entrada){
    $('#msjConfirmacion'+id_entrada).hide('fast');
    $('#iconTrash'+id_entrada).show('fast'); 
}

function confirmarBorrado(id_entrada,cantidad,id_producto){
    /* Confirmamos el borrado del producto a la luista de la factura */
    $.ajax({
        url:'php/altafacturas.php?op=7',
        data:'id_entrada='+id_entrada+'&cantidad='+cantidad+'&id_producto='+id_producto,
        dataType:'JSON',
        type:'POST',
        success:function(data){

        },
        error:function(error){
            console.error('ERROR: ',error);
        }
    }).done(
        function(data){
            //console.log('Mensaje al borrar el registro: ',data);
            $.each(data,function(ind,val){
                if(val.stat==0){
                    console.error('Ocurrió un error:',data);
                }else if(val.stat==1){
                    $('#trIngreso'+val.id_entrada).remove();
                }
               
            });
        }
    ).fail(
        function(error){
            console.error('FAIL: ',error);
        }
    );
}



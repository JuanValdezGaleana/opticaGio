function InventarioRapido(contenedor){

    $(function(){
            contenedor.load('./vistas/inventarioRapido.html',function(){
             
                const espTbInventarios=$('#espTbInventarios');
                const frmAltaInventarioRapido=$('#frmAltaInventarioRapido');
            
                cargarListaInventarios(espTbInventarios);
                
                frmAltaInventarioRapido.on('submit',function(){
                    event.preventDefault();
                    var iper=$('#iper').val();
                    $.ajax({
                        url:'php/inventarios_rapidos.php?op=2',
                        data:$(this).serialize()+'&p='+iper,
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
                                if(val.stat==1){
                                    $('#msjAltaInventario').html('<div class="alert alert-success text-center col-12">'+val.msj+'</div>');
                                    
                                    setTimeout(
                                        function(){ 
                                            $('#msjAltaInventario').empty();
                                        }, 5000); 
                                }else if(val.stat==0){
                                    $('#msjAltaInventario').html('<div class="alert alert-danger text-center col-12">'+val.msj+'</div>');
                                    /*setTimeout(
                                        function(){ 
                                            $('#msjAltaInventario').empty();
                                    }, 10000); */
                                }
                            });

                            $('#nombre_inv').val('');
                            $('#fecha_inv').val('');
                            //$('#comentarios_inv').val('');
                            //cargarListaInventarios(espTbInventarios);
                            
                            $('#espTbInventarios').empty();
 $('#espTbInventarios').html('<table id="tbInventarios" class="hover table table-sm" style="width:100%">'+
            '<thead>'+
                '<tr>'+
                    '<th>&nbsp;</th>'+
                    '<th>Nombre de inventario</th>'+
                    '<th>Fecha de inventario</th>'+
                    //'<th>Comentarios</th>'+
                    '<th>&nbsp;</th>'+
                    
                '</tr>'+
            '</thead>'+
            '<tbody>'+
            '</tbody>'+
            '<tfoot>'+
                '<tr>'+
                    '<th>&nbsp;</th>'+
                    '<th>Nombre de inventario</th>'+
                    '<th>Fecha de inventario</th>'+
                    //'<th>Comentarios</th>'+
                    '<th>&nbsp;</th>'+
                '</tr>'+
            '</tfoot>'+
        '</table>');

        


         $.ajax({
            url:'php/inventarios_rapidos.php?op=7',
            dataType:'JSON',
            success:function(data){

            },
            error:function(error){
                console.error('ERROR: ',error);
            }
        }).done(
            function(data){
                
                    $.each(data,function(ind,val){
                        $('#tbInventarios tbody').append(''+
                            '<tr>'+
                                '<td>'+(ind+1)+'</td>'+
                                '<td>'+val.nombre_inv+'</td>'+
                                '<td>'+val.fecha_inv+'</td>'+
                                //'<td>'+val.comentarios_inv+'</div>'+
                                '<td><a href="" onclick="abrirInventarioRapido('+val.id_inventario+')">Abrir</a></div>'+
                            '</tr>'+
                            '');
                    });
                  
                $('#tbInventarios').DataTable({
                    "language": {
                        "url": "DataTables/json/idioma_ES.json"
                      }
                 });
            }
        ).fail(
            function(error){
                console.error('FAIL: ',error);
            }
        );






                        }
                    ).fail(
                        function(error){
                            console.error('FAIL: ',error);
                        }
                    );
                    

                });


            });
            


           

        
        

    });

}

function cargarListaInventarios(espTbInventarios){

 $('#espTbInventarios').empty();
 $('#espTbInventarios').html('<table id="tbInventarios" class="hover table table-sm" style="width:100%">'+
            '<thead>'+
                '<tr>'+
                    '<th>&nbsp;</th>'+
                    '<th>Nombre de inventario</th>'+
                    '<th>Fecha de inventario</th>'+
                    //'<th>Comentarios</th>'+
                    '<th>&nbsp;</th>'+
                    
                '</tr>'+
            '</thead>'+
            '<tbody>'+
            '</tbody>'+
            '<tfoot>'+
                '<tr>'+
                    '<th>&nbsp;</th>'+
                    '<th>Nombre de inventario</th>'+
                    '<th>Fecha de inventario</th>'+
                    //'<th>Comentarios</th>'+
                    '<th>&nbsp;</th>'+
                '</tr>'+
            '</tfoot>'+
        '</table>');

        


         $.ajax({
            url:'php/inventarios_rapidos.php?op=1',
            dataType:'JSON',
            success:function(data){

            },
            error:function(error){
                console.error('ERROR: ',error);
            }
        }).done(
            function(data){
                
                    $.each(data,function(ind,val){
                        $('#tbInventarios tbody').append(''+
                            '<tr>'+
                                '<td>'+(ind+1)+'</td>'+
                                '<td>'+val.nombre_inv+'</td>'+
                                '<td>'+val.fecha_inv+'</td>'+
                                //'<td>'+val.comentarios_inv+'</div>'+
                                '<td><a href="" onclick="abrirInventarioRapido('+val.id_inventario+')">Abrir</a></div>'+
                            '</tr>'+
                            '');
                    });
                  
                $('#tbInventarios').DataTable({
                    "language": {
                        "url": "DataTables/json/idioma_ES.json"
                      }
                 });
            }
        ).fail(
            function(error){
                console.error('FAIL: ',error);
            }
        );


}

function infoInventarioRap(id_inventario){
    $.ajax({
        url:'php/inventarios_rapidos.php?op=8',
        data:'id_inventario='+id_inventario,
        dataType:'JSON',
        type:'POST',
        success:function(data){

        },
        error:function(error){
            console.error('ERROR: ',error);
        }
    }).done(
        function(data){
            //console.log('Info: ',data);
            $.each(data,function(ind,val){
                $('#lbInventario').text(val.nom_inv);
                $('#lbFechaInv').text(val.fecha_inv);
                if(val.estatus==0){ //Si está abierta la factura
                    $('#espBtnCerrarInv').html('<div class="btn btn-primary" onclick="btnCerrarInv('+id_inventario+')"><span class="fa fa-lock"></span> Cerrar factura para que ya no se puedan agregar productos</div>');
                }else if(val.estatus==1){ //Si está cerrada la factura
                    $('#espBtnCerrarInv').html('<div class="alert alert-success text-center"><span class="fa fa-check-circle"></span> Esta factura se encuentra cerrada</div>');
                    $('#frmEntradas').remove();
                    
                }
            });
            
        }
    ).fail(
        function(error){
            console.error('FAIL: ',error);
        }
    );






    $('#frmConfirmCerrarInv').on('submit',function(){
        event.preventDefault();
        
        $.ajax({
            url:'php/inventarios_rapidos.php?op=9',
            data:$(this).serialize(),
            dataType:'JSON',
            type:'POST',
            success:function(data){},
            error:function(error){
                console.error('ERROR: ',error);
            }
        }).done(
            function(data){
                //('RES INV: ',data);
                $.each(data,function(ind,val){
                    $('#espBtnCerrarInv').show('fast');
                    $('#espBtnsInv').hide('fast');
                    $('#ifc').val(val.id_factura);
                });
                $.ajax({
                    url:'php/inventarios_rapidos.php?op=8',
                    data:'id_inventario='+id_inventario,
                    dataType:'JSON',
                    type:'POST',
                    success:function(data){
            
                    },
                    error:function(error){
                        console.error('ERROR: ',error);
                    }
                }).done(
                    function(data){
                        //console.log('Info: ',data);
                        $.each(data,function(ind,val){
                            $('#lbInventario').text(val.nom_inv);
                            $('#lbFechaInv').text(val.fecha_inv);
                            if(val.estatus==0){ //Si está abierta la factura
                                $('#espBtnCerrarInv').html('<div class="btn btn-primary" onclick="btnCerrarInv('+id_inventario+')"><span class="fa fa-lock"></span> Cerrar factura para que ya no se puedan agregar productos</div>');
                            }else if(val.estatus==1){ //Si está cerrada la factura
                                $('#espBtnCerrarInv').html('<div class="alert alert-success text-center"><span class="fa fa-check-circle"></span> Esta factura se encuentra cerrada</div>');
                                $('#frmEntradas').remove();
                                
                            }
                        });
                        
                    }
                ).fail(
                    function(error){
                        console.error('FAIL: ',error);
                    }
                );

            }
        ).fail(
            function(error){
                console.error('FAIL: ',error);
            }
        );
    });











}

function btnCerrarInv(id_inventario){
    $('#espBtnCerrarInv').hide('fast');
    $('#espBtnsInv').show('fast');
    $('#iic').val(id_inventario);
}

function cancelarCerrarInv(){
    $('#espBtnsInv').hide('fast');
    $('#espBtnCerrarInv').show('fast');
    $('#iic').val('');
}

function abrirInventarioRapido(id_inventario){

    event.preventDefault();
    const espTbProdsRap=$('#espTbProdsRap');
    cargarListaProdsRap(espTbProdsRap,id_inventario);
    infoInventarioRap(id_inventario);
    const frmAltaProd=$('#frmAltaProd');
    const cod=$('#cod');
    
  
    $('#espCodigo').html('<input type="text" class="form-control" name="cod" id="cod" value="" required autofocus="autofocus">');
    
        /* Submit al insertar un producto al inventario rápido */
    frmAltaProd.on('submit',function(){
        event.preventDefault();

        $.ajax({
            url:'php/inventarios_rapidos.php?op=4',
            data:$(this).serialize()+'&id_inventario='+id_inventario,
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
                                $('#msjAltaProd').html('<div class="col-12 text-center alert alert-danger">'+val.msj+'</div>'); 
                            break;
                            case 1: 
                                $('#msjAltaProd').html('<div class="col-12 text-center alert alert-success">'+val.msj+'</div>'); 
                                $('#cod').val('');
                                setTimeout(
                                    function(){ 
                                        $('#msjAltaProd').empty();
                                    }, 2000);
                                    cargarListaProdsRap(espTbProdsRap,id_inventario);
                                    
                                    

                                  
                            break;
                            case 2: 
                                $('#msjAltaProd').html('<div class="col-12 text-center alert alert-warning">'+val.msj+'</div>'); 
                            break;
                            default: 
                                $('#msjAltaProd').html('<div class="col-12 text-center alert alert-danger">'+val.msj+'</div>'); 
                            break;
                        }
                    });
                
            }
        ).fail(
            function(error){
                console.error('FAIL: ',error);
            }
        );

    });

    $('#espCargaInventarioRapido').hide('fast');
    $('#espListaProductos').show('fast');

}


function cargarListaProdsRap(espTbProdsRap,id_inventario){

    espTbProdsRap.empty();
    espTbProdsRap.html('<table id="tbProdsRap" class="table table-sm hover" style="width:100%">'+
               '<thead>'+
                   '<tr>'+
                       '<th>&nbsp;</th>'+
                       '<th>SKU</th>'+
                       '<th>Producto</th>'+
                       '<th>Grad. Esfera</th>'+
                       '<th>Grad. Cilindro</th>'+
                       '<th>Cantidad</th>'+
                       '<th>&nbsp;</th>'+
                       '<th>&nbsp;</th>'+
                       
                   '</tr>'+
               '</thead>'+
               '<tbody>'+
               '</tbody>'+
               '<tfoot>'+
                   '<tr>'+
                    '<th>&nbsp;</th>'+
                    '<th>SKU</th>'+
                    '<th>Producto</th>'+
                    '<th>Grad. Esfera</th>'+
                    '<th>Grad. Cilindro</th>'+
                    '<th>Cantidad</th>'+
                    '<th>&nbsp;</th>'+
                    '<th>&nbsp;</th>'+
                   '</tr>'+
               '</tfoot>'+
           '</table><div id="objLista"></div><a class="btn btn-info" onclick="exportarExcel('+id_inventario+')">Exportar a excel</a>');
   
   
            $.ajax({
               url:'php/inventarios_rapidos.php?op=3',
               data:'idInv='+id_inventario,
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
                           $('#tbProdsRap tbody').append(''+
                               '<tr id="trpir'+ind+'">'+
                                   '<td>'+(ind+1)+'</td>'+
                                   '<td>'+val.SKU+'</td>'+
                                   '<td>'+val.DESCRIPCION+'</td>'+
                                   '<td>'+val.GRADUACION_ESFERA+'</td>'+
                                   '<td>'+val.GRADUACION_CILINDRO+'</td>'+
                                   '<td id="tdcant'+val.id_pir+'">'+val.CANTIDAD+'</div>'+
                                   '<td><a href="" onclick="modif('+val.id_pir+','+ind+')">Modificar</a></div>'+
                                   '<td><a href="" onclick="borrarProd('+val.id_pir+','+ind+','+id_inventario+')">Borrar</a></div>'+
                               '</tr>'+
                               '');
                       });
                     
                   $('#tbProdsRap').DataTable({
                       "language": {
                           "url": "DataTables/json/idioma_ES.json"
                         }
                    });


                   

               }
           ).fail(
               function(error){
                   console.error('FAIL: ',error);
               }
           );
   
   
   }

   function exportarExcel(id_inventario){

            event.preventDefault();
            $.ajax({
                url:'php/inventarios_rapidos.php?op=3',
                data:'idInv='+id_inventario,
                dataType:'JSON',
                type:'POST',
                success:function(data){},
                error:function(error){
                    console.error('ERROR: ',error);
                }
            }).done(
                function(data){
                    $("#objLista").excelexportjs({
                        containerid:"objLista",
                        datatype:'json',
                        dataset: data,
                        columns: getColumns(data) 
                    });
                }
            ).fail(
                function(error){
                    console.error('FAIL: ',error);
                }
            );

   }


   function modif(id_pir,ind){
       event.preventDefault();
       $('#espModificar').remove();
       $('#espBorrar').remove();
       $('tr').css('background','');

       

        $('#trpir'+ind).css('background','#EBFFEB').after('<tr id="espModificar" class="" style="background:#EBFFEB;"><td colspan="6">'+
            '<div class="row justify-content-md-center" >'+
            '<div class="col-sm-10 col-md-6" id="espfrmmodif">'+
            '    <form class="row" id="frmUpdIr" method="POST">'+
            '        <div class="form-group">'+
            '            <label for="">Cantidad</label>'+
            '                <input type="number" class="form-control" id="cantActual" name="cantActual" required>'+
            '        </div>'+
            '        <div class="form-group col-6 text-center">'+
            '           <input type="hidden" class="form-control" id="id_prodir" name="id_prodir" value="'+id_pir+'" required>'+
            '           <button class="btn btn-primary col-6" type="submit" >Modificar</button>'+
            '        </div>'+
            '        <div class="form-group col-6 text-center">'+
            '           <button class="btn btn-warning col-6" onclick="cancelarModificar('+id_pir+')" >Cancelar</button>'+
            '        </div>'+
            '    </form>'+
            '</div>'+
            '</div>'+
        '</td></tr>');

        $('#frmUpdIr').on('submit',function(){
            event.preventDefault();

            $.ajax({
                url:'php/inventarios_rapidos.php?op=5',
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
                           $('#tdcant'+val.id_pir).text(val.cant);
                        });
                        $('#frmUpdIr').hide('fast').after('<div class="alert alert-success text-center"><span class="fa fa-check-square-o fa-2x"></span> Se ha modificado la cantidad</div>');
                        setTimeout(
                            function(){ 
                                //$('#msjAltaInventario').empty();
                                $('#espModificar').remove();
                                $('#espBorrar').remove();
                                $('tr').css('background','');
                        }, 3000); 


                }
            ).fail(
                function(error){
                    console.error('FAIL: ',error);
                }
            );

            });

   }

   function cancelarModificar(id_pir){
       event.preventDefault();
        $('#espfrmmodif').hide('fast',function(){
            $('#espModificar').remove();
            $('#espBorrar').remove();
        });
        $('tr').css('background','');
   }


   function borrarProd(id_pir,ind,id_inventario){
    event.preventDefault();
    $('#espModificar').remove();
    $('#espBorrar').remove();
    $('tr').css('background','');

     $('#trpir'+ind).css('background','#FFDBD2').after('<tr id="espBorrar" class="" style="background:#FFDBD2;"><td colspan="6">'+
         '<div class="row justify-content-md-center" >'+
         '<div class="col-sm-10 col-md-6" id="espfrnborrar">'+
         '    <form class="row" id="frmDelIr" method="POST">'+
         '<h4>¿Realmente deseas borrar este registro?</h4>'+
         '<div class="form-group col-6 text-center">'+
         '<input type="hidden" class="form-control" id="id_pirdel" name="id_pirdel" value="'+id_pir+'" required>'+
         '        <button class="btn btn-primary col-6" type="submit" >Borrar</button>'+
         '</div>'+
         '<div class="form-group col-6 text-center">'+
         '        <button class="btn btn-warning col-6" onclick="cancelarBorrar('+id_pir+')" >Cancelar</button>'+
         '</div>'+
         '    </form>'+
         '</div>'+
         '</div>'+
     '</td></tr>');


     $('#frmDelIr').on('submit',function(){
        event.preventDefault();

        $.ajax({
            url:'php/inventarios_rapidos.php?op=6',
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
                
                const espTbProdsRap=$('#espTbProdsRap');
                cargarListaProdsRap(espTbProdsRap,id_inventario);
                   
                


            }
        ).fail(
            function(error){
                console.error('FAIL: ',error);
            }
        );

        });






}

function cancelarBorrar(id_pir){
    event.preventDefault();
     $('#espfrnborrar').hide('fast',function(){
        $('#espModificar').remove();
         $('#espBorrar').remove();
     });
     $('tr').css('background','');
}

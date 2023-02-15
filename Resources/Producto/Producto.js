const myModalFisico = new bootstrap.Modal('#ModalFisico', {keyboard: false})
const myModalVirtual = new bootstrap.Modal('#ModalVirtual', {keyboard: false})
const myModalExistencia = new bootstrap.Modal('#ModalExistencia', {keyboard: false})
$(document).ready(function () {
    $('#example').DataTable();
});


$(".producto_fisico").on("click", function(){
    var idProducto = $(this).attr('id-producto');
    var idAlmancenTipo = $(this).attr('id-almacen');
    var data = {
        "idProducto" : idProducto,
        "idTipoAlmacen" : idAlmancenTipo 
        };
     var url = 'producto/getProducAlmacen'
    $.ajax({
        type: "post",
        dataType: 'json',
        data: data,
        url: url,
        success: function (response) {
            $('#tblFisico').find('tr:not(:first)').remove(); 
            $.each(response.data, function(index, value) {
                var _fila= $((document).createElement('tr'));
                $.each(value, function(i, v) {
                    var _columna= $((document).createElement('td'));
                    var text = $((document).createTextNode(v));
                    _columna.append(text);
                    _fila.append(_columna);
                });
                $('#tblFisico').append(_fila);
              });
             myModalFisico.show();
          },
          error: function (request, status, error) {
              console.log(request)
              console.log(status)
              console.log(error)
          }
      });
     
      
  })
                
  $(".producto_virtual").on("click", function(){
    var idProducto = $(this).attr('id-producto');
    var idAlmancenTipo = $(this).attr('id-almacen');
    var data = {
          "idProducto" : idProducto,
          "idTipoAlmacen" : idAlmancenTipo 
          };
    var url = 'producto/getProducAlmacen'
    $.ajax({
      type: "post",
      dataType: 'json',
      data: data,
      url: url,
      success: function (response) {
          $('#tblVirtual').find('tr:not(:first)').remove(); 
          $.each(response.data, function(index, value) {
              var _fila= $((document).createElement('tr'));
              $.each(value, function(i, v) {
                  var _columna= $((document).createElement('td'));
                  var text = $((document).createTextNode(v));
                  _columna.append(text);
                  _fila.append(_columna);
              
            });
            $('#tblVirtual').append(_fila);
          });
          myModalVirtual.show();
      },
      error: function (request, status, error) {
          console.log(request)
          console.log(status)
          console.log(error)
      }
  });
  
    
  })

  $(".addExist").on("click", function(){
    $("#frm-existencia")[0].reset();
    var idProducto = $(this).attr('id-producto');
    campo = '<input type="hidden" id="idProductoInput"' + ' name="idProducto"' + idProducto + ' value="'+idProducto+ '" />';
    $("#frm-existencia").append(campo);
    var data = {
        "idProducto" : idProducto,
        };
    var url = 'producto/getAlmacen'
    $.ajax({
        type: "post",
        dataType: 'json',
        data: data,
        url: url,
        success: function (response) {
            $.each(response.data, function(key, value) {
               
                $('#selec-almacen')
                     .append($('<option>', { value : value.id_almacen })
                     .text(value.nombre_almacen+' - '+value.tipo_almacen));
           });
           myModalExistencia.show();
        },
        error: function (request, status, error) {
            console.log(request)
            console.log(status)
            console.log(error)
        }
    });
    
  })

  $(".btn-addExistencia").on("click", function(){
    var idalmacen = $("#selec-almacen") .val();
    var cantidad = $('input[type=number]').val();
    var idProducto = $('input[type=hidden]').val();

    var data = {
        "idalmacen" : idalmacen,
        "cantidad" : cantidad,
        "idProducto":idProducto
        };

    var url = 'producto/updateExistencia'
    $.ajax({
        type: "post",
        dataType: 'json',
        data: data,
        url: url,
        success: function (response) {
          console.log(response)
           myModalExistencia.close();
        },
        error: function (request, status, error) {
            console.log(request)
            console.log(status)
            console.log(error)
        }
    });
    
    
  })

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link type="text/css" rel="stylesheet" href="Resources/bootstrap/css/bootstrap.min.css"/>
        <link type="text/css" rel="stylesheet" href="Resources/bootstrap/css/dataTables.bootstrap.min.css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    </head>
    <body>
        <div class="container" style="margin-top: 5rem;">
           <div id="table-productos">
           <div>
    <div class="center">
        <h2>Listado de productos</h3>
    </div>
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Marca</th>
                <th>Precio</th>
                <th>Descripcion</th>
                <th>Sku </th>
                <th>Color</th>
                <th>Almacen</th>
                <th>Almacen</th>
                <th>Agregar</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $result_productos = $this->data;
                foreach( $result_productos['data'] as $key => $value){
                echo "<tr>
                        <td>". $value['id_producto'] . " </td> 
                        <td>". $value['marca'] . " </td> 
                        <td>". $value['precio'] . " </td> 
                        <td>". $value['descripcion'] . " </td> 
                        <td>". $value['sku'] . " </td> 
                        <td>". $value['color'] . " </td> 
                        <td> 
                            <button type=".'"'."button".'"'.
                            "class=".'"'."btn btn-primary producto_fisico".'"'
                            ."id-producto = ".$value['id_producto'].
                            " id-almacen = 1 >Fisico</button> 
                        </td>".
                        "<td>
                             <button type=".'"'."button".'"'.
                             "class=".'"'."btn btn-primary producto_virtual".'"'.
                             "id-producto = ".$value['id_producto'].
                             " id-almacen = 2 >Virtual</button> 
                        </td>".
                        "<td> 
                            <button type=".'"'."button".'"'.
                            "class=".'"'."btn btn-success addExist".'"'.
                            "id-producto = ".$value['id_producto'].
                             
                            ">Agregar</button>
                        </td>".
                        
                    "</tr>";
                }
            ?> 
        </tbody>
    </table>
    
    <?php require_once 'mdlFisico.php';?>
    <?php require_once 'mdlVirtual.php';?>
    <?php require_once 'addExistencia.php';?>
</div>
                
           </div>
        </div>
    </body>
    <script type="text/javascript" src="Resources/jquery/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="Resources/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="Resources/jquery/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="Resources/bootstrap/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="Resources/Producto/Producto.js"></script>
</html>
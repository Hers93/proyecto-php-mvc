<?php
    
    require_once './utilerias/database/SQLModel.php';
    class ProductoModel{
        private $SQLModel;
        function __construct()
        {
            $this->SQLModel = new SQLModel();
        }

        public function getProducto(){
            $qry = ' SELECT ';
            $qry .= ' id_producto,' ;
            $qry .= ' marca,';
            $qry .= ' precio,';
            $qry .= ' descripcion,';
            $qry .= ' sku,';
            $qry .= ' color';
            $qry .=' FROM productos';
            $result_producto = $this->SQLModel->executeQuery($qry);
            return $result_producto;
        }

        public function getProductoAlmacen($idProducto, $idTipoAlmacen){
            $qry = ' SELECT';
            $qry .= ' pr.marca,';
            $qry .= ' pr.descripcion,';
            $qry .= ' ex.existencia,';
            $qry .= ' al.nombre_almacen';
            $qry .= ' FROM existencias ex';
            $qry .= ' INNER JOIN productos pr ON ex.id_producto = pr.id_producto';
            $qry .= ' INNER JOIN almacen al ON al.id_almacen = ex.id_almacen';
            $qry .= ' WHERE ex.id_producto = '. $idProducto .' and al.id_tipo_almacen = '.$idTipoAlmacen;
            $result_producto = $this->SQLModel->executeQuery($qry);
            return $result_producto;
        }

        public function getAlmacen($idProducto){
            $qry = ' SELECT';
            $qry .= ' al.id_almacen,';
            $qry .= ' al.nombre_almacen,';
            $qry .= ' at.nombre_almacen as tipo_almacen';
            $qry .= ' FROM existencias ex';
            $qry .= ' INNER JOIN productos pr ON ex.id_producto = pr.id_producto';
            $qry .= ' INNER JOIN almacen al ON al.id_almacen = ex.id_almacen';
            $qry .= ' INNER JOIN almancen_tipo at ON at.id_almacen_tipo =  al.id_tipo_almacen';
            $qry .= ' WHERE ex.id_producto = '. $idProducto;
            $qry .= ' GROUP BY al.id_almacen, al.nombre_almacen';
            
            $result_producto = $this->SQLModel->executeQuery($qry);
            return $result_producto;
        }

        public function updateExistencia($idalmacen, $cantidad, $idproducto){
                $qry = ' UPDATE existencias';
                $qry .= ' SET existencia = s.x';
                $qry .= ' FROM(';
                $qry .= ' SELECT';
                $qry .= ' id_existencia,';
                $qry .= ' id_producto,';
                $qry .= ' existencia + '.$cantidad .'as x';
                $qry .= ' FROM existencias';
                $qry .= ' WHERE id_producto = '.$idproducto.' and id_almacen = '.$idalmacen.')s';
                $qry .= ' WHERE existencias.id_existencia = s.id_existencia;';
                print_r($qry);
                die('X_x');
                $result_producto = $this->SQLModel->executeQuery($qry);
                return $result_producto;
            }
    }
            
           
           
          

<?php

require_once './src/Producto/model/ProductoModel.php';

class ProductoController extends Controller{
    
    private $ProductoModel;
    /* private $view; */
    function __construct()
    {
        parent:: __construct();
        $this->ProductoModel = new ProductoModel();
    }

    public function productoAction(){
        $result = $this->ProductoModel->getProducto();
        $this->view->data = $result;
        $this->view->render('Producto','Producto');
       /*  return $result; */
    }

    public function getProducAlmacenAction()
    {
        $idProducto = $_POST['idProducto'];
        $idTipoAlmacen = $_POST['idTipoAlmacen'];
        $resulProd = $this->ProductoModel->getProductoAlmacen($idProducto, $idTipoAlmacen);
       if( count($resulProd['data']) > 0){
            $result['status'] = 'true';
            $result['data'] = $resulProd['data'];
       }else{
        $result['status'] = 'false';
        $result['data'] = [];
       }
       header("Content-Type: application/json");
       $result= $this->json_response(200,$result['data']);
       return $result;
    }


    public function getAlmacenAction(){
        $idProducto = $_POST['idProducto'];
        $resulProd = $this->ProductoModel->getAlmacen($idProducto);
        if( count($resulProd['data']) > 0){
            $result['status'] = 'true';
            $result['data'] = $resulProd['data'];
       }else{
        $result['status'] = 'false';
        $result['data'] = [];
       }
       header("Content-Type: application/json");
       $result= $this->json_response(200,$result['data']);
       return $result;
    }

    public function updateExistenciaAction(){
        $idalmacen = $_POST['idalmacen'];
        $cantidad = $_POST['cantidad'];
        $idProducto = $_POST['idProducto'];
        $resulUpdate =$this->ProductoModel->updateExistencia($idalmacen, $cantidad,$idProducto);
        if( count($resulUpdate['data']) > 0){
            $result['status'] = 'true';
            $result['data'] = $resulUpdate['data'];
       }else{
        $result['status'] = 'false';
        $result['data'] = [];
       }
       header("Content-Type: application/json");
       $result= $this->json_response(200,$result['data']);
       return $result;
    }
       
    function json_response($code = 200, $args)
    {
        header_remove();
        http_response_code($code);
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
            );
        header('Status: '.$status[$code]);
        print_r(json_encode(array(
            'status' => $code < 300, // success or not?
            'data' => $args
        )));
     
    }
}
       
         
       

   
    
   
   
   

    
 

       


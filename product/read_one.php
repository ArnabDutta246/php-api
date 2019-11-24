<?php 
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');



//include database connention and table call
include_once '../config/database.php';
include_once '../objects/product.php';

//call and connect database.php object
$database = new Database();
$db = $database->getConnection();

//call and connect product.php object
$product = new Product($db);
//as in product.php we use public $id
$product->id = isset($_GET['id'])?$_GET['id']:die(); 

//read the details of product to be edited
$product->readOne();

if($product->name!=null){
  $product_item=array(
    "id" =>  $product->id,
    "name" => $product->name,
    "description" => $product->description,
     "price" => $product->price,
    "category_id" => $product->category_id,
    "category_name" => $product->category_name
  );

  http_response_code(200);
  echo json_encode($product_item);
}else{
    http_response_code(404);
  echo json_encode(array("message"=>"not found data"));
}




?>
<?php 

header('Content-type: application/json; charset=utf-8');
include("../connection.php");
$db = new dbObj();
$connection =  $db->getConnstring();
$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method)
  {
    case 'GET':
      // Retrive Products
      if(!empty($_GET["id"]))
      {
        $id=intval($_GET["id"]);
        get_product($id);
      }
      else
      {
        get_products();
      }
      break;
    case 'POST':
      // Insert Product
      insert_product();
      break;
    case 'PUT':
      // Update Product
      $id=intval($_GET["id"]);
      update_product($id);
      break;
    case 'DELETE':
      // Delete Product
      $id=intval($_GET["id"]);
      delete_product($id);
      break;
    default:
      // Invalid Request Method
      header("HTTP/1.0 405 Method Not Allowed");
      break;
  }

  function get_product($id=0)
  {
    global $connection;
    $query="SELECT * FROM product";
    if($id != 0)
    {
      $query.=" WHERE id=".$id." LIMIT 1";
    }
    $response=array();
    $result=mysqli_query($connection, $query);
    while($row=mysqli_fetch_array($result))
    {
      $response[]=$row;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  function get_products()
  {
    global $connection;
    $query="SELECT * FROM product";
    $response=array();
    $result=mysqli_query($connection, $query);
    while($row=mysqli_fetch_array($result))
    {
      $response[]=$row;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }
  
  function insert_product()
  {
    global $connection;

    $data = json_decode(file_get_contents('php://input'), true);
    $product_name=$data["product_name"];
    $product_name_escape = mysqli_real_escape_string($connection, $product_name);
    $product_price=$data["product_price"];
    $product_amount=$data["product_amount"];
    $query="INSERT INTO product SET product_name='".$product_name_escape."', product_price='".$product_price."', product_amount='".$product_amount."'";
    if(mysqli_query($connection, $query))
    {
      log_app('ok','001','insert_product '.json_encode($data)); 
      $response=array(
        'status' => 1,
        'status_message' =>'Product Added Successfully.'
      );
    }
    else
    {
      log_app('error','002','insert_product '.json_encode($data).' '.mysqli_error($connection));
      $response=array(
        'status' => 0,
        'status_message' =>'Product Addition Failed. Wrong data type. '.mysqli_error($connection).''
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  function update_product($id)
  {
    global $connection;
    $data = json_decode(file_get_contents("php://input"),true);
    $product_name=$data["product_name"];
    $product_name_escape = mysqli_real_escape_string($connection, $product_name);
    $product_price=$data["product_price"];
    $product_amount=$data["product_amount"];
    $query="UPDATE product SET product_name='".$product_name_escape."', product_price='".$product_price."', product_amount='".$product_amount."' WHERE id=".$id;
    if(mysqli_query($connection, $query))
    {
      log_app('ok','001','update_product id'.$id.' '.json_encode($data));
      $response=array(
        'status' => 1,
        'status_message' =>'Product Updated Successfully.'
      );
    }
    else
    {
      log_app('error','002','update_product id'.$id.' '.json_encode($data).' '.mysqli_error($connection)); 
      $response=array(
        'status' => 0,
        'status_message' =>'Product Updation Failed. Wrong data type. '.mysqli_error($connection).''
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  function delete_product($id)
  {
    global $connection;
    $query="DELETE FROM product WHERE id=".$id;
    if(mysqli_query($connection, $query))
    {
      log_app('ok','001','delete_product id'.$id); 
      $response=array(
        'status' => 1,
        'status_message' =>'Product Deleted Successfully.'
      );
    }
    else
    {
      log_app('error','002','delete_product id'.$id.' '.mysqli_error($connection));  
      $response=array(
        'status' => 0,
        'status_message' =>'Product Deletion Failed. '.mysqli_error($connection).''
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  function log_app($action, $number,$text){ 
    $ddf = fopen('log_app.log','a'); 
    fwrite($ddf,"[".date("r")."] $action $number: $text\r\n"); 
    fclose($ddf); 
   } 
?>
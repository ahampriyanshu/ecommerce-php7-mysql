<?php
session_start();
require_once('essentials/config.php');
if($_SESSION['email'] == null){
  echo "<script>window.open('login.php','_self')</script>";
}
else{
  $customer_id = $_SESSION['id'];
  $product_id = $_GET['id'];
  $action = $_GET['action'];
    switch($action) {
 
      case "add":
        $sql = "INSERT INTO wishlist( product_id,customer_id,fav_date)
        VALUES('$product_id','$customer_id',NOW())";
        {$_SESSION['alertMsg'] = "New product added to your wishlist !";}

        mysqli_query($connect, $sql);
      break;

      case "remove":
        $sql = "DELETE FROM wishlist	WHERE `customer_id` = $customer_id and `product_id`=$product_id ";
        mysqli_query($connect,$sql);
        {$_SESSION['alertMsg'] = "One product removed from your wishlist !";}
        break;

        case "empty":
          $sql = "DELETE FROM wishlist WHERE `customer_id` = $customer_id ";
    
          mysqli_query($connect,$sql);
           break;

          default:
          header('location: error.php');
    }
     header('location: wishlist.php');
}
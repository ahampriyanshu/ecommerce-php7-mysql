<?php
session_start();
require_once('essentials/config.php');


if (isset($_POST["action"])) {
  if (isset($_POST["men"])) {
    $query = "
  SELECT * FROM product WHERE status = 1 AND section = 1
 ";
  } else
  if (isset($_POST["women"])) {
    $query = "
  SELECT * FROM product WHERE status = 1 AND section = 2
 ";
  }
  else if (isset($_POST["kid"])) {
    $query = "
  SELECT * FROM product WHERE status = 1 AND section = 3
 ";
  }
  else if (isset($_POST["best"])) {
    $query = "
    SELECT product.*,order_detail.product_id,
    SUM(order_detail.units) AS TotalQuantity
    FROM product
    INNER JOIN order_detail 
    ON product.id = order_detail.product_id
    WHERE status = 1
 ";
  }
  else{
    $query = "
  SELECT * FROM product WHERE status = 1
 ";
}
    if (isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"])) {
        $query .= "
   AND cost BETWEEN '" . $_POST["minimum_price"] . "' AND '" . $_POST["maximum_price"] . "'
  ";
    }
    if (isset($_POST["brand"])) {
        $brand_filter = implode("','", $_POST["brand"]);
        $query .= "
   AND brand IN('" . $brand_filter . "')
  ";
    }
    if (isset($_POST["category"])) {
        $category_filter = implode("','", $_POST["category"]);
        $query .= "
   AND category IN('" . $category_filter . "')
  ";
    }
    if (isset($_POST["section"])) {
        $section_filter = implode("','", $_POST["section"]);
        $query .= "
   AND section IN('" . $section_filter . "')
  ";
    }

    if (isset($_POST["start_from"])) {
        $start_from = $_POST['start_from'];
        $per_page = $_POST['per_page'];

        if (isset($_POST["best"])) 
        {
          $query .= "
          GROUP BY order_detail.product_id
          ORDER BY TotalQuantity DESC LIMIT $start_from, $per_page
            ";
        } else  if (isset($_POST["newArrival"])) {
          $query .= "
          ORDER BY 1 DESC LIMIT $start_from, $per_page
            ";
        }
        else
        { 
        $query .= "
ORDER BY 1 ASC LIMIT $start_from, $per_page
  ";
        }
    }
    
    $statement = $con->prepare($query);
    $statement->execute();
    $result    = $statement->fetchAll();
    $total_row = $statement->rowCount();
    $output    = '';
    if ($total_row > 0) {
        foreach ($result as $row) {
          $product_id =  $row['id'];
          $customer_id = $_SESSION['id'];
          $sql_fav = "SELECT * FROM wishlist WHERE customer_id ='$customer_id' AND product_id = '$product_id'";
          $run_fav = mysqli_query($connect, $sql_fav);
          $row_fav = mysqli_fetch_assoc($run_fav);

          if (!$row_fav) {

            $output .= '<a href="product.php?id=' .  $row['id'] . '">
            <div class="col-lg-4 col-sm-6">
                                <div class="product-item">
                                    <div class="pi-pic">
                                    
                                        <img width="200" height="300" src="uploads/' .  $row['file'] . '" alt="' .  $row['file'] . '">
                                        <div class="icon">
                                        <a href="updateWishlist.php?user='.$customer_id.'&action=add&id='.$product_id.'" ><i class="far fa-heart" style="color:red"></i></a>
                                        </div>
                                    </div>
                                    <div class="pi-text">
                                    <h4><span class="badge badge-light">' .  $row['name'] . '</span></h4>
                                        </a>
                                        <div class="product-price">
                                        &#x20B9;' .  $row['cost'] . '&nbsp;
                                        <span class="MRP">&#x20B9;' .  $row['MRP'] . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>';
        }
        else{
          $output .= '<a href="product.php?id=' .  $row['id'] . '">
          <div class="col-lg-4 col-sm-6">
                              <div class="product-item">
                                  <div class="pi-pic">
                                  
                                      <img width="200" height="300" src="uploads/' .  $row['file'] . '" alt="' .  $row['file'] . '">
                                      <div class="icon">
                                      <a href="updateWishlist.php?user='.$customer_id.'&action=remove&id='.$product_id.'" ><i class="fas fa-heart" style="color:red"></i></a> 
                                      </div>
                                  </div>
                                  <div class="pi-text">
                                  <h4><span class="badge badge-light">' .  $row['name'] . '</span></h4>
                                      </a>
                                      <div class="product-price">
                                      &#x20B9;' .  $row['cost'] . '&nbsp;
                                      <span class="MRP">&#x20B9; ' .  $row['MRP'] . '</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          </a>'; 
        }
    } 
  }else {
        $output = '<div  class="container">
        <div style="margin-top:40px;" class="row">
          <div class="col-md-12 text-center">
         <i class="fas fa-3x  text-danger fa-exclamation-triangle"></i>
          <h3><span class="badge badge-light">No matching items found !</span></h3>
          <p class="display-5 mb-5">Alter your filters and try again</p>
        </div>
        </div>
      </div>';
    }
    echo $output;
}
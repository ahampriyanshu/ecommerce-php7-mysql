<?php
session_start();
include('essentials/config.php');
$name = $_POST['name'];
$street = $_POST['street'];
$state = $_POST['state'];
$city = $_POST['city'];
$pincode = $_POST['pincode'];
$phone = $_POST['phone'];
$customer_email = $_SESSION['email'];

$sql = "INSERT INTO shipping(full_name, email, store_id, phone, shipping_type, street_address, state, city, pincode, created_date, modified_date)
                VALUES('$name', '$customer_email', 0, '$phone', 'home', '$street', '$state', '$city', '$pincode', NOW(), NOW())";

mysqli_query($connect, $sql);

$query = "SELECT * FROM shipping WHERE email = '$customer_email' ORDER BY shipping_id DESC LIMIT 0,1";
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_assoc($result)) {
  $id = $row['shipping_id'];
  echo "<script>window.location='payment.php?id=$id'</script>";
}

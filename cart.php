<?php
if(isset($_POST['delete'])){
  include_once 'includes/config.php';
$sql = "DELETE FROM carts where pid={$_GET['pid']} AND quantity={$_GET['q']} LIMIT 1"; //sql query for deleting
$conn->query($sql); //executing sql query

header("Location:cart.php?itemRemovedSuccessfully");
}
?>
<?php
   include_once('./includes/navbar.php');
      //this restriction will secure the pages path injection
      if(!(isset($_SESSION['id']))){
        header("location:index.php?UnathorizedUser");
        die();
       }
       include_once('./stripeConfig.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book|Cart</title>
 <style>
       *{
        margin:0;padding:0;
      }
      .facross{
    color:  #DC143C !important;
}
  .text-end{
     text-align:center
  }
 </style>
</head>
<body>
<div class='cart' >
  <div class="container" >
  <br><br>
  <br><br>
    <h1 style='float:left'>Cart</h1>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div style=''>

<?php
       $total=0;
  $sql = "SELECT * FROM carts where uid={$_SESSION['id']} AND status='active'";
$result = $conn->query($sql) or die("Query Failed.");
if ($result->num_rows > 0) {
?>
<div style='margin-left:5%'>
    <table class='cart-table' style="position:relative;">
<thead>
<thead >
        <tr>
          <th>Sn</th>
          <th>Book</th>
          <th>Rent Charge</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Return Date</th>
          <th>Action</th>
        </tr>
      </thead>
</thead>
<tbody >
     <?php
     $sn=0;
while($row = $result->fetch_assoc()) { 
  $sn = $sn+1;
  //by this way we can encode data and pass this data to anther page and use it after decoding
  // $quantArray[$sn-1] = $row['quantity'];
  // $dateArray[$sn-1] = $row['return_date'];
  // $encodedQuantityData = urlencode(serialize($quantArray));
  // $encodedReturnDateData = urlencode(serialize($dateArray));

  $total = $total+ ($row["price"]*$row["quantity"]);
?>
<tr>
    <td><?php echo $sn?></td>
    <td><?php echo $row["product"] ?></td>
          <td><?php echo $row["rent_charge"] ?>% 
        </td>
        <td><?php echo $row["price"] ?></td>
          <td>
          <p><?php echo $row["quantity"] ?></p>
          </td>
          <td><?php echo ($row["price"]*$row["quantity"]) ?></td>
          <td><?php echo $row["return_date"] ?></td>
          <td>
          <form action="<?php echo $_SERVER['PHP_SELF']?>?pid=<?php echo $row['pid']?>&q=<?php echo $row['quantity']?>" method="post">
<button name='delete' type='submit' ><i class="fa-solid fa-trash fa-lg facross"></i> </button>
</form>
        </td>
</tr>

<?php }?>
</tbody>
<button class="btn" style="background:#11C9B6;border:none;"><a href="./products.php?type=new" style='color:white;text-decoration:none'>Continue Renting</a></button>
</table>
</div>
<div style="margin-top:5px;border-bottom:1px solid white;"></div>
<div style='margin-left:5%'>Total: <?php echo ($total)?> (<i style='color:grey' class="fa fa-motorcycle" aria-hidden="true">Free</i>)</div>
<div style="margin-top:5px;border-bottom:1px solid white;"></div>
<div style="margin-top:5px;border-bottom:1px solid white;"></div>
<div style="margin-top:5px;border-bottom:1px solid white;"></div>
<!-- <form class='cart-stripe-form' style='' action="message.php?id=<?php echo $encodedPidData?>&q=<?php echo $encodedQuantityData?>&rd=<?php echo $encodedReturnDateData?>" method="post"> -->
<form class='cart-stripe-form' style='' action="message.php?items=carts" method="post">
	<script
		src="https://checkout.stripe.com/checkout.js" class="stripe-button"
		data-key="<?php echo $publishableKey?>"
		data-amount="<?php echo ($total) ?>"
		data-name="Book Rental"
		data-description="Book For Everyone"
		data-image="./images/logo.png"
		data-currency="usd"
		data-email="<?Php echo $_SESSION['customer_email']?>"
    success="<?php //it will be created only when payment is made
        $_SESSION['order_auth']=true;
        ?>"
	>
   //this form container will auto generate paynow button that comers form script form stripe
	</script>
</form>
<?php }else { echo "0 Results <br> No Books in a Cart"; }
             ?>
      </div>
  </div>
  </div>

<?php
// --- PURCHASE CART SECTION (session-based) ---
session_start();
if (!isset($_SESSION['purchase_cart'])) {
    $_SESSION['purchase_cart'] = [];
}
// Handle add, update, remove actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $book_id = $_POST['book_id'];
        $quantity = max(1, (int)$_POST['quantity']);
        if (isset($_SESSION['purchase_cart'][$book_id])) {
            $_SESSION['purchase_cart'][$book_id]['quantity'] += $quantity;
        } else {
            $_SESSION['purchase_cart'][$book_id] = [
                'book_title' => $_POST['book_title'],
                'book_price' => $_POST['book_price'],
                'book_img' => $_POST['book_img'],
                'quantity' => $quantity
            ];
        }
    } elseif ($_POST['action'] === 'update') {
        $book_id = $_POST['book_id'];
        $quantity = max(1, (int)$_POST['quantity']);
        if (isset($_SESSION['purchase_cart'][$book_id])) {
            $_SESSION['purchase_cart'][$book_id]['quantity'] = $quantity;
        }
    } elseif ($_POST['action'] === 'remove') {
        $book_id = $_POST['book_id'];
        unset($_SESSION['purchase_cart'][$book_id]);
    }
}
// Display purchase cart
if (!empty($_SESSION['purchase_cart'])) {
    echo "<h2>Purchase Cart</h2>";
    echo "<table border='1' cellpadding='8'><tr><th>Book</th><th>Image</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    $total = 0;
    foreach ($_SESSION['purchase_cart'] as $book_id => $item) {
        $item_total = $item['book_price'] * $item['quantity'];
        $total += $item_total;
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['book_title']) . "</td>";
        echo "<td><img src='admin/upload/" . htmlspecialchars($item['book_img']) . "' width='50'></td>";
        echo "<td>" . number_format($item['book_price'], 2) . "</td>";
        echo "<td>"
            . "<form method='post' action='cart.php' style='display:inline;'>"
            . "<input type='hidden' name='action' value='update'>"
            . "<input type='hidden' name='book_id' value='" . $book_id . "'>"
            . "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1' style='width:40px;'>"
            . "<button type='submit'>Update</button>"
            . "</form>"
        . "</td>";
        echo "<td>" . number_format($item_total, 2) . "</td>";
        echo "<td>"
            . "<form method='post' action='cart.php' style='display:inline;'>"
            . "<input type='hidden' name='action' value='remove'>"
            . "<input type='hidden' name='book_id' value='" . $book_id . "'>"
            . "<button type='submit'>Remove</button>"
            . "</form>"
        . "</td>";
        echo "</tr>";
    }
    echo "<tr><td colspan='4' align='right'><strong>Total:</strong></td><td colspan='2'><strong>" . number_format($total, 2) . "</strong></td></tr>";
    echo "</table>";
    echo "<br><a href='purchase.php' class='btn' style='background:#28a745;color:white;padding:8px 16px;text-decoration:none;'>Proceed to Checkout</a>";
} else {
    echo "<h2>Purchase Cart</h2><p>No items in your purchase cart.</p>";
}
?>

</body>
</html>






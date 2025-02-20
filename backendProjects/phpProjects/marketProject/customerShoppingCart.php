<?php

require "./db.php";

session_start();
$rememberedCustomerEmail = $_SESSION['rememberedEmail'];
$selectedTitle = $_SESSION['selectedTitle'];
$selectedQuantity = $_SESSION['selectedQuantity'];

$stmt = $db->prepare("select * from product");
$stmt->execute();
$pro = $stmt->fetchAll();

$stmt = $db->prepare("select * from marketuser");
$stmt->execute();
$mar = $stmt->fetchAll();

$stmt = $db->prepare("select * from customeruser");
$stmt->execute();
$cust = $stmt->fetchAll();

$stmt = $db->prepare("select * from shoppingcart");
$stmt->execute();
$cart = $stmt->fetchAll();

$quantity = 0;

for($i=0; $i<sizeof($pro); $i++ ){
    $flag = False;
    if($pro[$i]["title"] == $selectedTitle){
        $image = $pro[$i]["image"];
        $quantity = $selectedQuantity[$i];
        $normalPrice = $pro[$i]["normalPrice"];
        $discountedPrice = $pro[$i]["discountedPrice"];
        foreach($mar as $m){
            if($m["e-mail"] == $pro[$i]["productMail"]){
                $marketEmail = $m["e-mail"];
                $marketName = $m["marketName"];
            }
        }
        foreach($cart as $c1){
            if($c1["title"] == $selectedTitle && $c1["marketMail"] == $marketEmail){
                $flag = True;
                $upd = $c1["quantity"] + $quantity;
                $tit = $c1["title"];
                $stmt = $db->prepare("UPDATE shoppingcart SET quantity = ? WHERE title = ?");
                $stmt->execute([$upd, $tit]);
            }
        }
        if($flag == False){
            $sql = $db->prepare("INSERT INTO `shoppingcart`(`marketMail`, `customerMail`, `title`, `quantity`) VALUES (?, ?, ?, ?)");
            $sql->execute([$marketEmail, $rememberedCustomerEmail, $selectedTitle, $quantity]);
        }
    }
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST["logout"])){
        header('Location: customer.php');
    }else if(isset($_POST["purchase"])){
        $stmt = $db-> prepare("TRUNCATE TABLE shoppingcart;");
        $stmt->execute();
        foreach($cart as $c){
            foreach($pro as $p){
                if($p["title"] == $c["title"]){
                    $stmt = $db->prepare("UPDATE product SET stock = ? WHERE title = ?");
                    $upd = $p["stock"] - $c["quantity"];
                    $tit = $p["title"];
                    $stmt->execute([$upd, $tit]);
                }
            }
        }
    }else if(isset($_POST["trash"])){
        $delTitle = $_POST["trash"];
        // var_dump($delTitle);
        $stmt = $db->prepare("DELETE FROM `shoppingcart` WHERE `title` = ?");
        $stmt->execute([$delTitle]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://kit.fontawesome.com/f37f768322.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style/customerShoppingCart.css">
</head>
<body>
    <form  action="" method="post">
        <header> 
            <ul class="head">
                <li class="ad">MARKET LAB</li>
                <li class="shopCart">MY SHOPPING CART</li>
                <li class="continue"><button class="cnt" name="logout"><i class="fas fa-arrow-left return-icon"></i> Continue to Shopping</button></li>
            </ul>
        </header>

        <div class="cartList">
            <table class="cartTable">
                <tr class="headTable">
                    <td>Image</td>
                    <td>Market Name</td>
                    <td>Product Title</td>
                    <td>Normal Price</td>
                    <td>Discounted Price</td>
                    <td>Quantity</td>
                    <td>Price</td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
                    $total = 0;
                    $price = 0;
                    foreach($cart as $c){
                        foreach($mar as $m){
                            if($m["e-mail"] == $c["marketMail"]){
                                $mmail = $c["marketMail"];
                                $mname = $m["marketName"];
                            }
                        }
                        $nprice = 0;
                        $dprice = 0;
                        $pname = "";
                        for($k=0; $k<sizeof($pro); $k++){
                            if($pro[$k]["title"] == $c["title"]){
                                $i = $pro[$k]["image"];
                                $pname = $pro[$k]["title"];
                                $nprice = $pro[$k]["normalPrice"];
                                $dprice = $pro[$k]["discountedPrice"];
                            }
                        }
                        $q = $c["quantity"];
                        $price = $dprice * $q;
                        $total += $price;
                        echo "<tr class='detail'>";
                            echo "<td><img src='./products/$i'></td>";
                            echo "<td>$mname Market</td>";
                            echo "<td>$pname</td>";
                            echo "<td>$nprice</td>";
                            echo "<td>$dprice</td>";
                            echo "<td>$q</td>";
                            echo "<td>$price</td>";
                            echo "<td><button class='trash' name='trash' value='{$c['title']}'><i class='fa-solid fa-trash-can'></i></button></td>";
                            echo "<td></td>";
                        echo "</tr>";
                    }
                    echo "<tr>";
                    echo "<td colspan='9' class='total'>TOTAL: $total TL</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td colspan='9' class='pur'><button name='purchase' class='purchase'>PURCHASE</button></td>";
                    echo "</tr>";
                ?>
            </table>
        </div>
    </form>

</body>
</html>
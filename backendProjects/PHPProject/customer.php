<?php

require "./db.php";

session_start();
$rememberedEmail = $_SESSION['rememberedEmail'];
$selectedTitle = "";
$selectedQuantity = 0;

$stmt = $db->prepare("select * from product");
$stmt->execute();
$pro = $stmt->fetchAll();

$stmt = $db->prepare("select * from customeruser");
$stmt->execute();
$cust = $stmt->fetchAll();

$stmt = $db->prepare("select * from marketuser");
$stmt->execute();
$market = $stmt->fetchAll();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $_SESSION['rememberedEmail'] = $rememberedEmail;

    if(isset($_POST["myAccount"])){
        header('Location: customerAccount.php');
    }else if(isset($_POST["shopCart"])){
        header('Location: customerShoppingCart.php');
    }else if(isset($_POST["logout"])){
        $stmt = $db-> prepare("TRUNCATE TABLE shoppingcart");
        $stmt->execute();
        header('Location: customer_login.php');
    }else if (isset($_POST["add"])){

        $selectedTitle = $_POST["add"];
        $_SESSION['selectedTitle'] = $selectedTitle;

        $selectedQuantity = $_POST["quantity"];
        $_SESSION['selectedQuantity'] = $selectedQuantity;
        header('Location: customerShoppingCart.php');
    }else if(isset($_POST["searchProduct"])){
        $searchProduct = $_POST['searchProduct'] ?? '';
        if ($searchProduct) {
            $stmt = $db->prepare("SELECT * FROM product WHERE title LIKE ? AND expirationDate > NOW() ");
            $stmt->execute(['%' . $searchProduct . '%']);
            $pro = $stmt->fetchAll();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link rel="stylesheet" href="./style/customer.css">
    <script src="https://kit.fontawesome.com/f37f768322.js" crossorigin="anonymous"></script>
</head>
<body>
        <form action="" method="post">
            <header>
                <div class="menu">
                    <ul>
                        <li class="head">MARKET LAB</li>
                        <li class="search-wrapper">
                            <input type="text" name="searchProduct" class="search" placeholder="Search Product">
                            <button type="submit" name="search" class="search-icon"><i class="fas fa-search"></i></button>
                        </li>
                        <li class="myAccount">
                            <button  id="acntBtn" name="myAccount"><i class="fas fa-user"></i> My Account</button>
                        </li>
                        <li class="shoppingCart">
                            <button id="cartBtn" name="shopCart"><i class="fas fa-shopping-cart"></i> Shopping Cart</button>
                        </li>
                        <li class="logout">
                            <button id="lgtBtn" name="logout"><i class="fas fa-sign-out-alt"></i>Logout</button>
                        </li>
                    </ul>
                </div>
            </header>

            <div class="product">
            <?php
                $i = 0;
                foreach ($pro as $p) {
                    if ($i % 3 == 0) {
                        echo "<div class='row'>";
                    }
                    echo "<div class='pro'>";
                        echo "<ul>";
                        echo "<li><img src='./products/{$p['image']}'></li>";
                        echo "<li class='proName'>{$p['title']}</li>";
                        foreach ($market as $m) {
                            if ($m['e-mail'] == $p['productMail']) {
                                echo "<li class='mrktName'>{$m['marketName']} Market</li>";
                            }
                        }
                        echo "<li class='normalPrice'>{$p['normalPrice']} TL</li>";
                        echo "<li class='discountedPrice'>{$p['discountedPrice']} TL</li>";
                        echo "<li><button name='add' value='{$p['title']}'><i class='fa-solid fa-plus'></i></button>
                                <input type='text' class='quantity' name='quantity[]' placeholder='Quantity:'>
                            </li>";
                        echo "</ul>";
                    echo "</div>";
                    $i++;
                    if ($i % 3 == 0 ) {
                        echo "</div>";
                    }
                }
            ?>
            </div>
        </form>
</body>
</html>
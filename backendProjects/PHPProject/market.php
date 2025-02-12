<?php
require_once("db.php");
session_start();
$rememberedEmail = $_SESSION['rememberedEmail'];

$stmt = $db->prepare("select * from product where `productMail`=?");
$stmt->execute([$rememberedEmail]);
$product = $stmt->fetchAll();
$today = new DateTime();


if($_SERVER["REQUEST_METHOD"] == "POST" ){
    extract($_POST);
       
   
    if(isset($_POST["delBtn"])){

        $selectedTitle=$_POST["delBtn"];
        $stmt=$db->prepare("DELETE FROM product WHERE `title`=? and `productMail`=?;");
        $stmt->execute([$selectedTitle,$rememberedEmail]);

    }else if(isset($_POST["editBtn"])){
        $selectedTitle=$_POST["editBtn"];
        $data = array(
            'data1' => $rememberedEmail,
            'data2' => $selectedTitle,
        );
        $_SESSION['my_data'] = $data;
       
        header('Location: editProduct.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link rel="stylesheet" href="./style/market.css">
    <script src="https://kit.fontawesome.com/f37f768322.js" crossorigin="anonymous"></script>
    <style>
        .item-list{ display: flex; align-items: center; justify-content: center; margin: 10px;}
        .item-list th{ border-bottom: 1px solid orange; padding: 20px;}
        img{ width: 120px; height: 120px;}
        .passDate{ background-color: rgba(197,58,58,0.3);}
        .edit{ background-color: #555; color: white; border: none; padding: 10px; border-radius: 3px; cursor: pointer; width: 60px;}
        .del{ background-color: #555; color: white; border: none; padding: 10px; border-radius: 3px; cursor: pointer; width: 60px;}
        .edit:hover{background-color: orange; color: white;}
        .del:hover{background-color: orange; color: white;}
    </style>
</head>
<body>
    <header>
        <form action="" method="post">
            <header>
                <div class="menu">
                <ul>
                    <li class="head">MARKET LAB</li>
                    
                    <li class="myAccount">
                        <i class="fas fa-user"></i>
                        <a href="./marketAccount.php">My Account</a>
                    </li>
                    <li class="shoppingCart">
                        <i class="fas fa-shopping-cart"></i>
                        <a href="marketAddProduct.php">Add Product</a>
                    </li>
                    <li class="logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <a href="./market_login.php">Logout</a>
                    </li>
                </ul>
            </div>
    </header>
        
            <table class="item-list">
                <tr >
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Discounted Price</th>
                    <th>exp. Date</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php

        foreach($product as $p){
            $expirationDate = new DateTime($p['expirationDate']);
            
            if($expirationDate>$today ){
                echo "<tr>"; 
                echo "<td><img src='./products/{$p["image"]}'></td>";
                echo "<td>'{$p['stock']}'</td>";
                echo "<td>'{$p['title']}'</td>";
                echo "<td>'{$p['normalPrice']}'</td>";
                echo "<td>'{$p['discountedPrice']}'</td>";
                echo "<td>'{$p['expirationDate']}'</td>";
                echo "<td><button name='editBtn' value='{$p['title']}' class='edit'>EDIT</button></td>";
                echo "<td><button name='delBtn' value='{$p['title']}' class='del'>Delete</button></td>";
            echo"</tr>";
            }
            else{
                
                echo "<tr class='passDate'>"; 
                    echo "<td><img src='./products/{$p['image']}'></td>";
                    echo "<td>'{$p['stock']}'</td>";
                    echo "<td>'{$p['title']}'</td>";
                    echo "<td>'{$p['normalPrice']}'</td>";
                    echo "<td>'{$p['discountedPrice']}'</td>";
                    echo "<td>'{$p['expirationDate']}'</td>";
                    echo "<td><button name='editBtn' value='{$p['title']}' class='edit'>EDIT</button></td>";
                    echo "<td><button name='delBtn' value='{$p['title']}' class='del'>Delete</button></td>";
                echo"</tr>";
            }
                
        }
        ?>
            </table>    

        </form>
       
    
</body>
</html>
<?php 
require_once("db.php");
session_start();
$rememberedEmail = $_SESSION['rememberedEmail'];
$msg = "";
$stmt = $db->prepare("select image from product where productMail = ?");
$stmt->execute([$rememberedEmail]);
$proImg = $stmt->fetchAll();

//var_dump($allDatas);
$data = ["bal.jpg", "bulyon.jpg", "dardanel.jpg", "dove.jpg", "fıstık.jpg"
        , "hellim.jpg", "kekik.jpg", "krema.jpg", "makarna.jpg", "mayonez.jpg"
        , "mercimek.jpg", "mısır.jpg", "narekşi.jpg", "nivea.jpg", "nohut.jpg"
        , "nutella.jpg", "rulokat.jpg", "salça.jpg", "şeker.jpg", "sirke.jpg"
        , "tuz.jpg", "un.jpg", "yoğurt.jpg", "yumurta.jpg", "zeytin.jpg"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($data as $index => $img) {
        if (isset($_POST["addBtn_$index"])) {
            extract($_POST);
            $title = $_POST["name_$index"];
            $quantity = (int)$_POST["quantity_$index"];
            $price = (double)$_POST["normalPrice_$index"];
            $discPrice = (double)$_POST["discountedPrice_$index"];
            $expDate = $_POST["expirationDate_$index"];
            $selectedPic=$img;

            if (empty($title) && empty($quantity) && empty($price) && empty($discPrice)) {
                $msg = "Please fill all blanks correctly";
            } else {
                $flag=false;
                foreach ($proImg as $index) {
                    if($index["image"]==$selectedPic ){
                        $flag=true;
                    }
                }
                
                if($flag==true){
                    $msg = "This product is already exist.";
                }else{
                    $stmt = $db->prepare("INSERT INTO product (productMail, title, stock, normalPrice, discountedPrice, expirationDate, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$rememberedEmail, $title, $quantity, $price, $discPrice, $expDate, $selectedPic]);
                    $msg = "The product is added to the list. :)";
                }
           }
        
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/f37f768322.js" crossorigin="anonymous"></script>
    <style>
        body{margin: 0;}
        .menu{margin: 0 auto; box-shadow: 0 0 3px orange; height: 75px;padding: 25px;}
        .tab{margin-left: 20px;}
        .tab tr td{padding: 10px;}
        .lab{border: 1px solid orange; border-radius: 5px; background-color: orange;color: white;}
        .det{width: 900px; text-align: center; color: orange; font-weight: bold; font-size: 20px;}
        .return-icon{color: orange;}
        .tab tr td a{text-decoration: none; color: orange;}

        .detail{width: 80%; margin: 0 auto; margin-top: 50px;}
        .addTable{margin: 0 auto; width: 100%; margin-top: 40px;}
        .addTable tr{width: 100%;}
        .head{font-size: 20px; font-weight: bold;}
        .addTable tr td{text-align: center; padding: 10px; color: orange; border-bottom: 1px solid orange; border-collapse:collapse; width: 100%;}
        .addTable tr td img{width: 80px; height: 80px;}
        .addBtn{border: 1px solid #555; background-color: #555; color: white; padding: 10px;} 
        .addBtn:hover{border: 1px solid orange; background-color: orange; border-radius: 5px;}
    </style>
</head>
<body>
<header>
    <div class="menu">
        <table class="tab">
            <tr>
                <td class="lab">MARKET LAB</td>
                <td class="det"><h3>ADD PRODUCT</h3></td>
                <td>
                    <i class="fas fa-arrow-left return-icon"></i>
                    <a href="market.php"> Return Home</a>
                </td>
            </tr>
        </table>
    </div>
</header> 

<form action="" method="post">
    <div class="detail">
        <table class="addTable">
            <tr class="head">
                <td>Product</td>
                <td>Name</td>
                <td>Quantity</td>
                <td>Normal Price</td>
                <td>Discounted Price</td>
                <td>Expiration Date</td>
            </tr>
            <?php 
            foreach ($data as $index => $img) {
                echo "<tr>"; 
                echo "<td><img src='./products/$img'></td>";
                echo "<td><input type='text' name='name_$index'></td>" ;
                echo "<td><input type='text' name='quantity_$index'></td>" ;
                echo "<td><input type='text' name='normalPrice_$index'></td>" ;
                echo "<td><input type='text' name='discountedPrice_$index'></td>" ;
                echo "<td><input type='date' name='expirationDate_$index'></td>";
                echo "<td><button class='addBtn' name='addBtn_$index' value='$img'><i class='fa-solid fa-plus'></i></button></td>";
                echo "</tr>";
            }
            echo "<tr>$msg</tr>";
            ?>
        </table>
    </div>
</form>
    
</body>
</html>
<?php
require_once "db.php";
session_start();
$data = $_SESSION['my_data'];
$rememberedEmail = $data['data1'];
$rememberedTitle = $data['data2'];

$msg = "";
$stmt = $db->prepare("select * from product where productMail=? and title=?");
$stmt->execute([$rememberedEmail,$rememberedTitle]);
$check = $stmt->fetch();

$img=$check["image"];
$quantity=$check["stock"];
$price=$check["normalPrice"];
$discPrice=$check["discountedPrice"];
$expDate=$check["expirationDate"];

$rememberedDate = new DateTime($expDate);
if($_SERVER["REQUEST_METHOD"] == "POST"){
    extract($_POST);
    if(isset($_POST["update"])){
        $quantity = $_POST["amount"];
        $price = $_POST["price"];
        $discPrice = $_POST["discPrice"];
        $expDate = $_POST["expirationDate"];


        if(empty($quantity) || empty($price) || empty($discPrice)){
            $msg = "Please fill all blanks";
        
        }else{
            $stmt = $db->prepare("UPDATE product SET  stock=?, normalPrice=?, discountedPrice=?, expirationDate=? WHERE productMail = ? and title = ?" );
            $stmt->execute([$quantity, $price, $discPrice, $expDate, $rememberedEmail,$rememberedTitle]);

            $msg = "Your information is updated";
        
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
        .detail table{margin: 0 auto; margin-top: 40px; width: 400px; height: 500px;}
        .detail table tr td{text-align: center; padding: 10px; color: orange; border-bottom: 1px solid orange; border-collapse:collapse;}
        .detail table tr td input{width:400px}
        .update{padding: 10px; width: 300px; border: 1px solid orange; border-radius: 5px; background-color: #555; color: #fff;}
        .update:hover{background-color: orange; color: white;}
        .return-icon{color: orange;}
        a{text-decoration: none; color: orange;}
    </style>
</head>
<body>
    
<header>
        <div class="menu">
            <table class="tab">
                <tr>
                    <td class="lab">MARKET LAB</td>
                    <td class="det"><h3>Edit Product</h3></td>
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
        <table>
        <tr>
            <td>Title</td>
            <td><input type="text" name="title" disabled placeholder="<?php echo htmlspecialchars($rememberedTitle); ?>"></td>
        </tr>
        <tr>
            <td>Stock</td>
            <td><input type="text" name="amount" value="<?php echo htmlspecialchars($quantity); ?>"></td>
        </tr>
        <tr>
            <td>Normal Price</td>
            <td><input type="text" name="price" value="<?php echo htmlspecialchars($price); ?>"></td>
        </tr>
        <tr>
            <td>Discounted Price</td>
            <td><input type="text" name="discPrice" value="<?php echo htmlspecialchars($discPrice); ?>"></td>
        </tr>
        <tr>
            <td>Exp Date</td>
            <td><input type='date' name='expirationDate' value="<?php echo $rememberedDate->format('Y-m-d'); ?>"></td>
        </tr>
        <tr>
        <td colspan="2"><button class="update" name="update">UPDATE</button></td>
            
        </tr>
        <tr>
        <td colspan="2"><?php echo $msg;?></td>
            
        </tr>
    </table>
        </div>
   
    </form>
</body>
</html>
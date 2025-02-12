<?php

require "db.php";

session_start();
$rememberedEmail = $_SESSION['rememberedEmail'];

$stmt = $db->prepare("select * from customeruser where `e-mail`=?");
$stmt->execute([$rememberedEmail]);
$check = $stmt->fetch();

$fullname = $check["fullname"];
$city = $check["city"];
$district = $check["district"];
$address = $check["address"];
$password = $check["password"];

$msg = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    extract($_POST);

    if(isset($_POST["update"])){
        $fullname = $_POST["fullname"];
        $city = $_POST["city"];
        $district = $_POST["district"];
        $address = $_POST["address"];
        $password = $_POST["password"];

        if(strlen($fullname) != 0 && strlen($city) != 0 && strlen($district) != 0 && strlen($address) != 0 && strlen($password) != 0 && strlen($password) <= 12){
            $stmt = $db->prepare("UPDATE customeruser SET fullname=?, city=?, district=?, address=?, password=? WHERE `e-mail` = ?");
            $stmt->execute([$fullname, $city, $district, $address, $password, $rememberedEmail]);
            $msg = "Your information is updated";
        }else{
            if(strlen($fullname) == 0 || strlen($city) == 0 || strlen($district) == 0 || strlen($address) == 0 || strlen($password) == 0){
                $msg = "Please fill all fields";
            }
            if(strlen($password) > 12){
                $msg = "Password should be smaller than 12 character";
            }
        }
    }

    if(isset($_POST["continue"])){
        header('Location: customer.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <script src="https://kit.fontawesome.com/f37f768322.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style/customerAccount.css">
</head>
<body>
    <form action="" method="post">
        <header>
            <div class="menu">
                <ul>
                    <li class="ad">MARKET LAB</li>
                    <li class="acnt">MY ACCOUNT DETAILS</li>
                    <li class="cont">
                        <button name="continue" class="continue">
                            <i class="fas fa-arrow-left"></i>
                            Continue to Shopping
                        </button>
                    </li>
                </ul>
            </div>
        </header>
    
        <div class="detail">
            <table>
                <tr>
                    <td><h4>E-mail:</h4></td>
                    <td><input type="text" name="e-mail" disabled placeholder="<?php echo htmlspecialchars($rememberedEmail); ?>"></td>
                </tr>
                <tr>
                    <td><h4>Fullname:</h4></td>
                    <td><input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>"></td>
                </tr>
                <tr>
                    <td><h4>City:</h4></td>
                    <td><input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>"></td>
                </tr>
                <tr>
                    <td><h4>District:</h4></td>
                    <td><input type="text" name="district" value="<?php echo htmlspecialchars($district); ?>"></td>
                </tr>
                <tr>
                    <td><h4>Address:</h4></td>
                    <td><input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>"></td>
                </tr>
                <tr>
                    <td><h4>Password:</h4></td>
                    <td><input type="text" name="password" value="<?php echo htmlspecialchars($password); ?>"></td>
                </tr>
                <tr>
                    <td colspan="2"><button class="update" name="update">UPDATE</button></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $msg; ?></td>
                </tr>
            </table>
        </div>
    </form>
</body>
</html>
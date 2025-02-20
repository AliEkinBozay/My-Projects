<?php 
require_once "db.php";

session_start();
$rememberedEmail = $_SESSION['rememberedEmail'];


$stmt = $db->prepare("select * from marketuser where `e-mail`=?");
$stmt->execute([$rememberedEmail]);
$check = $stmt->fetch();
// var_dump($check);
$fullname = $check["marketName"];
$city = $check["city"];
$district = $check["district"];
$address = $check["address"];
$password = $check["password"];
$msg = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    extract($_POST);
    $fullname = $_POST["fullname"];
    $city = $_POST["city"];
    $district = $_POST["district"];
    $address = $_POST["address"];
    $password = $_POST["password"];

    if(strlen($fullname) != 0 && strlen($city) != 0 && strlen($district) != 0 && strlen($address) != 0 && strlen($password) != 0 && strlen($password) <= 12){
        $stmt = $db->prepare("UPDATE marketuser SET marketname=?, city=?, district=?, address=?, password=? WHERE `e-mail` = ?");
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
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
                    <td class="det"><h3>MY ACCOUNT DETAILS</h3></td>
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
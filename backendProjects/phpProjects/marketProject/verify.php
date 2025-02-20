<?php
require_once "db.php";
session_start();
$data = $_SESSION['data'];
$msg = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["code"])) {
        $verification_code=$_POST["code"];
        var_dump($verification_code);
        if($verification_code == $data[6]) {
            $msg = "Email verified successfully! You can now login.";
            $stmt = $db->prepare("INSERT INTO `marketuser`(`e-mail`, `marketName`, `city`, `district`, `address`, `password`, `verification_code`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data[0], $data[2], $data[3], $data[4], $data[5], $data[1], $data[6]]);

            header("Location:market_login.php");
        } else {
            $msg = "Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="./style/marketregister.css">
</head>
<body>
    
<header>MARKET LAB</header>
        <div class="container">
        <form action="" method="post">
            <table>
                <tr>
                    <td>Verification Code:</td>
                    <td><input type="text" name="code"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button>Verify</button>
                    </td>
                </tr>
                <tr class="error">
                    <td colspan="2"><p><?php echo $msg; ?></p></td>
                </tr>
            </table>
        </form>
    </div>
    
    <footer><img src="./images/custlogin.jpg" alt="Login" /></footer> 
</body>
</html>

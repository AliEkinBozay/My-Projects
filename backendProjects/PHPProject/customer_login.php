<?php 
require_once "db.php" ;

$msg = "";
$rememberedEmail = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["e-mail"]) && isset($_POST["pass"])) {
        extract($_POST);
        $user_mail=$_POST["e-mail"];
        $user_password=$_POST["pass"];

        $stmt = $db->prepare("select * from customeruser where `e-mail`=?");
        $stmt->execute([$user_mail]);
        $check = $stmt->fetch();

        if(!$check){
            $msg="There is no account with this e-mail";
        }else{
            $msg= "";
            $stmt = $db->prepare("select * from customeruser where `password`=?");
            $stmt->execute([$user_password]);
            $checkPass = $stmt->fetch();
            if(!$checkPass){
                $msg = "Password is Wrong";
            }else{
                $rememberedEmail = $user_mail;
                session_start();
                $_SESSION['rememberedEmail'] = $rememberedEmail;
                header('Location: customer.php');
            }
        }
        $rememberedEmail = $user_mail;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Log-in</title>
    <link rel="stylesheet" href="./style/custlogin.css">
    <style>
        header a{ color: white; text-decoration: none;}
    </style>
</head>
<body>
    <header><a href="./homepage.html">MARKET LAB</a></header>
    <div class="container">
        <form action="" method="post">
            <table>
                <tr>
                    <td>E-mail: </td>
                    <td><input type="text" name="e-mail" value="<?php echo htmlspecialchars($rememberedEmail); ?>"></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input type="text" name="pass"></td>
                </tr>
                <tr> 
                    <td colspan="2"><button class="button" name="btnAdd">LOG IN</button></td>
                </tr>
                <tr class="lastd">
                    <td colspan="2">Need an account? <a href="./customer_register.php">REGISTER</a></td>
                </tr>
                <tr class="error">
                    <td><p><?php echo $msg; ?></p></td>
                </tr>
            </table>
        </form>
    </div>
    <footer><img src="./images/custlogin.jpg" alt="Login" /></footer>
</body>
</html>

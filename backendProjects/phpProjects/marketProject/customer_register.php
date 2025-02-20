<?php 
require_once "db.php" ;
require './vendor/autoload.php'; // Make sure the path is correct



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


$msg = "";
$rememberedEmail = "";
$rememberedFullname = "";
$rememberedCity = "";
$rememberedDist = "";
$rememberedAddress = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["e-mail"]) && isset($_POST["pass"])) {
         extract($_POST);
        $user_mail=$_POST["e-mail"];
        $user_password=$_POST["pass"];
        $user_fullname=$_POST["fullname"];
        $user_city=$_POST["city"];
        $user_dist=$_POST["dist"];
        $user_address=$_POST["address"];

        $stmt = $db->prepare("select * from customeruser where `e-mail`=?");
        $stmt->execute([$user_mail]);
        $check = $stmt->fetch();

        if($check){
            $msg="This e-mail is already used!";
            $rememberedEmail = $user_mail;
        }else{
            if(strlen($user_password) > 12){
                $msg = "Password should be smaller than 12 character";
                $rememberedEmail = $user_mail;
                $rememberedFullname = $user_fullname;
                $rememberedCity = $user_city;
                $rememberedDist = $user_dist;
                $rememberedAddress = $user_address;
            }else if(strlen($user_mail) == 0 || strlen($user_password) == 0 || strlen($user_fullname) == 0 || strlen($user_city) == 0 || strlen($user_dist) == 0 || strlen($user_address) == 0){
                $msg = "Please fill all fields";
                $rememberedEmail = $user_mail;
                $rememberedFullname = $user_fullname;
                $rememberedCity = $user_city;
                $rememberedDist = $user_dist;
                $rememberedAddress = $user_address;
            }else{
                $ver_code = rand(100000, 999999);
                
                $mail = new PHPMailer();

                // Settings
                $mail->IsSMTP();
                $mail->CharSet = 'UTF-8';
            
                $mail->Host       = "asmtp.bilkent.edu.tr";    // SMTP server
                $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
                $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
                $mail->Username   = "ekin.bozay@ug.bilkent.edu.tr";            // SMTP account username example
                $mail->Password   = "aliekin44";            // SMTP account password example
            
                $code = fmod(time() * 257, 1000000); // couldve used a random() as well
                $mail->setFrom('ekin.bozay@ug.bilkent.edu.tr');   
                $mail->addAddress($user_mail);
            

                $mail->isHTML(true);
                $mail->Subject = 'Email Verification Code';
                $mail->Body    = "Your verification code is: $ver_code";

                $mail->send();
               
                
                $flag=1;
                $msg = "Verification email sent. Please check your inbox.";
                
                if($flag== 1){
                    session_start();
                    $data=array(
                        $user_mail,
                        $user_fullname,
                        $user_city,
                        $user_dist,
                        $user_address,
                        $user_password,
                        $ver_code,
                        
                    );
                    $_SESSION['data'] = $data;
                    header("Location: verify_user.php");
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
    <title>Customer Register</title>
    <link rel="stylesheet" href="./style/custregister.css">
</head>
<body>
    <header>MARKET LAB</header>
    <div class="container">
        <form action="" method="post">
            <table>
                <tr>
                    <td>E-mail:</td>
                    <td><input type="text" name="e-mail" value="<?php echo htmlspecialchars($rememberedEmail); ?>"></td>
                </tr>
                <tr>
                    <td>Full Name:</td>
                    <td><input type="text" name="fullname" value="<?php echo htmlspecialchars($rememberedFullname); ?>"></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><input type="text" name="city" value="<?php echo htmlspecialchars($rememberedCity); ?>"></td>
                </tr>
                <tr>
                    <td>District:</td>
                    <td><input type="text" name="dist" value="<?php echo htmlspecialchars($rememberedDist); ?>"></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><input type="text" name="address" value="<?php echo htmlspecialchars($rememberedAddress); ?>"></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="text" name="pass"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button>Register</button>
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
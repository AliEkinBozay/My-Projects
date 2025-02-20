<?php 
require_once "db.php";
require './vendor/autoload.php'; // Make sure the path is correct



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$flag=0;



$msg = "";
$rememberedEmail = "";
$rememberedMarketname = "";
$rememberedCity = "";
$rememberedDist = "";
$rememberedAddress = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["e-mail"]) && isset($_POST["pass"])) {
        extract($_POST);
        $market_mail = $_POST["e-mail"];
        $market_password = $_POST["pass"];
        $market_name = $_POST["marketname"];
        $market_city = $_POST["city"];
        $market_dist = $_POST["dist"];
        $market_address = $_POST["address"];

        $stmt = $db->prepare("SELECT * FROM marketuser WHERE `e-mail` = ?");
        $stmt->execute([$market_mail]);
        $check = $stmt->fetch();

        if($check){
            $msg = "This e-mail is already used!";
            $rememberedEmail = $market_mail;
        } else {
            if(strlen($market_password) > 12){
                $msg = "Password should be smaller than 12 characters";
                $rememberedEmail = $market_mail;
                $rememberedMarketname = $market_name;
                $rememberedCity = $market_city;
                $rememberedDist = $market_dist;
                $rememberedAddress = $market_address;
            } else {
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
                $mail->addAddress($market_mail);
            

                $mail->isHTML(true);
                $mail->Subject = 'Email Verification Code';
                $mail->Body    = "Your verification code is: $ver_code";

                $mail->send();
               
                
                $flag=1;
                $msg = "Verification email sent. Please check your inbox.";
                
                if($flag== 1){
                    session_start();
                    $data=array(
                        $market_mail,
                        $market_password,
                        $market_name,
                        $market_city,
                        $market_dist,
                        $market_address,
                        $ver_code
                    );
                    $_SESSION['data'] = $data;
                    header("Location: verify.php");
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
    <link rel="stylesheet" href="./style/marketregister.css">
</head>
<body>
    <!-- <header>MARKET LAB</header> -->


    <div class="container">
        <form action="" method="post">
            <table>
                <tr>
                    <td>E-mail:</td>
                    <td><input type="text" name="e-mail" value="<?php echo htmlspecialchars($rememberedEmail); ?>"></td>
                </tr>
                <tr>
                    <td>Market Name:</td>
                    <td><input type="text" name="marketname" value="<?php echo htmlspecialchars($rememberedMarketname); ?>"></td>
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


</body>
</html>

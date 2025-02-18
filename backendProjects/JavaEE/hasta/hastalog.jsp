<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>

<!DOCTYPE html>

<html lang="en">
<head>
    <link href="styles.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Girişi</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #ffffff;
            color: #00000f;
        }
        .container {
            margin-top: 100px;
        }
        .login-card {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            background: #e9e9e9; /* Beyaz arka plan rengi */
        }
        .login-card button {
            font-size: 18px;
        }
        .btn-primarya {
            margin-top: 20px;
        }
        .red-color {
            color: #c82333;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="login-card">
                <h3 class="text-center">Hasta Girişi</h3>
                <form action="hasta2" method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="patientTCKN">TC kimlik numaranızı girin</label>
                        <small id="tcHelp" class="form-text text-muted">11 haneli</small>
                        <input type="text" class="form-control" id="patientTCKN" name="patientTCKN" pattern="\d{11}" placeholder="TC Kimlik Numarası" required>
                        <label for="patientIsim">İsminizi girin</label>
                        <input type="text" class="form-control" id="patientIsim" name="patientName" placeholder="İsim" required>
                        <label for="patientSoyIsim">Soyisminizi girin</label>
                        <input type="text" class="form-control" id="patientSoyIsim" name="patientSurname" placeholder="Soyisim" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
                </form>
                <%

                    String msg= (String) request.getAttribute("errorMessage");

                    if(msg!=null){%>
                <div>
                    <p class="red-color"><%=msg%></p>
                </div>
                <%}%>
                <a href="index.jsp" class="btn btn-link btn-block">Geri Dön</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
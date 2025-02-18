<%--
  Created by IntelliJ IDEA.
  User: 01yen
  Date: 25.07.2024
  Time: 11:00
  To change this template use File | Settings | File Templates.
--%>
<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="styles.css" rel="stylesheet">
    <meta charset="UTF-8">
    <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>16T</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-gradient-primary {
            background: linear-gradient(45deg, #00aaff, #004d99); /* Mavi gradyan */
            color: #fff; /* Beyaz yazı rengi */
            border: none;
            border-radius: 10px;
            width: 100%;
            height: 40px;
            transition: background 0.3s ease; /* Geçiş efekti */
        }
        .btn-gradient-primary:hover {
            background: linear-gradient(45deg, #0056b3, #003d7a); /* Üzerine gelindiğinde daha koyu mavi gradyan */
        }
        .btn-gradient-secondary {
            background: linear-gradient(45deg, #6c757d, #343a40); /* Gri gradyan */
            color: #fff; /* Beyaz yazı rengi */
            border: none; /* Kenarlık yok */
            border-radius: 10px;
            width: 100%;
            height: 40px;
            transition: background 0.3s ease; /* Geçiş efekti */
        }
        .btn-gradient-secondary:hover {
            background: linear-gradient(45deg, #5a6268, #23272b); /* Üzerine gelindiğinde daha koyu gri gradyan */
        }
        body {
            background: linear-gradient(to right, #00aaff, #004d99);
            color: #fff;
        }
        .container {
            margin-top: 100px;
        }
        .selection-card {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            background: #ffffff80; /* Beyaz arka plan rengi */
        }
        .selection-card button {
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container text-center">
    <h1>16T Özel Hastanesi</h1>
    <p>Lütfen giriş türünü seçiniz:</p>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="selection-card">
                <button class="btn-gradient-secondary " onclick="location.href='doctorLogIn.jsp'">Doktor Girişi</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="selection-card">
                <button class="btn-gradient-primary" onclick="location.href='hastalog.jsp'">Hasta Girişi</button>
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
</title>
</head>
<body>

</body>
</html>
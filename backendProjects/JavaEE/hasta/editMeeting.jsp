

<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ page import="hasta.dataBasefunctions" %>
<%@ page import="java.io.PrintWriter" %>
<%@ page import="hasta.hastaDBconn" %>
<%@ page import="java.sql.*" %>
<%@ page import="java.util.List" %>
<%@ page import="java.util.ArrayList" %>
<%@ page import="hasta.appointment" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randevu Ekle</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #e9e9e9;
            color: #00000f;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            flex: 1;
        }
        .calendar-section {
            margin-top: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .modern-select {
            width: 100%;
        }
        .hidden {
            display: none;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-primary:focus, .btn-primary.focus {
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
        }
        .flatpickr-day.disabled {
            background-color: #ffdddd !important;
            color: #ff0000 !important;
        }
        .tooltip {
            position: absolute;
            background: #333;
            color: #fff;
            padding: 5px;
            border-radius: 3px;
            font-size: 12px;
            display: none;
            z-index: 10;
        }
        .margin-top {
            margin-top: 20px;
        }
        .button-container {
            text-align: center;
            padding: 10px 0;
            margin-top: 10px;
        }
        #doctor {
            width: 200px !important;
        }
    </style>
<%
   int editID = Integer.parseInt( request.getParameter("editID"));
   session.setAttribute("editID",editID);
%>

</head>
<body>


<form action="editmeeting" method="get" onsubmit="return validateForm()">
    <div class="container">
        <div class="form-section">
            <h1 class="text-center margin-top">Randevu Düzenle</h1>
            <div class="form-group">
                <label for="department">Departman</label>
                <select id="department" name="department" class="form-control modern-select"  required >
                    <option value="">Departman seçiniz</option>
                    <option value="1">Genel Cerrahi</option>
                    <option value="2">Dahiliye</option>
                    <option value="3">Kardiyoloji</option>
                    <option value="4">Nöroloji</option>
                    <option value="5">Çocuk Göğüs Hastalıkları</option>
                </select>
            </div>
            <div class="form-group hidden" id="doctor-group">
                <label for="doctor">Doktor</label>
                <select id="doctor" name="doctor" class="form-control modern-select" required>
                    <option value="">Doktor Seçin</option>

                </select>
            </div>
            <div class="form-group">
                <label for="appointment-time">Randevu Saati</label>
                <select id="appointment-time" name="appointment-time" class="form-control modern-select" required>
                    <!-- Saat seçenekleri buraya eklenecek -->
                </select>
            </div>
            <div class="form-group">
                <label for="appointment-date">Randevu Tarihi</label>
                <input type="date" id="appointment-date" name="appointment-date" class="form-control" placeholder="Tarihi seçin" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary">Randevu Düzenle</button>
                <a href="meetings.jsp" class="btn btn-link btn-block">Geri Dön</a>
            </div>
        </div>
    </div>
</form>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

<script>
    // Flatpickr Initialization
    flatpickr("#appointment-date", {
        minDate: "today",
        dateFormat: "Y-m-d",
        disable: [
            { from: "2024-08-02", to: "2024-08-02" }
        ],
        locale: "tr",
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            if (dayElem.dateObj.getFullYear() === 2024 && dayElem.dateObj.getMonth() === 7 && dayElem.dateObj.getDate() === 2) {
                dayElem.classList.add('disabled');
                dayElem.setAttribute('data-tooltip', 'Doktor müsait değil');
            }
        }
    });

    // Tooltip işlevi
    const tooltip = document.getElementById('tooltip');

    document.addEventListener('mouseover', function(event) {
        const target = event.target;
        if (target.classList.contains('flatpickr-day') && target.getAttribute('data-tooltip')) {
            tooltip.style.display = 'block';
            tooltip.textContent = target.getAttribute('data-tooltip');
            tooltip.style.left = '${event.pageX + 10}px';
            tooltip.style.top = '${event.pageY + 10}px';
        }
    });

    document.addEventListener('mouseout', function(event) {
        if (event.target.classList.contains('flatpickr-day')) {
            tooltip.style.display = 'none';
        }
    });

    // Select2 Initialization
    $('#department').select2({
        placeholder: "Departman Seçin"
    });

    $('#doctor').select2({
        placeholder: "Doktor Seçin",
        dropdownParent: $('#doctor').parent()
    });

    // Departman seçildiğinde doktorları güncelleme
    $('#department').on('change', function() {
        var department = $(this).val();


        var doctors = {
            '1': [
                { id: '1', name: 'Dr. Ferman Derin' },
                { id: '2', name: 'Dr. Cenk Akkaya' }
            ],
            '2': [
                { id: '3', name: 'Dr. Ahmet Uzun' },
                { id: '4', name: 'Dr. Alparslan Yavaş' }
            ],
            '3': [
                { id: '5', name: 'Dr. Burak Ergüden' },
                { id: '6', name: 'Dr. Feyza Güneş' }
            ],
            '4': [
                { id: '7', name: 'Dr. Ayşe Kızıl' },
                { id: '8', name: 'Dr. Ceren Dereli' }
            ],
            '5': [
                { id: '9', name: 'Dr. Alperen Kul' },
                { id: '10', name: 'Dr. Fatma Nur Deniz' }
            ]
        };


        var options = doctors[department] || [];
        var doctorSelect = $('#doctor');
        doctorSelect.empty();
        doctorSelect.append('<option value="">Doktor Seçin</option>');
        options.forEach(function(doctors) {
            doctorSelect.append('<option value="' + doctors.id + '">' + doctors.name + '</option>');
        });
        if (options.length > 0) {
            $('#doctor-group').removeClass('hidden');
        } else {
            $('#doctor-group').addClass('hidden');
        }
    });

    // Saat seçeneklerini oluşturma
    function populateTimeOptions() {
        var select = $('#appointment-time');
        var options = '';
        for (var hour = 7; hour < 23; hour++) {
            for (var minute = 0; minute < 60; minute += 60) {
                var value = ('0' + hour).slice(-2) + ':' + ('0' + minute).slice(-2);
                options += '<option value="' + value + '">' + value + '</option>';
            }
        }
        select.html(options);
        select.select2({
            placeholder: "Saat Seçin"
        });
    }

    populateTimeOptions();
</script>
</body>
</html>
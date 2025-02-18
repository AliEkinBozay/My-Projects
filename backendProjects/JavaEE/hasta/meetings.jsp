        <%@ page import="java.io.PrintWriter" %>
<%@ page import="hasta.hastaDBconn" %>
<%@ page import="java.sql.*" %>
        <%@ page import="java.util.List" %>
        <%@ page import="java.util.ArrayList" %>
        <%@ page import="hasta.appointment" %>
<%@ page contentType="text/html;charset=UTF-8" language="java" %>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randevularım</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- benim stillerim-->
    <link href="styles.css" rel="stylesheet">

    <style>
        body {
            background: #ffffff;
            color: #00000f;
        }
        .patient-info {
            margin-top: 100px;
            padding: 20px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .btn-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn-container .btn {
            width: 100%;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .appointment-list {
            margin-top: 20px;
        }


    </style>
</head>
<body>
<%
    String doctorName = null;
    Date datee = null;
    String doctorSurname = null;
    String patientSurame = "";

    Connection conn = null;
    PreparedStatement stmt = null;
    ResultSet rs = null;

    long patientTCKN1 = (long) session.getAttribute("patientTCKN1");
    String patientSurname1 = (String) session.getAttribute("patientSurname1");
    String patientName1 = (String) session.getAttribute("patientName1");


%>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <!-- Hasta Bilgileri -->
            <div class="patient-info">
                <h2>Hasta Bilgileri</h2>
                <table class="table">
                    <tbody>
                    <tr>
                        <th>TC Kimlik No</th>
                        <td><%=patientTCKN1%></td>
                    </tr>
                    <tr>
                        <th>Adı</th>
                        <td><%=patientName1%></td>
                    </tr>
                    <tr>
                        <th>Soyadı</th>
                        <td><%=patientSurname1%></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-8">
            <div class="text-center" style="margin-top: 20px">
                <h1>Randevularım</h1>
                <div class="appointment-list mx-auto">
                    <!-- Bu kısım dinamik olarak randevu bilgileri ile doldurulmalıdır -->
                    <form action="deleteMulti" method="post" onsubmit="return checkBox()">
                    <table class="table">

                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>Tarih</th>
                            <th>Saat</th>
                            <th>Doktor</th>
                            <th>Departman</th>
                            <th></th>
                            <th></th>

                        </tr>
                        </thead>


                        <tbody id="appointment-table">


                        <!-- Randevular burada listelenecek -->
                        <%
                            appointment appointmentInfo =null;
                            List<appointment> rInfo = new ArrayList<>();


                            try {
                                conn = hastaDBconn.getConnection();
                                stmt = conn.prepareStatement("SELECT *  FROM doctors d , reservations r , patients p, departments dp where r.doctorID = d.doctorID and r.patientID=p.patientID and dp.departmentID=d.departmentID and p.TCKN=?");

                                stmt.setLong(1,patientTCKN1);
                                rs = stmt.executeQuery();
                               // int patientID = 0;
                                while (rs.next()) {
                                   // patientID=rs.getInt("p.patientID");
                                    doctorName = rs.getString("d.doctorName");
                                    doctorSurname = rs.getString("d.doctorSurname");
                                    String fullName=doctorName+" "+doctorSurname;
                                    appointmentInfo=new appointment( rs.getDate("r.reservationDate"),fullName,rs.getString("dp.departmentName"),rs.getString("r.time"), rs.getInt("r.reservationID"));
                                    rInfo.add(appointmentInfo);

                                }
                               // session.setAttribute("patientID",patientID);
                            } catch (SQLException e) {
                                e.printStackTrace();
                            }
                            if(rInfo!=null&&rInfo.size()>0) {
                        for(int i=0;i<rInfo.size();i++){
                        %>
                         <tr>
                            <td class="checkbox-cell margin-left"><input type="checkbox" name="appointment" value="<%=rInfo.get(i).getId()%>"></td>
                            <td><%=i+1%></td>

                            <td><%=rInfo.get(i).getDate()%></td>
                            <td><%=rInfo.get(i).getTime()%></td>
                            <td><%=rInfo.get(i).getdName()%></td>
                            <td><%=rInfo.get(i).getDepartment()%></td>
                            <td><a href="${pageContext.request.contextPath}/deleteAppointmentPatient?deleteID=<%=rInfo.get(i).getId()%>"><i class="fas fa-trash-alt trash-icon"></i></a></td>
                            <td><a href="${pageContext.request.contextPath}/editMeeting.jsp?editID=<%=rInfo.get(i).getId()%>&patientTCKN1=<%=patientTCKN1%>">
                                 <i class="fas fa-edit edit-icon"></i>
                             </a>
                            </td>
                        <tr>
                        <%}}%>

                        </tbody>
                    </table>
                    <div class="btn-container">

                        <div class="row">
                            <div class="column col-md-6">
                                <button type="button" class="btn btn-primary add-appointment-btn" onclick=location.href="AddMeetings.jsp">Randevu Ekle</button>
                            </div>
                            <div class="column col-md-6" >
                                <button type="submit" class="btn btn-danger delete-selected-btn" onclick="checkBox()">Seçilen Randevuları İptal Et</button>

                            </div>

                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ayarlar Butonu ve Menü -->
<div class="settings-container">
    <div class="settings-btn" onclick="toggleMenu()">
        <button class="btn btn-secondary">
            <i class="fas fa-cog"></i>
        </button>
        <div id="settingsMenu" class="settings-menu">
            <div onclick="openUserSettings()">
                <i class="fas fa-user margin-right"></i>
                <span>Kullanıcı</span>
            </div>
                <a href="index.jsp">
            <div>
                <i class="fas fa-sign-out-alt margin-right"></i>
                <span>Çıkış</span>
            </div>
                </a>
        </div>
    </div>
</div>

<div id="modalContainer"></div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- FontAwesome for Icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script>
    function editAppointment() {
            // Verileri form üzerinden gönderecek şekilde ayarlıyoruz
            var form = document.createElement('form');
            form.method = 'POST';
            form.setAttribute()
            form.action = 'editmeeting';
    }

    function toggleMenu() {
        var menu = document.getElementById("settingsMenu");
        if (menu.style.display === "block") {
            menu.style.display = "none";
        } else {
            menu.style.display = "block";
        }
    }

    function confirmDelete(element) {
        if (confirm("Bu randevuyu silmek istediğinizden emin misiniz?")) {
            var row = element.closest("tr");
            row.remove();
        }
    }

    function deleteSelected() {
        if (confirm("Seçilen randevuları silmek istediğinizden emin misiniz?")) {
            var checkboxes = document.querySelectorAll('input[name="appointment"]:checked');
            checkboxes.forEach(function(checkbox) {
                var row = checkbox.closest("tr");
                row.remove();
            });
        }
    }

    function openUserSettings() {
        fetch('patientUser.jsp')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ağ hatası: ' + response.statusText);
                }
                return response.text();
            })
            .then(data => {
                document.getElementById('modalContainer').innerHTML = data;
                $('#userSettingsModal').modal('show');
            })
            .catch(error => {
                console.error('Hata:', error);
            });
    }

    function checkBox() {
        // Seçili olan checkbox'ları al
        var selectedCheckboxes = document.querySelectorAll('input[name="appointment"]:checked');

        // Eğer hiç checkbox seçili değilse
        if (selectedCheckboxes.length < 1) {
            // Uyarı mesajı göster
            alert("Lütfen düzenlemek için en az bir randevu seçin.");
            // Formun gönderilmesini engelle
            return false;
        }
        // Seçili checkbox varsa, formu göndermeye izin ver
        return true;
    }

</script>
</body>
</html>
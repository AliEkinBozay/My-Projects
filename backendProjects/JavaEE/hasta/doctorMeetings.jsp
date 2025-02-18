<%@ page import="java.util.Date" %>
<%@ page import="java.sql.Connection" %>
<%@ page import="java.sql.PreparedStatement" %>
<%@ page import="java.sql.ResultSet" %>
<%@ page import="java.io.PrintWriter" %>
<%@ page import="doctors.DoctorsAppointments" %>
<%@ page import="java.util.ArrayList" %>
<%@ page import="doctors.dbConnection" %>
<%@ page import="java.util.List" %>
<%@ page import="java.sql.SQLException" %>


<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doktor Randevularım</title>
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
        .doctor-info {
            margin-top: 80px;
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 8px;
            width: 100%;
            height: auto;
        }
        .doctor-info table {
            width: 100%;
        }
        .appointment-list {
            margin-top: 20px;
        }
        .btn-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .btn-container .btn {
            flex: 1;
            margin: 0 5px;
        }
    </style>
    <%
        String doctorName = "";
        long doctorTCKN = 0;
        String departmentName = "";
        Date datee = null;
        String patientName = "";
        String patientSurname = "";
        String doctorSurname = null;

        Connection conn = null;
        PreparedStatement stmt = null;
        ResultSet rs = null;
        ResultSet rs2 = null;

        int doctorID = Integer.parseInt(session.getAttribute("doctorID").toString());
        List<DoctorsAppointments> rInfo = new ArrayList<>();

        try {
            conn = dbConnection.getConnection();
            stmt = conn.prepareStatement(
                    "SELECT p.patientName, p.patientSurname, r.reservationDate, r.reservationID, " +
                            "d.doctorName, d.doctorSurname, d.TCKN, dp.departmentName, r.time " +
                            "FROM doctors d, reservations r, patients p, departments dp " +
                            "WHERE d.departmentID = dp.departmentID " +
                            "AND r.doctorID = d.doctorID " +
                            "AND r.patientID = p.patientID " +
                            "AND d.doctorID = ?"
            );
            stmt.setInt(1, doctorID);
            rs = stmt.executeQuery();

            if (rs.next()) {
                do {
                    String fullName;
                    patientName = rs.getString("patientName");
                    patientSurname = rs.getString("patientSurname");
                    fullName = patientName + " " + patientSurname;
                    datee = rs.getDate("reservationDate");
                    doctorSurname = rs.getString("doctorSurname");
                    doctorName = rs.getString("doctorName");
                    doctorTCKN = rs.getLong("TCKN");
                    departmentName = rs.getString("departmentName");
                    session.setAttribute("TCKN", doctorTCKN);

                    DoctorsAppointments reservationInfo = new DoctorsAppointments(
                            datee, fullName, rs.getInt("reservationID"), rs.getString("time")
                    );
                    rInfo.add(reservationInfo);
                } while (rs.next());
            } else {
                stmt = conn.prepareStatement(
                        "SELECT doctorName, doctorSurname, TCKN, departmentName " +
                                "FROM doctors d, departments dp " +
                                "WHERE d.departmentID = dp.departmentID " +
                                "AND d.doctorID = ?"
                );
                stmt.setInt(1, doctorID);
                rs2 = stmt.executeQuery();

                while (rs2.next()) {
                    doctorSurname = rs2.getString("doctorSurname");
                    doctorName = rs2.getString("doctorName");
                    doctorTCKN = rs2.getLong("TCKN");
                    departmentName = rs2.getString("departmentName");
                    session.setAttribute("TCKN", doctorTCKN);
                    session.setAttribute("doctorID", doctorID);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    %>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <!-- Doktor Bilgileri -->
            <div class="doctor-info">
                <h2>Doktor Bilgileri</h2>
                <table class="table">
                    <tbody>
                    <tr>
                        <th>Ad</th>
                        <td><%=doctorName+" "+doctorSurname%></td>
                    </tr>
                    <tr>
                        <th>TC Kimlik No</th>
                        <td><%=doctorTCKN%></td>
                    </tr>
                    <tr>
                        <th>Departman</th>
                        <td><%=departmentName%></td>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <button type="button" class="btn btn-secondary" id="availabilityBtn" onclick="location.href='docAvailable.jsp'">Müsait Olmadığınız Günleri Belirleyin</button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="text-center">
                <h1>Randevularım</h1>
                <div class="appointment-list mx-auto">
                    <form action="deleteAppointments" method="post">
                        <!-- Bu kısım dinamik olarak randevu bilgileri ile doldurulmalıdır -->
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Tarih</th>
                                <th>Saat</th>
                                <th>Hastanın Adı</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="appointment-table">
                            <!-- Randevular burada listelenecek -->
                            <%for(int i = 0; i < rInfo.size(); i++) { %>
                            <tr>
                                <td class="checkbox-cell margin-left"><input type="checkbox" name="appointmentIDs" value="<%=rInfo.get(i).getReservationID()%>"></td>
                                <td><%=i+1%></td>
                                <td><%=rInfo.get(i).getDate()%></td>
                                <td><%=rInfo.get(i).getTime()%></td>
                                <td><%=rInfo.get(i).getPatientName()%></td>
                                <td><a href="${pageContext.request.contextPath}/deleteAppointment?deleteID=<%=rInfo.get(i).getReservationID()%>&doctorID=<%=doctorID%>"><i class="fas fa-trash-alt trash-icon"></i></a></td>
                            </tr>
                            <% } %>
                            </tbody>
                        </table>
                        <div class="btn-container">
                            <button type="submit" class="btn btn-danger delete-selected-btn">Seçilen Randevuları İptal Et</button>
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
            <div onclick="confirmLogout()">
                <i class="fas fa-sign-out-alt margin-right"></i>
                <span>Çıkış</span>
            </div>
        </div>
    </div>
</div>

<div id="modalContainer"></div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- FontAwesome for Icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script>
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
        fetch('docUser.jsp')
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


    function confirmLogout() {
        if (confirm("Çıkış yapmak istediğinizden emin misiniz?")) {
            // Redirect to the login page
            window.location.href = 'index.jsp';
        }
    }

</script>
</body>
</html>

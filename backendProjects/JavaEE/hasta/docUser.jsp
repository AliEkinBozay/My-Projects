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
<%@ page import="com.mysql.cj.Session" %>
<!-- Modal -->
<%
    long doctorTCKN = (Long) session.getAttribute("TCKN");
%>
<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<div class="modal fade" id="userSettingsModal" tabindex="-1" role="dialog" aria-labelledby="userSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title header-color-black" id="userSettingsModalLabel">Kullanıcı Bilgilerini Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="updateInfo" method="post">
                    <div class="form-group">
                        <label class="header-color-black" for="tcNo">TC Kimlik Numarası</label>
                        <input type="text" class="form-control" id="tcNo" name="tcNo" pattern="\d{11}" value="<%=doctorTCKN%>"  readonly>
                    </div>
                    <div class="form-group">
                        <label class="header-color-black" for="firstName">İsim</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="İsim" required>
                    </div>
                    <div class="form-group">
                        <label class="header-color-black" for="lastName">Soyisim</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Soyisim" required>
                    </div>



                    <button type="submit" class="btn btn-primary close" >Güncelle</button>


                </form>
            </div>
        </div>
    </div>
</div>

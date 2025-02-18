<%--
  Created by IntelliJ IDEA.
  User: 01yen
  Date: 1.08.2024
  Time: 10:57
  To change this template use File | Settings | File Templates.
--%>
<%@ page import="java.util.Date" %>
<%@ page import="java.sql.Connection" %>
<%@ page import="java.sql.PreparedStatement" %>
<%@ page import="java.sql.ResultSet" %>
<%@ page import="java.io.PrintWriter" %>
<%@ page import="hasta.appointment" %>
<%@ page import="java.util.ArrayList" %>
<%@ page import="hasta.hastaDBconn" %>
<%@ page import="java.util.List" %>
<%@ page import="java.sql.SQLException" %>
<%@ page import="com.mysql.cj.Session" %>
<!-- Modal -->
<%
 long patientTCKN1 = (Long) session.getAttribute("patientTCKN1");
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
                <form action="patientuserupdate" method="post">
                    <div class="form-group">
                        <label class="header-color-black" for="tcNo">TC Kimlik Numarası</label>
                        <input type="text" class="form-control" id="tcNo" name="tcNo" pattern="\d{11}" value="<%=patientTCKN1%>"  readonly>
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
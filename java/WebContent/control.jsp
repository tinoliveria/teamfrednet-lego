<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Controls</title>
</head>
<body>
Comport: <%= request.getAttribute("server.port") %>
<form action="" method="post">
<table>
<tr>
<td>
command:
</td>
<td>
<input type="text" name="cmd" />
</td>
</tr>
<tr>
<td colspan="2">
<input type="submit" />
</td>
</tr>
</table>
</form>
</body>
</html>
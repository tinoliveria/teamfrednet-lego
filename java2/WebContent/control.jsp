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
<p>Motor A is left and Motor C is right</p>
<p>_motor_id_ port where the motor is connect to(A,B,C,All) or multi-id(AvC,BvC,CvA)<br /> 
_speed_ value in the range -100 to 100 (degree/s) <b>Note</b>: When setting <i>All</i> speed you will set <i>A</i> and <i>A</i> will be use if you call <i>All</i>. <br /> 
_degrees_ value in the range 0-100000 degrees(0 = forever)
</p> 
<pre> 
cmd motor _motor_id_ on
cmd motor _motor_id_ off
cmd motor speed _motor_id_ _speed_
cmd motor degrees _motor_id_ _degrees_ 
</pre> 
</body>
</html>
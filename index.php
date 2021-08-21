<?php
	session_start();
	if (isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==True))
	{
		header('Location: gra.php');
		exit();
	}
?>



<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>gra przegladarkowa - test logowania oraz rejstracji</title>
</head>

<body>
	
	
	<form action="zaloguj.php" method="POST">
	
		
		<b>Login:</b> <br/> <input type="text" name="login"/><br/>	
		<b>Hasło:</b> <br/> <input type="password" name="haslo"/><br/><br/>
		<input type="submit" value="zaloguj sie!"/> 	
	
	</form>
		
	
<?php
	if(isset($_SESSION['blad']))	
	
	echo  $_SESSION['blad'];
?>
	<br />
	<a href ="Rejstracja.php" >[Rejstracja]</a><br/>

</body>
</html>
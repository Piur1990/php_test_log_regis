<?php
	session_start();
	
	if (!isset($_SESSION['udanarejestracja'])) 
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['udanarejestracja']);
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
		
	<center><h3>Dziekujemy za rejstracje w serwisie!^^</h3><center>
		
	<a href ="index.php" >[zaloguj się na swoje konto!]</a><br/>

</body>
</html>
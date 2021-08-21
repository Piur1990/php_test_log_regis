<?php
	session_start();
	
	if (isset($_POST['email']))
	{
		//udana walidacja
		$wszystko_OK = True;
		//srawdzenia nicku
		$nick = $_POST['nick'];
		
		//sprawdzenie dlugosci nicka
		 if ((strlen($nick)<3) || (strlen($nick)>20))
		 {
			 $wszystko_OK = False;
			 $_SESSION['e_nick'] = "Nickname musi posiadac od 3 do 20 znaków!";
						 
		 }
		 
		 if (ctype_alnum($nick)==False)
		 {
			 $wszystko_OK = False;
			 $_SESSION['e_nick'] = "Nick może składać się tylko z liter i cyfr bez polskich znaków!";
		 }
		 
		 //Srawdz poprawność email
		 	 
		 $email = $_POST['email'];
		 $emailB = filter_var($email,FILTER_SANITIZE_EMAIL);
		 
		 if ((filter_var($emailB,FILTER_VALIDATE_EMAIL)==False) || ($emailB!=$email))
		 {
			 $wszystko_OK = False;
			 $_SESSION['e_email'] = "Podaj poprawny adres email!";
		 }
			 
		 $haslo1 = $_POST['haslo1'];
		 $haslo2 = $_POST['haslo2'];
		 
		 if  ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		 {
			$wszystko_OK = False;
			$_SESSION['e_haslo'] = "Hasło powinno zawierać od 8 do 20 znaków!";
			 
		 }
		 
		 if ($haslo1!=$haslo2)
		 {
			$wszystko_OK = False;
			$_SESSION['e_haslo'] = "Hasła powinny być takie same!";
		 }
		 
		$haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT);
		 
		
		if (!isset($_POST['regulamin']))
		{
			$wszystko_OK = False;
			$_SESSION['e_regulamin'] = "Zaznacz proszę regulamin!";
		}
		 
		 $sekret = '6Lf_dagaAAAAAHfAO8iLF_GLb3JgrIS_9RGWxc3n';
		 
		 $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		 
		 $odpowiedz = json_decode($sprawdz);
		 
		 if ($odpowiedz->success==False)
		 {
			$wszystko_OK = False;
			$_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem!";
		 }
		 
		 //powtórzenia 
		 
		 require_once "connect.php";
		 mysqli_report(MYSQLI_REPORT_STRICT);
		 
		 try
		 {
			 $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			 
			 if ($polaczenie->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					//czy email juz istnieje
					$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
					
					if (!$rezultat) throw new Exception($polaczenie->error);
					
					$ile_takich_mail = $rezultat->num_rows;
					
					if($ile_takich_mail>0)
					{
						$wszystko_OK = False;
						$_SESSION['e_email'] = "Istnieje juz konto przypisane do tego adresu email!";
					}
					
					//czy nick juz istnieje
					$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
					
					if (!$rezultat) throw new Exception($polaczenie->error);
					
					$ile_takich_nickow = $rezultat->num_rows;
					
					if($ile_takich_nickow>0)
					{
						$wszystko_OK = False;
						$_SESSION['e_nick'] = "Istnieje juz gracz o takim nicku!";
					}
					
					if ($wszystko_OK == True)
					{
						//wszystko ok dodajemy gracza
						if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email', 100, 100, 100, 14)"))	
						{
							$_SESSION['udanarejestracja'] = True;
							header('Location : witamy.php');
						}
						else
						{
							throw new Exception($polaczenie->error);
						}
					}
					
					$polaczenie->close();
					
				}
	
		 }
		 catch(Exception $e)
		 {
			 echo '<span style="color:red;">bład servera</span>';
			 echo '<br />Informacja developerska:'.$e;
		 }
			
	}
	
	
?>



<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Osadnicy - załóż konto!</title>
	<script src="https://www.google.com/recaptcha/api.js"></script>
	
	<style>
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>

	 
</head>

<body>
	
	<form method="post">
	
	<b>Nickname:</b> <br /> <input type="text" name='nick' /><br />
	<?php
		if (isset($_SESSION['e_nick']))
		{
			echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
			unset($_SESSION['e_nick']);
		}
	?>
	<b>E-mail: </b><br /> <input type="text" name='email' /><br />
	<?php
		if (isset($_SESSION['e_email']))
		{
			echo '<div class="error">'.$_SESSION['e_email'].'</div>';
			unset($_SESSION['e_email']);
		}
		?>
	<b>Hasło: </b><br /> <input type="password" name='haslo1' /><br />
	<?php
		if (isset($_SESSION['e_haslo']))
		{
			echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
			unset($_SESSION['e_haslo']);
		}
		?>
	<b>Powtórz Hasło</b><br /> <input type="password" name='haslo2' /><br />
	
	
		
	<label>
	<input type="checkbox" name='regulamin' /> <b>Akceptuję regulamin!</b>
	</label>
	<?php
		if (isset($_SESSION['e_regulamin']))
		{
			echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
			unset($_SESSION['e_regulamin']);
		}
	?>
	<div class="g-recaptcha" data-sitekey="6Lf_dagaAAAAAAG6de8_fjnNzwOcFgU0637q4z0n" ></div>
     <?php
		if (isset($_SESSION['e_bot']))
		{
			echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
			unset($_SESSION['e_bot']);
		}
		?>
	 <br />
      
	 <input type="submit" value="Zarejstruj się" />
	</form>

</body>
</html>
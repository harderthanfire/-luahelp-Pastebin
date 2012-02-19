<?php

include "includes/common.php";

if($_POST['user_token'] == $_SESSION['user_token']) 
{

	$sname = $_POST["sname"]; // script name
	$nname = $_POST["nname"]; // poster name
	$code = $_POST["code"]; // pasted code **required**
	$lang = $_POST["lang"]; //language to highlight it as
	$alter = $_POST["alter"]; // is it an alteration
	$shemail = $_POST["shemail"]; // email of user

	if(isset($_POST['website']) && !$_POST['website'] == '')
	{
		die('You are a spam bot.');
	}
 
	if(isset($_POST['email']) && !$_POST['email'] == '')
	{
		die('You are a spam bot.');
	}
	
	if( !empty( $code ) && $code != "" )
	{

		if( empty( $lang ) )
		{
			$lang = "text";
		}               
			
		$paste = array();
		$paste[ "sname" ] = $sname;
		$paste[ "nname" ] = $nname;
		$paste[ "code" ] = $code;
		$paste[ "language" ] = $lang;
		$paste[ "deleteafter" ] = (int)$_POST["keepfor"];
		$paste[ "shemail" ] = $shemail;		

		if( !empty( $alter ) )
		{	
			$paste[ "alter" ] = (int)$alter;
		}
		
		$db->SimpleInsert( "snippets", $paste );
		$id = $db->InsertID();
		unset($_SESSION['user_token']);
  
		if( $id != false )
		{
			unset($_SESSION['user_token']); 
			
			if( !$_POST["ajax"] ) header( "Location: $id\r\n" );
			echo $id;
			
			return;
		}
	}
}
 else 
{
	unset($_SESSION['user_token']);
	die( "Your request has expired, please go back and resubmit! " );
}
	
include "includes/page/header.php";
?>

<h2>Failed to Paste</h2>
<p>Your paste was not entered. This could be because you didn't enter any code, or could be to do with a database error. Please <a href="javascript:history.back(1)">go back</a> and try again.</p>

<?php include "includes/page/footer.php"; ?>

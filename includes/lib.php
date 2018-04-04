<php
include 'config.php';

function DbQuery($query)
{
   $db = mysql_connect($dbhost, $dbuser, $dbpass) or return die ('Error connecting to mysql');
   mysql_select_db($dbname, $db);
   $SQL = mysqli_real_escape_string($query);
   $result = mysql_query($query);
   if (!$result) 
   {
   return die('Invalid query: ' . mysql_error());
   }
   mysql_close($db);
   return $result;
}

function GetPaste($pasteno)
{
return DbQuery('SELECT * FROM pastes WHERE pasteid = ' . $pasteno);  
}

function SetPaste($paste, $user, $desc)
{
DbQuery("INSERT INTO pastes ( `paste` , `user` , 'desc' , 'time' ) VALUES ( ".$paste.", ".$user.", ".$desc.", ".time()." )");
}

function TotalPastes()
{

}

function RecentPastes()
{

}

?>

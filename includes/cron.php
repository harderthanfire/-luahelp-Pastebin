<?php

chdir( "../" ); // change cwdir to pastebin root

include "includes/common.php";

// run database query to delete old pastes
$db->Query( "DELETE FROM snippets WHERE deleteafter > 0 AND NOW() > DATE_ADD(`time`, INTERVAL `deleteafter` HOUR)");

// check to see if we have any new languages to add to the database
$db->Query( "CREATE TABLE IF NOT EXISTS `languages` (
  `lang_id` varchar(25) NOT NULL,
  `friendly_name` text NOT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;" ); // remove on next pull

$languages = $db->QueryArrayIndexed( "SELECT * FROM languages" );
$dir = opendir( getcwd() . "/includes/geshi/geshi");

while( $file = readdir($dir) )
{
	if ( $file == '..' || $file == '.' || !stristr($file, '.') || $file == 'css-gen.cfg' || $file == ".svn" ) continue;
		
	$lang = substr($file, 0,  strpos($file, '.'));
	$lang_file = file_get_contents( dirname(__FILE__) . "/geshi/geshi/$file" );

	$matches = array();
	preg_match( '/LANG_NAME\'.*?=>.*?\'(.+)\',/', $lang_file, $matches );
	
	$friendly_name = stripslashes( $matches[1] ); 
	
	if( !array_key_exists( $lang, $languages ) )
	{
		$db->Query( 'INSERT INTO languages (`lang_id`, `friendly_name`) VALUES(\'' . $lang . "', '" . $db->SanitizeString( $friendly_name ) . '\')' );
		echo "Inserted $lang / $friendly_name\r\n";
	}
	elseif( $languages[$lang] && $languages[$lang]['friendly_name'] != $friendly_name )
	{
		$db->Query( 'UPDATE languages SET `friendly_name` = \'' . $db->SanitizeString( $friendly_name ) . '\' WHERE `lang_id` = \'' . $lang . '\'' );
		echo "Updated record for $lang / $friendly_name";
	}
}

?>
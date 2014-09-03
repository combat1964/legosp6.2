<?php

//фаил перекодирован в utf-8
//connect to the database

include(dirname(__FILE__)."/connect.inc.php");
include(dirname(__FILE__)."/../includes/database/mysql.php");
include("general.inc.php");
include("appearence.inc.php");
include("functions.php");
include("category_functions.php");
include("language_list.php");
include("product.inc.php");
include("shipping.inc.php");
include("votes.inc.php");
include("company.inc.php");
include("redirect.inc.php");
require (dirname(__FILE__).'/../core/class/lego.php');

db_connect(DB_HOST,DB_USER,DB_PASS);


session_start();
currency();
$legosp = new lego();
//current language session variable
if (!isset($_SESSION["current_language"]) ||
    $_SESSION["current_language"] < 0 || $_SESSION["current_language"] > count($lang_list))
    $_SESSION["current_language"] = 0; //set default language
//include a language file
if (isset($lang_list[$_SESSION["current_language"]]) && file_exists(dirname(__FILE__)."/../languages/".$lang_list[$_SESSION["current_language"]]->filename))
    include(dirname(__FILE__)."/../languages/".$lang_list[$_SESSION["current_language"]]->filename); //include current language file
else
{
    die("<b style="color: red;">ERROR: Couldn't find language file!</b>");
}

?>
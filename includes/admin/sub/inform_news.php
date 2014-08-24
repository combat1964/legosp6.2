<?php
/*****************************************************************************
 *                                                                           *
 * Shop-Script FREE                                                          *
 * Copyright (c) 2009 Supme. All rights reserved.                     *
 *                                                                           *
 ****************************************************************************/

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	echo 'Acces denied to this module';
	die;
}

	if (!strcmp($sub, "news"))
	{

		if (isset($_GET["del_news"])) // delete page
		{
			//old picture
			$q = db_query("SELECT Pict FROM ".NEWS_TABLE." WHERE id='".$_GET["del_news"]."'") or die (db_error());
			$row = db_fetch_row($q);

			//remove old picture...
			if (($row[0]) && file_exists("./products_pictures/$row[0]"))
			unlink("./products_pictures/$row[0]");

			$q = db_query("DELETE FROM ".NEWS_TABLE." WHERE id='".$_GET["del_news"]."'") or die (db_error());

			header("Location: admin.php?dpt=inform&sub=news");
		}

		if (isset($_GET["news"])) // edit page
		{
			$q = db_query("SELECT id, title, text, date, brief, Pict, meta_title, meta_keywords, meta_desc, hurl, canonical FROM ".NEWS_TABLE." WHERE id='".$_GET["news"]."'") or die (db_error());
			$p = db_fetch_row($q);

			$smarty->assign("new_news", $_GET["news"]);
			$smarty->assign("newsedit", $p);
			$smarty->assign("edit_date_y", substr($p[3], 0, 4));
			$smarty->assign("edit_date_m", substr($p[3], 5, 2));
			$smarty->assign("edit_date_d", substr($p[3], 8, 2));
		}
		else
		{
			$smarty->assign("new_news", "0");
			$smarty->assign("edit_date_d", date("j")); $smarty->assign("edit_date_m", date("n")); $smarty->assign("edit_date_y", date("Y"));
		}

		if (isset($_POST["add_news"]))
		{
            $_POST['news']['date'] = $_POST["date_y"]."-".$_POST["date_m"]."-".$_POST["date_d"];
			
			
			  

			if ($_POST['news']["hurl"]=="") {$new_hurl=to_url($_POST["news"]["title"])."/";} else {$new_hurl=$_POST['news']['hurl'];}

			if ($_POST["add_news"])
			{

				//old picture
				$q = db_query("SELECT Pict FROM ".NEWS_TABLE." WHERE id='".$_POST["add_news"]."'") or die (db_error());
				$row = db_fetch_row($q);
				//save ubdate
                update_field(NEWS_TABLE,$_POST['news'],'id='.(int)$_POST["add_news"]);


				if (isset($_FILES['news_pic']) && $_FILES['news_pic']['name'] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp|png)$/i', $_FILES['news_pic']['name'])) //upload
					{

					$news_pic = file_url($_FILES['news_pic']['name']);

					if (!move_uploaded_file($_FILES['news_pic']['tmp_name'], './products_pictures/'.$news_pic)) //failed to upload
						{
						$smarty->assign("error", "loading error");
						}		
					else //update db
						{
						db_query("UPDATE ".NEWS_TABLE." SET Pict='".$news_pic."' WHERE id='".$_POST["add_news"]."'") or die (db_error());
						SetRightsToUploadedFile( "./products_pictures/".$news_pic );
						}

					//remove old picture...
					if ($row[0] && strcmp($row[0], $news_pic) && file_exists("./products_pictures/$row[0]"))
					unlink("./products_pictures/$row[0]");

					}

			}
			else //save new page
			{
			   if ($_POST["news"]["title"])
			   {
                $_POST['news']['enable']=1;
                add_field(NEWS_TABLE,$_POST['news']);
				$pid = db_insert_id();
				if (isset($_FILES['news_pic']) && $_FILES['news_pic']['name'] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp)$/i', $_FILES['news_pic']['name'])) //upload
					{
					$news_pic = file_url($_FILES['news_pic']['name']);
					$r = move_uploaded_file($_FILES['news_pic']['tmp_name'], './products_pictures/'.$news_pic);

					SetRightsToUploadedFile('./products_pictures/'.$news_pic);
					}
				$_POST["add_news"] = $pid;
			   }
			}

			// on/off

			$q = db_query("SELECT id FROM ".NEWS_TABLE." ") or die (db_error());
			$i=0;
			while ($row=mysql_fetch_array($q))
			  {
				if (!isset($_POST["news_".$row[0]])) $news_enable = 0;
				if (!strcmp($_POST["news_".$row[0]], "on")) $news_enable = 1;
				else $news_enable = 0;

				if (!isset($pid) || $row[0] <> $pid) db_query("UPDATE ".NEWS_TABLE." SET enable='".$news_enable."' WHERE id='".$row[0]."'");
				$i++;
			  }

			header("Location: admin.php?dpt=inform&sub=news&news=".$_POST["add_news"]);
		}

		if (isset($_GET["picture_remove"]) && isset($_GET["news"]) && ($_GET["news"])) // remove picture
		{
			$q = db_query("SELECT Pict FROM ".NEWS_TABLE." WHERE id='".$_GET["news"]."'") or die (db_error());
			$row = db_fetch_row($q);

			if (file_exists("./products_pictures/$row[0]"))	unlink("./products_pictures/$row[0]");
			
			db_query("UPDATE ".NEWS_TABLE." SET Pict='' WHERE id='".$_GET["news"]."'") or die (db_error());

			header("Location: admin.php?dpt=inform&sub=news&news=".$_GET["news"]);
		}

		$news_list=array();

        $news_list = db_arAll("SELECT id, title, enable FROM ".NEWS_TABLE." order by id desc ");
		$smarty->assign("news_list", $news_list);
		$smarty->assign("news_list_count", count($news_list));

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "inform_news.tpl.html");
	}

?>
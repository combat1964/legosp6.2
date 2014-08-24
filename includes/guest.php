<?php
/*****************************************************************************
*                                                                           *
* Lego SP - lego.shop-script.org                                            *
* Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  *
*                                                                           *
****************************************************************************/
    
    if (isset($_GET["guest"]))
    {
     // Смайлики ;-)    
    $s2='<img src="images/smile/biggrin.gif" />'; 
    $s1='<img src="images/smile/smile.gif" />';
    $s3='<img src="images/smile/razz.gif" />';
    $s4='<img src="images/smile/cool.gif" />';
    $s5='<img src="images/smile/mad.gif" />';
    $s6='<img src="images/smile/redface.gif" />';
    $s7='<img src="images/smile/wink.gif" />';
    $s8='<img src="images/smile/rolleyes.gif" />';
    $s9='<img src="images/smile/confused.gif" />';
    $s10='<img src="images/smile/eek.gif" />';
    $s11='<img src="images/smile/cry.gif" />';
    
      $guest_smile="
        <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' :-))');\">$s1</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' :-)');\">$s2</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' :-P');\">$s3</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' 8-)');\">$s4</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' :-(');\">$s5</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' :-O');\">$s6</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' ;-)');\">$s7</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' :roll:');\">$s8</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' :rf:');\">$s9</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' 8-(');\">$s10</a>
      <a href=\"javascript:%20x()\" onclick=\"DoSmilie(' `-(');\">$s11</a>";
          
    $smarty->assign("guest_smile", $guest_smile);  
      
    
     //фильтр для форм 
   $search_html = array ("'<script[^>]*?>.*?</script>'si",  // Вырезает javaScript
                   "'<[\/\!]*?[^<>]*?>'si",           // Вырезает HTML-теги
                   "'([\r\n])[\s]+'",                 // Вырезает пробельные символы
                   "'&(quot|#34);'i",                 // Заменяет HTML-сущности
                   "'&(amp|#38);'i",
                   "'&(lt|#60);'i",
                   "'&(gt|#62);'i",
                   "'&(nbsp|#160);'i",
                   "'&(iexcl|#161);'i",
                   "'&(cent|#162);'i",
                   "'&(pound|#163);'i",
                   "'&(copy|#169);'i",
                   "'&#(\d+);'e");        // интерпретировать как php-код

   $replace_update = array ("",
                    "",
                    "\\1",
                    "\"",
                    "&",
                    "<",
                    ">",
                    " ",
                    chr(161),
                    chr(162),
                    chr(163),
                    chr(169),
                    "chr(\\1)");
  
   $guest_messages = array();
     
     $guest_error = '';
    
        
    
       if ($_POST['do_new_messages']) { // добавляем новое сообщение
         if($_POST['kcaptcha'] == $_SESSION['captcha_keystring']) { 
           if ($_POST['new_messages']!='') { 
             if($_POST['name'] !='' ) {
                //заносим данные в базу
              $q_add_new_tema = mysql_query("INSERT INTO SS_guest VALUES(NULL, '".mysql_real_escape_string(date("d.m.Y  H:i:s"))."','".mysql_real_escape_string($_POST['name'])."','".mysql_real_escape_string($_POST['email'])."','".mysql_real_escape_string(wordwrap(preg_replace($search_html, $replace_update, $_POST['new_messages']),90,' ',1))."')");
              if ($q_add_new_tema) {$guest_error = 'Сообщение добавлено!';}
                
              }  else {$guest_error = 'Вы не ввели имя!';}  
            } else {$guest_error = 'Вы не ввели сообщение!';} 
         } else {$guest_error = 'Неверный код с картинки!';}
       }
        
    
       define('col_write_messages', 10); // количество сообщений на странице    
       
       $q_col_write_message=@mysql_query("SELECT count(*) FROM SS_guest"); 
     $r_col_write_message=@mysql_fetch_array($q_col_write_message); // количество сообщений всего
       
       $links_stran='';
       if ($r_col_write_message['count(*)'] == 0) {$guest_error='В гостевой нет сообщений';} else {            
               $col_stran = $r_col_write_message['count(*)']/col_write_messages; //вычисляем соотношение                   
               $i=0;
               while ($i != ceil($col_stran)) {                        
                    $ii=$i+1;                   
                    $links_stran.='<a href="index.php?guest&links='.intval($i).'"  style="font-size:16px;font-weight:bold">'.$ii.'</a> ';
                    $i++;                     
               }
      } 
       $smarty->assign("links_stran", $links_stran);
       
       
       if (!isset($_GET['links'])) {$num_stran = 0;} else {$num_stran = $_GET['links']*col_write_messages;}
       
       $q_messages = @mysql_query("SELECT *  FROM SS_guest ORDER BY date DESC LIMIT ".intval($num_stran).",".intval(col_write_messages)." "); 
              
       
       $i=0;  
       while ($r_messages = db_fetch_row($q_messages)){
        //выборка сообщений        
        $messages_plus_smile = $r_messages[4];
        // заменяем текстовые смайлики на графические если разрешено
        $messages_plus_smile=str_replace(":-))",$s1,$messages_plus_smile);
        $messages_plus_smile=str_replace(":-)",$s2,$messages_plus_smile);
        $messages_plus_smile=str_replace(":-P",$s3,$messages_plus_smile);
        $messages_plus_smile=str_replace("8-)",$s4,$messages_plus_smile);
        $messages_plus_smile=str_replace(":-(",$s5,$messages_plus_smile);
        $messages_plus_smile=str_replace(":-O",$s6,$messages_plus_smile);
        $messages_plus_smile=str_replace(";-)",$s7,$messages_plus_smile);
        $messages_plus_smile=str_replace(":roll:",$s8,$messages_plus_smile);
        $messages_plus_smile=str_replace(":rf:",$s9,$messages_plus_smile);
        $messages_plus_smile=str_replace("8-(",$s10,$messages_plus_smile);
        $messages_plus_smile=str_replace("`-(",$s11,$messages_plus_smile);  
        
           $guest_messages[$i][0] = $r_messages[0];
           $guest_messages[$i][1] = $r_messages[1];        
           $guest_messages[$i][2] = $r_messages[2];
           $guest_messages[$i][3] = $r_messages[3];
           $guest_messages[$i][4] = $messages_plus_smile;           
           if ($i%2 === 0) {$guest_messages[$i][6] = 0;} else {$guest_messages[$i][6] = 1;}                   
           $i++;
        //конец выборки сообщений
     }   
    
    $smarty->assign("guest_error", $guest_error); 
      $smarty->assign("guest_messages", $guest_messages);
      $smarty->assign("main_content_template", "guest.tpl.html");
    }
?>
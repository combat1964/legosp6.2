<?php
$title_last='¬ы смотрели';
if (DB_CHARSET == 'utf8') $title_last=win2utf($title_last);
define('LAST_VIEW_P', $title_last); // лимит выводимых позиций
define('LAST_ITEMS_LIMIT', 2); // лимит выводимых позиций


// ƒобавить продукт в сессию
if (isset($productID) && $productID>=0 && !isset($_POST["review"]))
{
        if(!isset($_SESSION["last_items_view"]) || !is_array($_SESSION["last_items_view"]))
        {
            $_SESSION["last_items_view"]=  array();
        }
        if (count($_SESSION["last_items_view"])>LAST_ITEMS_LIMIT){
            unset($_SESSION["last_items_view"][key($_SESSION["last_items_view"])]);
        }
        if (!in_array($productID,$_SESSION["last_items_view"])) {
            $_SESSION["last_items_view"][] = $productID;
            
        }
}
// ¬ывести продукты из сессии
if (isset($_SESSION["last_items_view"])&&$_SESSION["last_items_view"]!="")
{
 $p=0;
 if (isset($productID)) $p=$productID;   
 $sql='select productID,thumbnail,name,hurl,P.Price+IFNULL((select sum(`price_surplus`) from `'.PRODUCT_OPTIONS_V_TABLE.'` where `productID`=P.productID and `default`=1),0) Price from '.PRODUCTS_TABLE.' as P where P.productID!='.$p.' and P.productID in ('.add_in($_SESSION["last_items_view"]).')';
 $result=products_to_show($sql);
 $smarty->assign("last_view_products", $result['result']);
 unset($result);
}
?>
<?php
/*****************************************************************************
 *                                                                           *
 * LegoSP - legosp.net                                                       *
 * Copyright (c) 2010-2014 Sergey Piekhota. All rights reserved.             *
 *                                                                           *
 ****************************************************************************/

if (isset($_GET['ajax'])) {

    if (!isset($_GET['noinclude']))
    {
        include_once('../../../cfg/ajax_connect.inc.php');

    }
    $legosp->load_class('category');
    if (isset($_GET["del"]))
    {
       $legosp->category->categoryID=(int)$_GET["c_id"];
       $categorys=$legosp->category->get_Subs((int)$_GET["c_id"]);
       $picture = db_arAll('SELECT picture FROM ' . CATEGORIES_TABLE . " WHERE picture is not NULL and picture !='' and categoryID in (" . $categorys.')');

       foreach ($picture as $pic)
       {
           if (file_exists('./products_pictures/' . $pic['picture']))
               unlink('./products_pictures/'.$pic['picture']);
       }
       db_query('DELETE FROM '. CATEGORIES_TABLE . ' WHERE categoryID in (' . $categorys.')');
       db_query('DELETE FROM '. CATEGORIY_PRODUCT_TABLE . ' WHERE categoryID in (' . $categorys.')');
       db_query('DELETE FROM '. CATEGORIY_PRODUCT_TABLE . ' WHERE categoryID in (' . $categorys.')');
       $picture = db_arAll('SELECT picture,thumbnail,big_picture FROM ' . PRODUCTS_TABLE . " WHERE categoryID in (" . $categorys.')');
       $legosp->patch_dir=ROOT_DIR.'/products_pictures/';
       $legosp->delfiles($pictures);
       db_query('DELETE FROM '. PRODUCTS_TABLE . ' WHERE categoryID in (' . $categorys.')');
    }
    if (isset($_GET['categoryID'])) {
        $sql = 'select categoryID,CONCAT(name,IF((select count(*) from ' . CATEGORIES_TABLE . ' where parent=C.categoryID)>0,\' <b>[+]</b>\',\'\')) name,enabled,products_count_admin from ' . CATEGORIES_TABLE . ' as C where parent=' . (int)$_GET['categoryID'];
        $q = db_query($sql);
        $_GET['level']++;
        $nbsp = '';
        for ($i = 1; $i <= $_GET['level']; $i++)
            $nbsp .= '&nbsp;&nbsp;&nbsp;';
        while ($row = db_assoc_q($q)) {
            $chek = '';
            if ($row['enabled']) $chek = 'checked';
            echo "<tr data-parrent='" . $_GET['categoryID'] . "' data-level='" . $_GET['level'] . "'>";
            echo "<td>" . $row['categoryID'] . '</td>';
            echo '<td align="center">';
            echo '<input type="checkbox" class="enabled" ' . $chek . '>';
            echo '<input type="hidden" value=' . $row['enabled'] . ' name="enabled[' . $row['categoryID'] . ']">';
            echo '</td>';
            echo "<td>$nbsp<span class='category' data-categotyid='" . $row['categoryID'] . "'>" . $row['name'] . '</span></td>';
            echo "<td align=\"center\">(" . $row['products_count_admin'] . ')</td>';
            echo '<td align="center">';
            echo    '<a class="btn btn-default btn-sm" title="'.ADMIN_EDIT.'" href="./'.CONF_ADMIN_FILE.'?dpt=catalog&sub=categories_edit&c_id='.$row['categoryID'].'"><i class="glyphicon glyphicon-edit"></i></a>';
            echo    '<a class="btn btn-default btn-sm delajax" title="'.DELETE_BUTTON.'" href="./'.CONF_ADMIN_FILE.'?dpt=catalog&sub=categories&c_id='.$row['categoryID'].'&del"><i class="glyphicon glyphicon-trash"></i></a>';
            echo '</td>';
            echo "</tr>";

        }
    }
    if (isset($_POST["categories_update"]))
    {

        $value = '';
        foreach ($_POST['enabled'] as $key => $val) {
            $value .= '(' . (int)$key . ',' . (int)$val . '),';
        }
        $value = substr($value, 0, strlen($value) - 1);
        $sql = 'insert INTO ' . CATEGORIES_TABLE . ' (categoryID,enabled) VALUES ' . $value . ' ON DUPLICATE KEY UPDATE enabled=VALUES(enabled)';
        db_query($sql);
        unset($value,$sql);


    }

    exit;
}
if (!defined('WORKING_THROUGH_ADMIN_SCRIPT')) {
    die;
}
//show new orders page if selected
if (!strcmp($sub, "categories")) {


    $legosp->load_class('category');
    if (isset($_POST["categories_update"])) {
        $value = '';
        foreach ($_POST['enabled'] as $key => $val) {
            $value .= '(' . (int)$key . ',' . (int)$val . '),';
        }
        $value = substr($value, 0, strlen($value) - 1);
        $sql = 'insert INTO ' . CATEGORIES_TABLE . ' (categoryID,enabled) VALUES ' . $value . ' ON DUPLICATE KEY UPDATE enabled=VALUES(enabled)';
        unset($value);
        db_query($sql);
        header('Location: ' . CONF_ADMIN_FILE . '?dpt=catalog&sub=categories');
        exit;
    }
    if (isset($_GET["del"]) && isset($_GET["c_id"])) //delete category
    {

        $_GET["c_id"] = (int)$_GET["c_id"];
        $picture = db_r("SELECT picture FROM " . CATEGORIES_TABLE . " WHERE categoryID='" . $_GET["c_id"] . "' and categoryID<>0");
        if ($picture && file_exists('./products_pictures/' . $picture)) unlink('./products_pictures/' . $picture);
        //delete from db
        $q = db_query("DELETE FROM " . CATEGORIES_TABLE . " WHERE categoryID='" . $_GET["c_id"] . "' and categoryID<>0") or die (db_error());
        deleteSubCategories($_GET["c_id"]);
        update_products_Count_Value_For_Categories(0);
        header("Location: " . CONF_ADMIN_FILE . "?dpt=catalog&sub=categories");
        exit;
    }
    //calculate how many products are there in the root category
    $cnt = db_r("SELECT count(*) FROM " . PRODUCTS_TABLE . " WHERE categoryID=0");
    $smarty->assign("products_in_root_category", $cnt);
    //create a category tree
    $legosp->category->sql = 'SELECT categoryID,CONCAT(name,IF((select count(*) from ' . CATEGORIES_TABLE . ' where parent=C.categoryID)>0,\' <b>[+]</b>\',\'\')) name,parent,products_count_admin,enabled,hurl FROM `' . CATEGORIES_TABLE . '` as C where 1=1 ';
    $smarty->assign("categories", $legosp->category->category_list(0));

    //set main template
    $smarty->assign("admin_sub_dpt", "catalog_categories.tpl.html");
}

?>
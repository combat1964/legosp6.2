<?php

/**
 * Created by PhpStorm.
 * User: psergey
 * Date: 28.07.14
 * Time: 15:51
 */
class lego_category
{
    public $sql = '';
    public $categoryID = '';
    public $colums=array();

    function category_list($cat = FALSE, $categoryID = FALSE)
    {
        $levels = array();
        $tree = array();
        $cur = array();

        if ($this->sql === '')
            $sql = 'SELECT categoryID,name,parent,products_count_admin,enabled,hurl FROM `' . CATEGORIES_TABLE . '` where 1=1 ';
        else
            $sql = $this->sql;
        if ($cat !== FALSE) {
            $this->categoryID=$cat;
            $sql .= ' and parent in (' . $this->get_paterns() . ')';
        }
        if ($categoryID !== FALSE) {
            $sql .= ' and categoryID=' . $categoryID;
        }
        $sql .= ' ORDER BY ' . CONF_SORT_CATEGORY . ' ' . CONF_SORT_CATEGORY_BY;
        $q=db_query($sql); unset($sql);
        while ($rows = db_assoc_q($q)) {
            if ($rows['hurl'] != "" && CONF_CHPU) {
                $rows['hurl'] = REDIRECT_CATALOG . "/" . $rows['hurl'];
            } else {
                $rows['hurl'] = "index.php?categoryID=" . $rows['categoryID'];
            }
            $cur = & $levels[$rows['categoryID']];
            if (is_array($cur)) {
                $cur = array_merge($cur, $rows);
            } else $cur = $rows;
            if ($rows['parent'] == 0) {
                $tree[$rows['categoryID']] = & $cur;
            } else {
                $levels[$rows['parent']]['subitems'][$rows['categoryID']] = & $cur;
            }

        }
        return $tree;
    }

    function get_Subs($start_array=FALSE) //get current category's subcategories IDs (of all levels!)
    {
        $q = db_query("select categoryID from " . CATEGORIES_TABLE . ' where parent='.$this->categoryID) or die(db_error());
        $r = array();
        if ($start_array !==FALSE)
        {
            array_push($r,$start_array);
        }
        while ($row = db_fetch_row($q)) {
            $a = get_Subs($row[0]);
            for ($i = 0;$i < count($a);$i++) $r[] = $a[$i];
            $r[] = $row[0];
        }
        return add_in($r);
    }
    function get_paterns()
    {
        $path = array($this->categoryID);
        $curr = $this->categoryID;
        do {
            $curr = db_r("SELECT parent FROM " . CATEGORIES_TABLE . ' WHERE categoryID=' . $curr);
            $curr = $curr ? $curr : 0;
            $path[] = $curr;
        } while ($curr);
        return add_in($path);
    }
    function update_count_products($admin_count,$count=FALSE)
    {
        if ($count===FALSE) $count=$admin_count;
        db_query('UPDATE '.CATEGORIES_TABLE.' SET `products_count_admin`=`products_count_admin`+'.$admin_count.', `products_count`=`products_count`+'.$count.' where categoryID='.$this->categoryID);
    }
    function get_path(){
        return get_paterns('GROUP_CONCAT(T2.name SEPARATOR "->") category_path');
    }

    function del_category()
    {

    }
} 
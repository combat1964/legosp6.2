<?php
/****************************************************************************
 *                                                                           *
 * Lego SP - legosp.net                                                      *
 * Copyright (c) 2011-2014 Sergey Piekhota. All rights reserved.             *
 *                                                                           *
 ****************************************************************************/
//	database functions :: MySQL

function add_in($fields)
{
    $set = '';
    foreach ($fields as $field => $value) {
        $set .= $value . ',';
    }
    $set = substr($set, 0, strlen($set) - 1);
    return $set;
}

function getDbCollation($db)
{
    return db_r("SELECT DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '" . $db . "'");
}

function db_connect($host, $user, $pass, $db_name = DB_NAME, $charset = DB_CHARSET) //create connection
{
    GLOBAL $db;
    try {
        $dsn = 'mysql:dbname=' . $db_name . ';host=' . $host . ';charset=' . $charset;
        $db = new PDO($dsn, $user, $pass);
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }
}


function db_query($s, $params = FALSE) //database query
{

    GLOBAL $esql, $db;
    if ($esql) echo "<p>" . $s . "</p>";
    if ($params !== FALSE) {
        $stmt = $db->prepare($s);
        $result = $stmt->execute($params);

    } else
        $result = $db->query($s);
    if (!$result) {
        $error = "<h3>SQL Error</h3>";
        $error .= 'SQL: ' . $s . "<br>";
        $error .= "Error: <font style='color: red'>" . $db->errorInfo()[2] . "</font>";
        die($error);
    }

    return $result;
}

function db_fetch_row($result) //row fetching
{
    $result->setFetchMode(PDO::FETCH_NUM);
    return $result->fetch();
}

function db_insert_id()
{
    global $db;
    return $db->lastInsertId();

}

function db_error() //database error message
{
    GLOBAL $db;
    $error_array = $db->errorInfo();

    if ($db->errorCode() != 0000)

        return "SQL error: " . $error_array[2] . '<br />';

}

function db_assoc($s)
{
    $result = db_query($s);
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $r = $result->fetch();
    if ($r)
        return $r;
    else return false;
}

function db_assoc_q($result)
{
    $result->setFetchMode(PDO::FETCH_ASSOC);
    return $result->fetch();

}

function db_affected_rows()
{
    return mysql_affected_rows();
}

function db_arAll($s)
{
    // выгребает все значения из выборки в один ассоциативный массив
    $result = db_query($s);
    $result->setFetchMode(PDO::FETCH_ASSOC);
    return $result->fetchAll();
}

function db_r($s)
{
    return db_query($s)->fetchColumn();
}

function isTextValue($field_type)
{
    switch ($field_type) {
        case "tinytext":
        case "text":
        case "mediumtext":
        case "longtext":
        case "binary":
        case "varbinary":
        case "tinyblob":
        case "blob":
        case "mediumblob":
        case "longblob":
            return True;
            break;
        default:
            return False;
    }
}


function db_num_rows($q)
{

    return $q->rowCount();

}

function db_num_fields($q)
{
    if ($q) return mysql_num_fields($q);
    else return 0;
}

function int_text($val)
{
    global $db;
    if (get_magic_quotes_gpc()) $val = stripslashes($val);
    $val = $db->quote($val);
    return $val;
}

function add_set($fields)
{

    $set = '';
    foreach ($fields as $field => $value) {


        #echo $value; echo '=';
        if (get_magic_quotes_gpc()) $value = stripslashes($value);
        $value = int_text($value);
        $set .= "`" . $field . "`=" . $value . ',';
    }
    $set = substr($set, 0, strlen($set) - 1);
    #exit;
    return $set;

}


function add_field($table, $fields)
{
    global $db;
    db_query("INSERT INTO `" . $table . "` SET " . add_set($fields));
    return $db->lastInsertId();

}

function update_field($table, $fields, $where = "1>0")
{

    return $q = db_query("UPDATE `" . $table . "` SET " . add_set($fields) . " WHERE " . $where);

}

function validate_form_string($inp)
{
    if (is_array($inp))
        return array_map(__METHOD__, $inp);

    if (!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }
}

function db_create($db, $charset = 'UTF8')
{

    $general = $charset . '_general_ci';
    return db_query('CREATE DATABASE $db CHARACTER SET ' . $charset . ' COLLATE ' . $general);
}

?>
<?php
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_GET['q']))
    {
        require_once("my_mysqli.php");
        require_once("config.inc.php");
        $db = new my_mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $employer = $_GET['q'];
        
        $sql = "SELECT * FROM `cegek` WHERE `cegnev` = '?'";
        $result = $db->select_one_row($sql, $params = array($employer));
        //$datas = array($result['cegcim_cs'], $result['cegcim_sz']);

        echo json_encode($result);
    }
?>
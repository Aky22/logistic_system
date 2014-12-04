<?php
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_GET['callback']))
    {
        require_once("my_mysqli.php");
        require_once("config.inc.php");
        $db = new my_mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $sql = "SELECT * FROM `cegek`";
        $result = $db->select($sql);
        $data = array();
        for ($i = 0; $i < count($result); $i++)
        {
            array_push($data, $result[$i]['cegnev']);
        }
        //$datas = array($result['cegcim_cs'], 'Bela');

        echo $_GET['callback']."(".json_encode($data).")";
    }
?>
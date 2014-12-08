<?php
    require_once('include/login.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Logistic System</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.mobile-1.4.5.js"></script>
    <script type="text/javascript" src="js/autocomplete.js"></script>
    
    <!-- jquery mobile css file -->
    <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css">
    
	<!-- general boring stuff and some visual tweaks -->
	<link rel="stylesheet" href="css/screen.css">
    
    <script>
        $( document ).on( "pagecreate", ".jqm-demos", function( event ) {
            var page = page = $( this );
            // Global navmenu panel
            $( ".jqm-navmenu-panel ul" ).listview();

            $( ".jqm-navmenu-link" ).on( "click", function() {
                page.find( ".jqm-navmenu-panel:not(.jqm-panel-page-nav)" ).panel( "open" );
            });


        });
    </script>

</head>
<body>

<div class="container jqm-demos" data-role="page">
    <?php
        if(!isset($_SESSION['username']))
        {
            header("Location: index.php");
        }
        else
        {
            require_once('include/config.inc.php');
            require_once('include/my_mysqli.php');
            
            $db = new my_mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            $sql_nagykerek = "SELECT * FROM `nagykerek`";
            $result_nagykerek = $db->select($sql_nagykerek);
            
            $sql_anyag = "SELECT * FROM `anyagszin`";
            $result_anyag = $db->select($sql_anyag);
            
            $sql_szita = "SELECT * FROM `szitahimzes`";
            $result_szita = $db->select($sql_szita);
            
            $sql_csomagolas = "SELECT * FROM `csomagolas`";
            $result_csomaglas = $db->select($sql_csomagolas);
            
            echo '<div data-role="header" class="jqm-header">
                    <h1>Logistic system</h1>
                    <a href="#" class="jqm-navmenu-link ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
                </div>';
            echo '<div class="ui-content jqm-content" role="main">
                <div class="ui-grid-b">
                    <div class="ui-block-a">
                        <label for="date">Megrendelés dátuma:</label>
                        <input type="date" name="date" id="date" value="">
                        <ul class="autocomplete"  data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Megrendelő neve" data-filter-theme="e"></ul>
                        <label for="billingaddress">Számlázási cím:</label>
                        <input type="text" name="billingaddress" id="billingaddress" value="">
                        <label for="shippingaddress">Postázási cím:</label>
                        <input type="text" name="shippingaddress" id="shippingaddress" value="">
                        <label for="contact">Kapcsolattartó:</label>
                        <input type="text" name="contact" id="contact" value="">
                        <label for="tel">Telefon:</label>
                        <input type="tel" name="tel" id="tel" value="">
                        <label for="enddate">Elkészülési határidő:</label>
                        <input type="date" name="enddate" id="enddate" value="">
                    </div>
                    <div class="ui-block-b">
                        <select name="purchaseplace" id="purchaseplace" data-native-menu="false">
                            <option>Anyagbeszerzési hely</option>';
                            for ($i = 0; $i < count($result_nagykerek); $i++)
                            {
                                echo '<option value="'.$result_nagykerek[$i]['nagykernev'].'">'.$result_nagykerek[$i]['nagykernev'].'</option>';
                            }
                        echo '</select>
                        <select name="materialthickness" id="materialthickness" data-native-menu="false">
                            <option>Anyagvastagság</option>';
                            for ($i = 0; $i < count($result_anyag); $i++)
                            {
                                echo '<option value="'.$result_anyag[$i]['anyagsuly'].'">'.$result_anyag[$i]['anyagsuly'].'</option>';
                            }
                        echo '</select>
                        <select name="mastercolor" id="mastercolor" data-native-menu="false">
                            <option>Főszín</option>';
                            for ($i = 0; $i < count($result_anyag); $i++)
                            {
                                echo '<option value="'.$result_anyag[$i]['anyagszin'].'">'.$result_anyag[$i]['anyagszin'].'</option>';
                            }
                    echo '</select>
                        <select name="distinctcolor" id="distinctcolor" data-native-menu="false">
                            <option>Elütő színe</option>';
                            for ($i = 0; $i < count($result_anyag); $i++)
                            {
                                echo '<option value="'.$result_anyag[$i]['anyagszin'].'">'.$result_anyag[$i]['anyagszin'].'</option>';
                            }
                    echo '</select>
                        <select name="paszpolcolor" id="paszpolcolor" data-native-menu="false">
                            <option>Paszpol színe</option>';
                            for ($i = 0; $i < count($result_anyag); $i++)
                            {
                                echo '<option value="'.$result_anyag[$i]['anyagszin'].'">'.$result_anyag[$i]['anyagszin'].'</option>';
                            }
                    echo '</select>
                        <select name="logo" id="logo" data-native-menu="false">
                            <option>Logózás</option>';
                            for ($i = 0; $i < count($result_szita); $i++)
                            {
                                echo '<option value="'.$result_szita[$i]['szhID'].'">'.$result_szita[$i]['muvelet'].'</option>';
                            }
                    echo '</select>
                        <select name="label" id="label" data-native-menu="false">
                            <option>Címke</option>
                            <option value="standard">Standard: 7 day</option>
                            <option value="rush">Rush: 3 days</option>
                            <option value="express">Express: next day</option>
                            <option value="overnight">Overnight</option>
                        </select>
                        <select name="delivery" id="delivery" data-native-menu="false">
                            <option>Kiszállítás</option>
                            <option value="standard">Standard: 7 day</option>
                            <option value="rush">Rush: 3 days</option>
                            <option value="express">Express: next day</option>
                            <option value="overnight">Overnight</option>
                        </select>
                        <select name="packing" id="packing" data-native-menu="false">
                            <option>Csomagolás</option>';
                            for ($i = 0; $i < count($result_csomaglas); $i++)
                            {
                                echo '<option value="'.$result_csomaglas[$i]['cs_ID'].'">'.$result_csomaglas[$i]['cs_csomagtip'].'</option>';
                            }
                    echo '</select>
                    <a href="#popupParis" data-rel="popup" data-position-to="window" data-transition="fade" id="popupgomb">Gomb</a>
                    <div data-role="popup" id="popupParis" data-overlay-theme="a" data-theme="a" data-corners="false">
                        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>';
                        require_once('include/szinezo.html');
                    echo '</div>
                    </div>
                    <div class="ui-block-c"><div class="ui-bar ui-bar-a" style="height:60px">Block C</div></div>
                </div><!-- /grid-a -->
                </div>';
            echo '<div data-role="panel" id="menu" data-position="left" data-display="overlay" class="jqm-navmenu-panel">
                    <ul data-role="listview" class="jqm-list ui-alt-icon ui-nodisc-icon">
                        <!--<li data-icon="delete"><a href="#" data-rel="close">Bezárás</a></li>-->
                        <li><a href="index.php">Kezdőlap</a></li>
                        <li><a href="?logout=1">Kijelentkezés</a></li>
                        <li><a href="order_form.php">Új megrendelés</a></li>
                    </ul>
                    <br><br>
                    
                </div>';
            
        }

    if(isset($_GET['logout']))
    {
        session_destroy();
        unset($_SESSION);
        header("Location: index.php");
    }
    ?>
</div>

</body>
</html>

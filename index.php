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
    ?>
    <div id="login">
        <form method="post" action="index.php">
            <div class="ui-field-contain">
                <label for="username">Felhasználónév:</label>
                <input type="text" name="username" id="username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" data-clear-btn="true">
            </div>
            <div class="ui-field-contain">
                <label for="pass">Jelszó:</label>
                <input type="password" name="pass" id="pass" value="<?php echo isset($_COOKIE['pass']) ? $_COOKIE['pass'] : ''; ?>" data-clear-btn="true">
            </div>
            <div class="ui-field-contain">
                <label for="remember">Emlékezz rám:</label>
                <select name="remember" id="remember" data-role="flipswitch">
                    <option value="0">Nem</option>
                    <option value="1" <?php echo (isset($_COOKIE['username']) ? 'selected=""' : ''); ?>>Igen</option>
                </select>
            </div>
            <div class="ui-field-contain">
                <input type="submit" id="submit-1" value="Bejelentkezés" name="submit">
            </div>
        </form>
    </div>
    <?php
        }
        else
        {
            echo '<div data-role="header" class="jqm-header">
                    <h1>Logistic system</h1>
                    <a href="#" class="jqm-navmenu-link ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
                </div>';
            echo '<div class="ui-content jqm-content" role="main">
                    
                </div>';
            echo '<div data-role="panel" id="menu" data-position="left" data-display="overlay" class="jqm-navmenu-panel">
                    <ul data-role="listview" class="jqm-list ui-alt-icon ui-nodisc-icon">
                        <!--<li data-icon="delete"><a href="#" data-rel="close">Bezárás</a></li>-->
                        <li><a href="index.php">Kezdőlap</a></li>
                        <li><a href="?logout=1">Kijelentkezés</a></li>
                        <li><a href="#">Új megrendelés</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
                        <li><a href="#">List item</a></li>
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

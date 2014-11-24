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

    <script>
		$( document ).on( "pagecreate", function() {
				var themeClass = "b";
				$( ".container" ).removeClass( "ui-page-theme-a ui-page-theme-b" ).addClass( "ui-page-theme-" + themeClass );
		});
	</script>
    
    <!-- jquery mobile css file -->
    <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css">
    
	<!-- general boring stuff and some visual tweaks -->
	<link rel="stylesheet" href="css/screen.css">

</head>
<body>

<div class="container" data-role="page">
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
					<option value="1">Igen</option>
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
            echo '<div data-role="navbar">
                <ul>
                    <li><a href="index.php" class="ui-btn-active ui-state-persist">Homepage</a></li>
                    <li><a href="#">Menu item 2</a></li>
                    <li><a href="#">Menu item 3</a></li>
                    <li><a href="#">Menu item 3</a></li>
                    <li><a href="#">Menu item 3</a></li>
                </ul>
            </div>';
        }
    ?>
</div>

</body>
</html>

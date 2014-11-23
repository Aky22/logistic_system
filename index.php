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
    
	<!-- a grid framework in 250 bytes? are you kidding me?! -->
	<link rel="stylesheet" href="css/grid.css">

	<!-- all the important responsive layout stuff -->
	<style>

		/* you only need width to set up columns; all columns are 100%-width by default, so we start
		   from a one-column mobile layout and gradually improve it according to available screen space */

		@media only screen and (min-width: 34em) {
			.feature, .info { width: 50%; }
		}

		@media only screen and (min-width: 54em) {
			.content { width: 33.33%; }
			.sidebar { width: 33.33%; }
			.info    { width: 100%;   }
		}

		@media only screen and (min-width: 76em) {
			.content { width: 58.33%; } /* 7/12 */
			.sidebar { width: 20.83%; } /* 5/12 */
			.info    { width: 50%;    }
		}
	</style>

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
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" data-clear-btn="true">
            </div>
            <div class="ui-field-contain">
                <label for="pass">Passworld:</label>
                <input type="password" name="pass" id="pass" value="<?php echo isset($_COOKIE['pass']) ? $_COOKIE['pass'] : ''; ?>" data-clear-btn="true">
            </div>
            <div class="ui-field-contain">
				<label for="remember">Remember me:</label>
				<select name="remember" id="remember" data-role="flipswitch">
					<option value="0">No</option>
					<option value="1">Yes</option>
				</select>
			</div>
            <div class="ui-field-contain">
                <input type="submit" id="submit-1" value="Login" name="submit">
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

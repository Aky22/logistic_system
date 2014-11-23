<?php
	function date_convert($date)
	{
		$month_number = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
		$month_name = array('január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');
		return str_replace($month_number, $month_name, $date);
	}
	
	function language($language,$var,$cat = ""){
		$lang = parse_ini_file("language/".$language."/language.ini", true);
		if(empty($cat))
		{
			return $lang[$var];
		}
		else
		{
			return $lang[$cat][$var];
		}
	}
	
	function menu_active_block($url, $name, $language){
		if($name == 'message')
        {
            return '<div class="menu_item"><a href="#" data-reveal-id="message">'.language($language, $name, 'Menu').'</a></div>';
        }
		else
		{
			if($_SERVER['SCRIPT_NAME'] == LOCATION.$url)
			{
				return '<div class="menu_item active"><a href="'.$url.'">'.language($language, $name, 'Menu').'</a></div>';
			}
			else
			{
				return '<div class="menu_item"><a href="'.$url.'">'.language($language, $name, 'Menu').'</a></div>';
			}
		}
	}
	
	function trim_text($input, $length, $ellipses = true, $strip_html = true) {
		//ha ha paraméternek van értéke kiveszi a HTML elemeket
		if ($strip_html) {
			$input = strip_tags($input);
		}
		//csak akkor vágjuk le ha hosszabb mind amit mi szeretnénk
		if (strlen($input) <= $length) {
			return $input;
		}
		//megkeressük az utolsó space -t
		$last_space = strrpos(substr($input, 0, $length), ' ');
		$trimmed_text = substr($input, 0, $last_space);
		//betesszük a levágást jelző karaktereket
		if ($ellipses) {
			$trimmed_text .= ' ...';
		}
		//a levágott szöveg
		return $trimmed_text;
	}
	
	function replace_special($text){
		$ekezetesek = array('á', 'é', 'í', 'ó', 'ő', 'ö', 'ú', 'ü', 'ű', ' ', 'Á', 'É', 'Í', 'Ó', 'Ő', 'Ö', 'Ú', 'Ü', 'Ű');
		$normal = array('a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', '_', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U');
		return strtolower(str_replace($ekezetesek, $normal, $text));
	}
	
	function delTree($dir) { 
		$files = array_diff(scandir($dir), array('.','..')); 
		foreach ($files as $file) { 
		  (is_dir("$dir/$file") && !is_link($dir)) ? delTree("$dir/$file") : unlink("$dir/$file"); 
		} 
		return rmdir($dir); 
	}

    function checkexist($varname, $messagetext){
        $db = count($varname);
        $end = 'true';
        for($i = 0; $i < $db; $i++)
        {
            if(empty($_POST[$varname[$i]]))
            {
                $end = false;
                return $messagetext[$i].' megadása kötelező!';
            }
        }
        if($end == 'true')
        {
            return 'true';
        }
    }

    function check_permissions($location){
        if(!isset($_SESSION['name']) && empty($_SESSION['name']) && $_SERVER['SCRIPT_NAME'] != LOCATION.'admin/login.php')
        {
            header("Location:login.php?redirect=$location");
        }
    }

    function kepgenerator ($kepfajl, $maxmeret, $ujfajlnev)  
    {  
        if (!file_exists($kepfajl))  
            return (false);  

        // Megfelelő méret kiválasztása  
        list($width, $height, $type) = getimagesize($kepfajl);  
        $nagyobb = ($width > $height) ? $width : $height;  
        $kisebb = ($width > $height) ? $height : $width;  
        if ($nagyobb <= $maxmeret)  
            {  
            $new_nagyobb = $nagyobb;  
            $new_kisebb = $kisebb;  
            }  
        else  
            {  
            $szorzo = $maxmeret / $nagyobb; // Mennyire (milyen aránnyal) kicsinyítjük le a képet - ez egy 0 és 1 közötti szám lesz  
            $new_nagyobb = $maxmeret; // A nagyobb oldalszélesség lesz a maximális  
            $new_kisebb = $kisebb * $szorzo; // A nagyobb oldalméret kicsinyítésével ($szorzo) arányosan kicsinyítjük le a kisebb oldalt is  
            }  
        $new_width = ($width > $height) ? $new_nagyobb : $new_kisebb; // Az eredeti méretek alapján összepárosítjuk az új szélességet és magasságot a kissebb-nagyobb értékekkel  
        $new_height = ($width > $height) ? $new_kisebb : $new_nagyobb;  

        // Kép generálása  
        switch ($type) // A kép formátumától függően más-más függvénnyel dolgozzuk fel a képet  
            {  
            case 1:  
                $kep = imagecreatefromgif ($kepfajl);  
                break;  
            case 2:  
                $kep = imagecreatefromjpeg ($kepfajl);  
                break;  
            case 3:  
                $kep = imagecreatefrompng ($kepfajl);  
                break;  
            }  
        $ujkep = imagecreatetruecolor ($new_width, $new_height);  
        imagecopyresampled ($ujkep, $kep, 0, 0, 0, 0, $new_width, $new_height, $width, $height); // A lényeg - most generáljuk az új képet  
        imagejpeg ($ujkep, $ujfajlnev, 100); // És végül egy (lehető legjobb minoségű) jpeg képet generálunk az egészből, és azt elmentjük a megadott néven  
        return (array($new_width, $new_height)); // Visszaadjuk a generált kép szélességét és magasságát  
    }

    function picture_reader($destination){
        $out = array();
        if ($kepekkonyvtara = @opendir($destination))
        {

            $csak_kep = array(".bmp", ".gif", ".jpeg", ".jpg", ".png");
            while($kep = @readdir($kepekkonyvtara)) 
            {
                if  (in_array(strtolower(strrchr($kep, ".")), $csak_kep)) 
                {
                    array_push($out, $kep);
                }
            }
            @closedir($kepekkonyvtara);  
            return $out;
        }
        else
        {  
            return 'Nem tudom megnyitni a könyvtárat!';
        }
    }
?>
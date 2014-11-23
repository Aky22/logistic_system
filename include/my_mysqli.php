<?php
/**
 * Adatbázis-műveletek egyszerűsítésére szolgáló osztály. Segítségével
 * könnyebben lehet a szokásos adatbázisműveleteket elvégezni. A
 * lekérdezések futtatása előtt minden adat ellenőrzésen megy át, így
 * az adatbázis védve van az SQL-befecskendezéses támadás ellen,
 * illetve a weboldal is védve van az XSS-támadások ellen.
 *
 * @author Úr Balázs
 * @copyright Creative Commons BY-SA http://creativecommons.org/licenses/by-sa/2.5/hu/
 * @version 1.0.2
 * @date 2014-09-15
 *
 * Az osztály gyors használata:
 *
 * Példányosítás
 * $db = new my_mysqli(HOST, USER, PASS, NAME);
 *
 * Új rekord beszúrása
 * $db->insert($table, $data);
 *
 * Meglévő rekord módosítása ID alapján
 * $db->update($table, $data, $id);
 *
 * Adatok lekérése kétdimenziós tömbként
 * $db->select($sql, $params = array());
 *
 * Egy soros eredményhalmaz lekérése egydimenziós tömbként
 * $db->select_one_row($sql, $params = array());
 *
 * Rekord törlése ID alapján
 * $db->delete($table, $id)
 */
class my_mysqli extends mysqli {
	
	/**
	 * Konstruktor. Példányosításkor megkapja az adatbázis-kapcsolathoz
	 * szükséges adatokat és elvégzi a kapcsolódást. Sikeres kapcsolódás után
	 * beállítja a kapcsolat karakterkódolását UTF-8 formátumra.
	 *
	 * @param $db_host A MySQL adatbázis-kiszolgáló címe
	 * @param $db_user A kapcsolódáshoz használt felhasználónév
	 * @param $db_pass A kapcsolódáshoz használt felhasználó jelszava
	 * @param $db_name Az adatbázis neve, amelyben a táblák vannak
	 */
	public function __construct($db_host, $db_user, $db_pass, $db_name) {
		// a szülő (mysqli) konstruktorának hívása
		parent::__construct($db_host, $db_user, $db_pass, $db_name);
		$this->set_charset('utf8');
	}
	
	/**
	 * Biztonságossá teszi a paraméterként kapott szöveget. Ez a szöveg használható adtabázis lekéréshez
	 */
	private function safe_text($text) {
		return htmlspecialchars($this->real_escape_string(trim($text)));
	}
	
	/**
	 * A paraméterként megadott adatbázis táblából lekéri az oszlopok nevét és
	 * visszaadja őket egy tömbben.
	 *
	 * @param string $table
	 *   Annak az adatbázis táblának a neve, amelyikből az oszlopok nevét le
	 *   szeretnénk kérdezni.
	 *
	 * @return array $field_list
	 *   A mezőneveket tartalmazó tömb.
	 */
	public function get_field_list($table) {
		// Az oszlopok információinak lekérdezése
		if ($result = $this->query('SHOW COLUMNS FROM ' . $this->real_escape_string($table)) ) {
			$columns = array();
			while($row = $result->fetch_assoc()) {
				// Csak a Field oszlop kell az eredményhalmazból
				$columns[] = $row['Field'];
			}
			return $columns;
		}
		return false;
	}
	
	/**
	 * A megadott tábla megadott ENUM típusú oszlopának lekérdezi az értékeit
	 * és visszaadja azokat egy (nem asszociatív) tömbben.
	 *
	 * @param string  $table
	 *   Az a tábla, amelyben az oszlopot keressük
	 *
	 * @param string  $field
	 *   Az az oszlop, amelynek az értékeire szükségünk van
	 *
	 * @return array
	 *   Az enum típusú oszlop értékei egy (nem asszociatív) tömbben
	 */
	public function get_enum_values($table, $field) {
		$result = $this->query(
			sprintf(
				"SHOW COLUMNS FROM `%s` WHERE Field = '%s'",
				$this->real_escape_string($table),
				$this->real_escape_string($field)
			)
		);
		$row = $result->fetch_assoc();
		
		// A szting elejéröl az 'enum(' substring eltávolítása
		$enum_string = substr($row['Type'], 5);
		
		// A string végéröl a ')' karakter eltávolítása
		$enum_string = substr($enum_string, 0, -1);
		
		// Az aposztrof karakterek eltávolítása
		$enum_string = str_replace("'", '', $enum_string);
		
		// A string felbontása elemekre a ',' karakter mentén
		$enum_values = explode(',', $enum_string);
		
		return $enum_values;
	}
	
	/**
	 * Ez a függvény egy tábla megadott mezőjéből lekéri a különböző értékeket
	 * @param String $table
	 *   Az a tábla, amiből adatot kell lekérni
	 * @param String $field
	 *   Az a mező, amelyből a különböző értékek kellenek
	 *
	 * @return array()
	 *   A különböző értékek tömbben, ABC sorrendben
	 */
	public function get_distinct_values($table, $field) {
		$result = $this->query(
			sprintf(
				'SELECT DISTINCT %s FROM %s ORDER BY %s',
				$this->real_escape_string($field),
				$this->real_escape_string($table),
				$this->real_escape_string($field)
			)
		);
		
		$out = array();
		while ($row = $result->fetch_array()) {
			$out[] = $row[0];
		}
		
		return $out;
	}

	/**
	 * A függvény a kapott paraméteres SQL utasításba behelyettesíti a $params
	 * tömbben lévő paramétereket. A tömb számosságának meg kell egyeznie az
	 * $sql stringben lévő paraméterek számával. Az SQL kérésében a
	 * paramétereket „?” karakterek jelentik, Lefuttatja a kérést és visszatér
	 * egy kétdimenziós tömbbel. A tömb első dimenziója az eredményhalmaz sorait
	 * tartalmazza, a második dimenzió egy asszociatív tömb, amelyben a kulcsok
	 * az eredményhalmaz oszlopai nevével egyeznek meg.
	 *
	 * Példa egy SQL kérésre:
	 * SELECT id, nev FROM tabla WHERE szuldatum = '?' AND helyszin = '?' AND nem = ? LIMIT 4
	 * A paraméterek: array('2013-01-01', 'Budapest', 1)
	 *
	 * A visszaadott tömb az alábbi formában jelenik meg:
	 * array(
	 * 	[0] => array(
	 * 		[oszlop1] => érték1,
	 * 		[oszlop2] => érték2,
	 * 		...
	 * 	),
	 * 	[1] => array(
	 * 		[oszlop1] => érték1,
	 * 		[oszlop2] => érték2,
	 * 		...
	 * 	),
	 * 	...
	 * )
	 *
	 * @param string $sql A paraméteres SQL kérés
	 * @param array $params A kérésbe behelyettesítendő paraméterek
	 * @return Az eredményhalmaz sorait tartalmazó kétdimenziós asszociatív tömb,
	 * ahol a kulcsok megegyeznek a tábla oszlopneveivel. Lehet üres tömb is.
	 */
	public function select($sql, $params = array()) {
		// Ebbe a kétdimenziós tömbbe kerülnek az eredményhalmaz sorai
		$rows = array();
		
		// Ha a megadott $sql utasítás nem SELECT-tel kezdődik, akkor nem fog lefutni a lekérdezés
		if (strpos(trim($sql), 'SELECT') !== 0) {
			return $rows;
		}
		
		// Az SQL kérés feldarabolása tömbre a „?” karakterek mentén. Ezután
		// össze lehet fésülni az SQL kérés darabjait a paraméterekkel:
		// SQL darab, paraméter, SQL darab, paraméter, ... , SQL darab
		// Ha a $params üres, akkor további teendő nincs, végre lehet hajtani
		// az SQL kérést.
		if ($params) {
			$sql_parts = explode('?', $sql);
			
			// Megfelelő számú paraméter jött-e?
			if (count($sql_parts) - 1 != count($params)) {
				// Hiba, a paraméterek száma nem egyezik a „?”-ek számával
				return $out;
			}
		
			// A tömbök összefésülése
			$sql = '';
			for ($i = 0; $i < count($params); $i++) {
				$sql .= $sql_parts[$i];
				$sql .= htmlspecialchars($this->real_escape_string(trim($params[$i])));
			}
			$sql .= $sql_parts[count($sql_parts) - 1];
		}

		// A lekérdezés végrehajtása. Ha voltak paraméterek, akkor azok be lettek
		// ágyazva a lekérdezésbe. A paraméterek adatellenőrzésen estek át.
		$result = $this->query($sql);
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
		
		return $rows;
	}
	
	/**
	 * A függvény ugyanazt végzi el mint a select(), azonban nem az egész
	 * eredményhalmazzal tér vissza, hanem csak egyetlen rekorddal.
	 * Azért íródott ez a függvény, mert gyakran kell olyan lekérdezéseket
	 * futtatni, ahol az eredményhalmaz vagy csak egyetlen sorból áll, vagy
	 * csak az eredményhalmaz első sora kell. Ezekben az esetekben sokkal
	 * könnyebb a visszakapott adatokkal dolgozni, ha az olyan egydimenziós
	 * tömbként érkezik, ahol a kulcsok megegyeznek a tábla oszlopneveivel.
	 *
	 * A visszaadott tömb az alábbi formában jelenik meg:
	 * array(
	 * 	[oszlop1] => érték1,
	 * 	[oszlop2] => érték2,
	 * 	...
	 * )
	 *
	 * @param string $sql A paraméteres SQL kérés.
	 * @param array $params A kérésbe behelyettesítendő paraméterek.
	 * @return Az eredményhalmaz sorát tartalmazó asszociatív tömb, ahol a
	 * kulcsok megegyeznek a tábla oszlopneveivel. Lehet üres tömb is.
	 * @see select()
	 */
	public function select_one_row($sql, $params = array()) {
		$result = $this->select($sql, $params);
		if ($result) {
			// Ha a $result nem üres tömb, akkor annak az 1. eleme kell
			return $result[0];
		} else {
			// Ha a $result üres
			return array();
		}
	}

	/**
	 * A paraméterként megadott adatbázis táblába beszúrja a kapott tömbben lévő
	 * értékek közül azokat, amelyeknek a kulcsa megegyezik a táblában lévő
	 * oszlopok nevével. Ha olyan kulcsú elemet talál a tömbben, amelyhez nem
	 * lehet oszlopot párosítani, akkor az az elem nem kerül beszúrásra.
	 * Meghívja az update() függvényt $id = null paraméterrel. A tényleges
	 * beszúrást az update() fogja elvégezni.
	 *
	 * @param string $table
	 *   Az az adatbázis tábla, amelybe az adatokat be szeretnénk szúrni
	 *
	 * @param array $data
	 *   A beszúrni kívánt adatokat tartalmazó asszociatív tömb
	 *
	 * @return
	 *   Siker esetén a beszúrt rekord azonosítója, egyébként FALSE
	 *
	 * @see update()
	 */
	public function insert($table, $data) {
		if ($this->update($table, $data)) {
			return $this->insert_id;
		}
		return false;
	}

	/**
	 * Beszúr, vagy módosít egy rekordot a megadott táblában. A paraméterként
	 * kapott asszociatív tömbből kikeresi azokat az elemeket, amelyeknek a kulcsa
	 * megegyezik a táblában lévő oszlopok nevével. Ha olyan kulcsú elemet
	 * talál a tömbben, amelyhez nem lehet oszlopot párosítani, akkor az az
	 * elem nem kerül feldolgozásra.
	 * Ha a 3. paraméter (az $id) értéke NULL, akkor a paraméterként kapott
	 * tömbben lévő értékeket új rekordként beszúrja, egyébként pedig az $id-ban
	 * megadott indexű rekordot módosítja.
	 * Azért került összevonaásra az insert() függvénnyel, mert az INSERT és az
	 * UPDATE csak abban különbözik, hogy az UPDATE végén van egy WHERE id = x
	 * feltétel, illetve más a lekérdezés kezdő szava.
	 *
	 * @param string $table
	 *   Az az adatbázis tábla, amelyben az adatokat módosítani szeretnénk
	 *
	 * @param array $data
	 *   A módosítandó adatokat tartalmazó asszociatív tömb
	 *
	 *	@param int $id
	 *   Az az azonosító, amely rekordot módosítani kell. Ha értéke NULL, akkor
	 *   nem módosítás kell, hanem a rekordot újként kell beszúrni.
	 *
	 * @return
	 *   Siker esetén az érintett sorok száma, egyébként FALSE
	 */
	public function update($table, $data, $id = null) {
		// TODO: Van-e tábla megadva, illetve meg lehetne vizsgálni, hogy a SHOW TABLES eredményében van-e ilyen tábla
		/*if ($table == '') {
			return false;
		}*/
	
		// Nem lehet megcsinálni a beszúrást, ha nincsenek adatok 
		/*if (empty($input_array)) {
			return false;
		}*/
		// Ez lehet, hogy nem kell, mert hiba esetén egyébként is FALSE a visszatérési érték
		
		// A tábla oszlopainak lekérdezése
		$result = $this->query('SHOW COLUMNS FROM ' . $this->real_escape_string($table));
		$columns = array();
		while($row = $result->fetch_assoc()) {
			// Csak a Field oszlop kell az eredményhalmazból, de annak az értéke
			// kulcsként kell a tömb metszetképzés miatt
			$columns[$row['Field']] = 1;
		}
		
		// Beszúrandó adatok: a tábla oszlopainak és a kapott tömb kulcsainak
		// metszeteként kapott tömb
		$data_to_insert = array_intersect_key($data, $columns);
		
		// Ha az $id meg van adva, akkor UPDATE, egyébként INSERT
		// UPDATE tabla SET oszlop1 = ertek1, oszlop2 = ertek2, ... WHERE id = ...
		// INSERT INTO tabla SET oszlop1 = ertek1, oszlop2 = ertek2, ...
		$sql = $id ? 'UPDATE ' : 'INSERT INTO ';
		$sql .= $this->real_escape_string($table) . ' SET';
		
		// A kapott tömb adatainak ellenőrzése
		foreach ($data_to_insert as $column => $value) {
			$sql .= sprintf(" `%s` = '%s',",
				htmlspecialchars($this->real_escape_string(trim($column))),
				htmlspecialchars($this->real_escape_string(trim($value)))
			);
		}
		
		// A végén most van egy felesleges vessző
		$sql = substr($sql, 0, -1);
		
		// Csak update esetén kell a lekérdezés végére a feltétel
		if ($id) {
			$sql .= ' WHERE id = ' . $this->real_escape_string($id);
		}
		
		if ($this->query($sql)) {
			return $this->affected_rows;
		}
		return false;
	}

	/**
	 * Kitörli a megadott azonosítójú rekordot a táblából.
	 *
	 * @param $table A tábla neve, ameyből törölni kell
	 * @param $id Annak a sornak az azonosítója (elsődleges kulcsa), amelyet törölni kell
	 * @return Siker esetén a törölt sorok száma (1), egyébként FALSE
	 */
	public function delete($table, $id) {
		if ($id != '') {
			$sql = 'DELETE FROM ' . $this->real_escape_string($table) . ' WHERE id = ' . $this->real_escape_string($id) . ' LIMIT 1';
			if ($this->query($sql)) {
				return $this->affected_rows;
			}
		}
		return false;
	}

	/**
	 * Destruktor. A példány megszűnésekor lezárja az adatbázis-kapcsolatot.
	 */
	public function __destruct() {
		$this->close();
	}
}
?>
<?php
class ContinentModel {
	### PROPIETATS ###
	public	$continent_code = "",
			$name = "";

	### MÈTODES ###
	# SELECT » RETORNA TOTS ELS TIPUS DE CANCER
	public static function getAll() : array {

		$strSQL = "SELECT * FROM continents;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
	public static function getOne(string $strCode) : ? ContinentModel {

		$strSQL = "SELECT * FROM continents WHERE continent_code = ?;";

		return DB::selectOne($strSQL, self::class, [$strCode]);
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

		$strSQL = " INSERT INTO continents(continent_code, name)
					VALUES	(?, ?);";

		return DB::insert($strSQL, [$this->continent_code, $this->name]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE continents 
					SET continent_code = ?, name = ?
					WHERE continent_code = ?;";

		return DB::update($strSQL, [$this->continent_code, $this->name]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $strCode) {

		$strSQL = "DELETE FROM continents WHERE continent_code = ?;";

		return DB::delete($strSQL, [$strCode]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"continent code:	$this->continents_code<br>
				 name:		$this->name<br>";
	}
}

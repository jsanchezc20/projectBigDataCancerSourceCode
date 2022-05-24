<?php
class ContinentModel {
	### PROPIETATS ###
	public	$continent_code = "",
			$name = "";

	### MÈTODES ###
	# SELECT » RETORNA TOTS ELS ACCESSOS
	public static function getAll() : array {

		$strSQL = "SELECT * FROM continent;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
	public static function getOne(int $intId) : ? ContinentModel {

		$strSQL = "SELECT * FROM continent WHERE continent_code = ?;";
		
		return DB::selectOne($strSQL, self::class, [$intId]);
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

		$strSQL = " INSERT INTO continent(continent_code, name)
					VALUES	(?, ?);";

		return DB::insert($strSQL, [$this->continent_code, $this->name]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE continent 
					SET continent_code = ?, name = ?
					WHERE continent_code = ?;";
		
		return DB::update($strSQL, [$this->continent_code, $this->name, $this->continent_code]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM continent WHERE continent_code = ?;";

		return DB::delete($strSQL, [$intId]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"continent_code:$this->continent_code<br>
				 name:			$this->name<br>";
	}
}
<?php
class ContinentModel {
	### PROPIETATS ###
	public	$continent_code = "",
			$name = "";

	### MÈTODES ###
<<<<<<< HEAD
	# SELECT » RETORNA TOTS ELS TIPUS DE CANCER
	public static function getAll() : array {

		$strSQL = "SELECT * FROM continents;";
=======
	# SELECT » RETORNA TOTS ELS ACCESSOS
	public static function getAll() : array {

		$strSQL = "SELECT * FROM continent;";
>>>>>>> 42a781ee12d5a841b24ba89a646d149dfc49dea3

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
<<<<<<< HEAD
	public static function getOne(string $strCode) : ? ContinentModel {

		$strSQL = "SELECT * FROM continents WHERE continent_code = ?;";

		return DB::selectOne($strSQL, self::class, [$strCode]);
=======
	public static function getOne(int $intId) : ? ContinentModel {

		$strSQL = "SELECT * FROM continent WHERE continent_code = ?;";
		
		return DB::selectOne($strSQL, self::class, [$intId]);
>>>>>>> 42a781ee12d5a841b24ba89a646d149dfc49dea3
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

<<<<<<< HEAD
		$strSQL = " INSERT INTO continents(continent_code, name)
=======
		$strSQL = " INSERT INTO continent(continent_code, name)
>>>>>>> 42a781ee12d5a841b24ba89a646d149dfc49dea3
					VALUES	(?, ?);";

		return DB::insert($strSQL, [$this->continent_code, $this->name]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

<<<<<<< HEAD
		$strSQL = " UPDATE continents 
					SET continent_code = ?, name = ?
					WHERE continent_code = ?;";

		return DB::update($strSQL, [$this->continent_code, $this->name]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $strCode) {

		$strSQL = "DELETE FROM continents WHERE continent_code = ?;";

		return DB::delete($strSQL, [$strCode]);
=======
		$strSQL = " UPDATE continent 
					SET continent_code = ?, name = ?
					WHERE continent_code = ?;";
		
		return DB::update($strSQL, [$this->continent_code, $this->name, $this->continent_code]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM continent WHERE continent_code = ?;";

		return DB::delete($strSQL, [$intId]);
>>>>>>> 42a781ee12d5a841b24ba89a646d149dfc49dea3
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
<<<<<<< HEAD
		return	"continent code:	$this->continents_code<br>
				 name:		$this->name<br>";
	}
}
=======
		return	"continent_code:$this->continent_code<br>
				 name:			$this->name<br>";
	}
}
>>>>>>> 42a781ee12d5a841b24ba89a646d149dfc49dea3

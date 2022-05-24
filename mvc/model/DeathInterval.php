<?php
class DeathIntervalMode {
	### PROPIETATS ###
	public	$death_interval_code = "",
			$intervals = "";

	### MÈTODES ###
	# SELECT » RETORNA TOTS ELS ACCESSOS
	public static function getAll() : array {

		$strSQL = "SELECT * FROM continent;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
	public static function getOne(int $intId) : ? DeathIntervalMode {

		$strSQL = "SELECT * FROM continent WHERE death_interval_code = ?;";
		
		return DB::selectOne($strSQL, self::class, [$intId]);
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

		$strSQL = " INSERT INTO continent(death_interval_code, intervals)
					VALUES	(?, ?);";

		return DB::insert($strSQL, [$this->death_interval_code, $this->intervals]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE continent 
					SET death_interval_code = ?, intervals = ?
					WHERE death_interval_code = ?;";
		
		return DB::update($strSQL, [$this->death_interval_code, $this->intervals, $this->death_interval_code]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM continent WHERE death_interval_code = ?;";

		return DB::delete($strSQL, [$intId]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"death_interval_code:	$this->death_interval_code<br>
				 intervals:				$this->intervals<br>";
	}
}
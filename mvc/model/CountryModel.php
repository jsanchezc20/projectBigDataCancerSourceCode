<?php
class CountryModel {
	### PROPIETATS ###
	public	$country_code = "",
			$name= "",
			$fullName= "",
			$continent_code="";


	### MÈTODES ###
	# SELECT » RETORNA TOTS ELS PAISOS
	public static function getAll() : array {

		$strSQL = "SELECT * FROM countries;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
	public static function getOne(int $intId) : ? CountryModel {

		$strSQL = "SELECT * FROM countries WHERE country_code = ?;";
		
		return DB::selectOne($strSQL, self::class, [$intId]);
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

		$strSQL = " INSERT INTO access(country_code, name, fullName, continent_code)
					VALUES	(?, ?, ?, ?);";

		return DB::insert($strSQL, [$this->country_code, $this->name, $this->fullName, $this->continent_code]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE countries
					SET country_code = ?, name = ?, fullName = ?, continent_code = ?
					WHERE country_code = ?;";
		
		return DB::update($strSQL, [$this->country_code, $this->name, $this->fullName, $this->continent_code]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM countries WHERE country_code = ?;";

		return DB::delete($strSQL, [$intId]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"country_code:		$this->country_code<br>
				 name:	$this->name<br>
				 fullName:	$this->fullName<br>
				 continent_code:	$this->continent_code<br>";
	}
}
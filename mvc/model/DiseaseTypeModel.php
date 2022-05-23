<?php
class DiseaseTypeModel {
	### PROPIETATS ###
	public	$disease_type_code = "",
			$name = '';

	### MÈTODES ###
	# SELECT » RETORNA TOTS ELS ACCESSOS
	public static function getAll() : array {

		$strSQL = "SELECT * FROM diseases_types;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
	public static function getOne(string $strCode) : ? DiseaseTypeModel {

		$strSQL = "SELECT * FROM diseases_types WHERE disease_type_code = ?;";
		
		return DB::selectOne($strSQL, self::class, [$strCode]);
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

		$strSQL = " INSERT INTO diseases_types(disease_type_code, description)
					VALUES	(?, ?);";

		return DB::insert($strSQL, [$this->disease_type_code, $this->description]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE diseases_types 
					SET disease_type_code = ?, description = ?
					WHERE access_id = ?;";
		
		return DB::update($strSQL, [$this->disease_type_code, $this->description, $this->access_id]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM diseases_types WHERE disease_type_code = ?;";

		return DB::delete($strSQL, [$intId]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"disease_type_code:		$this->disease_type_code<br>
				 description:	$this->description<br>";
	}
}
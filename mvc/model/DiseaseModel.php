<?php
class DiseaseModel {
	### PROPIETATS ###
	public	$disease_id = "",
			$year = 0,
            $sex = "",
            $country_code = "",
            $disease_type_code = "",
            $createdAt = "",
            $updatedAt = "";

	### MÈTODES ###
	# SELECT » RETORNA TOTS LES MALATIES
	public static function getAll() : array {

		$strSQL = "SELECT * FROM diseases;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
	public static function getOne(int $intId) : ? DiseaseModel {

		$strSQL = "SELECT * FROM diseases WHERE disease_id = ?;";
		
		return DB::selectOne($strSQL, self::class, [$intId]);
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

		$strSQL = " INSERT INTO diseases(disease_id, year, sex, country_code, disease_type_code)
					VALUES	(?, ?, ?, ?, ?, ?);";

		return DB::insert($strSQL, [$this->disease_code, $this->sex, $this->country_code, $this->disease_type_code, $this->createdAt, $this->updateAt]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE diseases
					SET disease_id = ?, sex = ?, country_code = ?, disease_type_code = ?
					WHERE disease_id = ?;";
		
		return DB::update($strSQL, [$this->disease_code, $this->sex, $this->country_code, $this->disease_type_code]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM diseases WHERE disease_id = ?;";

		return DB::delete($strSQL, [$intId]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"disease_code:		$this->disease_id<br>
				 sex:	$this->sex<br>
				 country_code:	$this->country_code<br>
				 disease_type_code:  $this->disease_type_code<br>
                 createdAt:  $this->createdAt<br>
                 updateAt:  $this->updatedAt<br>";
	}
}
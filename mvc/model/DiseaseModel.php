<?php
class DiseaseModel {
	### PROPIETATS ###
	public	$disease_code = "",
            $sex = "",
            $country_code = "",
            $disease_type_code = "",
            $createdAt = "",
            $updateAt = "",

	### MÈTODES ###
	# SELECT » RETORNA TOTS LES MALATIES
	public static function getAll() : array {

		$strSQL = "SELECT * FROM diseases;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
	public static function getOne(int $intId) : ? DiseaseModel {

		$strSQL = "SELECT * FROM diseases WHERE disease_code = ?;";
		
		return DB::selectOne($strSQL, self::class, [$intId]);
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

		$strSQL = " INSERT INTO access(disease_code, sex, country_code, disease_type_code, createdAt, updateAt)
					VALUES	(?, ?, ?, ?, ?, ?);";

		return DB::insert($strSQL, [$this->disease_code, $this->sex, $this->country_code, $this->disease_type_code, $this->createdAt, $this->updateAt]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE diseases
					SET disease_code = ?, sex = ?, country_code = ?, disease_type_code = ?, createdAt = ?, updateAt = ?,
					WHERE disease_code = ?;";
		
		return DB::update($strSQL, [$this->disease_code, $this->sex, $this->country_code, $this->disease_type_code, $this->createdAt, $this->updateAt]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM diseases WHERE disease_code = ?;";

		return DB::delete($strSQL, [$intId]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"disease_code:		$this->disease_code<br>
				 sex:	$this->sex<br>
				 country_code:	$this->country_code<br>
				 disease_type_code:  $this->disease_type_code<br>
                 createdAt:  $this->createdAt<br>
                 updateAt:  $this->updateAt<br>";
	}
}
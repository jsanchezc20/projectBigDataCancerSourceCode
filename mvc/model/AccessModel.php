<?php
class AccessModel {
	### PROPIETATS ###
	public	$access_id = 0,
			$description = "";

	### MÈTODES ###
	# SELECT » RETORNA TOTS ELS ACCESSOS
	public static function getAll() : array {

		$strSQL = "SELECT * FROM access;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR ACCÉS PER ID
	public static function getOne(int $intId) : ? AccessModel {

		$strSQL = "SELECT * FROM access WHERE access_id = ?;";
		
		return DB::selectOne($strSQL, self::class, [$intId]);
	}

	# INSERT » CREA UN NOU ACCÉS
	public function insert() {

		$strSQL = " INSERT INTO access(access_id, description)
					VALUES	(?, ?);";

		return DB::insert($strSQL, [$this->access_id, $this->description]);
	}

	# UPDATE » ACTUALITZA ACCÉS EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE access 
					SET access_id = ?, description = ?
					WHERE access_id = ?;";
		
		return DB::update($strSQL, [$this->access_id, $this->description, $this->access_id]);
	}

	# DELETE » ESBORRA ACCÉS EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM access WHERE access_id = ?;";

		return DB::delete($strSQL, [$intId]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"access_id:		$this->access_id<br>
				 description:	$this->description<br>";
	}
}
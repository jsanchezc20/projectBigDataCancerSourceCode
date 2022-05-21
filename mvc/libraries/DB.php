<?php
class DB {
	### PROPIETATS ###
	# OBJECTE QUE CONTINDRÁ LA CONNEXIÓ
	private static $objConn = null;
	
	### MÈTODES ###
	# CONNEXIÓ A LA BASE DE DADES
	public static function getConn() : PDO {
		#Si no estem connectats, conecta amb la BD
		if (!self::$objConn) {
			$strDSN = SGDB.':host='.DB_HOST.';
							dbname='.DB_NAME.';
							charset='.DB_CHARSET;
			self::$objConn = new PDO($strDSN, DB_USER, DB_PASS);
		}
		#Retorna l'objecte de connexió
		return self::$objConn;
	}

	# PREPARACIÓ DE LA CONSULTA
	private static function executeStatement(string $strSQL, array $arrBindings = []) : PDOStatement {
		#Prepara la sentència
		$stmt = self::getConn()->prepare($strSQL);

		#Vincula els paràmetres
		for ($i = 0; $i < sizeof($arrBindings); $i++)
			$stmt->bindParam($i + 1, $arrBindings[$i]);

		$stmt->execute();
		return $stmt;
	}

	# SELECT » RETORNA UN REGISTRE O null
	public static function selectOne(string $strSQL, string $strClass = 'stdClass', array $arrBindings = []) {
		
		$objRS = self::executeStatement($strSQL, $arrBindings);
		
		return $objRS->rowCount() ? $objRS->fetchObject($strClass) : null;
	}

	# SELECT » RETORNA UN, DIVERSOS REGISTRES O VECTOR BUIT
	public static function selectAll(string $strSQL, string $strClass = 'stdClass', array $arrBindings = []) : array {
			
		$stmt = self::executeStatement($strSQL, $arrBindings);
		
		$arrObj=[];

		while($object=$stmt->fetchObject($strClass))
			$arrObj[]=$object;

		return $arrObj;
	}

	# INSERT » RETORNA ID AUTONUMÈRIC O FALSE
	public static function insert(string $strSQL, array $arrBindings = []) {

		self::executeStatement($strSQL, $arrBindings);

		return self::getConn()->lastInsertId();
	}

	# UPDATE » RETORNA NÚM. DE FILES AFECTADES O FALSE
	public static function update(string $strSQL, array $arrBindings = []) {

		$stmt = self::executeStatement($strSQL, $arrBindings);

		return $stmt ? $stmt->rowCount() : false;
	}

	# DELETE » RETORNA NÚM. DE FILES AFECTADES O FALSE
	public static function delete(string $strSQL, array $arrBindings = []) {

		$stmt = self::executeStatement($strSQL, $arrBindings);
		
		return $stmt ? $stmt->rowCount() : false;
	}
}
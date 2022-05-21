<?php
	class Respuesta{
		#PROPIEDADES
		public	$id = 0,
				$texto='',
				$correcta = 0,
				$idPregunta = 0;

		#MÉTODOS
		###
			#RECUPERA TODAS LAS RESPUESTAS
			public static function getAll() : array {
				$strSQL = "SELECT * FROM respuestas;";
				return DB::selectAll($strSQL,self::class);
			}

			#RECUPERA RESPUESTAS CON UN FILTRO AVANZADO
			public static function getFiltered (string $strField  ='texto',
												string $strValue  ='',
												string $strOrder  ='id',
												string $strAscDesc='ASC') : array {
				$strSQL = "SELECT *
						 FROM respuestas
						 WHERE $strField LIKE '%$strValue%'
						 ORDER BY $strOrder $strAscDesc;";
				return DB::selectAll($strSQL,self::class);
			}

			#RECUPERA UNA RESPUESTA POR ID
			public static function getOne(int $intId) : ? Respuesta{
				$strSQL = "SELECT * FROM respuestas WHERE id=$intId;";
				return DB::selectOne($strSQL,self::class);
			}

			#RECUPERA LAS RESPUESTAS POR ID DE LA PREGUNTA
			public static function getAllByQueryId(int $intId) : array {
				$strSQL = "SELECT texto,correcta FROM respuestas WHERE idPregunta=$intId;";
				return DB::selectAll($strSQL,self::class);
			}

			#INSERTA UNA NUEVA RESPUESTA
			public function insert() {
				$strSQL = "INSERT INTO respuestas(texto,
											 correcta,
											 idPregunta)
						 VALUES			(	'$this->texto',
											 $this->correcta,
											 $this->idPregunta)";
				return DB::insert($strSQL);
			}

			#ACTUALIZA UN RESPUESTA
			public function update() {
				$strSQL = "UPDATE respuestas 
						 SET
							texto	  ='$this->texto',
							correcta   = $this->correcta,
							idPregunta = $this->idPregunta
						 WHERE id=$this->id;";
				return DB::update($strSQL);
			}

			#BORRA UN RESPUESTA POR ID
			public static function delete(int $intId) {
				$strSQL = "DELETE FROM respuestas WHERE id=$intId;";
				return DB::delete($strSQL);
			}

			#RETORNA LA INFORMACIÓN QUE CONTIENE EL OBJETO
			public function __toString() : string {
				return	"ID: $this->id<br>
						 TEXTO: $this->texto<br>
						 CORRECTA: $this->correcta<br>
						 IDPREGUNTA: $this->idPregunta<br>";
			}
		###
	}
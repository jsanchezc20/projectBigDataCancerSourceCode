<?php
	class Pregunta{
		#PROPIEDADES
		public	$id = 0,
				$enunciado='',
				$dificultad = 0,
				$publicacion='';

		#MÉTODOS
		###
			#RECUPERA TODAS LAS PREGUNTAS
			public static function getAll() : array {
				$strSQL = "SELECT * FROM preguntas;";
				return DB::selectAll($strSQL,self::class);
			}

			#RECUPERA PREGUNTAS CON UN FILTRO AVANZADO
			public static function getFiltered (string $strField  ='enunciado',
												string $strValue  ='',
												string $strOrder  ='id',
												string $strAscDesc='ASC') : array {
				$strSQL = "SELECT *
						 FROM preguntas
						 WHERE $strField LIKE '%$strValue%'
						 ORDER BY $strOrder $strAscDesc;";
				return DB::selectAll($strSQL,self::class);
			}

			#RECUPERA UNA PREGUNTA POR ID
			public static function getOne(int $intId) : ? Pregunta{
				$strSQL = "SELECT * FROM preguntas WHERE id=$intId;";
				return DB::selectOne($strSQL,self::class);
			}

			#INSERTA UNA NUEVA PREGUNTA
			public function insert(int $intModuloId) {
				#INICIA TRANSACCIÓN
				DB::getConn()->beginTransaction();

				try {
					#1ª CONSULTA » NUEVA PREGUNTA
					$strSQL = "INSERT INTO preguntas(enunciado,
												 dificultad)
							 VALUES			(	'$this->enunciado',
												 $this->dificultad)";
					$intIdPregunta=DB::insert($strSQL);
					
					if (!$intIdPregunta)
						throw new Exception("Error al insertar una nueva pregunta.");
					#2ª CONSULTA » RELACIÓN PREGUNTAS/MÓDULOS
					$strSQL = "INSERT INTO preguntas_modulos
								(idModulo, idPregunta)
							 VALUES
							 	($intModuloId, $intIdPregunta);";

					if (DB::insert($strSQL)===false)
						throw new Exception("Error al insertar en la tabla de relación 'preguntas_modulos'.");

					#EJECUTA LA TRANSACCIÓN
					DB::getConn()->commit();

					return $intIdPregunta;						
						
				} catch (Throwable $t) {
					DB::getConn()->rollBack();
					return false;					
				}
			}

			#ACTUALIZA UNA PREGUNTA
			public function update() {
				$strSQL = "UPDATE preguntas 
						 SET
							enunciado   ='$this->enunciado',
							dificultad  = $this->dificultad
						 WHERE id=$this->id;";
				return DB::update($strSQL);
			}

			#BORRA UNA PREGUNTA POR ID
			public static function delete(int $intId) {
				$strSQL = "DELETE FROM preguntas WHERE id=$intId;";
				return DB::delete($strSQL);
			}

			#RETORNA LA INFORMACIÓN QUE CONTIENE EL OBJETO
			public function __toString() : string {
				return	"ID: $this->id<br>
						 ENUNCIADO: $this->enunciado<br>
						 DIFICULTAD: $this->dificultad<br>
						 PUBLICACIÓN: $this->publicacion<br>";
			}

			#RETORNA LAS RESPUESTAS DE LA PREGUNTA
			public function getRespuestas() : array {
				$consulta= "SELECT * FROM respuestas 
							WHERE idPregunta=$this->id";
				return DB::selectAll($consulta, 'Respuesta');
			}
		###
	}
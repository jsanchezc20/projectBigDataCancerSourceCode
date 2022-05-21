<?php
	class Modulo{
		#PROPIEDADES
		public	$id = 0,
				$codigo='',
				$nombre='',
				$horas = 0;

		#MÉTODOS
		###
			#RECUPERA TODOS LOS MÓDULOS
			public static function getAll() : array {
				$strSQL = "SELECT * FROM modulos;";
				return DB::selectAll($strSQL,self::class);
			}

			#RECUPERA MÓDULOS CON UN FILTRO AVANZADO
			public static function getFiltered (string $strField  ='nombre',
												string $strValue  ='',
												string $strOrder  ='id',
												string $strAscDesc='ASC') : array {
				$strSQL = "SELECT *
						 FROM modulos
						 WHERE $strField LIKE '%$strValue%'
						 ORDER BY $strOrder $strAscDesc;";
				return DB::selectAll($strSQL,self::class);
			}

			#RECUPERA UN MÓDULO POR ID
			public static function getOne(int $intId) : ? Modulo{
				$strSQL = "SELECT * FROM modulos WHERE id=$intId;";
				return DB::selectOne($strSQL,self::class);
			}

			#INSERTA UN NUEVO MÓDULO
			public function insert(int $intAccionId) {

				#INICIA TRANSACCIÓN
				DB::getConn()->beginTransaction();

				try {
					#1ª CONSULTA » NUEVO MÓDULO
					$strSQL = "INSERT INTO modulos(codigo,
												 nombre,
												 horas)
							 VALUES			   ('$this->codigo',
												'$this->nombre',
												 $this->horas)";
					$intIdModulo=DB::insert($strSQL);
					
					if (!$intIdModulo)
						throw new Exception("Error al insertar un nuevo módulo.");

					#2ª CONSULTA » RELACIÓN MÓDULOS/ACCIONES
					$strSQL = "INSERT INTO modulos_acciones
								(idAccion, idModulo)
							 VALUES
							 	($intAccionId, $intIdModulo);";
					
					if (DB::insert($strSQL)===false)
						throw new Exception("Error al insertar en la tabla de relación 'modulos_acciones'.");

					#EJECUTA LA TRANSACCIÓN
					DB::getConn()->commit();

					return $intIdModulo;

				} catch (Throwable $t) {
					DB::getConn()->rollBack();
					return false;
				}
			}

			#ACTUALIZA UN MÓDULO
			public function update() {
				$strSQL = "UPDATE modulos 
						 SET
							codigo ='$this->codigo',
							nombre ='$this->nombre',
							horas  = $this->horas
						 WHERE id=$this->id;";
				return DB::update($strSQL);
			}

			#BORRA UN MÓDULO POR ID
			public static function delete(int $intId) {
				$strSQL = "DELETE FROM modulos WHERE id=$intId;";
				return DB::delete($strSQL);
			}

			#RETORNA LA INFORMACIÓN QUE CONTIENE EL OBJETO
			public function __toString() : string {
				return	"ID: $this->id<br>
						 CÓDIGO: $this->codigo<br>
						 NOMBRE: $this->nombre<br>
						 HORAS: $this->horas<br>";
			}

			#RECUPERA LAS PREGUNTAS DE UN MÓDULO POR ID
			public function getPreguntas() {
				$strSQL = "SELECT *
						 FROM preguntas_modulos pm
						 INNER JOIN preguntas p ON pm.idPregunta=p.id
						 WHERE idModulo=$this->id";
				return DB::selectAll($strSQL,'Pregunta');
			}
		###
	}
<?php
	class Nivel{
		#PROPIEDADES
		public	$id = 0,
				$nivel = 0,
				$descripcion='',
				$requisitos='';

		#MÉTODOS
		###
			#RECUPERA TODOS LOS NIVELES
			public static function getAll() : array {
				$strSQL = "SELECT * FROM niveles;";
				return DB::selectAll($strSQL,self::class);
			}

			#RECUPERA NIVELES CON UN FILTRO AVANZADO
			public static function getFiltered (string $strField  ='descripcion',
												string $strValue  ='',
												string $strOrder  ='nivel',
												string $strAscDesc='ASC') : array {
				$strSQL = "SELECT *
						 FROM niveles
						 WHERE $strField LIKE '%$strValue%'
						 ORDER BY $strOrder $strAscDesc;";
				return DB::selectAll($strSQL,self::class);
			}

			#RECUPERA UN NIVEL POR ID
			public static function getOne(int $intId) : ? Nivel{
				$strSQL = "SELECT * FROM niveles WHERE nivel=$intId;";
				return DB::selectOne($strSQL,self::class);
			}

			#INSERTA UN NUEVO NIVEL
			public function insert() {
				$strSQL = "INSERT INTO niveles(nivel,
											 descripcion,
											 requisitos)
						 VALUES			(	 $this->nivel,
											'$this->descripcion',
											'$this->requisitos')";
				return DB::insert($strSQL);
			}

			#ACTUALIZA UN NIVEL
			public function update() {
				$strSQL = "UPDATE niveles 
						 SET
							nivel	   =$this->nivel,
							descripcion ='$this->descripcion',
							requisitos  ='$this->requisitos'
						 WHERE nivel=$this->nivel;";
				return DB::update($strSQL);
			}

			#BORRA UN NIVEL POR ID
			public static function delete(int $intId) {
				$strSQL = "DELETE FROM niveles WHERE nivel=$intId;";
				return DB::delete($strSQL);
			}

			#RETORNA LA INFORMACIÓN QUE CONTIENE EL OBJETO
			public function __toString() : string {
				return	"NIVEL: $this->nivel<br>
						 DESCRIPCIÓN: $this->descripcion<br>
						 REQUISITOS: $this->requisitos<br>";
			}
			
		###
	}
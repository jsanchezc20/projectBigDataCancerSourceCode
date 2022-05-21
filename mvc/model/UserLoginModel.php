<?php
	class UserLoginModel {
		# PROPIETAT QUE CONTÉ L'USUARI IDENTIFICAT
		private static $objUser = null;

		# RETORNA L'USUARI IDENTIFICAT
		public static function getUser() {
			return self::$objUser;
		}

		 # RETORNA SI L'USUARI IDENTIFICAT ÉS ADMIN
		public static function isAdmin() : bool {
			return self::$objUser && self::$objUser->access_id == 1 ;
		}

		# REALITZA LOG-IN
		public static function logIn(string $strUser, string $strPassword) {
			# TRACTA D'IDENTIFICAR L'USUARI
			$objUser = UserModel::identify($strUser, $strPassword);

			# SI NO S'IDENTIFICA A L'USUARI
			if (!$objUser)
				throw new Exception("Error en la identificació d'Usuari '$strUser'.");

			# GUARDA L'USUARI EN VARIABLE DE SESSIÓ
			$_SESSION["user"] = serialize($objUser);
		}

		#REALIZA log-out
		public static function logOut() {
			#BUIDA EL VECTOR DE SESSIÓ
			session_unset();

			header("Refresh:1; url=".DEFAULT_PATH."index.php");
			die("<div style='background-color:#396;
							border-radius:5px 15px;
							border:3px solid #69C;
							bottom:0;
							color:#FFF;
							font-family:monospace;
							font-size:3.000em;
							height:60px;
							left:0;
							margin:auto;
							padding:15px;
							position:absolute;
							right:0;
							text-align:center;
							top:0;
							width:900px'>
					Redirigiendo a la portada…
				 </div>");
		}

		# MÈTODE QUE GESTIONA LES OPERACIONS DE LOGIN/LOGOUT
		# A PARTIR DE LES SOL·LICITUDS D'USUARI (FORMULARIS)
		public static function check() {
			#LogIn
			if (!empty($_POST["logIn"]))
				self::LogIn($_POST["user"], $_POST["password"]);

			#logOut
			if (!empty($_POST["logOut"]))
				self::logOut();

			# RECUPERA LA INFO DE SESSIÓ PER A GUARDAR-LA EN $OBJUSER
			self::$objUser = empty($_SESSION["user"]) ? null : unserialize($_SESSION["user"]);
		}
	}
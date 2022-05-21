<?php
	class FC {

		# MÈTODE PER A RECUPERAR USUARI I CARREGAR LA VISTA
		public static function getUserView(string $strView, $arrObj = null) {

			# RECUPERA L'USUARI PER A PASSAR-LO A LA VISTA
			$objUser = UserLoginModel::getUser();

			# CARREGA LA VISTA
			require_once "./mvc/view/$strView.php";
		}

		# MÈTODE PER A CONTROLAR L'ACCÉS A DETERMINAT CONTINGUT
		private static function checkAccess(string $strController,
											string $strMethod) {
			
			if (UserLoginModel::isAdmin())
				return;

			$strMsgError="";

			if (strpos(" update delete", $strMethod))
				$strMsgError="No tens permissos per<br>Actualitzar, Esborrar o Editar.";

			if (empty($_SESSION["user"]) && strpos(" Query", $strController))
				$strMsgError="No tens accés a l'apartat Preguntes.";

			if (strpos(" user", $strController))
				$strMsgError="No tens accés a l'apartat d'Usuaris.";

			if (strlen($strMsgError))
				throw new Exception($strMsgError);
		}

		# MÈTODE PRINCIPAL
		public static function main() {
			try {
				# CONTROL DE LOG_IN/LOG_OUT
				UserLoginModel::check();

				# RECUPERA EL CONTROLADOR
				$c = empty($_GET["c"]) ? DEFAULT_CONTROLLER : $_GET["c"];

				# RECUPERA EL MÈTODE
				$m = empty($_GET["m"]) ? DEFAULT_METHOD : $_GET["m"];

				# VERIFICA QUE L'USUARI TINGUI PERMISOS PER A ACCEDIR
				self::checkAccess($c, $m);

				switch ($c) {
					case "img":
						throw new Exception();
					case "access":
						$c = "AccessController";
						break;
					case "user": 
						// if (!UserLoginModel::isAdmin())
						// 	throw new Exception("No tens permissos per accedir a l'apartat d'usuaris.");
						$c = "UserController";
						break;
					// TODO
				}

				# RECUPERA EL PARÀMETRE
				$p = empty($_GET["p"]) ? '' : $_GET["p"];

				# CARREGA EL CONTROLADOR
				$objController = new $c();

				# COMPROVA SI EXISTEIX
				if (!is_callable([$objController,$m]))
					throw new Exception("No existeix la operació $m");
				
				# CRIDA AL MÈTODE DEL CONTROLADOR, PASSANT EL PARÀMETRE
				$objController->$m($p);

			} # SI ES PRODUEIX UN ERROR
			 catch (Throwable $e) {
				if ($e->getMessage()) {
					$arrMsg = ["Error","Error » ".$e->getMessage(),""];
					self::getUserView("frontSuccessError", $arrMsg);
				}
			}
		}
	}
<?php
class Welcome {
	public function index() {
		FC::getUserView("frontSuccessError");
	}

	public function contact() {
		FC::getUserView("contact");
	}

	public function send() {
		if (!empty($_POST["enviar"])) {
			$strTo="jaime.sc@gmail.com";

			#Emisor
			$strFrom=$_POST["txtNombreApellidos"];

			#eMail
			$strEmail=$_POST["txtEmail"];

			#Asunto
			$strSubject=$_POST["txtAsunto"];

			#Cabeceras
			$strHeaders="MIME-Version:1.0\r\n".
						"Content-type:text/html;charset=UTF-8\r\n".
						"to:<$strTo>\r\n".
						"From:$strFrom <$strEmail>\r\n";
			#Mensaje
			$strMessage=$_POST["txtMensaje"];

			if (mail($strTo, $strSubject, $strMessage, $strHeaders))
				$arrMsg = ["Success","Su mensaje ha sido enviado correctamente a las ".date("Y-m-d H:i:s").".","welcome"];
			else
				$arrMsg = ["Error","Su mensaje de correo no pudo ser enviado.".date("Y-m-d H:i:s").".","welcome"];

			FC::getUserView("frontSuccessError", $arrMsg);
		}
	}
}
<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Formulari de contacte</title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
	</head>
	<body>
		<?php
			$strUser='';
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();
		?>
        <div class="formContainer">
            <form action="/welcome/send" method="post">
                <div class="formHeader med br">Formulari de contacte</div>
                <div class="formGroup">
                    <input type="text"
                           id="txtNombreApellidos"
                           name="txtNombreApellidos"
                           title="Nom i Cognoms (requerit)"
                           required>
                    <span class="bar"></span>
                    <label for="txtNombreApellidos">Nom i Cognoms</label>
                </div>
                <div class="formGroup">
                    <input type="text"
                           id="txtEmail"
                           name="txtEmail"
                           title="e-mail@domini.com (requerit)"
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                           required>
                    <span class="bar"></span>
                    <label for="txtEmail">e-mail@domini.com</label>
                </div>
                <div class="formGroup">
                    <input type="text"
                           id="txtAsunto"
                           name="txtAsunto"
                           title="Assumpte (requerit)"
                           required>
                    <span class="bar"></span>
                    <label for="txtAsunto">Assumpte</label>
                </div>
                <div class="formGroup">
                    <textarea
                            id="txtMensaje"
                            name="txtMensaje"
                            cols="70"
                            rows="13"
                            title="Missatge (requerit)"
                            required></textarea>
                    <span class="bar"></span>
                    <label for="txtMensaje">Missatge</label>
                </div>
                <div class="formContainerFoot tac">
                    <a class="iconButtonContainer" onclick="enviarMissatgeSubmit.click();">
                        <div class="fl">
                            <?=Utils::loadFile("svg","send")?>
                        </div>
                        <span class="sml bd">Enviar Missatge</span>
                    </a>
                    <a class="iconButtonContainer" onclick="esborrarMissatgeReset.click();">
                        <div class="fl">
                            <?=Utils::loadFile("svg","reset")?>
                        </div>
                        <span class="sml bd">Esborrar Formulari</span>
                    </a>
                    <input class="dn" id="enviarMissatgeSubmit"	 name="logIn" type="submit" value="Enviar">
                    <input class="dn" id="esborrarMissatgeReset" name="reset" type="reset"  value="Borrar">
                </div>
            </form>
        </div>
		<?php Template::footer();?>
	</body>
</html>
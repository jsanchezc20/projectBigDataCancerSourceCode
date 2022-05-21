<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Usuari nou</title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
        <script src="<?=DEFAULT_PATH?>js/user.js"></script>
	</head>
	<body onload="name_id.focus();">
		<?php
			$objUser = null;
		    # MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();
		?>
        <div class="formContainer">
            <div class="formHeader med br">Usuari nou</div>
                <form action="/user/store" method="post"><?php
                foreach($arrObj as $objUser) {
					foreach ($objUser as $key => $value) {
						if ($key == "Field" && !(strpos("user_id created updated", $value) !== false)) {
							$strPasswordMatch = ""; ?>
                            <div class="formGroup"><?php
                                switch ($value) {
                                    case "name":
                                        $strLabel = "Usuari (3-30…lletres, números i punts)" ?>
                                        <input type="text"
                                               name="<?= $value ?>"
                                               id='<?= $value ?>_id'
                                               pattern="[a-z0-9]{3,30}"
                                               title="^(?!\.)(?!.*\.$)(?!.*?\.\.)[a-zA-Z\d.]+$"
                                               onblur='checkUser(this);'
                                               required><?php
                                        break;
                                    case "password":
                                        $strLabel = "Contrasenya (8-15…qualsevol caràcter)" ?>
                                        <input type="password" id="id<?= $value ?>" name="<?= $value ?>" required><?php
                                        $strPasswordMatch = "
                                            <div class='formGroup'>
                                                <input type='password' id='passwordMatch' required>
                                                <span class='bar'></span>
                                                <label  for='passwordMatch'>Confirmar contrasenya</label>
                                            </div>";
                                        break;
                                    case "access_id":
                                        $strLabel = "Accés";
                                        $objAccess = AccessModel::getAll(); ?>
                                        <select name="<?= $value ?>" id="<?= $value ?>_id">
                                        <option value="0">
                                            Selecciona el tipus d'accés
                                        </option><?php
                                        foreach ($objAccess as $idxArr => $objFieldValue) { ?>
                                            <option value='<?= $objFieldValue->id ?>'
                                                    title='<?= htmlentities($objFieldValue->access_id . " » " . $objFieldValue->description, ENT_QUOTES) ?>'><?php
                                            echo "$objFieldValue->access_id » $objFieldValue->description" ?>
                                            </option><?php
                                        } ?>
                                        </select><?php
                                        break;
                                    default:
                                        ?>
                                        <input type='text' name='<?= $value ?>' id='<?= $value ?>_id' required><?php
                                } ?>
                                <span class="bar"></span><?php
                                echo "<label for='" . $value . "'>" . $strLabel . "</label>";
                                echo "<span id='" . $value . "Err' class='lblError nrm fr'></span>" ?>
                            </div><?php
                            echo $strPasswordMatch;
						}
					}
				} ?>
                    <div class="formContainerFoot tac">
                        <a class="iconButtonContainer" onclick="guardarUsuariSubmit.click();">
                            <div class="fl">
								<?=Utils::loadFile("svg", "save")?>
                            </div>
                            <span class="sml bd">Guardar</span>
                        </a>
                        <a class="iconButtonContainer" href="<?=$_SERVER['HTTP_REFERER']?>">
                            <div class="fl">
								<?=Utils::loadFile("svg", "back")?>
                            </div>
                            <span class="sml bd">Tornar</span>
                        </a>
                        <input class="dn" id="guardarUsuariSubmit" name="guardarUsuariSubmit" type="submit" value="Guardar">
                    </div>
                </form>
        </div>
        <?php Template::footer(); ?>
    </body>
</html>
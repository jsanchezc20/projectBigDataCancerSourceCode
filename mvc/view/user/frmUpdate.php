<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Actualitzar usuari</title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
	</head>
	<body onload="btnActualizar.focus();">
		<?php
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();?>
		<form action="/user/update" method="post">
			<table class="formTable">
				<tr>
                    <th class="formHeader med" colspan="2">
						Actualitzar usuari
					</th>
				</tr><?php
                foreach ($arrObj as $key => $value) {
                    if ($key == "id" || $key == "publicacion") { ?>
                        <input type="hidden" name="<?=$key?>" value="<?=$value?>"><?php
                    }
                    else {
						if (!(strpos(" user_id createdAt updatedAt", $key) !== false)) { ?>
                            <tr>
                                <th class="formDetails tar">
                                    <?php
                                        $strTitulo="";
                                        switch ($key) {
                                            case "name"     : $strTitulo = "Nom";           break;
                                            case "password" : $strTitulo = "Contrasenya";   break;
											case "access_id": $strTitulo = "Accés";         break;
                                            default         : $strTitulo = "$key";
                                        }
                                        echo $strTitulo;
                                    ?>
                                </th>
                                <td><?php
                                    switch($key) {
                                        case "password":?>
                                            <input type='password' name='oldPassword' id='oldPassword' onblur="console.log('cambia')" value='' placeholder="Introdueix la contrasenya antiga" required>
                                            <input type='password' name='newPassword' id='newPassword' value='' placeholder="Introdueix la nova contrasenya" required>
                                            <input type='password' name='newPassword' id='newPassword' value='' placeholder="Confirma la nova contrasenya" required>
                                            <?php
                                            break;
                                        case "access_id":
                                            $objAccess = AccessModel::getAll();?>
                                            <select name="selAccessId" id="selAccessId"><?php
											foreach ($objAccess as $idxArr => $objFieldValue) { ?>
	                                                <option value='<?=$objFieldValue->access_id?>' <?=($idxArr + 1) == $value ? "selected='selected'" : ""?>
                                                        title='<?=htmlentities($objFieldValue->access_id." » ".$objFieldValue->description,ENT_QUOTES)?>'>
                                                    <?php echo "$objFieldValue->access_id » $objFieldValue->description"?>
                                                </option><?php
											} ?>
                                            </select><?php
											break;
                                        default:?>
                                            <input type='text' name='<?=$value?>' id='id<?=$value?>' value='<?=$value?>' required><?php
                                    }
                                ?>
                                </td>
                        </tr><?php
                        }
                    }
                } ?>
				<tr>
                    <td class="tac" colspan="2">
                        <a class="iconButtonContainer" onclick="actualitzarUsuariSubmit.click();">
                            <div class="fl">
								<?=Utils::loadFile("svg", "save")?>
                            </div>
                            <span class="sml bd">Actualitzar</span>
                        </a>
                        <a class="iconButtonContainer" href="<?=$_SERVER['HTTP_REFERER']?>">
                            <div class="fl">
								<?=Utils::loadFile("svg", "back")?>
                            </div>
                            <span class="sml bd">Tornar</span>
                        </a>
                        <input class="dn" id="actualitzarUsuariSubmit" name="actualitzarUsuariSubmit" type="submit" value="Guardar">
                    </td>
				</tr>
			</table>
		</form>
		<?php Template::footer(); ?>
	</body>
</html>
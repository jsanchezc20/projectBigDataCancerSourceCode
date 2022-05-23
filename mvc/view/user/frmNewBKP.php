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
		<form action="/user/store" method="post">
			<table class="formTable">
				<tr>
					<th class="formHeader med" colspan="2">
						Usuari nou
					</th>
				</tr>
			<?php
			foreach($arrObj as $objUser)
				foreach ($objUser as $key => $value)
					if ($key == "Field" && !(strpos("user_id createdAt updatedAt", $value) !== false)) { ?>
						<tr>
							<th class="tar pr"><?php
                                $strTitulo="";
                                switch ($value) {
                                    case "name"		: $strTitulo = "Nom";			break;
                                    case "password"	: $strTitulo = "Contrasenya";	break;
                                    case "access_id": $strTitulo = "Accés";			break;
                                    default			: $strTitulo = "$value";
                                }
                                echo $strTitulo;?>
							</th>
							<td class="tal"><?php
							if ($key=="Field") {
								switch($value) {
                                    case "name":?>
										<input type="text" name="<?=$value?>" id='<?=$value?>_id' required  placeholder="Nom"><?php
                                        break;
									case "password":?>
										<input type="password" id="id<?=$value?>" name="<?=$value?>" placeholder="Contrasenya" required>
                                        <input type="password" id="id<?=$value?>Match" placeholder="Confirmar contrasenya" required><?php
										break;
									case "access_id":
										$objAccess = AccessModel::getAll();?>
										<select name="selAccessId" id="selAccessId"><?php
											foreach ($objAccess as $idxArr => $objFieldValue) { ?>
												<option value='<?=$objFieldValue->id?>' title='<?=htmlentities($objFieldValue->access_id." » ".$objFieldValue->description,ENT_QUOTES)?>'><?php echo "$objFieldValue->access_id » $objFieldValue->description"?></option><?php
											} ?>
										</select><?php
										break;
									default:?>
										<input type='text' name='<?=$value?>' id='<?=$value?>_id' required><?php
								}
							} ?>
							</td>
						</tr><?php
					} ?>
						<tr>
							<td class="tac" colspan="2">
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
							</td>
						</tr>
					</table>
			<?php Template::footer(); ?>
		</form>
	</body>
</html>
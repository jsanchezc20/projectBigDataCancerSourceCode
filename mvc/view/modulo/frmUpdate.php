<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			Actualizar pregunta
		</title>
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
		<form action="/module/update" method="post">
			<table>
				<tr>
					<th class="green med" colspan="2">
						Actualizar módulo
					</th>
				</tr><?php
		foreach ($arrObj as $key => $value) {
			if ($key=="id" || $key=="publicacion") { ?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>"><?php
			}
			else {
				$intLen=strlen($value);?>
				<tr>
					<th class="r">
						<?=$key?>
					</th>
					<td><?php
					if ($intLen>100) { ?>
						<textarea name="<?=$key?>" rows="<?=intval($intLen*0.020)?>" cols="60"><?=$value?></textarea><?php
					} else { ?>
						<input type="text" name="<?=$key?>" id="id<?=$key?>" value="<?=$value?>" required><?php
					} ?>
					</td>
				</tr><?php
			}
		} ?>
				<tr>
					<td class="c" colspan="2">
						<input type="submit" name="actualizar" value="Actualizar" id="btnActualizar">
						<input type="button" name="volver" value="Volver" onclick="window.history.back();return false;">
					</td>
				</tr>
			</table>
		</form>
		<?php Template::footer(); ?>
	</body>
</html>
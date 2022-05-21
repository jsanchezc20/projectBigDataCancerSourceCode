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
		<form action="/query/update" method="post">
			<table>
				<tr>
					<th class="green med" colspan="2">
						Actualizar pregunta
					</th>
				</tr><?php
		foreach ($arrObj as $key => $value) {
			if ($key=="id" || $key=="publicacion") { ?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>"><?php
			}
			else { ?>
				<tr>
					<th class="r">
						<?=$key?>
					</th>
					<td>
						<input type="text" name="<?=$key ?>" id="id<?=$key?>" value="<?=$value?>" required>
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
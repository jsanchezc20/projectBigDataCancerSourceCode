
<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			Confirmación de borrado de pregunta
		</title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
	</head>
	<body onload="btnBorrar.focus();">
		<?php
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();
		?>
		<form action="/action/destroy" method="post">
			<input type="hidden" name="id" value="<?=$arrObj->id;?>">
			<input type="hidden" name="nombre" value="<?=$arrObj->nombre;?>">
			<input type="hidden" name="codigo" value="<?=$arrObj->codigo;?>">
			<table>
				<tr>
					<th class="green med">
						Confirmación de borrado de la acción
					</th>
				</tr>
				<tr>
					<td class="c">
						<?=$arrObj->nombre;?>
					</td>
				</tr>
				<tr>
					<td class='Pregunta Back c' colspan="2">
						<input type="submit" name="borrar" value="Borrar" id="btnBorrar">
						<input type="button" name="volver" value="Volver" onclick="window.history.back();return false;">
					</td>
				</tr>
			</table>
		</form>
		<?php Template::footer(); ?>
	</body>
</html>
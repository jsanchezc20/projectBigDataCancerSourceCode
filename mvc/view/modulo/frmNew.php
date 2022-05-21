<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			Nueva pregunta
		</title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
	</head>
	<body onload="selAccionId.focus();">
		<?php
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();
		?>
		<form action="/module/store" method="post">
			<table>
				<tr>
					<th class="green med" colspan="2">
						Nuevo módulo
					</th>
				</tr>
				<tr>
					<th class="r">Módulo</th>
					<td><?php
						$objAcciones=Accion::getAll();?>
						<select name="selAccionId" id="selAccionId"><?php
							foreach ($objAcciones as $idxArr => $objFieldValue) { ?>
								<option value='<?=$objFieldValue->id?>' title='<?=htmlentities($objFieldValue->codigo." » ".$objFieldValue->nombre,ENT_QUOTES)?>'><?php echo "$objFieldValue->codigo » $objFieldValue->nombre"?></option><?php
							} ?>
						</select>
					</td>
				</tr>
	<?php
	foreach($arrObj as $objAccion)
		foreach ($objAccion as $key => $value)
			if ($key=="Field" && $value!="id") { ?>
				<tr>
					<th class='r'>
						<?=ucfirst($value)?>
					</th>
					<td class='l'><?php
					if ($key=="Field") { ?>
						<input type='text' name='<?=$value?>' id='id<?=$value?>' required><?php
					} ?>
				 	</td>
				</tr><?php
			} ?>
				<tr>
					<td class='c' colspan="2">
						<input type="submit" name="guardar" value="Guardar">
						<input type="button" name="volver" value="Volver" onclick="self.location='/module'">
					</td>
				</tr>
			</table>
			<?php Template::footer(); ?>
		</form>
	</body>
</html>
<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			Nueva acción
		</title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
	</head>
	<body onload="idcodigo.focus();">
		<?php
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();
		?>
		<form action="/action/store" method="post">
			<table>
				<tr>
					<th class="green med" colspan="2">
						Nova entrada enfermetat
					</th>
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
					if ($key=="Field") {
						switch ($value) {
							case 'nivel':
								$objNiveles=Nivel::getAll();?>
								<select name="$value"><?php
									foreach ($objNiveles as $idxArr => $objFieldValue) { ?>
										<option value='<?=$objFieldValue->nivel?>' title='<?=htmlentities($objFieldValue->requisitos,ENT_QUOTES)?>'><?php echo "$objFieldValue->descripcion » $objFieldValue->requisitos"?></option><?php
									} ?>
								</select><?php
								break;
							case "Country_code":
								$objCountry = CountryModel::getAll();?>
								<select name="selAccessId" id="selAccessId"><?php
								foreach ($objAccess as $idxArr => $objFieldValue) { ?>
										<option value='<?=$objFieldValue->access_id?>' <?=($idxArr + 1) == $value ? "selected='selected'" : ""?>
											title='<?=htmlentities($objFieldValue->access_id." » ".$objFieldValue->description,ENT_QUOTES)?>'>
										<?php echo "$objFieldValue->access_id » $objFieldValue->description"?>
									</option><?php
								} ?>
								</select><?php
								break;
							case "Disease_type_code":
							case "Death_interval_code":
								
							case 'descripcion':
							case 'objectivos':
							case 'requisitos':?>
								<textarea name="<?=$key?>" rows="10" cols="60"></textarea><?php
								break;
							default:?><input type='text' name='<?=$value?>' id='id<?=$value?>' required><?php
						}
					
					} ?>
				 	</td>
				</tr><?php
			} ?>
				<tr>
					<td class='c' colspan="2">
						<input type="submit" name="guardar" value="Guardar">
						<input type="button" name="volver" value="Volver" onclick="self.location='/action'">
					</td>
				</tr>
			</table>
			<?php Template::footer(); ?>
		</form>
	</body>
</html>
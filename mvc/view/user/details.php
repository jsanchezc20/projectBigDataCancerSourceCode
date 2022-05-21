<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Pregunta <?=$intPreguntaID?></title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
	</head>
	<body onload="volver.focus();">
		<?php
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();
		?>
        <table class="formTable">
			<tr>
                    <th class="formHeader med br" colspan="2">
					Detall - Usuari
				</th>
			</tr>
			<?php
				foreach($arrObj as $key => $value) { ?>
						<tr class="nrm br">
							<th class="formDetails tar">
								<?=$key?>
							</th>
							<td>
								<?=$value?>
							</td>
						</tr><?php
                } ?>
			<tr>
				<td class="tac nrm" colspan="2">
                    <a class="iconButtonContainer" href="<?=$_SERVER['HTTP_REFERER']?>">
                        <div class="fl">
							<?=Utils::loadFile("svg", "back")?>
                        </div>
                        <span class="sml bd">Tornar</span>
                    </a>
				</td>
			</tr>
		</table>
		<?php Template::footer();?>
	</body>
</html>
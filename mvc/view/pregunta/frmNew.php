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
		<script type="text/javascript">
			//Comprueba que haya al menos 1 respuesta correcta y
			//que todas las respuestas	no puedan ser correctas.
			function compruebaRespuestas(intNumeroRespuestas, blnSubmitForm=false) {
				//Inicializo mensaje de error, contador respuestas.
				var strMsgError="";
				var intContadorRespuestasCorrectas = 0;

				//Inicializo los objetos que mostrarán error en caso que lo haya.
				outError.value="";
				lblError.style.visibility=outError.style.visibility="hidden";

				//Crea un contador de respuestas marcadas como correctas.
				for (var i=intNumeroRespuestas;i;i--)
					if (eval('checkRespuesta'+i).checked)intContadorRespuestasCorrectas++;

				//Si el contador es 0 y la llamada a la función viene de un envío de
				//formulario, muestra que no se ha marcado respuesta alguna.
				if (!intContadorRespuestasCorrectas && blnSubmitForm)strMsgError=' No has marcado ninguna respuesta como Correcta. ';

				//Si las respuestas marcadas son igual al número de respuestas
				//muestra que todas las respuestas no pueden ser correctas.
				if (intContadorRespuestasCorrectas == intNumeroRespuestas)strMsgError=' Todas las respuestas no pueden ser correctas. ';

				//Si el mensaje de error tiene longitud, es que ha habido algún error
				//lo muestra en la etiqueta y el output. Además RETORNA false por si
				//viene de la petición de envío de formulario.

				if (strMsgError.length) {
					outError.value=strMsgError;
					lblError.style.visibility=outError.style.visibility="visible";
					return false;
				}
				//Si no ha habido error y hay petición de envío de formulario RETORNA
				//true para que el formulario sea enviado.
				return TRUE;
			}

			function setValues() {
				selModuloId.focus();
				outError.style.visibility="hidden";
				
				iddificultad.pattern="[1-3]";
				iddificultad.placeholder=iddificultad.title="Números del 1 al 3";
			}
		</script>
	</head>
	<body onload="setValues();">
		<?php
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();
		?>
		<form action="/query/store" method="post">
			<table>
				<tr>
					<th class="green med" colspan="3">
						Nueva pregunta
					</th>
				</tr>
				<tr>
					<th class="r">Módulo</th>
					<td colspan="2"><?php
						$objModulos=Modulo::getAll();?>
						<select name="selModuloId" id="selModuloId"><?php
							foreach ($objModulos as $idxArr => $objFieldValue) { ?>
								<option value='<?=$objFieldValue->id?>' title='<?=htmlentities($objFieldValue->codigo." » ".$objFieldValue->nombre,ENT_QUOTES)?>'><?php echo "$objFieldValue->codigo » $objFieldValue->nombre"?></option><?php
							} ?>
						</select>
					</td>
				</tr>
	<?php
	foreach($arrObj as $objPregunta)
		foreach ($objPregunta as $key => $value)
			if ($key=="Field" && $value!="id" && $value!="publicacion") { ?>
				<tr>
					<th class='r'>
						<?=ucfirst($value)?>
					</th>
					<td colspan="2" class='l'>
						<input type='text' name='<?=$value?>' id='id<?=$value?>' required>
				 	</td>
				</tr><?php
			}

			for ($i=1;$i<=4;$i++) { 
				echo "<tr>
						<td class='b r' width='80px'>
							<label for='textResposta$i'>Respuesta $i</label>
						</td>
						<td>
							<input type='text' name='txtRespuesta$i' id='txtRespuesta$i' required style='width:92%'>
							<label class='mainChkRad'>
								<input type='checkbox' name='chkRespuesta$i' value='$i' id='checkRespuesta$i' onclick='compruebaRespuestas(4);'>
								<span class='checkMark'></span>
							</label>
						</td>
					</tr>";
			}

			?>
				<tr>
					<td class='Libro Back c' colspan="3">
						<br><br>
						<input type="submit" name="guardar" value="Guardar">
						<input type="button" name="volver"  value="Volver" onclick="self.location='/query'">
						<br><br>
						<label id="lblError" class="lblError" for="outError">Error: </label>
						<output id="outError" class="msgError" style="padding:inherit;"></output>
					</td>
				</tr>
			</table>
			<?php Template::footer();?>
		</form>
	</body>
</html>
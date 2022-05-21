<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			Front / Success / Error
		</title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
	</head>
	<body onload="if (typeof volver==='object')volver.focus();">
		<?php
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();?>
			<table>
				<tr class='med tac'><?php
				if (empty($arrObj)) { ?>
					<td>
						<div class="youtubeContainer">
							<iframe width="710" height="400" src="https://www.youtube.com/embed/LEpTTolebqo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
						<div class="med br">
							Què es el càncer?
						</div>
						<div class="smlr">
							<p>
								No és una malaltia única, són moltes malalties diferents. Apareix quan una cèl·lula muta i es divideix sense control. Aquestes cèl·lules no moren
								com les cèl·lules «normals», es reprodueixen sense parar ocupant el lloc de les normals. Es pot diagnosticar en diferents fases:
							</p>
							<ul>
								<li> Localitzat i petit</li>
								<li> Quan comença a envair òrgans pròxims.</li>
								<li> Està disseminat per la sang a tot l'organisme.</li>
							</ul>
						</div>
						<div class="med br">
							Causes
						</div>
						<div class="smlr">
							El seu origen sol ser majorment per causes externes, en un 85%, les quals són molt variades però fonamentalment les destacades són:
							<ul>
								<li> Dieta</li>
								<li> Tabaquisme.</li>
								<li> Virus.</li>
								<li> Radiacions.</li>
							</ul>
							Com a influència hereditària són el 15%.
							<br>
							L'obesitat, sobre pes i estil de vida està íntimament relacionats amb càncer de mama, còlon i pròstata. La dieta causa el 35% de morts, tenir uns hàbits de vida saludables i seguir una dieta mediterrània prevé el càncer.
							<br>
							El tabac s'associa al 16-40% dels casos, fumar produeix càncer de pulmó, estómac, esòfag i mama, així com de laringe, faringe, fetge, leucèmia, ronyó i pàncrees. A més afecta també afecta als quals li envolten (fumadors passius).
							El 18% són atribuïbles a infeccions persistents provocades per virus, bacteris o paràsits, destaquen:
							<img class="imgContent fr" src="../img/content/riskFactors.png" alt="" width="400px" height="304px">
							<ul>
								<li> El virus del papil·loma humà (càncer de coll uterí, càncer de penis i càncer oral)</li>
								<li> El virus de l'hepatitis B (càncer de fetge).</li>
								<li> El Helicobacter pylori (càncer d'estómac).</li>
								<li> Radiacions.</li>
							</ul>
							<br>
							Els raigs solars i els raigs ultraviolats artificials són els causants del càncer de pell, els més comuns entre la població:
							<ul>
								<li> Carcinoma de cèl·lules basals (CCB).</li>
								<li> Carcinoma de cèl·lules escatoses (CCE).</li>
								<li> Melanoma, menys freqüent però més agressiu perquè pot envair òrgans adjacents.</li>
							</ul>
						</div>
					</td><?php
				}
				else { ?>
					<th class="tac">
						<div class='msg<?=$arrObj[0]?> sml'>
							<?=$arrObj[1];?>	
						</div>
					</th>
					<tr class="tac">
						<td>
							<input type="button" id="volver" name="volver" value="Volver" onclick="location.href='<?=DEFAULT_PATH.$arrObj[2]?>'">
						</td>
					</tr><?php
				} ?>
				</tr>
			</table>
		<?php Template::footer();?>
	</body>
</html>
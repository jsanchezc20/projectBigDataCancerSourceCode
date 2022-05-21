<?php
	class Template {
		public static function header() {
			# ENCAPÇALAT + FORMULARI DE LOGOUT
			$objUser = UserLoginModel::getUser();?>
			<nav class="headerContainer clearFix">
				<div class="socialContainer fl">
					<a class="socialLink ot" href="https://fb.com" target="_blank">
						<img src="<?=DEFAULT_PATH?>img/social/fb.png" height="36px" width="36px" alt="">
					</a>
					<a class="socialLink ot" href="https://youtu.be" target="_blank">
						<img src="<?=DEFAULT_PATH?>img/social/yt.png" height="36px" width="36px" alt="">
					</a>
					<a class="socialLink ot" href="https://instagr.am" target="_blank">
						<img src="<?=DEFAULT_PATH?>img/social/ig.png" height="36px" width="36px" alt="">
					</a>
					<a class="socialLink ot" href="https://twitter.com" target="_blank">
						<img src="<?=DEFAULT_PATH?>img/social/tw.png" height="36px" width="36px" alt="">
					</a>
				</div>
				<div class="mainTitle ot cp br" onclick="self.location='/'">
					<?=Utils::loadFile("svg","mainTitleB")?>
					<div class="text">igData</div>
					<?=Utils::loadFile("svg","mainTitleC")?>
					<div class="text">ancer	</div>
				</div>
				<div class="logInContainer fr"><?php
					if ($objUser) { ?>
						<form method="post">
							<a class="iconButtonContainer fr" href="#" onclick="logOutSubmit.click();">
								<div class="fl">
									<?=Utils::loadFile("svg","logOut")?>
								</div>
								<span class="sml bd">Log Out</span>
							</a>
							<input class="dn" id="logOutSubmit" name="logOut" type="submit" value="Log Out">
						</form>
						<div class="user fr logOut b">Benvingut <?=$objUser->name?>!</div>
						<div class="iconUser fr">
							<?=Utils::loadFile("svg", AccessModel::getOne($objUser->access_id)->description);?>
						</div><?php
					} else { ?>
						<a class="iconButtonContainer fr" onclick="openModal(logInFormContainer);">
							<div class="fl">
								<?=Utils::loadFile("svg","logIn")?>
							</div>
							<span class="sml bd">Log In</span>
						</a><?php 
					} ?>
				</div>
			</nav><?php
		}

		public static function logIn($objUser = null) {
			if (!$objUser) { ?>
                <div id="logInFormContainer">
                    <form method="post">
                    <div class="formHeader med br">
                        Iniciar sessió
                    </div>
                    <div class="formGroup">
                        <input  id="user"
                                name="user"
                                type="text"
                                pattern="^(?!\.)(?!.*\.$)(?!.*?\.\.)[a-zA-Z\d.]+$"
                                title="Usuari (3-30…lletres, números i punts)"
                                required>
                        <span class="bar"></span>
                        <label for="user">Usuari (3-30…lletres, números i punts)</label>
                    </div>
                    <div class="formGroup">
                        <input  id="password"
                                name="password"
                                type="password"
                                pattern="{8,15}"
                                title="Contrasenya (8-15…qualsevol caràcter)"
                                required>
                        <span class="bar"></span>
                        <label for="password">Contrasenya (8-15…qualsevol caràcter)</label>
                    </div>
                    <div class="formContainerFoot">
                        <a class="iconButtonContainer" onclick="logInSubmit.click();">
                            <div class="fl">
                                <?=Utils::loadFile("svg","send")?>
                            </div>
                            <span class="sml bd">Enviar</span>
                        </a>
                        <input class="dn" id="logInSubmit" name="logIn" type="submit" value="Enviar">
                    </div>
                    </form>
                </div><?php
			}
		}

		public static function menu() {
			$blnAdmin = false;
			$fontSize = "nrm";
			$classLogOutIn = "aLogOut";
			if (UserLoginModel::isAdmin()) {
				$blnAdmin = true;
				$fontSize = "sml";
				$classLogOutIn = "aLogIn";
			} ?>
			<div class="menuContainer <?=$fontSize?> br">
                <div class="menuContainerLinks">
                    <ul>
                        <li><a class="<?=$classLogOutIn?>" href="/">Causes</a></li>
                        <li><a class="<?=$classLogOutIn?>" href="/action">Factors</a></li>
                        <li><a class="<?=$classLogOutIn?>" href="/module">Registres</a></li>
                        <li><a class="<?=$classLogOutIn?>" href="/query">Recursos</a></li>
                        <li><a class="<?=$classLogOutIn?>" href="/welcome/contact">Contacte</a></li><?php
						if ($blnAdmin) { ?>
                            <li><a class="<?=$classLogOutIn?>" href="/access/create">+ accés</a></li>
                            <li><a class="<?=$classLogOutIn?>" href="/module/create">+ módulo</a></li>
                            <li><a class="<?=$classLogOutIn?>" href="/query/create">+ pregunta</a></li>
                            <li><a class="<?=$classLogOutIn?>" href="/user">usuaris</a></li><?php
						} ?>
                    </ul>
                </div>
			</div>
			<div id="main" class="main<?=(UserLoginModel::isAdmin() ? " mainLogIn" : "")?>"><?php
		}

		public static function footer() { ?>
			<table class="footer">
				<tr class="tac vam">
					<th>
						<div class="footerContainer">
							<div class="tabFooterContainer fc smlr">
								<div class="tab">
									<button id="tabBarcelona" class="tabLinks cp" onclick="openCity(event, 'Barcelona')">Barcelona</button>
									<button id="tabMadrid" class="tabLinks cp" onclick="openCity(event, 'Madrid')">Madrid</button>
								</div>
								<div id="Barcelona" class="tabContent">
									<table class="tabFooterContainer">
										<tr>
											<td>
												<strong class="footer">OFICINAS</strong><br>
												Diagonal, 500<br>
												1ª planta<br>
												08036 Barcelona<br>
												(+34) 976 556 484<br>
												Centralita 101<br>
												<a class="footerClean" href="mailto:oficinas.bcn@bigdatacancer.com">oficinas.bcn@bigdatacancer.com</a><br><br>
												<strong class="footer">HORARIO</strong><br>
												Lunes a Viernes<br>
												De 8:00 a 15:00 h
											</td>
											<td><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2993.010613104286!2d2.1525217154036023!3d41.395575879263454!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a4a29a07bf385b%3A0x84ecd6a1cb61d927!2sAvenida%20Diagonal%2C%20500%2C%2008006%20Barcelona!5e0!3m2!1ses!2ses!4v1650322094944!5m2!1ses!2ses" width="250" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></td>
											<td>
												<strong class="footer">PROYECTOS I+D</strong><br>
												Parque Tecnológico del Vallés<br>
												Carrer dels Argenters, 5,<br>
												08290 Cerdanyola del Vallès<br>
												(Barcelona)<br>
												(+34)934744375<br>
												<a class="footerClean" href="mailto:laboratorios.bcn@bigdatacancer.com">laboratorios.bcn@bigdatacancer.com</a><br><br>
												<strong class="footer">HORARIO</strong><br>
												Lunes a Viernes<br>
												De 8:00 a 15:00h
											</td>
											<td><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2988.951060997313!2d2.1234268154063733!3d41.483660679255514!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a496152dc3ea75%3A0xfd9d5db4391dab50!2sCarrer%20dels%20Argenters%2C%205%2C%2008290%20Cerdanyola%20del%20Vall%C3%A8s%2C%20Barcelona!5e0!3m2!1ses!2ses!4v1650322347119!5m2!1ses!2ses" width="250" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></td>
										</tr>
										<tr>
											<td>&nbsp;</td><td class="sml tac"><a href="https://goo.gl/maps/uTsmoLBENbSxxMCC7">Ver en Google Maps</a></td>
											<td>&nbsp;</td><td class="sml tac"><a href="https://goo.gl/maps/P4vrHfE62UeFcDs39">Ver en Google Maps</a></td>
										</tr>
									</table>
								</div>
								<div id="Madrid" class="tabContent">
									<table class="tabFooterContainer">
										<tr>
											<td>
												<strong class="footer">OFICINAS</strong><br>
												P.º de la Castellana, 101<br>
												2ª planta<br>
												28046 Madrid<br>
												(+34) 911 555 444<br>
												Centralita 910<br>
												<a class="footerClean" href="mailto:laboratorios.bcn@bigdatacancer.com">oficinas.mad@bigdatacancer.com</a><br><br>
												<strong class="footer">HORARIO</strong><br>
												Lunes a Viernes<br>
												De 8:00 a 15:00 h
											</td>
											<td><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3035.9761437435013!2d-3.69344998462571!3d40.45366497936099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4228e29a312403%3A0x565a4a7f3e7e67ad!2sP.%C2%BA%20de%20la%20Castellana%2C%20101%2C%2028046%20Madrid!5e0!3m2!1ses!2ses!4v1650323364716!5m2!1ses!2ses" width="250" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></td>
											<td>
												<strong class="footer">PROYECTOS I+D</strong><br>
												Parque Científico de Madrid<br>
												C. Faraday, 7<br>
												28049 Madrid<br>
												(Madrid)<br>
												(+34)917 777 333<br>
												<a class="footerClean" href="mailto:laboratorios.bcn@bigdatacancer.com">laboratorios.mad@bigdatacancer.com</a><br><br>
												<strong class="footer">HORARIO</strong><br>
												Lunes a Viernes<br>
												De 8:00 a 15:00h<br>
											</td>
											<td><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24253.276892925674!2d-3.706656060449219!3d40.549108799999985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422b10e4933241%3A0x39639dcbc5a793d2!2sParque%20Cient%C3%ADfico%20de%20Madrid!5e0!3m2!1ses!2ses!4v1650323692892!5m2!1ses!2ses" width="250" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></td>
										</tr>
										<tr>
											<td>&nbsp;</td><td class="sml tac"><a href="https://goo.gl/maps/eVzyCQr5QYQtjtX18">Ver en Google Maps</a></td>
											<td>&nbsp;</td><td class="sml tac"><a href="https://g.page/FPCMadrid?share">Ver en Google Maps</a></td>
										</tr>
									</table>
								</div>
							</div>
							<script>
								function openCity(evt, cityName) {
									var i, tabContent, tabLinks;
									
									tabContent = document.getElementsByClassName("tabContent");
									
									for (i = 0; i < tabContent.length; i++) {
                                        tabContent[i].style.display = "none";
                                    }
									
									tabLinks = document.getElementsByClassName("tabLinks");
									
									for (i = 0; i < tabLinks.length; i++) {
                                        tabLinks[i].className = tabLinks[i].className.replace(" active", "");
                                    }
									
									document.getElementById(cityName).style.display = "block";
									
									evt.currentTarget.className += " active";
								}
								//openCity(event, 'Barcelona');
								document.getElementById("tabBarcelona").click();
							</script>
							<nav class="menuFooter smlr">
								<ul>
									<li>
										<a class="home footer" href="#">Causes - definició</a> |
									</li>
									<li>
										<a class="footer" href="#">Factors de risc</a> |
									</li>
									<li>
										<a class="footer" href="#">Registres</a> |
									</li>
									<li>
										<a class="footer" href="#">Recursos</a> |
									</li>
									<li>
										<a class="footer" href="#">Contacte</a>
									</li>
								</ul>
							</nav>
						</div>
						<div class="smlr">Copyright &copy; 2022 <a class="footer" href="/">www.bigdatacenter.com</a> - todos los derechos reservados.</div>
					</th>
				</tr>
			</table>
		</div><?php // Acaba el div de class="main"
		}

		# MÈTODE QUE RETORNA LES IMATGES "CRUD" SI L'USUARI TÉ ELS PRIVILEGIS
		public static function getImages(bool $blnHeader = false) : string {
			$strResult = "";
			$strController	= $_GET["c"];
			$strMethod		= empty($_GET["m"]) ? "" : $_GET["m"];

			$arrMethodIMG = [
				"C" => "CREATE",
				"R" => "READ",
				"U" => "UPDATE",
				"D" => "DELETE"
			];

			foreach ($arrMethodIMG as $key => $value) {
				if ($strMethod !== strtolower($value) && ($strMethod === "" && $value !== "CREATE")) {
					$strResult .= "<a class='imgCRUD' href='/" . $strController . "/" . strtolower($value) . "/@@'>" . constant("CRUD_GN_$value") . "</a>";
				}
			}

            if ($blnHeader) {
				if ($strMethod === "") {
					$strResult = "<a class='imgCRUD' href='/" . $strController . "/create'>" . CRUD_WT_CREATE . "</a>";
				} else {
					$strResult = str_replace("_GN_", "_WT_", $strResult);
				}
			}

			return $strResult;
		}

		# MÈTODE QUE RETORNA EL DETALL DEL REGISTRE I ELS REGISTRES QUE PERTANYEN A AQUEST
		public static function getDetails($arrObj, string $strController) {
			switch(strtolower($strController)) {
				case "action":	$strMainTitle ="de la acción";
								$strChildTitle="Módulos ".$strMainTitle;
								$arrChild=$arrObj->getModulos();
								break;
				case "module":	$strMainTitle ="del módulo";
								$strChildTitle="Preguntas ".$strMainTitle;
								$arrChild=$arrObj->getPreguntas();
								break;
				case "query":	$strMainTitle ="de la pregunta";
								$strChildTitle="Respuestas ".$strMainTitle;
								$arrChild=$arrObj->getRespuestas();
								break;
			} ?>
			<table>
				<tr>
					<th class="green med" colspan="2">
						Detalles <?=$strMainTitle?>
					</th>
				</tr>
				<?php
					foreach($arrObj as $key => $value)
						if ($key!='id') { ?>
							<tr>
								<th class='nrm tar'>
									<?=$key?>
								</th>
								<td>
									<?=$value?>
								</td>
							</tr><?php
						} ?>
					<tr>
						<th class="green med" colspan="2">
							<?=$strChildTitle?>
							<div class='sml fr vab'>Total: <?=count((array)$arrChild)?></div>
						</th>
					</tr><?php
					foreach ($arrChild as $arrIdx => $objFieldValue) { ?>
						<tr>
							<td colspan="2">
								<table class="child"><?php
									foreach ($objFieldValue as $key => $value) {
										if (strpos($key,"id") !== 0) {
											if ($strController=="query") {
												$strClassBold=($objFieldValue->correcta)?"class='br'":""?>
													<tr>
														<td <?=$strClassBold?>><?=htmlspecialchars($objFieldValue->texto)?></td>
													</tr><?php break;
											} else { ?>
												<tr>
													<th class='tar' width="10%">
														<?=$key?>
													</th>
													<td>
														<?=$value?>
													</td>
												</tr><?php
											}
										}
									} ?>
								</table>
							</td>
						</tr>
					<?php
					}
				?>
				<tr>
					<td class="tac" colspan="2">
						<?=self::getImages($arrObj->user_id)?>
						<input type="button" id="volver" name="volver" value="Volver" onclick="self.location='/<?=$strController?>'">
					</td>
				</tr>
			</table><?php 
		}

		/*
		MÈTODE PER A MUNTAR ELS ENCAPÇALATS DE LES VISTES EN FUNCIÓ DE $arrObj. $value CONTÉ EL VECTOR
		ASSOCIATIU camp : valor (BDD) $arrDiscard ÉS UN VECTOR AMB ELS CAMPS QUE NO VOLEM INCLOURE COM A
		P.EJ:fechaPublicacion
		PER DEFECTE DESCARTA EL CAMP AMB NOM '_id'
		*/
		private static function getHeaders(array $arrObj, array $arrDiscard = array('_id')) : string {

			$strController = $_GET["c"];

			switch($strController) {
				case 'access': $strTitle="Accés"	;break;
				case 'module': $strTitle="Modulos"	;break;
				case 'query' : $strTitle="Preguntas";break;
				case 'user'  : $strTitle="d'Usuaris" ;break;
			}

			$strHeaders="";

			foreach ($arrObj as $idxArr => $objFieldValue) {
				$strHeaderFirst = "
                <table>
                    <tr>
                        <th class='formHeader med' colspan='".(count((array)$objFieldValue)+count($arrDiscard)-1)."'>
                            Llistat $strTitle
                            ".self::getImages(TRUE)."
                            <div class='sml fr vab'>Total: ".count((array)$arrObj)."</div>
                        </th>
                    </tr>
                    <tr class='tac'>";

				foreach ($objFieldValue as $field => $value) {
					$blnDiscard = false;
                    foreach ($arrDiscard as $pos => $val) {
						if (strrpos(" $field", $val)) {
							$blnDiscard = true;
						}
					}

					if (!$blnDiscard) {
						$strHeaders .= "<th>" . str_replace("_id", "", ucfirst($field)) . "</th>";
					}
				} break;
			}

			return ($strHeaderFirst.$strHeaders."<th>Operacions</th></tr>");
		}

		/*
			MÈTODE PER A CARREGAR EL CONTINGUT DE LA TAULA (FALTA ACABAR DE COMENTAR-HO)
		*/
		public static function getContent($arrObj, array $arrDiscard = []) {
			
			$strController = $_GET["c"];
			
			$strContent = self::getHeaders($arrObj, empty($arrDiscard) ? ["_id"] : $arrDiscard);

            $strImages = self::getImages();

			foreach ($arrObj as $idxArr => $objFieldValue) {
				
				$strContentOperations = "";
				$strContent .= "<tr". (strrpos(" action, module ", $strController) ? " class='vat'":""). ">";
				
				foreach ($objFieldValue as $field => $value) {
					$blnDiscard = false;

					foreach ($arrDiscard as $pos => $val) {
						if (strrpos(" $field", $val)) {
							$blnDiscard = true;
						}
					}

					if (!$blnDiscard) {
                        if ($field === "password") {
							$value = substr($value, 0, 30) . "…";
						}

                        if ($field === "access_id") {
							$value = AccessModel::getOne($value)->description;
						}

						$strVerticalAlignMiddle = (strlen($value) < 20) ? " class='tac vam'" : "";
						$strContent .= "<td$strVerticalAlignMiddle>$value</td>";
					}
				}

				$strContentOperations .= "<td class='imgCRUDContainer tac vam'>" . str_replace("@@", $objFieldValue->user_id, $strImages);

				$strContent .= $strContentOperations."</td></tr>";
			}
		
			echo ($strContent. "</table>");
    		}

		# MÈTODE PER A MOSTRAR EL CONTINGUT DE CREAR(C), DETALLS(R), ACTUALITZACIÓ(U), ESBORRAT(D)
		public static function getCRUD(string $strCRUD) {
			switch ($strCRUD) {
				case 'CREATE':
					# CODE...
					break;
				case 'READ':
					# CODE...
					break;
				case 'UPDATE':
					# CODE...
					break;
				case 'DELETE':
					# CODE...
					break;
			}

		}

	}
<?php
class Utils {
	### MÈTODES ###

	public static function checkVariable($objData, bool $blnDie = true ) {
		highlight_string("<?php\n\$data =\n" . var_export($objData, true) . ";\n?>");
		if ($blnDie)
			die("<br><br>Comprovació de valors + die();");
	}

	public static function createMenu(string $rol) : string {
		$arrTipusPlural		= ["fàrmacs", "proteïnes", "assajos"];
		$arrTipusSingular	= ["fàrmac" , "proteina",  "assaig"];
		$arrOperacions		= ["Nou", "Veure"];
		$arrCRUD			= ["create", "read"];
		$arrType			= ["drug", "target", "docking"];

		$strMenu = "";

		for ($i = 0; $i < count($arrTipusPlural); $i++)  {

			$strMenu .= '<li class="dropdown">' .
							'<a class="dropdown-toggle" data-toggle="dropdown" href="/">' . ucfirst($arrTipusPlural[$i]) .
								'<span class="caret"></span></a>' .
									'<ul class="dropdown-menu">';
					
					$blnPrint = true;

					for ($j = 0; $j < count($arrOperacions); $j++) {
						if (($rol == "User" || $rol == "Editor") && $arrOperacions[$j] !== "Nou" || $rol == "Admin") {
							$strTitle =
							($arrOperacions[$j] == "Nou" && $arrTipusSingular[$i] == "proteina" ? "Nova" : $arrOperacions[$j]) . ' ' .
							($arrOperacions[$j] == "Nou" ? $arrTipusSingular[$i] : $arrTipusPlural[$i]);

							$strOperationType = $arrCRUD[$j] . $arrType[$i];

							$strMenu .= '
							<li>
								<a href="#" onclick="' . $strOperationType . '.submit();">' .
									$strTitle .
								'</a>
							</li>';
							$strMenuForms .= '
							<form id="' . $strOperationType . '" method="post">
								<input type="hidden" name="operation"	id="operation"	value="' . $arrCRUD[$j] . '">
								<input type="hidden" name="type"		id="type"		value="' . $arrType[$i] . '">
								<input type="hidden" name="title"		id="title"		value="' . $strTitle . '">
							</form>';
						}
					}
			$strMenu .=				'</ul>' .
						'</li>';
		}

		return $strMenu . $strMenuForms;
	}

	public static function createHead(string $postType, string $strHeadDiscardFields, string $rol) : string {
		$strSQL	 = "DESCRIBE " . $postType . "s;";
		$objHead = DB::selectAll($strSQL);
		$strHead = "";
		$strHead .= '<tr id="head">';
		foreach ($objHead as $objElement)
			foreach ($objElement as $key => $value)
				if ($key == "Field" && !strpos($strHeadDiscardFields, $value))
					$strHead .= "<td>" . ($value == "smiles" ? strtoupper($value) : str_replace("_", "&nbsp;", ucfirst($value))) . "</td>";
		
		$strHead .= ($rol == "User" ? "</tr>" : "<td>Operation</td></tr>");

		return $strHead;
	}

	public static function showList(string $strHeadDiscardFields, string $postType, string $rol) : string {
		
		$strContent = "";

		$strSQL = "SELECT * FROM ". $postType . "s;";
		$objContent = DB::selectAll($strSQL);

		if (count($objContent)) {

			foreach ($objContent as $idxArr => $objFieldValue) {
		
				$intId = 0;
				$strConfirmDelete = "";
				$strContent .= "<tr>";

				foreach ($objFieldValue as $field => $value) {
					if (!strpos($strHeadDiscardFields, $field)) {
						$strContent .= '<td class="' . (strlen($value) > 50 ? 'taj vam' : 'tac vam') . '">' . $value . '</td>';
						$strConfirmDelete .= ($field !== "description" ? $field . " => " . $value . "\n" : "");
					}
					if (strpos($field, "_id"))
						$intId = $value;
				}

				$strFormIni = '<form class="operations" method="post"';
				$strFormEnd = '
					<input type="hidden" name="type"	id="type"	value="' . $postType . '">
					<input type="hidden" name="id"		id="id"		value="' . $intId . '">
					<input type="hidden" name="confirm" id="confirm" value="' . $strConfirmDelete . '">
				</form>';
				
				$strEdit =	$strFormIni . '>
							<button data-toggle="tooltip" data-placement="left" type="submit" title="Editar"><span class="glyphicon glyphicon-edit"></span></button>
							<input type="hidden" name="operation"	id="operation"	value="update">' .
							$strFormEnd;

				$strErase =$strFormIni . ' onsubmit="return confirmDelete(this.confirm)">
							<button data-toggle="tooltip" data-placement="left" type="submit" title="Esborrar"><span class="glyphicon glyphicon-erase"></span></button>
							<input type="hidden" name="operation"	id="operation"	value="delete">' .
							$strFormEnd;

				$strOperations = $rol == "Admin" ? $strEdit . $strErase : $strEdit;

				if ($rol !== "User")
					$strContent .= '
						<td class="UpDel tac vam">' .
							$strOperations . '
						</td>
					</tr>';
			}
		} else {
			$strContent = '</table><div class="jumbotron"><h3>No hi ha resgistres per mostrar</h3></div>';
		}

		return $strContent;
	}

	public static function printErrSuccess($strErrSuccess, $strMessage) : string {
		$strMissatge = "";

		if ($strErrSuccess == "Ok") {
			$strMissatge .= '<span class="msgSuccess">' . $strMessage . ' 😃 </span>';
		} else {
			$strMissatge .= '<span class="msgError">' . $strMessage . ' 😞 </span>';
		}

		return $strMissatge;
	}

}
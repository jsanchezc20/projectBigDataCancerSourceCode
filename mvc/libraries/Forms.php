<?php
class Forms {
	### MÈTODES ###

	private static function getValue(string $strFieldType, string $strValue, string $strType) : string {
		$strResult = '';

		switch($strFieldType) {
			case "path":
				$strResult = ' value="./workflow/' . $strType . '/' . $strValue . '"';
				break;
			case "description":
				$strResult = $strValue;
				break;
			default:
				$strResult = ' value="' . $strValue . '"';
		}
		
		return $strResult;
	}

	public static function createUpdate(string $postType, string $strHeadDiscardFields, string $postOperation, int $id = 0) : string {
		
		$strSQL	 = "DESCRIBE " . $postType . "s;";
		$objStruct = DB::selectAll($strSQL);

		if ($id) {
			$strSQL = "SELECT * FROM " . $postType . "s WHERE " . $postType . "_id = " . $id . ";";
			$objData = DB::selectOne($strSQL);
		}
		
		$strNewForm = "";
		$strNewFormGroup = "";
		foreach ($objStruct as $objElement) {
			foreach ($objElement as $key => $value) {
				if ($key == "Field" && !strpos($strHeadDiscardFields, $value)) {
					
					preg_match_all('!\d+!', $objElement->Type, $matches); # Extreu el número d'un string
					$strPatternMax = strlen($matches[0][0]) ? 'pattern="{1,'. $matches[0][0] .'}" maxlength="' . $matches[0][0] . '"' : '';

					$strValue = $id ? self::getValue($value, $objData->$value, $postType) : '' ;

					$strNewFormGroup .= '
						<div class="formGroup">';

					switch($value) {
						case "path":
							$strNewFormGroup .= '<input type="file" name="' . $value . '" id="' . $value . '" ' . $strPatternMax . $strValue . ' required>';
							break;
						case "description":
							$strNewFormGroup .= 
							'<textarea cols="50" rows="10" name="' . $value . '" id="' . $value . '" ' . $strPatternMax . ' required>' . $strValue . '</textarea>';
							break;
						default:
							$strNewFormGroup .= '<input type="text" name="' . $value . '" id="' . $value . '" ' . $strPatternMax . $strValue .' required>';
					}
					$strNewFormGroup .= '<span class="bar"></span>
									<label for="' . $value . '">' . ($value == "smiles" || $value == "pdb" ? strtoupper($value) : str_replace("_", " ", ucfirst($value))) . '</label>
						</div>';
				}
			}
		}

		if ($id) {
			$strGlyphicon	= "refresh";
			$strTitleButton	= "Actualitzar";
		} else {
			$strGlyphicon	= "plus";
			$strTitleButton	= "Crear";
		}

		$strNewForm .= '<div class="formContainer">
			<div class="formHeader med br">' . $_POST["title"] . '</div>
				<form method="post">'
					. $strNewFormGroup . '
					<div class="tac"><button class="btn btn-default" type="submit">' . 
						$strTitleButton . '&nbsp;<span class="glyphicon glyphicon-' . $strGlyphicon . '"></span></button><br><br>
					</div>
					<input type="hidden" name="operation"	id="operation"	value="' . $postOperation . '">
					<input type="hidden" name="type"		id="type"		value="' . $postType . '">
					<input type="hidden" name="action"		id="action"		value="execute">
				</form>
			</div>';

		return $strNewForm;
	}
}
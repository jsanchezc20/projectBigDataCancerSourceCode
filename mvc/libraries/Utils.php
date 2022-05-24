<?php
class Utils {
	### MÈTODES ###

	# MÈTODE PER A MOSTRAR LES PROPIETATS D'UNA VARIABLE
	public static function checkVariable($variable, bool $blnDie = false) {
		highlight_string("<?php\n\$data =\n" . var_export($variable, true) . ";\n?>");
		if ($blnDie)
			die("<br><br>Comprobació de valor + die();");
	}

	public static function checkFields(string $strFind, array $arrMatch = []) {
		foreach($arrMatch as $value) {
			if (strpos($strFind, $value)) {
				return true;
				break;
			}
		}
		return false;
	}

	public static function loadFile(string $strType, string $strFile) {
		/*	Example:
			<?=Utils::loadFile("svg","send")?> */
		if ($strType === "svg")
			include "img/svg/".$strFile;
	}

}
<?php
	function imprimir(string $strMissatge, bool $blnOk) : void {
		$strColor = $blnOk ? "green" : "red";
		echo "<h4><span style='color:$strColor'>$strMissatge</span></h4>";
	}

	function executaConsulta(PDO $objConn, string $strAccio, string $strSQL, string $strName) : void {
		try {
			$result = $objConn->prepare($strSQL);

			if ($result->execute()) {
				imprimir("$strAccio $strName correctament.", true);
				highlight_string("<?php\n $strSQL \n?>");
			}
		} catch (PDOException $e) {
			imprimir("$strAccio $strName incorrecte.", false);
			imprimir("Error! ".$e->getMessage(), false);
		}
	}

	$serverName	= "localhost";
	$dbName		= "big_data_cancer";
	$user		= "admin";
	$password	= "JsZT*-Hf!t_DWwq)";

	try {
		$objConn = new PDO("mysql:host=$serverName;dbname=$dbName", $user, $password);
		// set the PDO error mode to exception
		$objConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		imprimir("Connectat correctament!", true);

		$strSQLTaulaAccess = 
		<<<SQL
			CREATE TABLE IF NOT EXISTS `access` (
				`access_id`		TINYINT	UNSIGNED NOT NULL AUTO_INCREMENT,
				`description`	VARCHAR(50) NOT NULL,
				CONSTRAINT PK_access_id PRIMARY KEY (access_id)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTaulaUsuari = 
		<<<SQL
			CREATE TABLE IF NOT EXISTS `users` (
				`user_id`		INT	UNSIGNED	NOT NULL AUTO_INCREMENT,
				`name`			VARCHAR(50)		NOT NULL UNIQUE KEY,
				`password`		VARCHAR(255)	NOT NULL,
				`access_id`		TINYINT			UNSIGNED NOT NULL,
				`createdAt`		TIMESTAMP 		NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`updatedAt`		TIMESTAMP		NULL ON UPDATE CURRENT_TIMESTAMP,
				CONSTRAINT PK_user_id PRIMARY KEY (user_id),
				CONSTRAINT FK_access_id FOREIGN KEY (access_id) REFERENCES access(access_id) ON DELETE CASCADE ON UPDATE CASCADE
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTaulaContinent = 
		<<<SQL
			CREATE TABLE IF NOT EXISTS `continents` (
				`continent_id`		TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
				`continent_code`	CHAR(2) NOT NULL UNIQUE KEY,
				`name`				VARCHAR(20)	NOT NULL UNIQUE KEY,
				CONSTRAINT PK_continent_id PRIMARY KEY (continent_id)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTaulaCountry = 
		<<<SQL
			CREATE TABLE IF NOT EXISTS `countries` (
				`country_id`		SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
				`country_code`		CHAR(2) 	NOT NULL UNIQUE KEY,
				`name`				VARCHAR(90)	NOT NULL,
				`fullName`			VARCHAR(90)	NOT NULL,
				`continent_code`	CHAR(2) NOT NULL,
				CONSTRAINT PK_country_id PRIMARY KEY (country_id),
				CONSTRAINT FK_continent_code FOREIGN KEY (continent_code) REFERENCES continents(continent_code) ON DELETE CASCADE ON UPDATE CASCADE
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTaulaDiseaseType = 
		<<<SQL
			CREATE TABLE IF NOT EXISTS `diseases_types` (
				`disease_type_code`	VARCHAR(3)	NOT NULL UNIQUE KEY,
				`name`				VARCHAR(50) NOT NULL,
				`description`		TEXT NOT NULL,
				CONSTRAINT PK_disease_type_id PRIMARY KEY (disease_type_id)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTaulaDisease = 
		<<<SQL
			CREATE TABLE IF NOT EXISTS `diseases` (
				`disease_id`		INT	UNSIGNED	NOT NULL AUTO_INCREMENT,
				`sex`				BOOLEAN			NOT NULL,
				`age`				TINYINT			UNSIGNED NOT NULL,
				`country_code` 		SMALLINT		UNSIGNED NOT NULL,
				`disease_type_code`	TINYINT			UNSIGNED NOT NULL,
				`createdAt`			TIMESTAMP		NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`updatedAt`			TIMESTAMP		NULL ON UPDATE CURRENT_TIMESTAMP,
				CONSTRAINT PK_disease_id		PRIMARY KEY (disease_id),
				CONSTRAINT FK_country_code		FOREIGN KEY (country_code) 		REFERENCES countries(country_code)			ON DELETE CASCADE ON UPDATE CASCADE,
				CONSTRAINT FK_disease_type_code	FOREIGN KEY (disease_type_code)	REFERENCES diseases_types(disease_type_code)ON DELETE CASCADE ON UPDATE CASCADE
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strAccio = "Creació de taula ";
		executaConsulta($objConn, $strAccio, $strSQLTaulaAccess,		"access");
		executaConsulta($objConn, $strAccio, $strSQLTaulaUsuari,		"users");
		executaConsulta($objConn, $strAccio, $strSQLTaulaContinent,		"continents");
		executaConsulta($objConn, $strAccio, $strSQLTaulaCountry,		"countries");
		executaConsulta($objConn, $strAccio, $strSQLTaulaDiseaseType,	"diseasesTypes");
		executaConsulta($objConn, $strAccio, $strSQLTaulaDisease,		"diseases");
		
		$strAccio = "Inserció de dades a la taula ";

		$strSQLInsertAccessos = 
		<<<SQL
			INSERT INTO access(access_id, description) VALUES
				(1, "Admin"),
				(2, "Editor"),
				(3, "User");
		SQL;	
		
		$strPasswordUser1 = password_hash('123454321', PASSWORD_BCRYPT);
		$strPasswordUser2 = password_hash('987654321', PASSWORD_BCRYPT);
		$strPasswordUser3 = password_hash('123456789', PASSWORD_BCRYPT);

		$strSQLInsertUsers =
		<<<SQL
			INSERT INTO users(name, password, access_id) VALUES
				("erik",	"$strPasswordUser1", 1),
				("jaime", 	"$strPasswordUser2", 2),
				("marcello","$strPasswordUser3", 3);
		SQL;

		$strSQLInsertContinents = 
		<<<SQL
			INSERT INTO continents(continent_code, name) VALUES
				("AF", "Africa"),
				("AS", "Asia"),
				("EU", "Europe"),
				("NA", "North America"),
				("SA", "South America"),
				("OC", "Oceania"),
				("AN", "Antarctica");
		SQL;	

		$strSQLInsertCountries = 
		<<<SQL
			INSERT INTO countries(country_code, name, fullname, continent_code) VALUES
				("AF", "Afghanistan", "Islamic Republic of Afghanistan", "AS"),
				("AX", "Åland Islands", "Åland Islands", "EU"),
				("AL", "Albania", "Republic of Albania", "EU"),
				("DZ", "Algeria", "People's Democratic Republic of Algeria", "AF"),
				("AS", "American Samoa", "American Samoa", "OC"),
				("AD", "Andorra", "Principality of Andorra", "EU"),
				("AO", "Angola", "Republic of Angola", "AF"),
				("AI", "Anguilla", "Anguilla", "NA"),
				("AQ", "Antarctica", "Antarctica (the territory South of 60 deg S)", "AN"),
				("AG", "Antigua and Barbuda", "Antigua and Barbuda", "NA"),
				("AR", "Argentina", "Argentine Republic", "SA"),
				("AM", "Armenia", "Republic of Armenia", "AS"),
				("AW", "Aruba", "Aruba", "NA"),
				("AU", "Australia", "Commonwealth of Australia", "OC"),
				("AT", "Austria", "Republic of Austria", "EU"),
				("AZ", "Azerbaijan", "Republic of Azerbaijan", "AS"),
				("BS", "Bahamas", "Commonwealth of the Bahamas", "NA"),
				("BH", "Bahrain", "Kingdom of Bahrain", "AS"),
				("BD", "Bangladesh", "People's Republic of Bangladesh", "AS"),
				("BB", "Barbados", "Barbados", "NA"),
				("BY", "Belarus", "Republic of Belarus", "EU"),
				("BE", "Belgium", "Kingdom of Belgium", "EU"),
				("BZ", "Belize", "Belize", "NA"),
				("BJ", "Benin", "Republic of Benin", "AF"),
				("BM", "Bermuda", "Bermuda", "NA"),
				("BT", "Bhutan", "Kingdom of Bhutan", "AS"),
				("BO", "Bolivia", "Plurinational State of Bolivia", "SA"),
				("BQ", "Bonaire, Sint Eustatius and Saba", "Bonaire, Sint Eustatius and Saba", "NA"),
				("BA", "Bosnia and Herzegovina", "Bosnia and Herzegovina", "EU"),
				("BW", "Botswana", "Republic of Botswana", "AF"),
				("BV", "Bouvet Island (Bouvetoya)", "Bouvet Island (Bouvetoya)", "AN"),
				("BR", "Brazil", "Federative Republic of Brazil", "SA"),
				("IO", "British Indian Ocean Territory (Chagos Archipelago)", "British Indian Ocean Territory (Chagos Archipelago)", "AS"),
				("VG", "British Virgin Islands", "British Virgin Islands", "NA"),
				("BN", "Brunei Darussalam", "Brunei Darussalam", "AS"),
				("BG", "Bulgaria", "Republic of Bulgaria", "EU"),
				("BF", "Burkina Faso", "Burkina Faso", "AF"),
				("BI", "Burundi", "Republic of Burundi", "AF"),
				("KH", "Cambodia", "Kingdom of Cambodia", "AS"),
				("CM", "Cameroon", "Republic of Cameroon", "AF"),
				("CA", "Canada", "Canada", "NA"),
				("CV", "Cape Verde", "Republic of Cape Verde", "AF"),
				("KY", "Cayman Islands", "Cayman Islands", "NA"),
				("CF", "Central African Republic", "Central African Republic", "AF"),
				("TD", "Chad", "Republic of Chad", "AF"),
				("CL", "Chile", "Republic of Chile", "SA"),
				("CN", "China", "People's Republic of China", "AS"),
				("CX", "Christmas Island", "Christmas Island", "AS"),
				("CC", "Cocos (Keeling) Islands", "Cocos (Keeling) Islands", "AS"),
				("CO", "Colombia", "Republic of Colombia", "SA"),
				("KM", "Comoros", "Union of the Comoros", "AF"),
				("CD", "Congo", "Democratic Republic of the Congo", "AF"),
				("CG", "Congo", "Republic of the Congo", "AF"),
				("CK", "Cook Islands", "Cook Islands", "OC"),
				("CR", "Costa Rica", "Republic of Costa Rica", "NA"),
				("CI", "Cote d'Ivoire", "Republic of Cote d'Ivoire", "AF"),
				("HR", "Croatia", "Republic of Croatia", "EU"),
				("CU", "Cuba", "Republic of Cuba", "NA"),
				("CW", "Curaçao", "Curaçao", "NA"),
				("CY", "Cyprus", "Republic of Cyprus", "AS"),
				("CZ", "Czech Republic", "Czech Republic", "EU"),
				("DK", "Denmark", "Kingdom of Denmark", "EU"),
				("DJ", "Djibouti", "Republic of Djibouti", "AF"),
				("DM", "Dominica", "Commonwealth of Dominica", "NA"),
				("DO", "Dominican Republic", "Dominican Republic", "NA"),
				("EC", "Ecuador", "Republic of Ecuador", "SA"),
				("EG", "Egypt", "Arab Republic of Egypt", "AF"),
				("SV", "El Salvador", "Republic of El Salvador", "NA"),
				("GQ", "Equatorial Guinea", "Republic of Equatorial Guinea", "AF"),
				("ER", "Eritrea", "State of Eritrea", "AF"),
				("EE", "Estonia", "Republic of Estonia", "EU"),
				("ET", "Ethiopia", "Federal Democratic Republic of Ethiopia", "AF"),
				("FO", "Faroe Islands", "Faroe Islands", "EU"),
				("FK", "Falkland Islands (Malvinas)", "Falkland Islands (Malvinas)", "SA"),
				("FJ", "Fiji", "Republic of Fiji", "OC"),
				("FI", "Finland", "Republic of Finland", "EU"),
				("FR", "France", "French Republic", "EU"),
				("GF", "French Guiana", "French Guiana", "SA"),
				("PF", "French Polynesia", "French Polynesia", "OC"),
				("TF", "French Southern Territories", "French Southern Territories", "AN"),
				("GA", "Gabon", "Gabonese Republic", "AF"),
				("GM", "Gambia", "Republic of the Gambia", "AF"),
				("GE", "Georgia", "Georgia", "AS"),
				("DE", "Germany", "Federal Republic of Germany", "EU"),
				("GH", "Ghana", "Republic of Ghana", "AF"),
				("GI", "Gibraltar", "Gibraltar", "EU"),
				("GR", "Greece", "Hellenic Republic Greece", "EU"),
				("GL", "Greenland", "Greenland", "NA"),
				("GD", "Grenada", "Grenada", "NA"),
				("GP", "Guadeloupe", "Guadeloupe", "NA"),
				("GU", "Guam", "Guam", "OC"),
				("GT", "Guatemala", "Republic of Guatemala", "NA"),
				("GG", "Guernsey", "Bailiwick of Guernsey", "EU"),
				("GN", "Guinea", "Republic of Guinea", "AF"),
				("GW", "Guinea-Bissau", "Republic of Guinea-Bissau", "AF"),
				("GY", "Guyana", "Co-operative Republic of Guyana", "SA"),
				("HT", "Haiti", "Republic of Haiti", "NA"),
				("HM", "Heard Island and McDonald Islands", "Heard Island and McDonald Islands", "AN"),
				("VA", "Holy See (Vatican City State)", "Holy See (Vatican City State)", "EU"),
				("HN", "Honduras", "Republic of Honduras", "NA"),
				("HK", "Hong Kong", "Hong Kong Special Administrative Region of China", "AS"),
				("HU", "Hungary", "Hungary", "EU"),
				("IS", "Iceland", "Republic of Iceland", "EU"),
				("IN", "India", "Republic of India", "AS"),
				("ID", "Indonesia", "Republic of Indonesia", "AS"),
				("IR", "Iran", "Islamic Republic of Iran", "AS"),
				("IQ", "Iraq", "Republic of Iraq", "AS"),
				("IE", "Ireland", "Ireland", "EU"),
				("IM", "Isle of Man", "Isle of Man", "EU"),
				("IL", "Israel", "State of Israel", "AS"),
				("IT", "Italy", "Italian Republic", "EU"),
				("JM", "Jamaica", "Jamaica", "NA"),
				("JP", "Japan", "Japan", "AS"),
				("JE", "Jersey", "Bailiwick of Jersey", "EU"),
				("JO", "Jordan", "Hashemite Kingdom of Jordan", "AS"),
				("KZ", "Kazakhstan", "Republic of Kazakhstan", "AS"),
				("KE", "Kenya", "Republic of Kenya", "AF"),
				("KI", "Kiribati", "Republic of Kiribati", "OC"),
				("KP", "Korea", "Democratic People's Republic of Korea", "AS"),
				("KR", "Korea", "Republic of Korea", "AS"),
				("KW", "Kuwait", "State of Kuwait", "AS"),
				("KG", "Kyrgyz Republic", "Kyrgyz Republic", "AS"),
				("LA", "Lao People's Democratic Republic", "Lao People's Democratic Republic", "AS"),
				("LV", "Latvia", "Republic of Latvia", "EU"),
				("LB", "Lebanon", "Lebanese Republic", "AS"),
				("LS", "Lesotho", "Kingdom of Lesotho", "AF"),
				("LR", "Liberia", "Republic of Liberia", "AF"),
				("LY", "Libya", "Libya", "AF"),
				("LI", "Liechtenstein", "Principality of Liechtenstein", "EU"),
				("LT", "Lithuania", "Republic of Lithuania", "EU"),
				("LU", "Luxembourg", "Grand Duchy of Luxembourg", "EU"),
				("MO", "Macao", "Macao Special Administrative Region of China", "AS"),
				("MK", "Macedonia", "Republic of Macedonia", "EU"),
				("MG", "Madagascar", "Republic of Madagascar", "AF"),
				("MW", "Malawi", "Republic of Malawi", "AF"),
				("MY", "Malaysia", "Malaysia", "AS"),
				("MV", "Maldives", "Republic of Maldives", "AS"),
				("ML", "Mali", "Republic of Mali", "AF"),
				("MT", "Malta", "Republic of Malta", "EU"),
				("MH", "Marshall Islands", "Republic of the Marshall Islands", "OC"),
				("MQ", "Martinique", "Martinique", "NA"),
				("MR", "Mauritania", "Islamic Republic of Mauritania", "AF"),
				("MU", "Mauritius", "Republic of Mauritius", "AF"),
				("YT", "Mayotte", "Mayotte", "AF"),
				("MX", "Mexico", "United Mexican States", "NA"),
				("FM", "Micronesia", "Federated States of Micronesia", "OC"),
				("MD", "Moldova", "Republic of Moldova", "EU"),
				("MC", "Monaco", "Principality of Monaco", "EU"),
				("MN", "Mongolia", "Mongolia", "AS"),
				("ME", "Montenegro", "Montenegro", "EU"),
				("MS", "Montserrat", "Montserrat", "NA"),
				("MA", "Morocco", "Kingdom of Morocco", "AF"),
				("MZ", "Mozambique", "Republic of Mozambique", "AF"),
				("MM", "Myanmar", "Republic of the Union of Myanmar", "AS"),
				("NA", "Namibia", "Republic of Namibia", "AF"),
				("NR", "Nauru", "Republic of Nauru", "OC"),
				("NP", "Nepal", "Federal Democratic Republic of Nepal", "AS"),
				("NL", "Netherlands", "Kingdom of the Netherlands", "EU"),
				("NC", "New Caledonia", "New Caledonia", "OC"),
				("NZ", "New Zealand", "New Zealand", "OC"),
				("NI", "Nicaragua", "Republic of Nicaragua", "NA"),
				("NE", "Niger", "Republic of Niger", "AF"),
				("NG", "Nigeria", "Federal Republic of Nigeria", "AF"),
				("NU", "Niue", "Niue", "OC"),
				("NF", "Norfolk Island", "Norfolk Island", "OC"),
				("MP", "Northern Mariana Islands", "Commonwealth of the Northern Mariana Islands", "OC"),
				("NO", "Norway", "Kingdom of Norway", "EU"),
				("OM", "Oman", "Sultanate of Oman", "AS"),
				("PK", "Pakistan", "Islamic Republic of Pakistan", "AS"),
				("PW", "Palau", "Republic of Palau", "OC"),
				("PS", "Palestine", "State of Palestine", "AS"),
				("PA", "Panama", "Republic of Panama", "NA"),
				("PG", "Papua New Guinea", "Independent State of Papua New Guinea", "OC"),
				("PY", "Paraguay", "Republic of Paraguay", "SA"),
				("PE", "Peru", "Republic of Peru", "SA"),
				("PH", "Philippines", "Republic of the Philippines", "AS"),
				("PN", "Pitcairn Islands", "Pitcairn Islands", "OC"),
				("PL", "Poland", "Republic of Poland", "EU"),
				("PT", "Portugal", "Portuguese Republic", "EU"),
				("PR", "Puerto Rico", "Commonwealth of Puerto Rico", "NA"),
				("QA", "Qatar", "State of Qatar", "AS"),
				("RE", "Réunion", "Réunion", "AF"),
				("RO", "Romania", "Romania", "EU"),
				("RU", "Russian Federation", "Russian Federation", "EU"),
				("RW", "Rwanda", "Republic of Rwanda", "AF"),
				("BL", "Saint Barthélemy", "Saint Barthélemy", "NA"),
				("SH", "Saint Helena, Ascension and Tristan da Cunha", "Saint Helena, Ascension and Tristan da Cunha", "AF"),
				("KN", "Saint Kitts and Nevis", "Federation of Saint Kitts and Nevis", "NA"),
				("LC", "Saint Lucia", "Saint Lucia", "NA"),
				("MF", "Saint Martin", "Saint Martin (French part)", "NA"),
				("PM", "Saint Pierre and Miquelon", "Saint Pierre and Miquelon", "NA"),
				("VC", "Saint Vincent and the Grenadines", "Saint Vincent and the Grenadines", "NA"),
				("WS", "Samoa", "Independent State of Samoa", "OC"),
				("SM", "San Marino", "Republic of San Marino", "EU"),
				("ST", "Sao Tome and Principe", "Democratic Republic of Sao Tome and Principe", "AF"),
				("SA", "Saudi Arabia", "Kingdom of Saudi Arabia", "AS"),
				("SN", "Senegal", "Republic of Senegal", "AF"),
				("RS", "Serbia", "Republic of Serbia", "EU"),
				("SC", "Seychelles", "Republic of Seychelles", "AF"),
				("SL", "Sierra Leone", "Republic of Sierra Leone", "AF"),
				("SG", "Singapore", "Republic of Singapore", "AS"),
				("SX", "Sint Maarten (Dutch part)", "Sint Maarten (Dutch part)", "NA"),
				("SK", "Slovakia (Slovak Republic)", "Slovakia (Slovak Republic)", "EU"),
				("SI", "Slovenia", "Republic of Slovenia", "EU"),
				("SB", "Solomon Islands", "Solomon Islands", "OC"),
				("SO", "Somalia", "Federal Republic of Somalia", "AF"),
				("ZA", "South Africa", "Republic of South Africa", "AF"),
				("GS", "South Georgia and the South Sandwich Islands", "South Georgia and the South Sandwich Islands", "AN"),
				("SS", "South Sudan", "Republic of South Sudan", "AF"),
				("ES", "Spain", "Kingdom of Spain", "EU"),
				("LK", "Sri Lanka", "Democratic Socialist Republic of Sri Lanka", "AS"),
				("SD", "Sudan", "Republic of Sudan", "AF"),
				("SR", "Suriname", "Republic of Suriname", "SA"),
				("SJ", "Svalbard & Jan Mayen Islands", "Svalbard & Jan Mayen Islands", "EU"),
				("SZ", "Swaziland", "Kingdom of Swaziland", "AF"),
				("SE", "Sweden", "Kingdom of Sweden", "EU"),
				("CH", "Switzerland", "Swiss Confederation", "EU"),
				("SY", "Syrian Arab Republic", "Syrian Arab Republic", "AS"),
				("TW", "Taiwan", "Taiwan, Province of China", "AS"),
				("TJ", "Tajikistan", "Republic of Tajikistan", "AS"),
				("TZ", "Tanzania", "United Republic of Tanzania", "AF"),
				("TH", "Thailand", "Kingdom of Thailand", "AS"),
				("TL", "Timor-Leste", "Democratic Republic of Timor-Leste", "AS"),
				("TG", "Togo", "Togolese Republic", "AF"),
				("TK", "Tokelau", "Tokelau", "OC"),
				("TO", "Tonga", "Kingdom of Tonga", "OC"),
				("TT", "Trinidad and Tobago", "Republic of Trinidad and Tobago", "NA"),
				("TN", "Tunisia", "Tunisian Republic", "AF"),
				("TR", "Turkey", "Republic of Turkey", "AS"),
				("TM", "Turkmenistan", "Turkmenistan", "AS"),
				("TC", "Turks and Caicos Islands", "Turks and Caicos Islands", "NA"),
				("TV", "Tuvalu", "Tuvalu", "OC"),
				("UG", "Uganda", "Republic of Uganda", "AF"),
				("UA", "Ukraine", "Ukraine", "EU"),
				("AE", "United Arab Emirates", "United Arab Emirates", "AS"),
				("GB", "United Kingdom of Great Britain & Northern Ireland", "United Kingdom of Great Britain & Northern Ireland", "EU"),
				("US", "United States of America", "United States of America", "NA"),
				("UM", "United States Minor Outlying Islands", "United States Minor Outlying Islands", "OC"),
				("VI", "United States Virgin Islands", "United States Virgin Islands", "NA"),
				("UY", "Uruguay", "Eastern Republic of Uruguay", "SA"),
				("UZ", "Uzbekistan", "Republic of Uzbekistan", "AS"),
				("VU", "Vanuatu", "Republic of Vanuatu", "OC"),
				("VE", "Venezuela", "Bolivarian Republic of Venezuela", "SA"),
				("VN", "Vietnam", "Socialist Republic of Vietnam", "AS"),
				("WF", "Wallis and Futuna", "Wallis and Futuna", "OC"),
				("EH", "Western Sahara", "Western Sahara", "AF"),
				("YE", "Yemen", "Yemen", "AS"),
				("ZM", "Zambia", "Republic of Zambia", "AF"),
				("ZW", "Zimbabwe", "Republic of Zimbabwe", "AF");
		SQL;	

		executaConsulta($objConn, $strAccio, $strSQLInsertAccessos, "access");
		executaConsulta($objConn, $strAccio, $strSQLInsertUsers, "users");
		executaConsulta($objConn, $strAccio, $strSQLInsertContinents, "continent");
		executaConsulta($objConn, $strAccio, $strSQLInsertCountries, "countries");
		
	} catch(PDOException $e) {
		imprimir("Connexió fallida:".$e->getMessage(), false);
	}

	$objConn = null;
?>
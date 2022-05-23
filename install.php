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

		$strSQLTableAccess =
		<<<SQL
			CREATE TABLE IF NOT EXISTS `access` (
				`access_id`		TINYINT	UNSIGNED NOT NULL AUTO_INCREMENT,
				`description`	CHAR(15) NOT NULL,
				CONSTRAINT PK_access_id PRIMARY KEY (access_id)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTableUsers =
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

		$strSQLTableContinents =
		<<<SQL
			CREATE TABLE IF NOT EXISTS `continents` (
				`continent_code`	CHAR(2) NOT NULL UNIQUE KEY,
				`name`				VARCHAR(20)	NOT NULL UNIQUE KEY,
				CONSTRAINT PK_continent_code PRIMARY KEY (continent_code)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTableCountries =
		<<<SQL
			CREATE TABLE IF NOT EXISTS `countries` (
				`country_code`		CHAR(2) 	NOT NULL UNIQUE KEY,
				`name`				VARCHAR(90)	NOT NULL,
				`fullName`			VARCHAR(90)	NOT NULL,
				`continent_code`	CHAR(2) NOT NULL,
				CONSTRAINT PK_country_code PRIMARY KEY (country_code),
				CONSTRAINT FK_continent_code FOREIGN KEY (continent_code) REFERENCES continents(continent_code) ON DELETE CASCADE ON UPDATE CASCADE
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTableDiseasesTypes =
		<<<SQL
			CREATE TABLE IF NOT EXISTS `diseases_types` (
				`disease_type_code`	CHAR(3)		NOT NULL UNIQUE KEY,
				`name`				VARCHAR(50) NOT NULL,
				CONSTRAINT PK_disease_type_code PRIMARY KEY (disease_type_code)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;
//			`description`		TEXT NOT NULL,

		$strSQLTabledeathsIntervals =
			<<<SQL
			CREATE TABLE IF NOT EXISTS `deaths_intervals` (
				`death_interval_code`	CHAR(3)		NOT NULL UNIQUE KEY,
			    `intervals`				CHAR(15)	NOT NULL,
			    `column_reference`		CHAR(25)	NOT NULL,
			    CONSTRAINT PK_death_interval_code PRIMARY KEY (death_interval_code)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strSQLTableDiseases =
		<<<SQL
			CREATE TABLE IF NOT EXISTS `diseases` (
				`disease_id`			INT	UNSIGNED	NOT NULL AUTO_INCREMENT,
				`year`					INT UNSIGNED	NOT NULL,
				`sex`					BOOLEAN			NOT NULL,
				`age`					TINYINT			UNSIGNED NOT NULL,
				`country_code` 			CHAR(2)			NOT NULL,
				`disease_type_code`		CHAR(3)			NOT NULL,
			    `death_interval_code`	CHAR(3)			NOT NULL,
			    `count`					SMALLINT		NOT NULL,
				`createdAt`				TIMESTAMP		NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`updatedAt`				TIMESTAMP		NULL ON UPDATE CURRENT_TIMESTAMP,
				CONSTRAINT PK_disease_id			PRIMARY KEY (disease_id),
				CONSTRAINT FK_country_code			FOREIGN KEY (country_code) 			REFERENCES countries(country_code)					ON DELETE CASCADE ON UPDATE CASCADE,
				CONSTRAINT FK_disease_type_code		FOREIGN KEY (disease_type_code)		REFERENCES diseases_types(disease_type_code)		ON DELETE CASCADE ON UPDATE CASCADE,
			    CONSTRAINT FK_death_interval_code	FOREIGN KEY (death_interval_code)	REFERENCES deaths_intervals(death_interval_code)	ON DELETE CASCADE ON UPDATE CASCADE
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strAccio = "Creació de taula ";
		executaConsulta($objConn, $strAccio, $strSQLTableAccess,			"access");
		executaConsulta($objConn, $strAccio, $strSQLTableUsers,				"users");
		executaConsulta($objConn, $strAccio, $strSQLTableContinents,		"continents");
		executaConsulta($objConn, $strAccio, $strSQLTableCountries,			"countries");
		executaConsulta($objConn, $strAccio, $strSQLTableDiseasesTypes,		"diseases_types");
		executaConsulta($objConn, $strAccio, $strSQLTabledeathsIntervals,	"deaths_intervals");
		executaConsulta($objConn, $strAccio, $strSQLTableDiseases,			"diseases");

		$strSQLInsertAccess =
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



//		INSERT INTO diseases_types(disease_type_code, name, description) VALUES
		$strSQLInsertDiseasesTypes =
		<<<SQL
			INSERT INTO diseases_types(disease_type_code, name) VALUES 
				("C0", "Malignant neoplasm of lip"),
				("C1", "Malignant neoplasm of base of tongue"),
				("C2", "Malignant neoplasm of other and unspecified parts of tongue"),
				("C3", "Malignant neoplasm of gum"),
				("C4", "Malignant neoplasm of floor of mouth"),
				("C5", "Malignant neoplasm of palate"),
				("C6", "Malignant neoplasm of other and unspecified parts of mouth"),
				("C7", "Malignant neoplasm of parotid gland"),
				("C8", "Malignant neoplasm of other and unspecified major salivary glands"),
				("C9", "Malignant neoplasm of tonsil"),
				("C10", "Malignant neoplasm of oropharynx"),
				("C11", "Malignant neoplasm of nasopharynx"),
				("C12", "Malignant neoplasm of pyriform sinus"),
				("C13", "Malignant neoplasm of hypopharynx"),
				("C14", "Malignant neoplasm of other and ill-defined sites in the lip, oral cavity and pharynx"),
				("C15", "Malignant neoplasm of esophagus"),
				("C16", "Malignant neoplasm of stomach"),
				("C17", "Malignant neoplasm of small intestine"),
				("C18", "Malignant neoplasm of colon"),
				("C19", "Malignant neoplasm of rectosigmoid junction"),
				("C20", "Malignant neoplasm of rectum"),
				("C21", "Malignant neoplasm of anus and anal canal"),
				("C22", "Malignant neoplasm of liver and intrahepatic bile ducts"),
				("C23", "Malignant neoplasm of gallbladder"),
				("C24", "Malignant neoplasm of other and unspecified parts of biliary tract"),
				("C25", "Malignant neoplasm of pancreas"),
				("C26", "Malignant neoplasm of other and ill-defined digestive organs"),
				("C28", "Malignant neoplasm of trachea, bronchus and lung"),
				("C29", "Malignant neoplasm of female breast(carcinoma in situ of breast and genitourinary system)"),
				("C30", "Malignant neoplasm of nasal cavity and middle ear"),
				("C31", "Malignant neoplasm of accessory sinuses"),
				("C32", "Malignant neoplasm of larynx"),
				("C33", "Malignant neoplasm of trachea"),
				("C34", "Malignant neoplasm of bronchus and lung"),
				("C37", "Malignant neoplasm of thymus"),
				("C38", "Malignant neoplasm of heart, mediastinum and pleura"),
				("C39", "Malignant neoplasm of other and ill-defined sites in the respiratory system and intrathoracic organs"),
				("C40", "Malignant neoplasm of bone and articular cartilage of limbs"),
				("C41", "Malignant neoplasm of bone and articular cartilage of other and unspecified sites"),
				("C43", "Malignant melanoma of skin"),
				("C44", "Other and unspecified malignant neoplasm of skin"),
				("C45", "Mesothelioma"),
				("C46", "Kaposi's sarcoma"),
				("C47", "Malignant neoplasm of peripheral nerves and autonomic nervous system"),
				("C48", "Malignant neoplasm of retroperitoneum and peritoneum"),
				("C49", "Malignant neoplasm of other connective and soft tissue"),
				("C50", "Malignant neoplasm of breast"),
				("C51", "Malignant neoplasm of vulva"),
				("C52", "Malignant neoplasm of vagina"),
				("C53", "Malignant neoplasm of cervix uteri"),
				("C54", "Malignant neoplasm of corpus uteri"),
				("C55", "Malignant neoplasm of uterus, part unspecified"),
				("C56", "Malignant neoplasm of ovary"),
				("C57", "Malignant neoplasm of other and unspecified female genital organs"),
				("C58", "Malignant neoplasm of placenta"),
				("C60", "Malignant neoplasm of penis"),
				("C61", "Malignant neoplasm of prostate"),
				("C62", "Malignant neoplasm of testis"),
				("C63", "Malignant neoplasm of other and unspecified male genital organs"),
				("C64", "Malignant neoplasm of kidney, except renal pelvis"),
				("C65", "Malignant neoplasm of renal pelvis"),
				("C66", "Malignant neoplasm of ureter"),
				("C67", "Malignant neoplasm of bladder"),
				("C68", "Malignant neoplasm of other and unspecified urinary organs"),
				("C69", "Malignant neoplasm of eye and adnexa"),
				("C70", "Malignant neoplasm of meninges"),
				("C71", "Malignant neoplasm of brain"),
				("C72", "Malignant neoplasm of spinal cord, cranial nerves and other parts of central nervous system"),
				("C73", "Malignant neoplasm of thyroid gland"),
				("C74", "Malignant neoplasm of adrenal gland"),
				("C75", "Malignant neoplasm of other endocrine glands and related structures"),
				("C76", "Malignant neoplasm of other and ill-defined sites"),
				("C77", "Secondary and unspecified malignant neoplasm of lymph nodes"),
				("C78", "Secondary malignant neoplasm of respiratory and digestive organs"),
				("C79", "Secondary malignant neoplasm of other and unspecified sites"),
				("C80", "Malignant neoplasm without specification of site"),
				("C81", "Hodgkin lymphoma"),
				("C82", "Follicular lymphoma"),
				("C83", "Non-follicular lymphoma"),
				("C84", "Mature T/NK-cell lymphomas"),
				("C85", "Other specified and unspecified types of non-Hodgkin lymphoma"),
				("C86", "Other specified types of T/NK-cell lymphoma"),
				("C88", "Malignant immunoproliferative diseases and certain other B-cell lymphomas"),
				("C90", "Multiple myeloma and malignant plasma cell neoplasms"),
				("C91", "Lymphoid leukemia"),
				("C92", "Myeloid leukemia"),
				("C93", "Monocytic leukemia"),
				("C94", "Other leukemias of specified cell type"),
				("C95", "Leukemia of unspecified cell type"),
				("C96", "Other and unspecified malignant neoplasms of lymphoid, hematopoietic and related tissue");
		SQL;

		$strSQLdeathsInterval =
		<<<SQL
			INSERT INTO deaths_intervals(death_interval_code, intervals, column_reference) VALUES
				("Y0", "0", "deaths_0"),
				("Y1", "1", "deaths1"),
				("Y2", "2", "deaths2"),
				("Y3", "3", "deaths3"),
				("Y4", "4", "deaths4"),
				("Y5", "5_9", "deaths5_9"),
				("Y10", "10_14", "deaths10_14"),
				("Y15", "15_19", "deaths15_19"),
				("Y20", "20_24", "deaths20_24"),
				("Y25", "25_29", "Deaths25_29d"),
				("Y30", "30_34", "deaths30_34"),
				("Y35", "35_39", "deaths35_39"),
				("Y40", "40_44", "deaths40_44"),
				("Y45", "45_49", "deaths45_49"),
				("Y50", "50_54", "deaths50_54"),
				("Y55", "55_59", "Deaths55_59"),
				("Y60", "60_64", "deaths60_64"),
				("Y65", "65_69", "deaths65_69"),
				("Y70", "70_74", "deaths70_74"),
				("Y75", "75_79", "Deaths75_79d"),
				("Y80", "80_84", "Deaths80_84d"),
				("Y85", "85_89", "deaths85_89"),
				("Y90", "90_94", "deaths90_94"),
				("Y95", "95_", "deaths95_or_mored"),
				("D0", "0", "deaths0_days"),
				("D1", "1_6", "deaths1_6days"),
				("D7", "7_27", "deaths7_27days"),
				("D28", "28_365", "deaths28_365days"),
				("U", "unspecified", "deaths_age_unspecified");
		SQL;

		$strAccio = "Inserció de dades a la taula ";
		executaConsulta($objConn, $strAccio, $strSQLInsertAccess,		"access");
		executaConsulta($objConn, $strAccio, $strSQLInsertUsers,		"users");
		executaConsulta($objConn, $strAccio, $strSQLInsertContinents,	"continent");
		executaConsulta($objConn, $strAccio, $strSQLInsertCountries,	"countries");
		executaConsulta($objConn, $strAccio, $strSQLInsertDiseasesTypes,"diseases_types");
		executaConsulta($objConn, $strAccio, $strSQLdeathsInterval,		"deaths_intervals");

		$strSQLdiseasesCleanup =
		<<<SQL
			CREATE TABLE diseases_cleanup (
				id						MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
				country_code			CHAR(2) NOT NULL,
				year					SMALLINT UNSIGNED NOT NULL,
				sex						BOOLEAN NOT NULL,
				deaths_0				SMALLINT UNSIGNED NOT NULL,
				deaths1					SMALLINT UNSIGNED NOT NULL,
				deaths2					SMALLINT UNSIGNED NOT NULL,
				deaths3					SMALLINT UNSIGNED NOT NULL,
				deaths4					SMALLINT UNSIGNED NOT NULL,
				deaths5_9				SMALLINT UNSIGNED NOT NULL,
				deaths10_14				SMALLINT UNSIGNED NOT NULL,
				deaths15_19				SMALLINT UNSIGNED NOT NULL,
				deaths20_24				SMALLINT UNSIGNED NOT NULL,
				Deaths25_29d			SMALLINT UNSIGNED NOT NULL,
				deaths30_34				SMALLINT UNSIGNED NOT NULL,
				deaths35_39				SMALLINT UNSIGNED NOT NULL,
				deaths40_44				SMALLINT UNSIGNED NOT NULL,
				deaths45_49				SMALLINT UNSIGNED NOT NULL,
				deaths50_54				SMALLINT UNSIGNED NOT NULL,
				Deaths55_59				SMALLINT UNSIGNED NOT NULL,
				deaths60_64				SMALLINT UNSIGNED NOT NULL,
				deaths65_69				SMALLINT UNSIGNED NOT NULL,
				deaths70_74				SMALLINT UNSIGNED NOT NULL,
				Deaths75_79d			SMALLINT UNSIGNED NOT NULL,
				Deaths80_84d			SMALLINT UNSIGNED NOT NULL,
				deaths85_89				SMALLINT UNSIGNED NOT NULL,
				deaths90_94				SMALLINT UNSIGNED NOT NULL,
				deaths95_or_mored		SMALLINT UNSIGNED NOT NULL,
				deaths_age_unspecified	SMALLINT UNSIGNED NOT NULL,
				deaths0_days			SMALLINT UNSIGNED NOT NULL,
				deaths1_6days			SMALLINT UNSIGNED NOT NULL,
				deaths7_27days			SMALLINT UNSIGNED NOT NULL,
				deaths28_365days		SMALLINT UNSIGNED NOT NULL,
				disease_type_code		CHAR(3)	NOT NULL,
				PRIMARY KEY (id)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strAccio = "Creació de taula neteja  ";
		executaConsulta($objConn, $strAccio, $strSQLdiseasesCleanup,"diseases_cleanup");

	} catch(PDOException $e) {
		imprimir("Connexió fallida:".$e->getMessage(), false);
	}

	$objConn = null;
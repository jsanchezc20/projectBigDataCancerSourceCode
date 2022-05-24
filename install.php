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
				`disease_type_code`	CHAR(3)			NOT NULL UNIQUE KEY,
				`name`				VARCHAR(255) 	NOT NULL,
			    `description`		TEXT			NOT NULL,
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
			INSERT INTO diseases_types(disease_type_code, name, description) VALUES
				("C0","Malignant neoplasm of lip","is a disease in which malignant (cancer) cells form in the lips or mouth. Tobacco and alcohol use can affect the risk of lip and oral cavity cancer. Signs of lip and oral cavity cancer include a sore or lump on the lips or in the mouth."),
				("C1","Malignant neoplasm of base of tongue","Tumors on the base of the tongue are usually larger when diagnosed because in the early stages the tumor is difficult to see. The only early symptom is ear pain. Voice changes and difficult swallowing occur later. Because base of the tongue cancer is diagnosed later, the cancer may have already spread to the neck."),
				("C2","Malignant neoplasm of other and unspecified parts of tongue","Tumours which location can be found in the dorsal surface of tongue, border of tongue, ventral surface of tongue or in the anterior two-thirds of tongue."),
				("C3","Malignant neoplasm of gum","is a type of head and neck cancer that begins when cells in the upper or lower gums grow out of control and form lesions or tumors. These cancers are often mistaken for gingivitis. Meet Memorial Sloan Kettering's renowned team of doctors specializing in mouth cancers."),
				("C4","Malignant neoplasm of floor of mouth","Floor of the mouth cancer most often begins in the thin, flat cells that line the inside of your mouth (squamous cells). Changes in the look and feel of the tissue on the floor of the mouth, such as a lump or a sore that doesn't heal, are often the first signs of floor of the mouth cancer."),
				("C5","Malignant neoplasm of palate","Cancer of the palate usually first noticed as an ulcer in the mouth. At first the ulcer is painless, but later becomes painful. Tobacco and alcohol use are risk factors for cancer of the soft palate. Cancer of the palate can be treated with surgery, radiation and chemotherapy"),
				("C6","Malignant neoplasm of other and unspecified parts of mouth","Those other parts of the mouth affected are the cheeck mucosa, the vestibule of mouth and retromolar area."),
				("C7","Malignant neoplasm of parotid gland","Parotid tumors often cause swelling in the face or jaw that usually isn't painful. Other symptoms include numbness, burning or prickling sensations in the face, or a loss of facial movement. Parotid tumor treatment is usually with surgery to remove the tumor."),
				("C8","Malignant neoplasm of other and unspecified major salivary glands","Tumours that can be found in the submandibular gland or the sublingual gland."),
				("C9","Malignant neoplasm of tonsil","Cancer of the tonsil is a type of head and neck cancer. Cancer is when abnormal cells start to divide and grow in an uncontrolled way. Symptoms often include a painless neck lump and a sore throat. The main risk factors for tonsil cancer are smoking, drinking alcohol and infection with the human papilloma virus (HPV)."),
				("C10","Malignant neoplasm of oropharynx","is cancer in the oropharynx, which is the middle part of your throat (pharynx). Symptoms include a sore throat that doesn't go away; a lump in the throat, mouth or neck; coughing up blood; white patch in the mouth and other symptoms"),
				("C11","Malignant neoplasm of nasopharynx","is a disease in which malignant (cancer) cells form in the tissues of the nasopharynx. Ethnic background and being exposed to the Epstein-Barr virus can affect the risk of nasopharyngeal cancer."),
				("C12","Malignant neoplasm of pyriform sinus"," is a highly malignant disease with a generally poor prognosis, accounting for almost 70% of all hypopharyngeal cancers.25 oct 2012"),
				("C13","Malignant neoplasm of hypopharynx","is a disease in which malignant (cancer) cells form in the tissues of the hypopharynx. Use of tobacco products and heavy drinking can affect the risk of developing hypopharyngeal cancer."),
				("C14","Malignant neoplasm of other and ill-defined sites in the lip, oral cavity and pharynx ","Squamous cell carcinomas amount to more than 90% of malignant tumours of the oral cavity and oropharynx. As in other parts of the upper aerodigestive tract, there is a strong and synergistic association with tobacco smoking and alcohol abuse. In some regions, particularly the Indian subcontinent, oral cancer is among the most frequent malignancies, largely due to tobacco chewing."),
				("C15","Malignant neoplasm of esophagus","Esophageal cancer is a disease in which malignant (cancer) cells form in the tissues of the esophagus. Smoking, heavy alcohol use, and Barrett esophagus can increase the risk of esophageal cancer. Signs and symptoms of esophageal cancer are weight loss and painful or difficult swallowing."),
				("C16","Malignant neoplasm of stomach","Abnormal growth of cells that begins in the stomach. The stomach is a muscular sac located in the upper middle of your abdomen, just below your ribs. Your stomach receives and holds the food you eat and then helps to break down and digest it."),
				("C17","Malignant neoplasm of small intestine","Small intestine cancer happens when malignant (cancer) cells form in your small intestine, or small bowel. Your small intestine is part of your body's digestive system, which includes organs like your liver, pancreas and gallbladder, as well as your gastrointestinal (GI) tract"),
				("C18","Malignant neoplasm of colon","Develops from the inner lining of the bowel and is usually preceded by growths called polyps, which may become invasive cancer if undetected. Depending on where the cancer begins, bowel cancer may be called colon or rectal cancer."),
				("C19","Malignant neoplasm of rectosigmoid junction","Representative examples of benign neoplasms include lipoma and leiomyoma. Representative examples of malignant neoplasms include carcinoma, lymphoma, and sarcoma."),
				("C20","Malignant neoplasm of rectum","is a disease in which cancer cells develop in the rectum. Signs of rectal cancer include diarrhea, constipation or blood in your poop. Treatments include surgery, chemotherapy and radiation therapy. Rectal cancer is curable, especially when detected early through screening methods like colonoscopy."),
				("C21","Malignant neoplasm of anus and anal canal","Is an uncommon type of cancer that occurs in the anal canal. The anal canal is a short tube at the end of your rectum through which stool leaves your body."),
				("C22","Malignant neoplasm of liver and intrahepatic bile ","The most common form of liver cancer begins in cells called hepatocytes and is called hepatocellular carcinoma. Liver cancer is cancer that begins in the cells of your liver. Your liver is a football-sized organ that sits in the upper right portion of your abdomen, beneath your diaphragm and above your stomach."),
				("C23","Malignant neoplasm of gallbladder","is a disease in which malignant (cancer) cells form in the tissues of the gallbladder. Gallbladder cancer is a rare disease in which malignant (cancer) cells are found in the tissues of the gallbladder. The gallbladder is a pear-shaped organ that lies just under the liver in the upper abdomen"),
				("C24","Malignant neoplasm of other and unspecified parts of biliary tract","Most biliary tract neoplasms are malignant and have been traditionally divided into cancers of the gallbladder, the extrahepatic bile ducts, and ampulla of Vater. Although infrequent, bile duct carcinomas and cancer of the gallbladder are not rare. In the United States, an estimated 6000–7000 new cases of carcinoma of the gallbladder and 3000–4000 new cases of carcinoma of the bile ducts are diagnosed annually."),
				("C25","Malignant neoplasm of pancreas","Pancreatic cancer begins in the tissues of your pancreas — an organ in your abdomen that lies behind the lower part of your stomach. Your pancreas releases enzymes that aid digestion and produces hormones that help manage your blood sugar."),
				("C26","Malignant neoplasm of other and ill-defined digestive organs","Rare tumours found in the digestive organs which cannot be diagnosed as a single organ location or its grow affect more than one."),
				("C28","Malignant neoplasm of trachea, bronchus and lung","is a malignant neoplasm of the lung arising from the epithelium of the bronchus or bronchiole. Accounts for 14% of all new cancers in males and 13% of all new cancers in females. Seventy percent of all lung cancer deaths occur between the ages of 55 and 74"),
				("C29","Malignant neoplasm of female breast(carcinoma in situ of breast and genitourinary system)","Rare tumours found in the genitourinary organs which cannot be diagnosed as a single organ location or its grow affect more than one."),
				("C30","Malignant neoplasm of nasal cavity and middle ear","Rare tumours found inside the middle ear area or nasal cavity which cannot be diagnosed as a single organ location or its grow affect more than one."),
				("C31","Malignant neoplasm of accessory sinuses","A primary or metastatic malignant neoplasm involving the paranasal sinuses."),
				("C32","Malignant neoplasm of larynx","Malignant tumours of the larynx are cancerous growths that have the potential to spread (metastasize) to other parts of the body. The most common type of laryngeal cancer is squamous cell carcinoma (SCC). It accounts for 95% of all laryngeal cancers."),
				("C33","Malignant neoplasm of trachea","The most common types of malignant tracheal and bronchial tumors include the following: Squamous cell carcinoma: This is the most common type of tracheal tumor. It is a fast-growing cancer that usually arises in the lower portion of the trachea."),
				("C34","Malignant neoplasm of bronchus and lung","It begins in the lungs. Your lungs are two spongy organs in your chest that take in oxygen when you inhale and release carbon dioxide when you exhale. Lung cancer is the leading cause of cancer deaths worldwide."),
				("C37","Malignant neoplasm of thymus","are diseases in which malignant (cancer) cells form on the outside surface of the thymus. The thymus, a small organ that lies in the upper chest under the breastbone, is part of the lymph system. It makes white blood cells, called lymphocytes, that protect the body against infections."),
				("C38","Malignant neoplasm of heart, mediastinum and pleura","The mediastinal is the middle part of the chest, marked by the breastbone in front, the spine in back, and the lungs on each side. The mediastinal contains vital organs, including the heart, aorta, trachea, and thymus. A malignant neoplasm is an abnormal growth of cells known as a tumor, which can spread to other parts of the body. Tumors can occur in any area of the mediastinal and are categorized by their location."),
				("C39","Malignant neoplasm of other and ill-defined sites in the respiratory system and intrathoracic organs","Rare tumours found inside the respiratory system which cannot be diagnosed as a single organ location or its grow affect more than one."),
				("C40","Malignant neoplasm of bone and articular cartilage","is a type of bone cancer that develops in cartilage cells. Cartilage is the specialized, gristly connective tissue that is present in adults and the tissue from which most bones develop. Cartilage plays an important role in the growth process."),
				("C41","Malignant neoplasm of bone and articular cartilage of other and unspecified sites","Rare tumours found inside bones and cartilage which cannot be diagnosed as a single organ location or its grow affect more than one."),
				("C43","Malignant melanoma of skin","Melanoma is a type of skin cancer that develops when melanocytes (the cells that give the skin its tan or brown color) start to grow out of control."),
				("C44","Other and unspecified malignant neoplasm of skin","Primary malignant skin tumors most often are carcinomas (either basal cell or squamous cell carcinomas that arise from cells in the epidermis) or melanomas that arise from pigment-containing skin melanocytes."),
				("C45","Mesothelioma","Malignant mesothelioma (me-zoe-thee-lee-O-muh) is a type of cancer that occurs in the thin layer of tissue that covers the majority of your internal organs (mesothelium)."),
				("C46","Kaposi's sarcoma","is a disease in which cancer cells are found in the skin or mucous membranes that line the gastrointestinal (GI) tract, from mouth to anus, including the stomach and intestines. These tumors appear as purple patches or nodules on the skin and/or mucous membranes and can spread to lymph nodes and lungs."),
				("C47","Malignant neoplasm of peripheral nerves and autonomic nervous system","Malignant Peripheral Nerve Sheath Tumor, or MPNST, is a cancer of the cells that form the sheath that covers and protects peripheral nerves. Peripheral nerves are those outside of the central nervous system (brain and spinal cord). MPNST is a type of sarcoma. This cancer grows in the soft tissues of the body, such as muscle, fat, tendons, ligaments, lymph and blood vessels, nerves, and other tissue that connects and supports the body. MPSNST grows quickly and can spread to other parts of the body."),
				("C48","Malignant neoplasm of retroperitoneum and peritoneum","Peritoneal cancer is a rare cancer. It develops in a thin layer of tissue that lines the abdomen. It also covers the uterus, bladder, and rectum. Made of epithelial cells, this structure is called the peritoneum. It produces a fluid that helps organs move smoothly inside the abdomen. Peritoneal cancer is not the same as intestinal or stomach cancer. Nor is it to be confused with cancers that spread (metastasize) to the peritoneum. Peritoneal cancer starts in the peritoneum, and hence is called primary peritoneal cancer."),
				("C49","Malignant neoplasm of other connective and soft tissue","Malignant soft tissue tumors are known as sarcomas. These tumors form in connective tissues, such as muscles, tendons, ligaments, fat and cartilage."),
				("C50","Malignant neoplasm of breast","It occurs when some breast cells begin to grow abnormally. These cells divide more rapidly than healthy cells do and continue to accumulate, forming a lump or mass. Cells may spread (metastasize) through your breast to your lymph nodes or to other parts of your body."),
				("C51","Malignant neoplasm of vulva","is a type of cancer that occurs on the outer surface area of the female genitalia. The vulva is the area of skin that surrounds the urethra and vagina, including the clitoris and labia. Vulvar cancer commonly forms as a lump or sore on the vulva that often causes itching"),
				("C52","Malignant neoplasm of vagina","is a rare cancer that occurs in your vagina — the muscular tube that connects your uterus with your outer genitals. Vaginal cancer most commonly occurs in the cells that line the surface of your vagina, which is sometimes called the birth canal."),
				("C53","Malignant neoplasm of cervix uteri","Cervical cancer is a type of cancer that occurs in the cells of the cervix — the lower part of the uterus that connects to the vagina. Various strains of the human papillomavirus (HPV), a sexually transmitted infection, play a role in causing most cervical cancer"),
				("C54","Malignant neoplasm of corpus uteri","The uterus is the hollow, pear-shaped pelvic organ where fetal development occurs. Endometrial cancer begins in the layer of cells that form the lining (endometrium) of the uterus. Endometrial cancer is sometimes called uterine cancer."),
				("C55","Malignant neoplasm of uterus, part unspecified","Are the tumors that affect the uterus aside from endometrail cancer. Such as Serous adenocarcinoma, Adenosaquamous carcinoma, Uterine carcinosarcoma, endometrial stromal sarcoma, leiomyosarcoma and undifferentiated sarcoma."),
				("C56","Malignant neoplasm of ovary","The ovaries — each about the size of an almond — produce eggs (ova) as well as the hormones estrogen and progesterone. Ovarian cancer is a growth of cells that forms in the ovaries. The cells multiply quickly and can invade and destroy healthy body."),
				("C57","Malignant neoplasm of other and unspecified female genital organs","Those organs are the Fallopian tubes, Broad ligament, Round ligament, Parametrium or Uterine adnexa."),
				("C58","Malignant neoplasm of placenta","is a fast-growing cancer that occurs in a woman's uterus (womb). The abnormal cells start in the tissue that would normally become the placenta. This is the organ that develops during pregnancy to feed the fetus."),
				("C60","Malignant neoplasm of penis"," is a disease in which malignant (cancer) cells form in the tissues of the penis. Human papillomavirus infection may increase the risk of developing penile cancer. Signs of penile cancer include sores, discharge, and bleeding. Tests that examine the penis are used to diagnose penile cancer."),
				("C61","Malignant neoplasm of prostate","Prostate cancer is one of the most common types of cancer. Many prostate cancers grow slowly and are confined to the prostate gland, where they may not cause serious harm. However, while some types of prostate cancer grow slowly and may need minimal or even no treatment, other types are aggressive and can spread quickly."),
				("C62","Malignant neoplasm of testis","Pain, swelling or lumps in your testicle or groin area may be a sign or symptom of testicular cancer or other medical conditions requiring treatment. Signs and symptoms of testicular cancer include: A lump or enlargement in either testicle. A feeling of heaviness in the scrotum."),
				("C63","Malignant neoplasm of other and unspecified male genital organs","They can be found at the epididymis, spermatic cord and scrotum."),
				("C64","Malignant neoplasm of kidney, except renal pelvis","A disease in which kidney cells become malignant (cancerous) and grow out of control, forming a tumor. Almost all kidney cancers first appear in the lining of tiny tubes (tubules) in the kidney. This type of kidney."),
				("C65","Malignant neoplasm of renal pelvis","Cancerous tumours of the renal pelvis and ureter Urothelial carcinoma (also called transitional cell carcinoma) is a cancerous tumour of the renal pelvis and ureter that can spread (metastasize) to other parts of the body. Cancerous tumours are also called malignant tumours."),
				("C66","Malignant neoplasm of ureter","Ureteral cancer is cancer of the ureters, muscular tubes that propel urine from the kidneys to the urinary bladder. It is also known as ureter cancer, renal pelvic cancer, and rarely ureteric cancer or uretal cancer. Cancer in this location is rare."),
				("C67","Malignant neoplasm of bladder","A disease in which malignant (cancer) cells form in the tissues of the bladder. Smoking can affect the risk of bladder cancer. Signs and symptoms of bladder cancer include blood in the urine and pain during urination. Tests that examine the urine and bladder are used to diagnose bladder cancer."),
				("C68","Malignant neoplasm of other and unspecified urinary organs","Malignant neoplasm of other and unspecified urinary organs. A primary or metastatic malignant tumor involving the urinary system. Common tumor types include carcinomas, lymphomas, and sarcomas."),
				("C69","Malignant neoplasm of eye and adnexa","Malignant neoplasm tumours that can be found inside the eye and its adnexa part. Also known as eye cancer."),
				("C70","Malignant neoplasm of meninges","Also known as meningioma. It arises from the meninges, the membranes that surround the brain and spinal cord."),
				("C71","Malignant neoplasm of brain","Many different types of brain tumors exist. Some brain tumors are noncancerous (benign), and some brain tumors are cancerous (malignant). Brain tumors can begin in your brain (primary brain tumors), or cancer can begin in other parts of your body and spread to your brain as secondary (metastatic) brain tumors."),
				("C72","Malignant neoplasm of spinal cord, cranial nerves ","A meningioma is a tumor that arises from the meninges — the membranes that surround the brain and spinal cord. Although not technically a brain tumor, it is included in this category because it may compress or squeeze the adjacent brain, nerves and vessels."),
				("C73","Malignant neoplasm of thyroid gland","Thyroid neoplasm is a neoplasm or tumor of the thyroid. It can be a benign tumor such as thyroid adenoma, or it can be a malignant neoplasm (thyroid cancer), such as papillary, follicular, medullary or anaplastic thyroid cancer"),
				("C74","Malignant neoplasm of adrenal gland","The type of cancer that develops in the cortex of the adrenal gland is called adrenal cortical carcinoma or just adrenal cancer. This rare type of cancer is also known as adrenocortical cancer (or carcinoma)"),
				("C75","Malignant neoplasm of other endocrine glands and related structures","It can grow inside the parathyroid gland, the pituitary gland, the craniopharyngeal duct, the pineal gland, the carotid body, the aortic body, other paraganglia and the pluriglandular involvement."),
				("C76","Malignant neoplasm of other and ill-defined sites","Rare tumours which cannot be diagnosed as a single organ location or its grow affect more than one."),
				("C77","Secondary and unspecified malignant neoplasm of lymph nodes","The tissue in the body is made up of cells. With cancer, the cells multiply uncontrollably, which leads to a malignant neoplasm (abnormal growth of tissue) developing. The cancer cells can destroy the healthy tissue and spread throughout the body. The cancer cells have spread in your body. The cancer cells have accumulated in the lymph nodes. These are called lymph node metastases. Fluid forms in tissue throughout the body every day. This tissue fluid is the lymph. The lymph is carried around the body by lymph vessels. The lymph vessels run around the entire body and are connected to blood vessels. At some points, there are lymph nodes along the lymph vessels. Pathogens are rendered harmless in the lymph nodes, for example."),
				("C78","Secondary malignant neoplasm of respiratory and digestive organs","Secondary malignant neoplasm of respiratory and digestive systems. Cancer that has spread from the original (primary) tumor to the lung. The spread of cancer to the lung. This may be from a primary lung cancer, or from a cancer at a distant site."),
				("C79","Secondary malignant neoplasm of other and unspecified sites","Secondary malignant neoplasm of other and unspecified sites. A tumor that has spread from its original (primary) site of growth to another site, close to or distant from the primary site. Metastasis is characteristic of advanced malignancies, but in rare instances can be seen in neoplasms lacking malignant morphology."),
				("C80","Malignant neoplasm without specification of site","Malignant carcinoid tumor of unspecified site. A term for diseases in which abnormal cells divide without control and can invade nearby tissues. Malignant cells can also spread to other parts of the body through the blood and lymph systems. There are several main types of malignancy."),
				("C81","Hodgkin lymphoma","Hodgkin's lymphoma is a type of cancer that affects the lymphatic system, which is part of the body's germ-fighting immune system. In Hodgkin's lymphoma, white blood cells called lymphocytes grow out of control, causing swollen lymph nodes and growths throughout the body"),
				("C82","Follicular lymphoma","Follicular lymphoma is a type of non-Hodgkin lymphoma (NHL). NHL is a cancer of the lymphatic system. Follicular lymphoma develops when the body makes abnormal B lymphocytes. These lymphocytes are a type of white blood cell that normally helps us fight infections."),
				("C83","Non-follicular lymphoma","Non-follicular indolent subtypes of non-Hodgkin lymphoma (NHL), which include chronic lymphocytic leukemia, small lymphocytic lymphoma (SLL) and marginal zone lymphomas (MZL), are a diverse group of disorders with different presenting features, behaviour patterns and treatment outcomes."),
				("C84","Mature T/NK-cell lymphomas","The mature T-cell and natural killer (NK)-cell lymphomas represent 10 to 15 percent of the non-Hodgkin lymphomas by incidence and comprise 23 clinicopathologic entities in the most recent classification. They include cutaneous T-cell lymphomas, discussed in Chap."),
				("C85","Other specified and unspecified types of non-Hodgkin lymphoma","It begins in your lymphatic system, which is part of the body's germ-fighting immune system. In non-Hodgkin's lymphoma, white blood cells called lymphocytes grow abnormally and can form growths (tumors) throughout the body."),
				("C86","Other specified types of T/NK-cell lymphoma","It’s classified in extranodal NK/T-cell lymphoma nasal type, hepatosplenic T-cell lymphoma, enteropathy-type (intestinal) T-cell lymphoma, blastic NK-cell lymphoma, angioimmunoblastic T-cell lymphoma and primary cutaneous CD30-positive T-cell proliferations"),
				("C88","Malignant immunoproliferative diseases and certain other B-cell lymphomas ","Its diseases are waldenström macroglobulinemia, heavy chain disease, immunoproliferative small intestinal disease, extranodal marginal zone B-cell lymphoma of mucosa-associated lymphoid tissue(MALT-lymphoma) and other malignant immunoproliferative diseases."),
				("C90","Multiple myeloma and malignant plasma cell neoplas","Myeloma is a type of blood cancer that develops from cells in the bone marrow called plasma cells. Bone marrow is the spongy tissue found inside the inner part of some of our large bones. The bone marrow produces different types of blood cells."),
				("C91","Lymphoid leukemia","Chronic lymphocytic leukemia (CLL) is the most common leukemia in adults. It's a type of cancer that starts in cells that become certain white blood cells (called lymphocytes) in the bone marrow. The cancer (leukemia) cells start in the bone marrow but then go into the blood"),
				("C92","Myeloid leukemia","Adult acute myeloid leukemia (AML) is a type of cancer in which the bone marrow makes a large number of abnormal blood cells."),
				("C93","Monocytic leukemia","Acute monocytic leukemia (AML-M5) is a subtype of AML, in which at least 80 percent of the affected blood cells are a type of white blood cell called monocytes. Half of the people diagnosed with AML M5 are older than age 49 . AML-M5 causes similar symptoms in the early stages as other types of leukemia."),
				("C94","Other leukemias of specified cell type","All other types of leukemia besides Chronic lymphocytic leukemia, Acute myeloid leukemia, Chronic myeloid leukemia and Acute lymphocytic leukemia"),
				("C95","Leukemia of unspecified cell type","A progressive, proliferative disease of blood cells, originating from myeloid or lymphoid stem cells. Cancer that starts in blood-forming tissue such as the bone marrow and causes large numbers of blood cells to be produced and enter the bloodstream."),
				("C96","Other and unspecified malignant neoplasms of lymph","Are tumors that affect the blood, bone marrow, lymph, and lymphatic system. Because these tissues are all intimately connected through both the circulatory system and the immune system, a disease affecting one will often affect the others as well, making myeloproliferation and lymphoproliferation (and thus the leukemias and the lymphomas) closely related and often overlapping problems");
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
				disease_type_code		CHAR(3)	NOT NULL
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ENGINE = InnoDB;
		SQL;

		$strAccio = "Creació de taula neteja  ";
		executaConsulta($objConn, $strAccio, $strSQLdiseasesCleanup,"diseases_cleanup");

	} catch(PDOException $e) {
		imprimir("Connexió fallida:".$e->getMessage(), false);
	}

	$objConn = null;
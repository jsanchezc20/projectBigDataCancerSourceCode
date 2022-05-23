<?php
	include 'mvc/config/config.php';
	include 'mvc/libraries/DB.php';

	$deathsInterval = DB::selectAll("SELECT * FROM deaths_intervals");

	foreach ($deathsInterval as $deathsIntervalRecordset)
	$strSQL =
	<<<SQL
		SELECT country_code, year, sex,  
		FROM diseases_cleanup
		WHERE 
		LIMIT 5;
	SQL;


	$diseasesCleanup = DB::selectAll(
		"SELECT * 
		FROM diseases_cleanup
         LIMIT 5"
	);

	foreach ($diseasesCleanup as $recordset) {
		echo "country_code: " . $recordset->country_code;
	}


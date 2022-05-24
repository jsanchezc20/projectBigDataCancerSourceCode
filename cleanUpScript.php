<?php
	include 'mvc/config/config.php';
	include 'mvc/libraries/DB.php';

	$strSQLInsert = "";

	$di = DB::selectAll("SELECT * FROM deaths_intervals;");

	foreach ($di as $dir) {

		$strColumnReference = $dir->column_reference;

		$strSQLSelect =
		<<<SQL
			SELECT country_code, year, sex, $strColumnReference, disease_type_code  
			FROM diseases_cleanup
			WHERE $strColumnReference <> 0;
		SQL;

		$dcu = DB::selectAll($strSQLSelect);

		$strSQLvalues = "";
		$strSQLInsert .= "INSERT INTO diseases (year,sex,country_code,disease_type_code,death_interval_code,count) VALUES";

		foreach ($dcu as $dcur) {

			$intCount = (int)$dcur->$strColumnReference;

			$strSQLvalues .=
			"($dcur->year,$dcur->sex,'$dcur->country_code','$dcur->disease_type_code','$dir->death_interval_code',$intCount),";
		}

		$strSQLInsert .= rtrim($strSQLvalues, ",") . ";";
	}

	file_put_contents("d:\diseases_data.sql", str_replace(array("\n", "\t", "\r"), '', $strSQLInsert));
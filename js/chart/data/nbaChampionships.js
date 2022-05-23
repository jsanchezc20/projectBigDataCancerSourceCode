function nbaChampionships(objScroll) {

	/* * * SET CHART TYPE * * */
	let strChartType = "bar"

	/* * * IMAGE SIZE * * */
	let intImgSize = 48;

	/* * * DATA * * */
	let strLegend = "Número de campeonatos por equipo";
	let strXYlabel = "::Campeonatos"
	let arrColors	= [],
		arrLabels	= [], 
		arrData		= [];

	arrColors[0] = "1, 130, 72";
	arrColors[1] = "253, 185, 39";
	arrColors[2] = "205, 16, 67";
	arrColors[3] = "0, 107, 182";
	arrColors[4] = "46, 41, 37";
	arrColors[5] = "200, 16, 46";
	arrColors[6] = "152, 1, 46";
	arrColors[7] = "0, 38, 117";
	arrColors[8] = "240, 8, 64";
	arrColors[9] = "246, 132, 40";
	arrColors[10] = "0, 71, 171";
	arrColors[11] = "39, 78, 55";
	arrColors[12] = "0, 0, 0";
	arrColors[13] = "80, 45, 127";
	arrColors[14] = "0, 51, 102";
	arrColors[15] = "204, 9, 47";
	arrColors[16] = "4, 42, 92";
	arrColors[17] = "0, 107, 182";
	arrColors[18] = "111, 38, 61";
	arrColors[19] = "42, 39, 35";

	arrLabels[0] = "Boston Celtics";
	arrLabels[1] = "Los Angeles Lakers";
	arrLabels[2] = "Chicago Bulls";
	arrLabels[3] = "Golden State Warriors";
	arrLabels[4] = "San Antonio Spurs";
	arrLabels[5] = "Detroit Pistons";
	arrLabels[6] = "Miami Heat";
	arrLabels[7] = "Philadelphia 76ers";
	arrLabels[8] = "Houston Rockets";
	arrLabels[9] = "New York Knicks";
	arrLabels[10] = "Baltimore Bullets (extinto)";
	arrLabels[11] = "Milwaukee Bucks";
	arrLabels[12] = "Portland Trail Blazers";
	arrLabels[13] = "Sacramento Kings";
	arrLabels[14] = "Oklahoma City Thunder";
	arrLabels[15] = "Atlanta Hawks";
	arrLabels[16] = "Washington Wizards";
	arrLabels[17] = "Dallas Mavericks";
	arrLabels[18] = "Cleveland Cavaliers";
	arrLabels[19] = "Toronto Raptors";

	arrData[0] = 17;
	arrData[1] = 17;
	arrData[2] = 6;
	arrData[3] = 6;
	arrData[4] = 5;
	arrData[5] = 3;
	arrData[6] = 3;
	arrData[7] = 3;
	arrData[8] = 2;
	arrData[9] = 2;
	arrData[10] = 1;
	arrData[11] = 1;
	arrData[12] = 1;
	arrData[13] = 1;
	arrData[14] = 1;
	arrData[15] = 1;
	arrData[16] = 1;
	arrData[17] = 1;
	arrData[18] = 1;
	arrData[19] = 1;

	loadChart(	objScroll,
				strChartType,
				intImgSize,
				strLegend,
				strXYlabel,
				arrColors,
				arrLabels,
				arrData);
}
function childLabor(objScroll) {

	/* * * SET CHART TYPE * * */
	let strChartType = "doughnut" //"horizontalBar"

	/* * * IMAGE SIZE * * */
	let intImgSize = 80;

	/* * * DATA * * */
	let strLegend = "Trabajo infantil en el mundo. Número de niños/as por regiones con edades entre 5-17 años (2012-2016)";
	let strXYlabel = "Niños (Miles)::"
	let arrColors	= [],
		arrLabels	= [], 
		arrData		= [];

	arrColors[0] = "0, 197, 114";
	arrColors[1] = "255, 120, 0";
	arrColors[2] = "82, 104, 34";
	arrColors[3] = "70, 85, 215";
	arrColors[4] = "99, 0, 168";

	arrLabels[0] = "África";
	arrLabels[1] = "Asia y el Pacífico";
	arrLabels[2] = "América";
	arrLabels[3] = "Europa y Asia Central";
	arrLabels[4] = "Estados Árabes";

	arrData[0] = 72113;
	arrData[1] = 62077;
	arrData[2] = 10735;
	arrData[3] = 5534;
	arrData[4] = 1162;

	loadChart(	objScroll,
				strChartType,
				intImgSize,
				strLegend,
				strXYlabel,
				arrColors,
				arrLabels,
				arrData);
}
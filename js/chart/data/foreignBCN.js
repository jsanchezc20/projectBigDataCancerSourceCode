google.charts.load("current", { packages: ['corechart'] });
//google.charts.setOnLoadCallback(drawChart);
function foreignBCN(objScroll) {
	
	const CHART_HEIGHT	= 590;
	const CHART_WIDTH	= 880;

	strBgColor = '#f2f2df'

	let data = google.visualization.arrayToDataTable([
		[	'Nacionalidad',
			'Italiana',
			'China',
			'Paquistaní',
			'Francesa',
			'Marroquí',
			'Colombiana',
			'Hondureña',
			'Filipina',
			'Peruana',
			'Boliviana',
			'Venezolana',
			'Ecuatoriana',
			'Británica',
			'Rusa',
			'Alemana',
			{ role: 'annotation' }
		],
		['2014', 25015, 16435, 20052, 13062, 1282 ,  9215, 5494, 8684, 10258, 1160, 3928, 1084, 6658, 5210, 6930, ''],
		['2015', 25707, 17487, 19414, 13281, 12601,  8011, 5849, 8491,  8486, 9946, 4344, 8647, 6758, 5840, 6870, ''],
		['2016', 26993, 18448, 19192, 13671, 12552,  7930, 6726, 8682,  7955, 9280, 5028, 8108, 6980, 6239, 6783, ''],
		['2017', 29272, 19866, 19285, 14717, 12827,  9059, 8005, 9049,  8372, 9063, 6277, 8059, 7345, 7015, 7041, ''],
		['2018', 31500, 20555, 19240, 15260, 13058, 10192, 9542, 9149,  9069, 8582, 7936, 7751, 7609, 7234, 7075, '']
	]);

	let options = {
		bar: { groupWidth: '75%' },
		backgroundColor: strBgColor,
		fontSize: 13,
		height: CHART_HEIGHT,
		legend: { position: 'top', maxLines: 3 },
		isStacked: true,
		width: CHART_WIDTH,
	};

	let objDiv = document.getElementById(`${objScroll.id}Chart`)
	
	objDiv.style.height = CHART_HEIGHT;
	objDiv.style.width	= CHART_WIDTH;

	let chart = new google.visualization.ColumnChart(objDiv);
	chart.draw(data, options);

	window.scrollTo(0, objScroll.offsetTop);
}
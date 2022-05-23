google.charts.load('current', {'packages':['line']});

function greenhouseEffect(objScroll) {
	
	const CHART_HEIGHT	= 580;
	const CHART_WIDTH	= 880;

	let strBgColor	= '#f2f2df'

	let strTitle	= 'Variación de las emisiones de gases de efecto invernadero respecto a 1984';
	let strSubTitle = 'El CO2 ha aumentado un 18,4%; el metano, un 13% y el óxido nitroso, un 9%';

	let data = new google.visualization.DataTable();
      data.addColumn('string', 'Año');
      data.addColumn('number', 'CO2');
      data.addColumn('number', 'CH4');
      data.addColumn('number', 'N2O');

	data.addRows([
		['1984', 0, 0, 0],
		['1985', 0.40662213, 0.72551391, 0.19743337],
		['1986', 0.8422887, 1.5114873, 0.46067785],
		['1987', 1.36508859, 2.23700121, 0.65811122],
		['1988', 2.0621551, 2.90205562, 0.92135571],
		['1989', 2.52686611, 3.56711004, 1.21750576],
		['1990', 2.87539936, 4.17170496, 1.54656137],
		['1991', 3.25297705, 4.83675937, 1.80980586],
		['1992', 3.45628812, 5.3808948, 1.97433366],
		['1993', 3.68864362, 5.56227328, 2.04014478],
		['1994', 4.12431019, 5.86457074, 2.23757815],
		['1995', 4.67615452, 6.28778718, 2.46791708],
		['1996', 5.16990996, 6.46916566, 2.66535044],
		['1997', 5.51844322, 6.65054414, 2.96150049],
		['1998', 6.30264304, 7.25513906, 3.22474498],
		['1999', 6.8835318, 7.6783555, 3.52089503],
		['2000', 7.31919837, 7.73881499, 3.8828562],
		['2001', 7.75486494, 7.6783555, 4.11319513],
		['2002', 8.30670927, 7.73881499, 4.34353406],
		['2003', 9.00377578, 7.98065296, 4.54096742],
		['2004', 9.52657566, 7.92019347, 4.77130635],
		['2005', 10.1365089, 7.92019347, 5.00164528],
		['2006', 10.7464421, 7.92019347, 5.29779533],
		['2007', 11.2692419, 8.22249093, 5.52813425],
		['2008', 11.8791751, 8.70616687, 5.8242843],
		['2009', 12.3148417, 9.00846433, 6.08752879],
		['2010', 12.9538193, 9.31076179, 6.4165844],
		['2011', 13.5347081, 9.61305925, 6.71273445],
		['2012', 14.1736857, 9.91535671, 7.0088845],
		['2013', 14.9288411, 10.2781137, 7.33794011],
		['2014', 15.5097299, 10.8222491, 7.69990128],
		['2015', 16.2067964, 11.4873035, 8.02895689],
		['2016', 17.1362184, 11.9709794, 8.25929582],
		['2017', 17.775196, 12.3941959, 8.55544587],
		['2018', 18.4432181, 12.9987908, 8.9503126]
	]);

	let options = {
		/*
		chart: {
			title: strTitle,
			subtitle: strSubTitle
		},
		pointSize: 7,
		dataOpacity: 0.3,
		*/
		backgroundColor: strBgColor,
		chartArea: {
			backgroundColor: strBgColor,
		},
		height: CHART_HEIGHT,
		width: CHART_WIDTH,
		axes: {
			x: {
				0: { side: 'top' }
			}
		}
	};
	
	let objDiv = document.getElementById(`${objScroll.id}Chart`)
	
	objDiv.style.height = CHART_HEIGHT;
	objDiv.style.width	= CHART_WIDTH;

	let chart = new google.charts.Line(objDiv);

	chart.draw(data, google.charts.Line.convertOptions(options));

	window.scrollTo(0, objScroll.offsetTop);

}
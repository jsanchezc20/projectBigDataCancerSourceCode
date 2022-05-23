function getArrayColorOpacity(arrayObj, fltOpacity) {
	let newArray = [];
	for (let i = arrayObj.length; i--;) {
		newArray[i] = "rgba(" + arrayObj[i] + ", " + fltOpacity + ")";
	}
	return newArray;
}

function loadChart(objScroll, strChartType, intImgSize, strLegend, strXYlabel, arrColors, arrLabels, arrData) {

	const BAR_OPACITY = "0.5";
	const BORDER_WIDTH = 3;
	const IMAGE_TOP = 1;

	let strComa			= "",
		strLabels		= "",
		strBgColor		= "",
		strBorderColor	= "",
		strData			= "",
		strImages		= "",
		strXYAxes		= "";

	let arrBgColor		= [],
		arrBorderColor	= [];

	let blnLegend		= 1;

	let strName = objScroll.id;
	let objCanvas = document.getElementById(`${strName}Canvas`)
	let objContext = objCanvas.getContext("2d");

	arrBgColor = getArrayColorOpacity(arrColors, BAR_OPACITY);
	arrBorderColor = getArrayColorOpacity(arrColors, 1);

	for (let i = 0 ; i < arrColors.length ; i++) {
		strComa = (i == arrColors.length) ? "" : ",";
		strLabels		+= `arrLabels[${i}]${strComa}`;
		strBgColor		+= `arrBgColor[${i}]${strComa}`;
		strBorderColor	+= `arrBorderColor[${i}]${strComa}`;
		strData			+= `arrData[${i}]${strComa}`;
		if (strName) {
			strImages 	+= `{src:'./img/${strName}/${i} ${arrLabels[i]}.png',width:${intImgSize},height:${intImgSize}}${strComa}`;
		}
	}

	if (strChartType!=="doughnut") {
		blnLegend = 0;
		strXYAxes = `
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: strXYlabel.split("::")[0]
					}
				}],
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: strXYlabel.split("::")[1]
					}
				}]
			},`;
	}

	/* * * BUILD * * */
	eval(`
		let myChart = new Chart(objContext, {
			type: strChartType,
			data: {
				labels: 	[${strLabels}],
				datasets:	[{
					//label: "",
					data: 			[${strData}],
					backgroundColor:[${strBgColor}],
					borderColor:	[${strBorderColor}],
					borderWidth: 	${BORDER_WIDTH}
				}]
			},
			options: {
				legend: {
					display:${blnLegend}
				},
				title: {
					display: true,
					text: strLegend.toUpperCase()
				},
				${strXYAxes}
				plugins: {
					labels: {
						render: 'image',
						textMargin: ${IMAGE_TOP},
						images: [${strImages}]
					}
				},
				layout: {
					padding: {
						top: 10
					}
				}
				
			}
		});
	`);

	if (objCanvas.style.visibility === "hidden" || objCanvas.style.visibility === "") {
		objCanvas.style.visibility = "visible";
		eval(`window.scrollTo(0, ${objScroll.id}Article.offsetTop)`);
	} else {
		if(objCanvas.offsetHeight < 200) {
			objCanvas.style.visibility = "hidden";
		}
	}

}
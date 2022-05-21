function destroyModal(objMain, objCenter, objBackgroundModal, objModalContainer) {
	objCenter.style.display = "none";
	objMain.appendChild(objCenter);
	objBackgroundModal.remove();
	objModalContainer.remove();
}

function createElementModal(id, height, objCSSAttributes) {
	let obj = document.createElement("div");
	obj.setAttribute("id", id);
	obj.style.setProperty( "height",height + "px");

	for (const [key, value] of Object.entries(objCSSAttributes)) {
		obj.style.setProperty(key, value);
	}

	return obj;
}

function openModal(objCenter) {
	/*	Si l'objecte a centrar no es mostra, l'hem d'habilitar
		momentàniament per aconseguir la seva alçada */
	let blnHideAgain = false;
	if (objCenter.style.display === "none" || objCenter.style.display === "") {
		objCenter.style.display = "block";
		blnHideAgain = true;
	} else {
		// Si l'objecte és visible es que ja s'ha carregat el modal, sortim
		return;
	}

	// Assignem l'element pare del formulari
	let objParent = objCenter.parentNode;

	let totalHeight = 0;
	let bodyScroll = document.body.scrollHeight;			// Alçada total incloent el recorregut del scroll
	let docClient = document.documentElement.clientHeight;	// Alçada del document, el que es veu per pantalla
	let verticalCenter = (docClient / 2) - (objCenter.offsetHeight / 2);
	//	Centrat vertical = (Meitat de l'alçada de la pantalla) - (meitat del mateix objecte a centrar)

	if (blnHideAgain) {
		objCenter.style.display = "none";
	}

	/*	Si l'alçada amb Scroll supera l'alçada de la pantalla assignem
		aquesta alçada, si nó, assignem l'alçada de la mateixa pantalla. */
	totalHeight = (bodyScroll > docClient) ? bodyScroll : docClient;

	// Definim els atributs CSS de cada element
	const backgroundModal = {
		"background-color" : "rgba(0, 0, 0, .75)",
		"left" : 0,
		"position" : "absolute",
		"top" : 0,
		"visibility" : "hidden",
		"width" : "100%",
	};

	const modalContainer = {
		"background-color": "transparent",
		"text-align" : "center",
		"visibility" : "hidden",
		"width" : "100%",
	}

	// Construïm els elements
	let objBackgroundModal = createElementModal("backgroundModal", totalHeight, backgroundModal);
	let objModalContainer = createElementModal("modalContainer", docClient, modalContainer);

	// Centrem verticalment l'objecte fill del modal
	objCenter.style.top =`${verticalCenter}px`;

	// Afegim al backgroundModal el contenidor
	objBackgroundModal.appendChild(objModalContainer);
	// Afegim al contenidor l'objecte centrat (horitzontal i vertical)
	objModalContainer.appendChild(objCenter);

	// Si premem la tecla 'Esc' sortim del modal
	let handlerKeyDown =
	document.addEventListener("keydown", (event) => {
		if (event.key === "Escape") {
			destroyModal(objParent, objCenter, objBackgroundModal, objModalContainer);
		}
	});

	// Si premem el fons del modal sortim del modal
	objBackgroundModal.onclick = function () {
		destroyModal(objParent, objCenter, objBackgroundModal, objModalContainer);
		document.removeEventListener("keydown", handlerKeyDown);
	};

	/*	Deshabilitem la sortida del modal si estem a sobre
		del fill, per poder clicar a sobre i no sortir. */
	objCenter.addEventListener("mouseover", (event) => {
		objBackgroundModal.onclick = null;
	});

	// Quan sortim del fill tornem a habilitar la sortida del modal
	objCenter.addEventListener("mouseout", (event) => {
		objBackgroundModal.onclick = function () {
			destroyModal(objParent, objCenter, objBackgroundModal, objModalContainer);
			document.removeEventListener("keydown", handlerKeyDown);
		};
	});

	// Mostrem el modal complet: fons + contenidor + objecte centrat (horitzontal i vertical)
	objBackgroundModal.style.visibility = "visible";
	objModalContainer.style.visibility = "visible";
	objCenter.style.display = "block";

	// Afegim a l'element principal el modal complet
	objParent.appendChild(objBackgroundModal);
}
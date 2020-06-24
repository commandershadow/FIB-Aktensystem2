/* */

function initMap(art) {
	var karte = document.getElementById('map');
	// Lösche sämtlichen Inhalt der Karte
	while(karte.firstChild) {
		karte.removeChild(karte.firstChild);
	}
	// lade die neuen Daten
	mapdata.data = art;
	loadMapData();
}

// Testfunktion zum anzeigen von variablen
function details() {
	alert(mapdata.data);
	alert(mapdata.zoomin);
	alert(mapdata.zoompos.top);
	alert(mapdata.zoompos.left);
}

// Was soll passieren wenn man auf die map klickt
function mapclick() {
	// Hereinzoomen wenn noch nicht geschehen
	if(mapdata.zoomin == false) {zoom(); return}
	// Hinzufügen eines Punktes aktivieren
	if(mapdata.addMode == true) {addPoint(); return}
}

function zoom() {
	var map = document.getElementById('map');
	var zoominfo = document.getElementById("mapInfo");
	if(mapdata.zoomin == true) {
		// Zoomout
		map.style.backgroundSize = "900px 900px";
		map.style.backgroundPosition = "0px 0px";
		zoominfo.innerHTML = 'Keine Funktionen ohne Zoom verf&uuml;gbar';
		mapdata.zoomin = false;
		if(mapdata.addMode == true)
			action('addPoint');
	} else {
		// Mausposition bestimmen
		var pos = mouse_pos();
		var elPos = findPos(map);
		// Zoomposition bestimmen
		pos.top = (pos.top - elPos.top) * 5 - 450;
		pos.left = (pos.left - elPos.left) * 5 - 450;
		if(pos.top < 0) pos.top = 0;
		if(pos.top > 3600) pos.top = 3600;
		if(pos.left < 0) pos.left = 0;
		if(pos.left > 3600) pos.left = 3600;
		// Zoomin
		map.style.backgroundSize = "4500px 4500px";
		map.style.backgroundPosition = "-" + pos.left + "px -" + pos.top + "px";
		zoominfo.innerHTML = '<a href="javascript:zoom()">&Uuml;bersicht</a>';
		if(mapdata.rang >= 6) zoominfo.innerHTML += ' | <a href="javascript:action(\'addPoint\')" id="addLink">Punkt Hinzufügen</a>';
		mapdata.zoompos.top = pos.top;
		mapdata.zoompos.left = pos.left;
		mapdata.zoomin = true;
	}
	initMap(mapdata.art);
}

/* Ermittlung der Mausposition */
function mouse_pos(e) {
	if(!e) e = window.event;
	var body = (window.document.compatMode && window.document.compatMode == "CSS1Compat") ? 
	window.document.documentElement : window.document.body;
	return {
	// Position im Dokument
	top: e.pageY ? e.pageY : e.clientY + body.scrollTop - body.clientTop,
	left: e.pageX ? e.pageX : e.clientX + body.scrollLeft  - body.clientLeft
	};
}

/* Ermittlung der Position eines Elements */
function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		} while (obj = obj.offsetParent);
	}
	return {left: curleft, top: curtop};
}

function loadMapData() {
	points.forEach(addPointOnMap);
}

function addPointOnMap(data,index) {
	// Bestimme die Position anhand der daten
	var data = {name: data[0],marker: data[1],link: data[2], left: data[3], top: data[4], wohnung: data[5], garage: data[6], id: data[7], desc: data[8], image: data[9]};
	var map = document.getElementById("map");
	if(mapdata.zoomin == true) {
		data.markerSize = 72;
		data.left = data.left - mapdata.zoompos.left - 36;
		data.top = data.top - mapdata.zoompos.top - 60;
		// Wenn ausserhalb des Ausschnittes dann skippen
		if(data.left < 36 || data.left > 864) return;
		if(data.top < 72 || data.top > 890) return;
		data.cursor = 'pointer';
		
		//Erstellen einer Zusatzinfobox, Wenn hereingezoomt ist
		var cb = new Array();
		cb.push('<img src="img/cbfalse.png" style="width:20px; height:20px">');
		cb.push('<img src="img/cbtrue.png" style="width:20px; height:20px">');
		var info = document.createElement("div");
		info.style.display = "block";
		info.style.position = "relative";
		info.style.top = "-10000px";
		info.style.left = "-200px";
		info.style.width = "472px";
		info.style.border = "1px solid #000";
		info.style.borderRadius = "5px";
		info.style.padding = "5px";
		info.style.backgroundColor = "#aaa";
		info.style.textAlign = "center";
		info.style.zIndex = 9500;
		info.innerHTML = 
			'<span style="font-weight:bold; text-decoration:underline">' + data.name + '</span>'+
			' | Garage: ' + cb[data.garage] + ' Wohnung: ' + cb[data.wohnung] + '<br>' +
			'<span style="font-style:italic;">' + data.desc + '</span>';
		if(mapdata.rang >= 4)
			info.innerHTML += ' | <a href="home.php?p=det_grunfr&typ=' + data.link + '">Details</a>';
		if(mapdata.rang >= 4)
			info.innerHTML += ' | <a href="javascript:deletePoint(' + data.id + ')">L&ouml;schen</a>';
		if(data.image != 'null') {
			info.innerHTML += '<img src="' + data.image + '" alt="BILD" style="width:460px">';
			if(mapdata.rang >= 6)
				info.innerHTML += '<br><a href="home.php?p=karte&f=delImg&id=' + data.id + '">Bildverknüpfung löschen</a>';
		} else {
			if(mapdata.rang >= 6)
				info.innerHTML += '<br><a href="home.php?p=karte&f=addImg&id=' + data.id + '">Bildverknüpfung hinzufügen</a>';
		}
	} else {
		data.markerSize = 36;
		data.left = Math.round(data.left/5)-18;
		data.top = Math.round(data.top/5)-30;
		data.cursor = 'crosshair';
	}
	
	// Erstelle den Mapmarker
	var point = document.createElement("div");
	point.id = data.id;
	point.style.top = data.top + "px";
	point.style.left = data.left + "px";
	point.style.width = point.style.height = data.markerSize + "px";
	point.style.backgroundImage = "url(" + data.marker + ")";
	point.style.backgroundSize = point.style.width + " " + point.style.height;
	point.style.position = 'absolute';
	point.style.cursor = data.cursor;
	point.style.zIndex = 9000;
	if(mapdata.zoomin == false) point.title = data.name;
	map.appendChild(point);
	if(mapdata.zoomin == true) point.appendChild(info);
	
}

function action(type) {
	if(type == 'addPoint') {
		var al = document.getElementById("addLink");
		var form = document.getElementById("mapAction");
		if(mapdata.addMode == false) {
			al.innerHTML = 'Hinzuf&uuml;gen Abbrechen';
			form.style.display = 'block';
			form.style.visibility = 'visible';
			mapdata.addMode = true;
		} else {
			if(al != null)
				al.innerHTML = 'Punkt hinzuf&uuml;gen';
			form.style.display = 'none';
			form.style.visibility = 'hidden';
			mapdata.addMode = false;
		}
	}
}

function addPoint() {
	var div = findPos(document.getElementById('map'));
	var ileft = document.getElementById('ypos');
	var itop = document.getElementById('xpos');
	var ipos = document.getElementById('koordInput');
	var mouse = mouse_pos();
	var top,left;
	top = mouse.top - div.top;
	left = mouse.left - div.left;
	ileft.innerHTML = left;
	itop.innerHTML = top;
	left = mapdata.zoompos.left + left;
	top = mapdata.zoompos.top + top;
	ipos.value = left + ';' + top;
}

function deletePoint(id) {
	if(confirm("Möchten Sie diesen Punkt wirklich löschen? Er kann nicht wiederhergestellt werden")) 
		window.location.href = "home.php?p=karte&f=delete&id=" + id;
}
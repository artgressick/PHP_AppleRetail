//dtn:  Set up the Ajax connections
function startAjax() {
	var ajax = false;
	try { 
		ajax = new XMLHttpRequest(); // Firefox, Opera 8.0+, Safari
	} catch (e) {
	    // Internet Explorer
	    try { ajax = new ActiveXObject("Msxml2.XMLHTTP");
	    } catch (e) {
			try { ajax = new ActiveXObject("Microsoft.XMLHTTP");
	        } catch (e) {
	        	alert("Your browser does not support AJAX!");
	        }
	    }
	}
	return ajax;
}

//dtn: This is the revert for the Warning Overlay page... it turns it from the dark background back to the normal view.
function revert() {
	document.getElementById('overlaypage').style.display = "none";
	document.getElementById('warning').style.display = "block";
}

//dtn: This is the warning window.  It sets up the gay overlay background with the window in the middle asking if you are sure you want to deleted whatever.
function warning(id,val1,chrKEY,table) {
	var height = (document.height > window.innerHeight ? document.height : window.innerHeight);
	document.getElementById('gray').style.height = height + "px";
	document.getElementById('message').style.top = window.pageYOffset+"px";
	
	document.getElementById('delName').innerHTML = val1;
	document.getElementById('idDel').value = id;
	document.getElementById('chrKEY').value = chrKEY;
	document.getElementById('tblName').value = table;
	document.getElementById('overlaypage').style.display = "block";
}

//dtn: This is the basic delete item script.  It uses GET's instead of Posts
//dtn: This is the basic delete item script.  It uses GET's instead of Posts
function delItem(address) {
	var id = document.getElementById('idDel').value;
	var chrKEY = document.getElementById('chrKEY').value;
	var tblName = document.getElementById('tblName').value;
	ajax = startAjax();

	if(tblName != '') { 
		address = address + id + "&chrKEY=" + chrKEY + "&tbl=" + tblName;
	} else {
		address = address + id + "&chrKEY=" + chrKEY;
	}

	if(ajax) {
		ajax.open("GET", address);

		ajax.onreadystatechange = function() { 
			if(ajax.readyState == 4 && ajax.status == 200) { 
				//alert(ajax.responseText);
				showNotice(id,ajax.responseText,tblName);
			} 
		} 
		ajax.send(null); 
	}
} 

//dtn: This is used to erase a line from the sort list.
function showNotice(id, type,tblName) {
	document.getElementById(tblName+'tr' + id).style.display = "none";
	if(document.getElementById('resultCount')) {
		var rc = document.getElementById('resultCount');
		rc.innerHTML = parseInt(rc.innerHTML) - 1;
	}
	
	repaint(tblName);
	revert();
}

//dtn: This is the quick delete used on the sort list pages.  It's the little hoverover x on the right side.
function quickdel(address, idEntity, fatherTable, attribute) {
	ajax = startAjax();
	
	if(ajax) {
		ajax.open("GET", address);
	
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200) { 
				//alert(ajax.responseText);
				document.getElementById(fatherTable + 'tr' + idEntity).style.display = "none";
				repaintmini(fatherTable);
			} 
		} 
		ajax.send(null); 
	}
} 

//dtn: Function added to get rid of the first line in the sort columns if there are no values in the sort table yet.
//		Ex: "There are no People in this table" ... that gets erased and replaced with a real entry
function noRowClear(fatherTable) {
	var val = document.getElementById(fatherTable).getElementsByTagName("tr");
	if(val.length <= 2 && val[1].innerHTML.length < 100) {
		var tmp = val[0].innerHTML
		document.getElementById(fatherTable).innerHTML = "";
		document.getElementById(fatherTable).innerHTML = tmp;
	}
}

//dtn: This is the main function to POST information through Ajax
function postInfo(url, parameters) {
	ajax = startAjax();
	ajax.open('POST', url, true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.setRequestHeader("Content-length", parameters.length);
	ajax.setRequestHeader("Connection", "close");
	ajax.send(parameters);
	
	ajax.onreadystatechange = function() { 
   		if(ajax.readyState == 4 && ajax.status == 200) {
			//alert(ajax.responseText);
   			//document.getElementById('showinfo').innerHTML = ajax.responseText;
   		}
  	}
}

// This will mark something as show or not
function showHide(bf,id,table,old,idUser,chrKEY) {
	ajax = startAjax();
	if(old == 0) { var show=1 ;} else { var show=0; }
	var address = bf + "ajax_delete.php?postType=showhide&tbl=" + table + "&idPerson=" + idUser + "&show=" + show + "&chrKEY=" + chrKEY + "&Old=" + old + "&id=";
	if(ajax) {
		ajax.open("GET", address + id);
	
		ajax.onreadystatechange = function() { 
			if(ajax.readyState == 4 && ajax.status == 200) { 
				//alert(ajax.responseText);
				if(ajax.responseText == 3) {
					if(old == 0) { document.getElementById('bShowTD'+id).innerHTML = 'Shown'; document.getElementById('bShow'+id).value = 1; } 
						else { document.getElementById('bShowTD'+id).innerHTML = 'Hidden'; document.getElementById('bShow'+id).value = 0; }
				}
			} 
		} 
		ajax.send(null); 
	}
} 

// This will mark something as show or not on the Discussion section
function quickhide(bf,chrKEY,table,id,idPerson) {
	ajax = startAjax();

	if(document.getElementById(table+id+'btn').alt == "Remove Post") { var show=0; var old=1;} else { var show=1; var old=0;}

	var address = bf + "ajax_delete.php?postType=showhide&tbl=" + table + "&idPerson=" + idPerson + "&show=" + show + "&chrKEY=" + chrKEY + "&Old=" + old + "&id=";
	if(ajax) {
		ajax.open("GET", address + id);
		ajax.onreadystatechange = function() { 
			if(ajax.readyState == 4 && ajax.status == 200) { 
//				alert(ajax.responseText);
				if(ajax.responseText == 3) {
					if(document.getElementById(table+id+'btn').alt == "Remove Post") { 
						document.getElementById(table+id+'btn').alt = "Show Post";
						document.getElementById(table+id).innerHTML = "<div class='Removed'>Removed by User</div>";
						document.getElementById(table+id+'btn').title = "Shows your Post so others can see.";
						document.getElementById(table+id+'btn').src = bf+"images/discussion_bottom-show.gif";
					} else { 
						document.getElementById(table+id+'btn').alt = "Remove Post";
						document.getElementById(table+id+'btn').title = "Hides your Post from View.";
						document.getElementById(table+id+'btn').src = bf+"images/discussion_bottom-remove.gif";
						getData(bf,chrKEY,table,id);
					}
				}
			} 
		} 
		ajax.send(null); 
	}
} 

function getData(bf,chrKEY,table,id) {
	ajax = startAjax();
	var address = bf + "ajax_delete.php?postType=get" + table + "&tbl=" + table + "&chrKEY=" + chrKEY + "&id=";

	if(ajax) {
		ajax.open("GET", address + id);
	
		ajax.onreadystatechange = function() { 
			if(ajax.readyState == 4 && ajax.status == 200) { 
//				alert(ajax.responseText);
				document.getElementById(table+id).innerHTML = ajax.responseText;
			} 
		} 
		ajax.send(null); 
	}
}

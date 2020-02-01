// ##############################################################
// # JS Show / Hide.
// #
// # Divs/Spans/Tables with the class name 'showHideTitle' will
// #   be made into click hiding menus UNLESS they have a
// #   <input type=... > field in them.
// #
// # NOTE:  if title can have any ID, but the body that will dissapear
// #   MUST have the same id with 'box' at the end.  Ex:
// #   Title ID:  'dissapearing'     Body ID:  'dissapearingbox'
// #
// # OPTIONAL:  Any class with 'no_show_hide' in it will not be
// #   made into a clickable option
// #############################################################

/* Mozilla? */
if (document.addEventListener) {
    document.addEventListener("DOMContentLoaded", showHide, false);
}

if (/WebKit/i.test(navigator.userAgent)) { // sniff
    var _timer = setInterval(function() {
        if (/loaded|complete/.test(document.readyState)) {
            showHide(); // call the onload handler
        }
    }, 10);
}

/* for other browsers */
window.onload = showHide;

	
function showHide() {
    if (arguments.callee.done) return;
    arguments.callee.done = true;

	showHideConvert('div');
	showHideConvert('span');

	var tbls = document.getElementsByTagName('table');
	i=0;
	while(i < tbls.length) {
		if(tbls[i].className == "showHideTitle") {
			var children = tbls[i].childNodes[1].childNodes[0].childNodes;
			var j = 0;
			var childrenTotal = children.length;
			while(j < childrenTotal) {
				if(children[j].nodeName == 'TD') {
					if((!children[j].innerHTML.match(/<input/)) && (!children[j].className.match(/no_show_hide/))) {
						children[j].onclick = function() {
							showHideLink(this.parentNode.parentNode.parentNode.id+"box");
						}
					}
				}
				j++;
			}
			
			}
		i++;
	}
}	

function showHideConvert(val) {
	type = document.getElementsByTagName(val);
	i=0;
	while(i < type.length) {
		if(type[i].className == "showHideTitle") {
			type[i].onclick = function() {
				showHideLink(this.id+"box");
			};
		}
		i++;
	}
}

function showHideLink(val) {
	if(document.getElementById(val).style.display == "") {
		document.getElementById(val).style.display = "none";
	} else {
		document.getElementById(val).style.display = "";
	}
}
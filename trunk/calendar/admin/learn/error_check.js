document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
	if(page == 'add') {
		if(document.getElementById('bParent1').checked==false && document.getElementById('bParent0').checked==false) {
			totalErrors++;
			setErrorMsg('You must select if this article is a Parent or Child.');
		}
	}
	if(errEmpty('chrTitle', "You must enter a Title.")) { totalErrors++; }
	
	if(page == 'add') {
		if(document.getElementById('bParent0').checked==true) {
			if(errEmpty('idParent', "You must select the Parent to this Child.")) { totalErrors++; }
		}
	}
	if(page == 'edit' && document.getElementById('idParent')) {
		if(errEmpty('idParent', "You must select the Parent to this Child.")) { totalErrors++; }
	}
	if(document.getElementById('bShow1').checked==false && document.getElementById('bShow0').checked==false) {
		totalErrors++;
		setErrorMsg('You must select to Show this article or not.');
	}
	return (totalErrors == 0 ? true : false);
}
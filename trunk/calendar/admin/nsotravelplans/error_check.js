document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		if(errEmpty('idUser', "You must choose a User.")) { totalErrors++; }
		if(errEmpty('chrShortTitle', "You must enter a Short Title.")) { totalErrors++; }
		if(errEmpty('dBegin', "You must enter a Begin Date.")) { totalErrors++; }
		if(errEmpty('dEnd', "You must enter an End Date.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}
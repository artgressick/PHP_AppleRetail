document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		//if(errEmpty('idNSOCategory', "You must choose a NSO Category.")) { totalErrors++; }
		if(errEmpty('chrNSOType', "You must enter a NSO/Remodel Type Name.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}
document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		if(errEmpty('chrAttachment', "You must choose a Picture to Upload.")) { totalErrors++; }
		if(errEmpty('idNSOFileGroup', "You must select a Picture Group.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}
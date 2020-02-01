document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		if(errEmpty('chrFileTitle', "You must enter a Document Title.")) { totalErrors++; }
		if(errEmpty('chrNSOFileGroup', "You must choose a File Group.")) { totalErrors++; }
		if(errEmpty('chrAttachment', "You must choose a Document to Upload.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}
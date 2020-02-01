document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
//		if(errEmpty('chrNSOFileType', "You must enter a File Type Name.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}
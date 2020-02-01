document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		if(errEmpty('chrCalendarType', "You must enter a Calendar Type Name.")) { totalErrors++; }
		if(errEmpty('chrColorText', "You must enter a Text Color.")) { totalErrors++; }
		if(errEmpty('chrColorBG', "You must enter a Background Color.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}
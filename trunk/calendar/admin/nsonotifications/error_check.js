document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		
		if(document.getElementById('chrGroup').value=='') {
			if(errEmpty('chrFirst', "You must enter a First Name.")) { totalErrors++; }
			if(errEmpty('chrLast', "You must enter a Last Name.")) { totalErrors++; }
		}
		
		if(errEmpty('chrEmail', "You must enter an Email Address.")) { 
			totalErrors++; 
		} else {
			if(errEmail('chrEmail','','You must enter a Valid Email Address.')) { totalErrors++; }
		}
	return (totalErrors == 0 ? true : false);
}
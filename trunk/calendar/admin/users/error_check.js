document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		if(page=='add') {
			if(document.getElementById('addType1').checked == true) {
				if(errEmpty('idUser', "You must select a User.")) { totalErrors++; }
				if(document.getElementById('idSecurity').value == '' && document.getElementById('idSecurity2').value == '') {
					totalErrors++;
					errCustom('idAccessType','You must select a Security Group.');
				}
			} else if(document.getElementById('addType2').checked == true) {
				if(errEmpty('chrFirst', "You must enter a First Name.")) { totalErrors++; }
				if(errEmpty('chrLast', "You must enter a Last Name.")) { totalErrors++; }
				if(errEmpty('chrEmail', "You must enter an Email Address.")) { 
					totalErrors++; 
				} else {
					if(errEmail('chrEmail','','You must enter a Valid Email Address.')) { totalErrors++; }
				}
				if(errPasswordsEmpty('chrPassword','chrPassword2', "You must enter a Password and Confirm it.")) { totalErrors++; 
				} else {
					if(errPasswordsMatch('chrPassword','chrPassword2', "Passwords Must Match.")) { totalErrors++; }
				}
				if(document.getElementById('idSecurity').value == '' && document.getElementById('idSecurity2').value == '') {
					totalErrors++;
					errCustom('idAccessType','You must select a Security Group.');
				}
			} else {
				errCustom('addType1','Your Must Select to Add an Existing User, or Add a New User.');
				totalErrors++;
			}
		} else {
			if(document.getElementById('idSecurity').value == '' && document.getElementById('idSecurity2').value == '') {
				totalErrors++;
				errCustom('idAccessType','You must select a Security Group.');
			}
		}
	return (totalErrors == 0 ? true : false);
}
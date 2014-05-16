	function ShowHideArea(d, k) {
	  if (d == 1) {
		document.getElementById(k).className = 'show';
	  } else {
		document.getElementById(k).className = 'hide';
	  }
	}
	function TypeSwitch(val) {
	  if ((val == 'text') || (val == 'email')) {
		document.getElementById('TextAdditional').className = 'show';
		document.getElementById('TextAreaAdditional').className = 'hide';
		document.getElementById('SelectOptionsAdditional').className = 'hide';
		document.getElementById('SelectCheckboxesAdditional').className = 'hide';
		document.getElementById('SelectRadioAdditional').className = 'hide';
	  } else if (val == 'textarea') {
		document.getElementById('TextAdditional').className = 'hide';
		document.getElementById('TextAreaAdditional').className = 'show';
		document.getElementById('SelectOptionsAdditional').className = 'hide';
		document.getElementById('SelectCheckboxesAdditional').className = 'hide';
		document.getElementById('SelectRadioAdditional').className = 'hide';
	  } else if (val == 'select') {
		document.getElementById('TextAdditional').className = 'hide';
		document.getElementById('TextAreaAdditional').className = 'hide';
		document.getElementById('SelectOptionsAdditional').className = 'show';
		document.getElementById('SelectCheckboxesAdditional').className = 'hide';
		document.getElementById('SelectRadioAdditional').className = 'hide';
	  } else if (val == 'checkbox') {
		document.getElementById('TextAdditional').className = 'hide';
		document.getElementById('TextAreaAdditional').className = 'hide';
		document.getElementById('SelectOptionsAdditional').className = 'hide';
		document.getElementById('SelectCheckboxesAdditional').className = 'show';
		document.getElementById('SelectRadioAdditional').className = 'hide';
	  } else if (val == 'radio') {
		document.getElementById('TextAdditional').className = 'hide';
		document.getElementById('TextAreaAdditional').className = 'hide';
		document.getElementById('SelectOptionsAdditional').className = 'hide';
		document.getElementById('SelectCheckboxesAdditional').className = 'hide';
		document.getElementById('SelectRadioAdditional').className = 'show';
	  } else {
		document.getElementById('TextAdditional').className = 'hide';
		document.getElementById('TextAreaAdditional').className = 'hide';
		document.getElementById('SelectOptionsAdditional').className = 'hide';
		document.getElementById('SelectCheckboxesAdditional').className = 'hide';
		document.getElementById('SelectRadioAdditional').className = 'hide';
	  }
	}
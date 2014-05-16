$.validator.addMethod("zipcodeUS", function(value, element) {
	return this.optional(element) || /\d{5}-\d{4}$|^\d{5}$/.test(value);
}, "The specified US ZIP Code is invalid");
$.validator.addMethod('phoneUS', function(phone_number, element) {
	phone_number = phone_number.replace(/\s+/g, ''); 
	return this.optional(element) || phone_number.length > 9 &&
	phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
}, 'Please enter a valid phone number.');
$.validator.addMethod("isstate", function(value) {
	var states = [
		"AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA",
		"HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD",
		"MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ",
		"NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC",
		"SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY",
		"AS", "DC", "FM", "GU", "MH", "MP", "PR", "PW", "VI"
	];
	return $.inArray(value.toUpperCase(), states) != -1;
}, "Data provided is not a valid state");
$(function(){
		$("#signUpForm").formwizard({ 
			formPluginEnabled: true,
			validationEnabled: true,
			focusFirstInput : true,
			disableUIStyles : true,
			formOptions :{
				success: function(data){
					$("#status").fadeTo(500,1,function(){ 
						$(this).html("You are now registered!").fadeTo(5000, 0); 
					})
				},
				beforeSubmit: function(data){
					// Debug
					//$("#data").html("data sent to the server: " + $.param(data));
				},
				dataType: 'json',
				resetForm: true,
				type: 'POST',
				url: '/regnow'
			},
			validationOptions : {
				rules: {
					Picture: {
						required: true,
						accept: "jpg,jpeg,gif,png"
					},
					Business_Address: {
						required: true,
						minlength: 10
					},
					City: "required",
					State: {
						required: true,
						isstate:true
					},
					ZIP: {
						required: true,
						zipcodeUS:true
					},
					Phone_Number: {
						required: true,
						phoneUS: true
					},
					NMLS_Number: "required",
					License_Number: "required",
					Facebook: {
						required: false,
						url: true
					},
					LinkedIn: {
						required: false,
						url: true
					},
					Instagram: {
						required: false,
						url: true
					},
					Pinterest: {
						required: false,
						url: true
					},
					YouTube: {
						required: false,
						url: true
					},
					Vine: {
						required: false,
						url: true
					},
					Twitter: {
						required: false,
						url: true
					},
					MySpace: {
						required: false,
						url: true
					},
					regusername: {
						required: true,
						minlength:5,
						remote: { 
							url: "/js/ajaxGateway/validate/validate_username.php",
							type:"post"
						},
					},
					regpassword: {
						required: true,
						/*remote: { 
							url: "/images/captcha/compare-passfields.php",
							type:"post",
							data: {
								retypePassword: function() {
									return $("#retypePassword").val();
								}
							}
						},*/
						equalTo: "#retypePassword"
					},
					retypePassword: {
						required: true,
						equalTo: "#password"
					},
					captcha: {
						required:true,
						minlength:6,
						maxlength:6,
						remote: "/images/captcha/process.php"
					},
					regemail: {
						required: true,
						email: true,
						remote: { 
							url: "/js/ajaxGateway/validate/validate_email.php",
							type:"post"
						},
					}
				},
				messages: {
					Picture: "A Profile Image is required",
					Business_Address: "Please enter your Business Address",
					City: "Please enter your City",
					State: "Please enter your State",
					ZIP: "Please enter a valid U.S. ZIP Code",
					Phone_Number: "Please enter a valid Phone Number",
					NMLS_Number: "Please enter your NMLS Number",
					License_Number: "Please enter your License Number",
					captcha: "Please enter the correct captcha number to continue",
					regusername: "Please enter a valid username. The username you entered may already be taken",
					regpassword: "Please enter your password and make sure it matches the one below",
					retypePassword: "Please re-enter your password and make sure it matches the one above",
					regemail: "Please enter a valid email. The email you entered may already be taken"
				}
			}
		}
	);
	$('#password').keyup(function(){
		$('#pwresult').html(checkStrength($('#regpassword').val()));
	});
	function checkStrength(password){
		//initial strength
		var strength = 0;
		//show the pwresults div
		$('#pwresult').show();
		//if the password length is less than 6, return message.
		if (password.length < 6) {
			$('#pwresult').html('<div class="short">Too Short</div>');
		} else {
			//length is ok, lets continue.
			//if length is 8 characters or more, increase strength value
			if (password.length > 7) { 
				strength += 1;
			}
			//if password contains both lower and uppercase characters, increase strength value
			if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) { 
				strength += 1;
			}
			//if it has numbers and characters, increase strength value
			if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
				strength += 1;
			}
			//if it has one special character, increase strength value
			if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
				strength += 1;
			}
			//if it has two special characters, increase strength value
			if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) {
				strength += 1;
			}
			//now we have calculated strength value, we can return messages
			//if value is less than 2
			if (strength < 1 || strength == 1) {
				$('#pwresult').html('<div class="weak">Weak</div>');
			} else if (strength < 2 ) {
				$('#pwresult').html('<div class="fair">Fair</div>');
			} else if (strength == 2 ) {
				$('#pwresult').html('<div class="good">Good</div>');
			} else {
				$('#pwresult').html('<div class="strong">Strong</div>');
			}
		}
	}
});
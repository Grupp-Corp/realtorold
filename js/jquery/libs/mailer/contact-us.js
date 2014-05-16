$(function() {
	$("button#cancelModalContact").click(function(){
		$("#oopsModal").hide();
		$("#oopsModal").css('visibility', 'none');
		$("#modalLoadBody").hide();
		$("#Name").css('border', '1px solid #CCCCCC');
		$("#nameRequired").css("display", "none");
		$("#Email").css('border', '1px solid #CCCCCC');
		$("#emailRequired").css("display", "none");
		$("#Message").css('border', '1px solid #CCCCCC');
		$("#messageRequired").css("display", "none");
	});
	$("button#submitModalContact").click(function(){
		$("#modalLoadBody").show();
		$error = 0;
		$emailValidation = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
		$("#mailerError").html('');
		if ($("#Name").val() == '') {
			$("#Name").css('border', '1px solid #FF0000');
			$("#nameRequired").css("display", "block");
			$error = 1;
		}  else {
			$("#Name").css('border', '1px solid #000000');
			$("#nameRequired").css("display", "none");
		}
		if ($("#Email").val() == '') {
			$("#Email").css('border', '1px solid #FF0000');
			$("#emailRequired").css("display", "block");
			$error = 1;
		} else {
			if ($emailValidation.test($("#Email").val())) {
				$("#Email").css('border', '1px solid #000000');
				$("#emailRequired").css("display", "none");
			} else {
				$("#Email").css('border', '1px solid #FF0000');
				$("#emailRequired").css("display", "block");
				$error = 1;
			}
		}
		if ($("#Message").val() == '' || $("#Message").val().length < 25) {
			$("#missingChars").html($("#Message").val().length);
			$("#Message").css('border', '1px solid #FF0000');
			$("#messageRequired").css("display", "block");
			$error = 1;
		} else {
			$("#Message").css('border', '1px solid #000000');
			$("#messageRequired").css("display", "none");
		}
		if ($error == 1) {
			$("#oopsModal").show();
			$("#oopsModal").css('visibility', 'visible');
			$("#modalLoadBody").hide();
		} else {
			$("#modalFormBody").hide();
			$("#modalLoadBody").show();
			$.ajax({
				type: "POST",
				url: "/js/ajaxGateway/contact-mailer.php?id=osm489po92@",
				data: $('form.modalContactForm').serialize(),
				success: function(msg){
					if (msg == 'name error') {
						$("#Name").css('border', '1px solid #FF0000');
						$("#nameRequired").css("display", "block");
						$("#modalFormBody").show();
						$("#modalLoadBody").hide();
					} else if (msg == 'email error') {
						$("#Email").css('border', '1px solid #FF0000');
						$("#emailRequired").css("display", "block");
						$("#modalFormBody").show();
						$("#modalLoadBody").hide();
					} else if (msg == 'message error') {
						$("#missingChars").html($("#Message").val().length);
						$("#Message").css('border', '1px solid #FF0000');
						$("#messageRequired").css("display", "block");
						$("#modalFormBody").show();
						$("#modalLoadBody").hide();
					} else if (msg == 'mailer error') {
						$("#oopsModal").show();
						$("#oopsModal").css('visibility', 'visible');
						$("#mailerError").html('There was an error with the form. Please contact me directly at steve.scharf@live.ca');
						$("#modalFormBody").show();
						$("#modalLoadBody").hide();
					} else if (msg == 'success') {
						$("#thanksModal").modal('show');
						$("#contactModal").modal('hide');
						$("#oopsModal").hide();
						$("#oopsModal").css('visibility', 'invisible');
						$("#Name").val('');
						$("#Email").val('');
						$("#Message").val('');
						$("#modalLoadBody").hide();
						$("#modalFormBody").show();
					} else {
						$("#oopsModal").show();
						$("#oopsModal").css('visibility', 'visible');
						$("#mailerError").html('There was an error with the form. Please contact me directly at steve.scharf@live.ca');
						$("#modalFormBody").show();
						$("#modalLoadBody").hide();
					}
				},
				error: function(){
					$("#oopsModal").show();
					$("#oopsModal").css('visibility', 'visible');
					$("#mailerError").html('There was an error with the form. Please contact me directly at steve.scharf@live.ca');
				}
			});
		}
	});
});
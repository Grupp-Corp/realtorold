// Login User with JQuery AJAX
function loginUser() {
	$.ajax({
		url: "/js/ajaxGateway/index.php?cb=AjaxLoginUser",
		type: "POST",
		timeout: 30000,
		data: {
			username: $('input[name=username]').val(),
			password: $('input[name=password]').val(),
			submit: 'Login'
		},
		beforeSend: function() {
			$('#FormWrapper').empty();
			$('#FormWrapper').append('<div class="loggedin"><img src="/images/loadingrm.gif" width="14" height="14" alt="Loading" title="Loading" />&nbsp;&nbsp;Authorizing...</div>');
		},
		complete: function(e, xhr, settings){
			if (e.status === 200) {
				var data = $.parseJSON(e.responseText);
				setTimeout(function() {
					$('#FormWrapper').empty();
					$('#FormWrapper').append('<div class="loggedin">[&nbsp;<a href="' + site_absolute_url + 'profile">Welcome ' + data.username + '</a>&nbsp;]&nbsp;&nbsp;&nbsp;[&nbsp;<a href="' + admin_access_folder + '">Administration</a>&nbsp;]&nbsp;&nbsp;&nbsp;[<a href="?logout=1">Logout</a>]</div>');
				}, 1000);
			} else {
				setTimeout(function() {
					$('#FormWrapper').empty();
					$('#FormWrapper').append('Connection Timeout');
				}, 1000);
			}
		}
	});
}
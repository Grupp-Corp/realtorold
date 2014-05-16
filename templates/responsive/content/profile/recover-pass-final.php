<?php 
$PageTitle = 'Recover Password';
$hideform = 0;
if (isset($_SESSION[$this->config['session_prefix'] . 'id'])) {
	if (($_SESSION[$this->config['session_prefix'] . 'id'] > '') && (is_numeric($_SESSION[$this->config['session_prefix'] . 'id']))) { // Logged in 
		$User = new UserActions();
		if (isset($_GET['rec_code'])) {
			if (strlen($_GET['rec_code']) == 32) {
				$UserInfo = $User->GetUserFromRecCode($_GET['rec_code']);
				if ($UserInfo === false) {
					$showform = 0;
					echo '<p><strong class="red">Invalid/Expired Password Recovery Code.</strong></p>';
				}
			} else {
				echo '<p><strong class="red">Invalid/Expired Password Recovery Code.</strong></p>';
			}
		} else {
			echo '<p><strong class="red">Invalid/Expired Password Recovery Codef.</strong></p>';
		}
?>
        <h1>Change Password</h1>
        <?php
		$html = '';
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
		    if ((isset($_POST['scriptsoff'])) && ($_POST['scriptsoff'] == 1)) {
			    $html = $User->QA_ChangePassword($_SESSION[$this->config['session_prefix'] . 'id'], $_SESSION[$this->config['session_prefix'] . 'username'], $_POST['newpassword'], $_POST['passwordagain']);
				$hideform = 1;
		    }
		}
		?>
        <div class="alignCenter">
          <?php if ($hideform == 0) { ?>
          	  <p>Enter your new password below.</p>
          <?php } ?>
          <div class="ContentContainer">
            <div class="alignLeft" id="ProfileContent">
              <?php
              echo $html;
              ?>
            </div>
            <?php if ($hideform == 0) { ?>
                <br />
                <h2>Change Password</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" id="ChangePassword" name="ChangePassword" method="post">
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>New Password:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><input type="password" id="newpassword" name="newpassword" value="" required="required" /><span id="NpJSErr"></span></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Re-enter Password:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><input type="password" id="passwordagain" name="passwordagain" value="" required="required" /><span id="PaJSErr"></span></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn">&nbsp;</div>
                  <div class="RightContentColumn"><div class="alignLeft"><input type="hidden" id="scriptsoff" name="scriptsoff" value="1" /><input type="submit" id="ChgPass" name="ChgPass" value="Change Password" /></div></div>
                  <br class="clear" />
                </div>
                </form>
            <?php } ?>
          </div>
        </div>
	<?php } else { ?>
        <h1>Login</h1>
        <p>You must login first to use this form.</p>
    <?php } ?>
<?php } else { ?>
    <h1>Login</h1>
    <p>You must login first to use this form.</p>
<?php } ?>
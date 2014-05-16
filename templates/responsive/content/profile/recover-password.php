<?php
$PageTitle = 'Recover Password';
// Vars
$showform = 1;
$User = new UserActions();
// Getting Data
if (isset($_GET['rec_code'])) {
	if (strlen($_GET['rec_code']) == 32) {
		$UserInfo = $User->GetUserFromRecCode($_GET['rec_code']);
		if ($UserInfo === false) {
			$showform = 0;
			echo '<p><strong class="red">Invalid/Expired Password Recovery Code.</strong></p>';
		}
	} else {
		$showform = 0;
		echo '<p><strong class="red">Invalid/Expired Password Recovery Code.</strong></p>';
	}
} else {
	$showform = 0;
	echo '<p><strong class="red">Invalid/Expired Password Recovery Code.</strong></p>';
}
// Posting
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ((isset($_POST['questionform'])) && ($_POST['questionform'] == 1)) {
		$AnswrMatch = $User->QuestionAnswerMatch($_POST['rec_uname'], $_POST['rec_answer']);
		if ($AnswrMatch === true) {
			$showform = 0;
			$_SESSION[$this->config['session_prefix'] . 'id'] = $UserInfo['id'];
			$_SESSION[$this->config['session_prefix'] . 'username'] = $UserInfo['username'];
			include($this->config['templates_path'] . '/profile/recover-pass-final.php');
		} else {
			echo '<p><strong class="red">' . $AnswrMatch . '</strong></p>';
		}
	} else {
		$showform = 0;
		include($this->config['templates_path'] . '/profile/recover-pass-final.php');
	}
}
$stopform = 0;
// Deciding if we should load form
if ($showform == 1) {
	if (isset($UserInfo['username']) && $UserInfo['username'] > '') {
		$username = $UserInfo['username'];
	} else {
		$username = '';
		$stopform = 1;
	}
	if ($stopform != 1) {
	?>
        <h1>Recover Password</h1>
        <div class="alignCenter">
          <p>Recover your password below.</p>
          <div class="ContentContainer">
            <br />
            <h2>Password Recovery for <?php echo $username; ?>
            </h2>
            <form action="#top" id="RecRecoverPassword" name="RecRecoverPassword" method="post">
            <input type="hidden" id="rec_uname" name="rec_uname" value="<?php echo $username; ?>" />
            <input type="hidden" id="questionform" name="questionform" value="1" />
            <div class="FirstColor">
              <div class="LeftContentColumn"><strong>Your Question:</strong></div>
              <div class="RightContentColumn"><div class="alignLeft"><strong><?php echo $UserInfo['secret_question']; ?></strong></div></div>
              <br class="clear" />
            </div>
            <div class="SecondColor">
              <div class="LeftContentColumn"><strong>Your Answer:</strong></div>
              <div class="RightContentColumn"><div class="alignLeft"><input type="password" id="rec_answer" name="rec_answer" value="" required="required" /></div></div>
              <br class="clear" />
            </div>
            <div class="SecondColor">
              <div class="LeftContentColumn">&nbsp;</div>
              <div class="RightContentColumn"><div class="alignLeft"><input type="submit" id="rec_ChgPass" name="rec_ChgPass" value="Access My Account" /></div></div>
              <br class="clear" />
            </div>
            </form>
        <?php } else { ?>
        	<span class="red">There was a problem with your request.</span>
        <?php } ?>
      </div>
    </div>
<?php } ?>
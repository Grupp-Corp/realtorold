        <div class="alignCenter" id="ProfileContent">
          <div class="ContentContainer">
            <?php
			$GetProfile = new UserProfile();
			$UserInfo = $GetProfile->GetProfile($_SESSION[$this->config['session_prefix'] . 'id']);
			$User = new UserActions();
            if (($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['scriptsoff'] == 1)) {
                $html = $User->ChangePassword($_SESSION[$this->config['session_prefix'] . 'id'], $_SESSION[$this->config['session_prefix'] . 'username'], $_POST['oldpassword'], $_POST['newpassword'], $_POST['passwordagain']);
                echo $html;
            }
            ?>
          </div>
          <h2>Change Password</h2>
          <div class="alignCenter">
            [<a href="/profile">View Profile</a>]&nbsp;&nbsp;|
            &nbsp;&nbsp;[<a href="/profile/edit">Edit Profile</a>]&nbsp;&nbsp;
            <?php if ($UserInfo['active'] == 0) { ?>
                |&nbsp;&nbsp;[<a href="/profile/enable">Enable Account</a>]
            <?php } else { ?>
                |&nbsp;&nbsp;[<a href="/profile/disable">Disable Account</a>]
            <?php } ?>
          </div>
          <br />
          <form action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" id="ChangePassword" name="ChangePassword" method="post">
            <div class="SecondColor">
              <div class="LeftContentColumn"><strong>Old Password:</strong></div>
              <div class="RightContentColumn"><div class="alignLeft"><input type="password" id="oldpassword" name="oldpassword" value="" required="required" /><span id="OpJSErr"></span></div></div>
              <br class="clear" />
            </div>
            <div class="FirstColor">
              <div class="LeftContentColumn"><strong>New Password:</strong></div>
              <div class="RightContentColumn"><div class="alignLeft"><input type="password" id="newpassword" name="newpassword" value="" required="required" /><span id="NpJSErr"></span></div></div>
              <br class="clear" />
            </div>
            <div class="SecondColor">
              <div class="LeftContentColumn"><strong>Re-enter Password:</strong></div>
              <div class="RightContentColumn"><div class="alignLeft"><input type="password" id="passwordagain" name="passwordagain" value="" required="required" /><span id="PaJSErr"></span></div></div>
              <br class="clear" />
            </div>
            <div class="FirstColor">
              <div class="LeftContentColumn">&nbsp;</div>
              <div class="RightContentColumn"><div class="alignLeft"><input type="hidden" id="scriptsoff" name="scriptsoff" value="1" /><input type="submit" id="ChgPass" name="ChgPass" value="Change Password" /></div></div>
              <br class="clear" />
            </div>
          </form>
        </div>
        
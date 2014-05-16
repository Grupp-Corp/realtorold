<?php
// Content
if ((isset($_SESSION[$this->config['session_prefix'] . 'passok'])) && ($_SESSION[$this->config['session_prefix'] . 'passok'] == 1)) {
	//if ((isset($_SESSION[$this->config['session_prefix'] . 'adminok'])) && ($_SESSION[$this->config['session_prefix'] . 'adminok'] == 1)) {
        $CheckPerms = new PermsPub();
        // Get permissions
        $GetCPPerms = $CheckPerms->GetUserPerms($_SESSION[$this->config['session_prefix'] . 'id']);
        // Loop through permissions
        foreach($GetCPPerms as $Array) {
            foreach ($Array as $key=>$val) {
                extract(array($key=>$val));
            }
        }
        echo '<h1>Control Panel</h1>';
        echo '<p>';
        echo '<ul>';
          if ($admin_access == 1) {
              echo '<li><a href="/admin/user/index.php" title="Registered Users"><span class="underline">Registered Users</span></a></li>';
          }
          if (($add_to_group == 1) or ($remove_from_group == 1)) {
              echo '<li><a href="/admin/perms/index.php" title="Permissions/Groups"><span class="underline">Permissions/Groups</span></a></li>';
          }
          if ($video_admin == 1 or $add_edit_blog == 1 or $delete_blog == 1 or $form_generator == 1) {
             // echo '<li><strong>Plug-in\'s</strong>';
              //echo '<ul>';
          }
          if (($add_edit_blog == 1) or ($delete_blog == 1)) {
              //echo '<li><a href="/admin/plugins/blog/index.php" title="Blog"><span class="underline">Blog</span></a></li>';
          }
          if ($video_admin == 1) {
              //echo '<li><a href="/admin/plugins/video/index.php" title="Video Administration"><span class="underline">Video Administration</span></a></li>';
          }
          if ($form_generator == 1) {
              //echo '<li><a href="/admin/plugins/ccpfg/index.php" title="Form Generator"><span class="underline">Form Generator</span></a></li>';
          }
          if ($video_admin == 1 or $add_edit_blog == 1 or $delete_blog == 1 or $form_generator == 1) {
              //echo '</ul>';
              //echo '</li>';
          }
        echo '</ul>';
        echo '</p>';
	/*} else {
        $User = new UserActions();
        $CheckPerms = new PermsPub();
        // Get permissions
        $GetCPPerms = $CheckPerms->GetUserPerms($_SESSION[$this->config['session_prefix'] . 'id']);
        // Set Permissions
        $admin_access = 0;
        // Loop through permissions
        foreach($GetCPPerms as $PermRow) {
            if (isset($PermRow['admin_access'])) {
                if ($PermRow['admin_access'] == 1) {
                    $admin_access = 1;
                }
            }
        }
        // Check if post is made
        if (($_SERVER['REQUEST_METHOD'] == "POST") && (isset($_POST['pwd']))) {
            if ($User->PasswordMatch($_SESSION[$this->config['session_prefix'] . 'username'], $_POST['pwd']) === true) {
                $CheckPerms = new PermsPub();
                $GetUserPerms = $CheckPerms->GetUserPerms($_SESSION[$this->config['session_prefix'] . 'id']);
                $admin_login = false;
                $_SESSION[$this->config['session_prefix'] . 'adminok'] = 0;
                foreach($GetUserPerms as $PermRow) {
                    if (isset($PermRow['admin_access']) && $PermRow['admin_access'] == 1) {
                        $admin_login = true;
                        $_SESSION[$this->config['session_prefix'] . 'adminok'] = 1;
						//ob_flush();
                        //header('Location: /admin/index.php');
						//ob_clean();
                    }
                }
            }
        }
        if ($admin_access == 0) {
            echo '<h1>Access Denied</h1>';
            echo '<p>You are not authorized to access this area.</p>';
        } else { 
            echo '<h1>Authenticate Login</h1>' . PHP_EOL;
            echo '<p>Please authenticate your login by supplying your password below.</p>' . PHP_EOL;
            echo '<form action="" method="post" id="AuthenticateAdmin" name="AuthenticateAdmin" autocomplete="off">' . PHP_EOL;
            echo '<label title="Password">Password:</label> <input type="password" id="pwd" name="pwd" maxlength="150" placeholder="Enter your password" autofocus="autofocus" size="25" value="" />' . PHP_EOL;
            echo '<input type="submit" id="submit" name="submit" value="Authenticate" />' . PHP_EOL;
            echo '</form>' . PHP_EOL;
        }
	} */
} else {
	echo '<h1>Access Denied</h1>';
    echo '<p>Please <a href="/profile/index.php?login=1">login</a> before trying to access this area.</p>';
}
?>
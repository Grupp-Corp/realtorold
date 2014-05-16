		<?php
		// Getting variables/objects
		$GetProfile = new UserProfile();
		$User = $GetProfile->GetProfile($_SESSION[$this->config['session_prefix'] . 'id']);
		// Process Enable/Disable account
		if ((isset($_GET['info'])) && ($_GET['info'] == 'enabled')) {
			echo '<div class="alignCenter"><strong class="red">Account Enabled.</strong></div>';
		} elseif ((isset($_GET['info'])) && ($_GET['info'] == 'disabled')) {
			echo '<div class="alignCenter"><strong class="red">Account Disabled.</strong></div>';
		}
		// Process Edit Profile Submission
		if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['info']) && $_REQUEST['info'] == 'edit') {
			if ($GetProfile->EditUserProfile() !== "true") { // edit profile error
				echo '<div class="alignCenter" id="ProfileContent"><div class="ContentContainer"><div id="status" class="alignLeft font_size_16 bold">Edit Profile Failed, see errors below:</div>';
				echo '<div class="alignLeft red">' . $GetProfile->EditUserProfile() . '</div></div></div>';
			} else { // profile edited
				ob_flush();
				header('Location: /profile/edit?success=1');
				exit();
			}
		}
		// Edit Profile
		if (isset($_REQUEST['info']) && $_REQUEST['info'] == 'edit') {
		?>
			<div class="alignCenter" id="ProfileContent">
              <div class="ContentContainer">
                <h2>Edit Your Information</h2>
                <div class="alignCenter">
                  [<a href="/profile">View Profile</a>]&nbsp;&nbsp;|
                  &nbsp;&nbsp;[<a href="/changepass">Change Password</a>]&nbsp;&nbsp;
                  <?php if ($User['active'] == 0) { ?>
                      |&nbsp;&nbsp;[<a href="/profile/enable">Enable Account</a>]
                  <?php } else { ?>
                      |&nbsp;&nbsp;[<a href="/profile/disable">Disable Account</a>]
                  <?php } ?>
                </div>
                <br />
                <?php
				if (isset($_REQUEST['success']) && $_REQUEST['success'] == 1) {
					echo '<div class="alignCenter" id="ProfileContent"><div class="ContentContainer"><div class="green">Profile Updated</div></div></div><br />';
				}
				?>
                  <div class="SecondColor">
                      <h2>Edit Your Information</h2>
                      <div class="progress progress-striped active">
                          <div class="bar"></div>
                      </div>
                  </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Username:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['username']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Group:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['title']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Active:</strong></div>
                  <div class="RightContentColumn">
                    <div class="alignLeft">
                      <?php 
                      if ($User['active'] == 1) {
                          echo 'Active';
                      } else {
                          echo 'Inactive';
                      }
                      ?>
                    </div>
                  </div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Last Login:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['lastlogin']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Last Activity:</strong></div>
                  <div class="RightContentColumn"><div cla ss="alignLeft"><?php echo $User['lastactivity']; ?></div></div>
                  <br class="clear" />
                </div>
                
                <form action="/profile/edit" method="POST" id="editProfile" name="editProfile">
                  <input type="hidden" id="insertID" name="insertID" value="<?php echo $_SESSION[$this->config['session_prefix'] . 'id']; ?>" />
                    <div class="SecondColor">
                      <div class="LeftContentColumn"><strong>Business Address:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="Business_Address_One" name="Business_Address_One" value="<?php if (isset($_REQUEST['Business_Address_One']) && $_REQUEST['Business_Address_One'] > '') { echo $_REQUEST['Business_Address_One']; } else { echo $User['business_address']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="FirstColor">
                      <div class="LeftContentColumn"><strong>Business Address 2:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="Business_Address_Two" name="Business_Address_Two" value="<?php  if (isset($_REQUEST['Business_Address_Two']) && $_REQUEST['Business_Address_Two'] > '') { echo $_REQUEST['Business_Address_Two']; } else { echo $User['business_address_two']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="SecondColor">
                      <div class="LeftContentColumn"><strong>City:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="City" name="City" value="<?php  if (isset($_REQUEST['City']) && $_REQUEST['City'] > '') { echo $_REQUEST['City']; } else { echo $User['business_city']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="FirstColor">
                      <div class="LeftContentColumn"><strong>State:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="State" name="State" value="<?php  if (isset($_REQUEST['State']) && $_REQUEST['State'] > '') { echo $_REQUEST['State']; } else { echo $User['business_state']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="SecondColor">
                      <div class="LeftContentColumn"><strong>ZIP:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="ZIP" name="ZIP" value="<?php  if (isset($_REQUEST['ZIP']) && $_REQUEST['ZIP'] > '') { echo $_REQUEST['ZIP']; } else { echo $User['business_zip']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="FirstColor">
                      <div class="LeftContentColumn"><strong>Phone Number:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="Phone_Number" name="Phone_Number" value="<?php  if (isset($_REQUEST['Phone_Number']) && $_REQUEST['Phone_Number'] > '') { echo $_REQUEST['Phone_Number']; } else { echo $User['business_phone_number']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="SecondColor">
                      <div class="LeftContentColumn"><strong>NMLS Number:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="NMLS_Number" name="NMLS_Number" value="<?php  if (isset($_REQUEST['LinkedIn']) && $_REQUEST['LinkedIn'] > '') { echo $_REQUEST['LinkedIn']; } else { echo $User['business_nmls']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="FirstColor">
                      <div class="LeftContentColumn"><strong>License Number:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="License_Number" name="License_Number" value="<?php  if (isset($_REQUEST['License_Number']) && $_REQUEST['License_Number'] > '') { echo $_REQUEST['License_Number']; } else { echo $User['business_license']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    
                    <div class="SecondColor">
                      <div class="LeftContentColumn"><strong>Facebook:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="Facebook" name="Facebook" value="<?php  if (isset($_REQUEST['Facebook']) && $_REQUEST['Facebook'] > '') { echo $_REQUEST['Facebook']; } else { echo $User['facebook_link']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="FirstColor">
                      <div class="LeftContentColumn"><strong>LinkedIn:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="LinkedIn" name="LinkedIn" value="<?php if (isset($_REQUEST['LinkedIn']) && $_REQUEST['LinkedIn'] > '') { echo $_REQUEST['LinkedIn']; } else { echo $User['linkedin_link']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="SecondColor">
                      <div class="LeftContentColumn"><strong>Instagram:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="Instagram" name="Instagram" value="<?php  if (isset($_REQUEST['Instagram']) && $_REQUEST['Instagram'] > '') { echo $_REQUEST['Instagram']; } else { echo $User['instagram_link']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="FirstColor">
                      <div class="LeftContentColumn"><strong>Pinterest:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="Pinterest" name="Pinterest" value="<?php  if (isset($_REQUEST['Pinterest']) && $_REQUEST['Pinterest'] > '') { echo $_REQUEST['Pinterest']; } else { echo $User['pinterest_link']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="SecondColor">
                      <div class="LeftContentColumn"><strong>Youtube:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="YouTube" name="YouTube" value="<?php  if (isset($_REQUEST['YouTube']) && $_REQUEST['YouTube'] > '') { echo $_REQUEST['YouTube']; } else { echo $User['youtube_link']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="FirstColor">
                      <div class="LeftContentColumn"><strong>Vine:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="Vine" name="Vine" value="<?php  if (isset($_REQUEST['Vine']) && $_REQUEST['Vine'] > '') { echo $_REQUEST['Vine']; } else { echo $User['vine_link']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="SecondColor">
                      <div class="LeftContentColumn"><strong>Twitter:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="Twitter" name="Twitter" value="<?php  if (isset($_REQUEST['Twitter']) && $_REQUEST['Twitter'] > '') { echo $_REQUEST['Twitter']; } else { echo $User['twitter_link']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                    <div class="FirstColor">
                      <div class="LeftContentColumn"><strong>MySpace:</strong></div>
                      <div class="RightContentColumn"><div class="alignLeft"><input class="input_field_12em_small" type="text" id="MySpace" name="MySpace" value="<?php  if (isset($_REQUEST['MySpace']) && $_REQUEST['MySpace'] > '') { echo $_REQUEST['MySpace']; } else { echo $User['myspace_link']; } ?>" /></div></div>
                      <br class="clear" />
                    </div>
                  </div>
                  <input type="submit" id="submitEditProfile" name="submitEditProfile" value="Edit Profile" />
              </form>
		<?php
		} else { // View profile
		?>
            <div class="alignCenter" id="ProfileContent">
              <div class="ContentContainer">
                <h2>User Information</h2>
                <div class="alignCenter">
                  [<a href="/profile/edit">Edit Profile</a>]&nbsp;&nbsp;|
                  &nbsp;&nbsp;[<a href="/changepass">Change Password</a>]&nbsp;&nbsp;
                  <?php if ($User['active'] == 0) { ?>
                      |&nbsp;&nbsp;[<a href="/profile/enable">Enable Account</a>]
                  <?php } else { ?>
                      |&nbsp;&nbsp;[<a href="/profile/disable">Disable Account</a>]
                  <?php } ?>
                </div>
                <br />
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Username:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['username']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Group:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['title']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Active:</strong></div>
                  <div class="RightContentColumn">
                    <div class="alignLeft">
                      <?php 
                      if ($User['active'] == 1) {
                          echo 'Active';
                      } else {
                          echo 'Inactive';
                      }
                      ?>
                    </div>
                  </div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Last Login:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['lastlogin']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Last Activity:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['lastactivity']; ?></div></div>
                  <br class="clear" />
                </div>
                
                
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Business Address:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['business_address']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Business Address 2:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['business_address_two']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>City:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['business_city']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>State:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['business_state']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>ZIP:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['business_zip']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Phone Number:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['business_phone_number']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>NMLS Number:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['business_nmls']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>License Number:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['business_license']; ?></div></div>
                  <br class="clear" />
                </div>
                
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Facebook:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><a href="<?php echo $User['facebook_link']; ?>" target="_blank"><?php echo $User['facebook_link']; ?></a></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>LinkedIn:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><a href="<?php echo $User['linkedin_link']; ?>" target="_blank"><?php echo $User['linkedin_link']; ?></a></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Instagram:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><a href="<?php echo $User['instagram_link']; ?>" target="_blank"><?php echo $User['instagram_link']; ?></a></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Pinterest:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><a href="<?php echo $User['pinterest_link']; ?>" target="_blank"><?php echo $User['pinterest_link']; ?></a></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Youtube:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><a href="<?php echo $User['youtube_link']; ?>" target="_blank"><?php echo $User['youtube_link']; ?></a></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>Vine:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><a href="<?php echo $User['vine_link']; ?>" target="_blank"><?php echo $User['vine_link']; ?></a></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><strong>Twitter:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><a href="<?php echo $User['twitter_link']; ?>" target="_blank"><?php echo $User['twitter_link']; ?></a></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><strong>MySpace:</strong></div>
                  <div class="RightContentColumn"><div class="alignLeft"><a href="<?php echo $User['myspace_link']; ?>" target="_blank"><?php echo $User['myspace_link']; ?></a></div></div>
                  <br class="clear" />
                </div>			
              </div>
            </div>
        <?php } ?>
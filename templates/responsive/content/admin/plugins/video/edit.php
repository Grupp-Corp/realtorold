<?php
// Get permissions
$GetCPPerms = $CheckPerms->GetUserPerms($_SESSION[$this->config['session_prefix'] . 'id']);
// Loop through permissions
foreach($GetCPPerms as $Array) {
	foreach ($Array as $key=>$val) {
		extract(array($key=>$val));
	}
}
if (((isset($_SESSION[$this->config['session_prefix'] . 'siteid'])) && ($_SESSION[$this->config['session_prefix'] . 'siteid'] > '') && ($_SESSION[$this->config['session_prefix'] . 'siteid'] > 0))) {
	// Get user's site ID
	$site_id = $_SESSION[$this->config['session_prefix'] . 'siteid'];
	$UserSites = $Videos->GetUserSites($_SESSION[$this->config['session_prefix'] . 'id']);
} else {
	$video_admin = -1;
}
// Check if user has permission to this section
if ($video_admin == 1) {
	// Checking Variables and setting Defaults
	$_accepted_extensions_ = 'f4v,flv,mp4,mpg,mpeg,avi,swf,mp3';
	$max_size = 52428800;
	// Is an ID present?
	if (isset($_GET['id'])) {
		if (is_numeric($_GET['id'])) {
			$id = $_GET['id'];
		} else {
			$id = 0;
		}
	} else {
		$id = 0;
	}
	// Getting Video Data Array with Pagination
	$VideoData = $Videos->GetVideos($id);
	if ((is_array($VideoData['RowArray'])) && (isset($VideoData['RowArray'][0]))) {
		$Video = $VideoData['RowArray'][0];
	} else {
		$Video = NULL;
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Checking which form was posted...
		if ((isset($_POST['id'])) && (is_numeric($_POST['id']))) {
			// Set some variables
			$id = $_POST['id'];
			$vid_title = $_POST['vid_title'];
			$vid_desc = $_POST['vid_desc'];
			$vid_download_title = $_POST['vid_download_title'];
			$vid_download_desc = $_POST['vid_download_desc'];
			$vidHours = $_POST['vidHours'];
			$vidMinutes = $_POST['vidMinutes'];
			$vidSeconds = $_POST['vidSeconds'];
			$active = $_POST['active'];
			$is_featured = $_POST['is_featured'];
			$show_in_list = $_POST['show_in_list'];
			$error = 0;
			$ErrorReturn = 0;
			// Validate/Set time
			$timeOk = 0;
			$vid_time = '';
			if ((isset($vidHours)) && ($vidHours != 00)) {
				$vid_time .= $vidHours . ':';
				$timeOk = 1;
			} else {
				$vid_time .= '';
			}
			if ((isset($vidMinutes)) && ($vidMinutes != 00)) {
				$vid_time .= $vidMinutes . ':';
				$timeOk = 1;
			} else {
				$vid_time .= '';
			}
			if ((isset($vidSeconds)) && ($vidSeconds != 00)) {
				$vid_time .= $vidSeconds;
				$timeOk = 1;
			} else {
				$vid_time .= '';
			}
			if ($timeOk == 0) {
				$timeError = 'Your must enter a time.';
				$error = 1;
			}
			// Check active entry
			if (!isset($active)) {
				$error = 1;
			} else if (!is_numeric($active)) {
				$error = 1;
			}
			// Video Title
			if ((!isset($vid_title)) || ($vid_title == '')) {
				$titleError = 'You must enter a title.';
				$error = 1;
			}
			if ((isset($_POST['site_id'])) && (is_numeric($_POST['site_id']))) {
				$site_id = $_POST['site_id'];
			} else {
				$siteError = 'You must select a website.';
				$site_id = 0;
				$error = 1;
			}
			$siteURL = $Videos->GetSiteNameFromID($site_id);
			$destination_folder = '/assets/site/' . $siteURL . '/videos/';
			$download_destination = '/assets/site/' . $siteURL . '/downloads/';
			// Check for errors
			if ($error == 0) {
				// Upload with file validation
				$UploadQuery = $Videos->EditVideo($id, $site_id, $vid_title, $vid_desc, $vid_time, $active, $_FILES['vid_file'], $_FILES['vid_file']['name'], $_accepted_extensions_, $max_size, $destination_folder, $_FILES['vid_download'], $_FILES['vid_download']['name'], $vid_download_title, $vid_download_desc, $download_destination, $show_in_list, $is_featured);
				if ($UploadQuery['query'] === true) {
					header('Location: index.php?act=edit&id=' . $id . '&update=1');
				} else {
					$message = '<strong>Video Update Failed.</strong><br />' . $UploadQuery['ErrorMessage'];
					if ($UploadQuery['UploadMessage'] != 1) {
						$message .= '<br />' . $UploadQuery['UploadMessage'];
					}
					$ErrorReturn = '<p class="red alignCenter">' . $message . '</p><br />';
					echo $ErrorReturn;
				}	
			} else {
				$ErrorReturn = '<p class="red alignCenter"><strong>There was an error with your submission.</strong><br />';
				if (isset($titleError)) {
					$ErrorReturn .= $titleError . '<br />';
				}
				if (isset($siteError)) {
					$ErrorReturn .= $siteError . '<br />';
				}
				if (isset($timeError)) {
					$ErrorReturn .= $timeError . '<br />';
				}
				$ErrorReturn .= '</p><br />';
				echo $ErrorReturn;
			}
		}
	}
	if ((isset($_GET['update'])) && ($_GET['update'] == 1)) {
		echo '<p class="red alignCenter"><strong>Video Entry Updated.</strong></p><br />';
	}
	if (($id > 0) && ($VideoData['TotalRows'] > 0)) {
	?>
        <div class="alignCenter"><a href="index.php" title="Video List">Video List</a></div>
        <br />
        <div class="alignCenter"><strong>Last Updated:</strong>&nbsp;<?php echo date('l F jS Y @ h:iA', strtotime($Video['date_modified'])); ?></div>
        <br />
        <div class="alignCenter">
          <div class="ContentContainer">
            <form action="#top" method="POST" id="EditVideoForm" name="EditVideoForm" enctype="multipart/form-data">
                <h2>Video Information</h2>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Video Title:</strong></div></div>
                  <div class="RightContentColumn"><div class="alignLeft">&nbsp;&nbsp;<input type="text" id="vid_title" name="vid_title" maxlength="255" value="<?php echo $Video['vid_title']; ?>" required="required" /> <strong class="red">*</strong></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Video Description:</strong></div></div>
                  <div class="RightContentColumn"><div class="alignLeft">&nbsp;&nbsp;<textarea id="vid_desc" name="vid_desc" cols="35" rows="5"><?php echo $Video['vid_desc']; ?></textarea></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Video File:</strong></div></div>
                  <div class="RightContentColumn"><div class="alignLeft">&nbsp;&nbsp;<input type="file" name="vid_file" id="vid_file" />&nbsp;<strong class="red">*</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;<strong>File:&nbsp;</strong><?php echo $Video['vid_file']; ?></div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Video Time:</strong></div></div>
                  <div class="RightContentColumn"><div class="alignLeft">&nbsp;&nbsp;
                  <?php
				  $timeParts = explode(':', $Video['vid_time']);
				  $k = 1;
				  $maxParts = count($timeParts);
				  foreach (array_reverse($timeParts) as $timePart) {
					  if ($k == 1) {
						  $formSeconds = $timePart;
					  } else if ($k == 2) {
						  $formMinutes = $timePart;
					  } else if ($k == 3) {
						  $formHours = $timePart;
					  }
					  $k++;
				  }
				  ?>
                  <select name="vidHours" id="vidHours" required="required">
                    <?php
					$maxHours = 60;
					for ($i=0;$i<$maxHours;$i++) {
						$addZero = '';
						$selected = '';
						if ($i < 10) {
							$addZero = 0;
						}
						$TheVal = $addZero. '' . $i;
						if ($formHours == $TheVal) {
							$selected = ' selected="selected"';
						}
						echo '<option value="' . $TheVal . '"' . $selected . '>' . $TheVal . '</option>';
					}
					?>
                  </select>&nbsp;&nbsp;:&nbsp;&nbsp;
                  <select name="vidMinutes" id="vidMinutes" required="required">
                    <?php
					$maxMinutes = 60;
					for ($i=0;$i<$maxMinutes;$i++) {
						$addZero = '';
						$selected = '';
						if ($i < 10) {
							$addZero = 0;
						}
						$TheVal = $addZero. '' . $i;
						if ($formMinutes == $TheVal) {
							$selected = ' selected="selected"';
						}
						echo '<option value="' . $TheVal . '"' . $selected . '>' . $TheVal . '</option>';
					}
					?>
                  </select>
                  &nbsp;&nbsp;:&nbsp;&nbsp;
                  <select name="vidSeconds" id="vidSeconds" required="required">
                    <?php
					$maxSeconds = 60;
					for ($i=0;$i<$maxSeconds;$i++) {
						$addZero = '';
						$selected = '';
						if ($i < 10) {
							$addZero = 0;
						}
						$TheVal = $addZero. '' . $i;
						if ($formSeconds == $TheVal) {
							$selected = ' selected="selected"';
						}
						echo '<option value="' . $TheVal . '"' . $selected . '>' . $TheVal . '</option>';
					}
					?>
                  </select>
                  &nbsp;&nbsp;<strong class="red">*</strong>&nbsp;&nbsp;<strong>Format:&nbsp;</strong>(hh:mm:ss)</div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Active:</strong></div></div>
                  <div class="RightContentColumn">
                    <div class="alignLeft">&nbsp;&nbsp;
                      <select name="active" id="active" required="required">
                        <?php
                        $selectedOne = '';
                        $selectedNone = '';
                        if ($Video['active'] == 1) {
                            $selectedOne = ' selected="selected"';
                        } else {
                            $selectedNone = ' selected="selected"';
                        }
                        ?>
                        <option value="1"<?php echo $selectedOne; ?>>Yes</option>
                        <option value="0"<?php echo $selectedNone; ?>>No</option>
                      </select> <strong class="red">*</strong>&nbsp;&nbsp;
                      <strong>Featured:</strong>
                      <select name="is_featured" id="is_featured" required="required">
                        <?php
                        $selectedOne = '';
                        $selectedNone = '';
                        if ($Video['is_featured'] == 1) {
                            $selectedOne = ' selected="selected"';
                        } else {
                            $selectedNone = ' selected="selected"';
                        }
                        ?>
                        <option value="1"<?php echo $selectedOne; ?>>Yes</option>
                        <option value="0"<?php echo $selectedNone; ?>>No</option>
                      </select> <strong class="red">*</strong>
                    </div>
                  </div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Link Published:</strong></div></div>
                  <div class="RightContentColumn">
                    <div class="alignLeft">
                        &nbsp;&nbsp;
                        <select name="show_in_list" id="show_in_list" required="required">
                          <?php
                          $selectedOne = '';
                          $selectedNone = '';
                          if ($Video['show_in_list'] == 1) {
                              $selectedOne = ' selected="selected"';
                          } else {
                              $selectedNone = ' selected="selected"';
                          }
                          ?>
                          <option value="1"<?php echo $selectedOne; ?>>Yes</option>
                          <option value="0"<?php echo $selectedNone; ?>>No</option>
                        </select> <strong class="red">*</strong>
                        &nbsp;&nbsp;
                        <strong>Site:</strong>
                        <?php
                        if ($UserSites['TotalRows'] > 0) {
                            $SitePullDown = '<select name="site_id" id="site_id">';
                            if ($_SESSION[$this->config['session_prefix'] . 'groupid'] == 1) {
                                $MainOptionSelected = '';
                                if ($Video['id'] == 0) {
                                    $MainOptionSelected = ' selected="selected';
                                }
                                $SitePullDown .= '<option value="0"' . $MainOptionSelected . '>main</option>';
                            }
                            foreach ($UserSites['RowArray'] as $RowCols) {
                                $OptionSelected = '';
                                if ($RowCols['id'] == $Video['site_id']) {
                                    $OptionSelected = ' selected="selected"';
                                }
                                $SitePullDown .= '<option value="' . $RowCols['id'] . '"' . $OptionSelected . '>' . $RowCols['url'] . '</option>';
                            }
                            $SitePullDown .= '</select>';
                            echo $SitePullDown;
                        }
                        ?> <strong class="red">*</strong>
                    </div>
              	  </div>
              	  <br class="clear" />
            	</div>
                <h2>Video Download Information</h2>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Video Download Title:</strong></div></div>
                  <div class="RightContentColumn"><div class="alignLeft">&nbsp;&nbsp;<input type="text" id="vid_download_title" name="vid_download_title" maxlength="255" value="<?php echo $Video['vid_download_title']; ?>" /> </div></div>
                  <br class="clear" />
                </div>
                 <div class="SecondColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Video Download Description:</strong></div></div>
                  <div class="RightContentColumn"><div class="alignLeft">&nbsp;&nbsp;<textarea id="vid_download_desc" rows="5" cols="35" name="vid_download_desc"><?php echo $Video['vid_download_desc']; ?></textarea> </div></div>
                  <br class="clear" />
                </div>
                <div class="FirstColor">
                  <div class="LeftContentColumn"><div class="alignRight"><strong>Download File:</strong></div></div>
                  <div class="RightContentColumn"><div class="alignLeft">&nbsp;&nbsp;<input type="file" name="vid_download" id="vid_download" /><br />&nbsp;&nbsp;&nbsp;&nbsp;<strong>File:&nbsp;</strong><?php if ($Video['vid_download'] > '') { echo $Video['vid_download']; } else { echo 'None'; } ?></div></div>
                  <br class="clear" />
                </div>
                <div class="SecondColor">
                  <div class="LeftContentColumn">&nbsp;</div>
                  <div class="RightContentColumn">
                    <div class="alignLeft">
                      <input type="hidden" id="id" name="id" value="<?php echo $Video['id']; ?>" />
                      <input type="submit" id="SubmitVideoEdit" name="SubmitVideoEdit" value="Edit Video" />
                    </div>
                  </div>
                  <br class="clear" />
                </div>
            </form>
          </div>
        </div>
<?php
	} else {
		echo 'Invalid ID';	
	}
} else {
	echo 'Access Denied.';
}
?>
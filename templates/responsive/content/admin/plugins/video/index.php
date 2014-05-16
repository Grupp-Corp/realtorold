<?php
$CheckPerms = new PermsPub();
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
} else {
	$video_admin = -1;
}
// Check if user has permission to this section
if ($video_admin == 1) {
	$Videos = new CCVideoAdmin();
	if (isset($_GET['act']) && $_GET['act'] == "edit") {
		include('edit.php');
	} else if (isset($_GET['act']) && $_GET['act'] == "delete") {
		include('delete.php');
	} else if (isset($_GET['act']) && $_GET['act'] == "add") {
		include('add.php');
	} else {
		// MainConfig
		$limit 				= 10; // Record Return Limit
		$ajax 				= 0;
		$page 				= 0;
		$id 				= 0;
		$sort 				= '';
		$sortLink 			= '';
		$col 				= '';
		$ajaxFunctionName 	= 'LoadVideoPage';
		// Column whitelist
		$goodCols 			= array('title', 'time', 'user', 'published', 'actions');
		$TableHeader		= 'Video List';
		$MainAdminLink		= '<a href="../index.php" title="Administration Index">Administration Index</a>';
		$AddVidLink			= '<p><a href="?act=add" title="Add Video">Add Video</a></p>';
		$colTitle 			= 'Title';
		$colTime 			= 'Time';
		$colUser 			= 'User';
		$colPublished 		= 'Active';
		$colActions 		= 'Actions';
		$pageLink 			= '';
		$sort 				= '';
		$col 				= '';
		$sortLink 			= 'DESC';
		$QueryProblem 		= '<p class="red alignCenter"><strong>There was a problem with your query. Please try again.</strong></p><br />';
		$VidAddedSuccess	= '<p class="red alignCenter"><strong>Video Added.</strong></p><br />';
		$VidDeletedSuccess	= '<p class="red alignCenter"><strong>Video Deleted.</strong></p><br />';
		// Column Links
		$TitleHeader 		= '<a href="?col=' . $goodCols[0] . '&amp;sort=DESC' . $pageLink . '">' . $colTitle . '</a>';
		$TimeHeader 		= '<a href="?col=' . $goodCols[1] . '&amp;sort=DESC' . $pageLink . '">' . $colTime . '</a>';
		$UserHeader 		= '<a href="?col=' . $goodCols[2] . '&amp;sort=DESC' . $pageLink . '">' . $colUser . '</a>';
		$PublishedHeader 	= '<a href="?col=' . $goodCols[3] . '&amp;sort=DESC' . $pageLink . '">' . $colPublished . '</a>';
		$ActionsHeader 		= '' . $colActions . '';
		// Checking Variables
		// Is an ID present?
		if (isset($_GET['id'])) {
			if (is_numeric($_GET['id'])) {
				$id = $_GET['id'];
			}
		}
		// Check to see if we have a page variable
		if (isset($_GET['page'])) {
			if (is_numeric($_GET['page'])) {
				$page = $_GET['page'];
				$pageLink = '&page=' . $page;
			}
		}
		// Sort Variable
		if (isset($_GET['sort'])) {
			if ($_GET['sort'] == 'ASC') {
				$sort = 'ASC';
				$sortLink = 'DESC';
			} else if ($_GET['sort'] == 'DESC') {
				$sort = 'DESC';
				$sortLink = 'ASC';
			}
		}
		// Column Variable
		if ((isset($_GET['col'])) && (in_array($_GET['col'], $goodCols))) {
			$col = $_GET['col'];
			switch ($col) {
				case 'title':
					$TitleHeader = '<a href="?col=title&amp;sort=' . $sortLink . '' . $pageLink . '">' . $colTitle . '</a>';
					break;
				case 'time':
					$TimeHeader = '<a href="?col=time&amp;sort=' . $sortLink . '' . $pageLink . '">' . $colTime . '</a>';
					break;
				case 'user':
					$UserHeader = '<a href="?col=user&amp;sort=' . $sortLink . '' . $pageLink . '">' . $colUser . '</a>';
					break;
				case 'published':
					$PublishedHeader = '<a href="?col=published&amp;sort=' . $sortLink . '' . $pageLink . '">' . $colPublished . '</a>';
					break;
			}
		}
		// Check for POST
		if ((($_SERVER['REQUEST_METHOD'] == "POST") && (isset($_POST['vidDelete'])) && ($_POST['vidDelete'] == 1))) {
			$DeleteVidReturn = $Videos->DeleteVideo($id);
			if ($DeleteVidReturn['query'] === true) {
				header('Location: index.php?deleted=1');
			} else {
				echo $QueryProblem;
			}
		}
		// Get video class
		$VideoList = $Videos->GetVideos($id, $site_id, $page, $limit, $ajax, $ajaxFunctionName, $sort, $col);
		// Paginator String
		if (!isset($_GET['act'])) {
			$PaginatorLink = '<div class="alignRight">Pages:&nbsp;';
			$PaginatorLink .= $VideoList['paginator'];
			$PaginatorLink .= '</div>';
		} else {
			$PaginatorLink = '';
		}
		//////////////////
		// Main Content //
		//////////////////
		echo '<div class="alignCenter">' . $MainAdminLink . '</div>';
		echo '<div id="ContentAjax">';
		echo $PaginatorLink;
		echo '<br /><br />
		<div class="alignCenter">
		<div class="MainContainer">';
		if (!isset($_GET['act'])) {
			if ((isset($_GET['added'])) && ($_GET['added'] == 1)) {
				echo $VidAddedSuccess;
			} else if ((isset($_GET['deleted'])) && ($_GET['deleted'] == 1)) {
				echo $VidDeletedSuccess;
			}
			echo $AddVidLink;
			echo '<h2>' . $TableHeader . '</h2>';
			?>
			<table id="adminTable">
			  <tr>
				<th><?php echo $TitleHeader; ?></th>
				<th><?php echo $UserHeader; ?></th>
				<th><?php echo $TimeHeader; ?></th>
				<th><?php echo $PublishedHeader; ?></th>
				<th><?php echo $ActionsHeader; ?></th>
			  </tr>
			  <tr>
				<?php
				// Loop Through Video List
				$i = 0;
				foreach ($VideoList['RowArray'] as $Video) {
					$VidModifiedTIme = $Video['date_modified'];
					$TwoWeeks = strtotime($VidModifiedTIme . ' +7 days');
					$today = strtotime("now");
					if ($TwoWeeks > $today) {
						$NewVideo = '&nbsp;&nbsp;<sup class="NewLabel">New</sup>';
					} else {
						$NewVideo = '';
					}
					if ($i == 1) {
						echo '<tr class="FirstColor">'; // Alternating row colour
					} else {
						echo '<tr class="SecondColor">'; // Alternating row colour
					}
					// Title/Description
					echo '<td id="colLeft">'; // Left Column
					echo '<a href="?act=edit&id=' . $Video['id'] . '">' . $Video['vid_title'] . '</a>' . $NewVideo . '';
					echo '<br />';
					if ($Video['vid_desc'] > '') {
						echo '<strong>Description:&nbsp;</strong>' . $Video['vid_desc'] . '</strong>';
					}
					echo '</td>';
					// User Info
					$UserInfo = $Videos->GetUserInfo($Video['user_id']);
					echo '<td id="colCenter1">'; // Center Column 
					echo '' . $UserInfo['username'] . '';
					echo '</td>';
					// Time
					echo '<td id="colCenter">'; // Center Column 
					echo '' . $Video['vid_time'] . '';
					echo '</td>';
					// Active/Published
					echo '<td id="colCenter2">'; // Center Column
					if ($Video['active'] == 1) {
						echo 'Yes';
					} else {
						echo'No';
					}
					echo '</td>';
					// Right Column
					echo '<td id="colRight">';
					echo '<a href="#" title="Watch Video">Watch</a>';
					echo ' | <a href="?act=edit&id=' . $Video['id'] . '">Edit</a>';
					echo ' | <a href="?act=delete&id=' . $Video['id'] . '">Delete</a>';
					echo '</td>'; // Right COlumn
					// Change alternating row
					if ($i == 1) { // Check iteration for row colour
						$i = 0;
					} else {
						$i = 1;
					}
					echo '</tr>';// Close row colour
				}
				?>
			  </tr>
			</table>
		<?php
			echo '</div>
			</div>
			<br /><br />';
			echo $PaginatorLink;
			echo '</div>';
		//////////////////
		// Delete Video //
		//////////////////
		} else {
			echo '<h2>Delete Video</h2>';
			echo '<div class="SecondColor">'; // Alternating row colour
			echo '<p>Are you sure you want to delete the video &quot;<strong>' . $VideoList['RowArray'][0]['vid_title'] . '</strong>&quot;?</p>';
			echo '<form action="#top" id="DeleteVid" name="DeleteVid" method="POST">';
			echo '<input type="hidden" id="id" name="id" value="' . $VideoList['RowArray'][0]['id'] . '" />';
			echo '<input type="hidden" id="vidDelete" name="vidDelete" value="1" />';
			echo '<input type="submit" name="DeleteVideoButton" id="DeleteVideoButton" value="Confirm" />';
			echo '</form>';
			echo '</div>';
		}
	}
} else if ($video_admin == -1) {
	echo 'You must be part of a web site to post videos.';
} else {
	echo 'Access Denied.';
}
?>
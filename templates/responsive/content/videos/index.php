<?php $PageTitle = 'My Videos'; ?>
<?php
//////////////////
// Main Content //
//////////////////
$Videos = new CCVideo();
// MainConfig
$limit 				= 10; // Record Return Limit
$ajax 				= 0;
$page 				= 0;
$id 				= 0;
$sort 				= '';
$sortLink 			= '';
$col 				= '';
$ajaxFunctionName 	= 'LoadVideoPage';
$videoURL 			= '/videos';
$downloadLink		= '<p><a href="/downloads" title="Downloads"><span class="underline">Download these songs</span></a>.</p>';
// Column whitelist
$goodCols 			= array('title', 'time', 'user', 'published');
$TableHeader		= 'Video List';
$colTitle 			= 'Title';
$colTime 			= 'Time';
$colUser 			= 'User';
$colPublished 		= 'Published';
$pageLink 			= '';
$sort 				= '';
$col 				= '';
$sortLink 			= 'DESC';
$pageLink 			= $page + 1;
// Check to see if we have a page variable
if (isset($_GET['page'])) {
	if (is_numeric($_GET['page'])) {
		$page = $_GET['page'];
		$pageLink = $page;
	}
}
// Column Links
$TitleHeader 		= '<a href="' . $videoURL  . '/' . $pageLink . '/' . $goodCols[0] . '/DESC">' . $colTitle . '</a>';
$TimeHeader 		= '<a href="' . $videoURL  . '/' . $pageLink . '/' . $goodCols[1] . '/DESC">' . $colTime . '</a>';
$UserHeader 		= '<a href="' . $videoURL  . '/' . $pageLink . '/' . $goodCols[2] . '/DESC">' . $colUser . '</a>';
$PublishedHeader 	= '<a href="' . $videoURL  . '/' . $pageLink . '/' . $goodCols[3] . '/DESC">' . $colPublished . '</a>';
// Checking Variables
// Is an ID present?
if ((isset($_GET['song'])) && ($_GET['song'] > '')) {
	$song = $_GET['song'];
} else {
	$song = '';
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
			$TitleHeader = '<a href="' . $videoURL  . '/' . $pageLink . '/title/' . $sortLink . '">' . $colTitle . '</a>';
			break;
		case 'time':
			$TimeHeader = '<a href="' . $videoURL  . '/' . $pageLink . '/time/' . $sortLink . '">' . $colTime . '</a>';
			break;
		case 'user':
			$UserHeader = '<a href="' . $videoURL  . '/' . $pageLink . '/user/' . $sortLink . '">' . $colUser . '</a>';
			break;
		case 'published':
			$PublishedHeader = '<a href="' . $videoURL  . '/' . $pageLink . '/published/' . $sortLink . '">' . $colPublished . '</a>';
			break;
	}
}
// Get video class
$VideoList = $Videos->GetVideos($song, 'canuckcoder', $page, $limit, $ajax, $ajaxFunctionName, $sort, $col, $videoURL);
if ($VideoList['TotalRows'] > 0) {
	// Get song
	if (!is_array($VideoList['Row'])) {
		$VideoName = $VideoList['RowArray'][0]['vid_title'];
		$VideoTime = $VideoList['RowArray'][0]['vid_time'];
		$VideoDesc = $VideoList['RowArray'][0]['vid_desc'];
	} else {
		$VideoName = $VideoList['Row']['vid_title'];
		$VideoTime = $VideoList['Row']['vid_time'];
		$VideoDesc = $VideoList['Row']['vid_desc'];
		$title = 'My Videos - ' . $VideoName;
	}
	// Paginator String
	if (($VideoList['paginator'] > '') && ($VideoList['paginator'] != '1')) {
		$PaginatorLink = '<div class="VidPages">';
		$PaginatorLink .= $VideoList['paginator'] . '</div>';
	} else {
		$PaginatorLink = '';
	}
}
echo '<h1>' . $title . '</h1>' . PHP_EOL;
// Check Rows
if ($VideoList['TotalRows'] > 0) {
	// Headers Checks
	if ((isset($song)) && ($song > '')) {
		// Video List Header
		$TableHeader = '<h2 id="header">' . $TableHeader . '</h2>' . PHP_EOL;
	} else {
		// Video List Headers
		echo '<h2>' . $VideoName . '</h2><br />' . PHP_EOL;
		$TableHeader = '<h3 id="header">' . $TableHeader . '</h3>' . PHP_EOL;
	}
	// Loop Through Video List
	echo '<div id="VideoList">' . PHP_EOL;
	echo $TableHeader . PHP_EOL;
	echo '<table id="VideoListTable">' . PHP_EOL;
	echo '<th>' . $TitleHeader . '</th>' . PHP_EOL;
	echo '<th class="centeringCol">' . $TimeHeader . '</th>' . PHP_EOL;
	foreach ($VideoList['RowArray'] as $key=>$Video) {
		if ($Video['active'] == 1) {
			$VidModifiedTIme = $Video['date_modified'];
			$TwoWeeks = strtotime($VidModifiedTIme . ' +7 days');
			$today = strtotime("now");
			if ($TwoWeeks > $today) {
				$NewVideo = '&nbsp;&nbsp;<sup id="NewLabel">New</sup>' . PHP_EOL;
			} else {
				$NewVideo = '';
			}
			// Column Backgrounds
			$bgCol = 'bg1';
			if ($key%2 == 1) {
				$bgCol = 'bg2';
			}
			echo '<tr class="' . $bgCol . '">' . PHP_EOL;
			echo '<td class="leftCol"><a href="/videos/' . $Video['vid_title'] . '" title="' . $Video['vid_title'] . '"><span class="underline">' . $Video['vid_title'] . '</span></a>' . $NewVideo . '</td>' . PHP_EOL;
			echo '<td class="rightCol">(' . $Video['vid_time'] . ')</td>' . PHP_EOL;
			echo '</tr>';
		}
	}
	echo '</table>' . PHP_EOL;
	echo $PaginatorLink . PHP_EOL;
	echo '</div>' . PHP_EOL;	
	?>
	<div id="player"></div>
	<script>
	hdwebplayer({ 
		id       : 'player',
		swf      : '/flash/player.swf',
		width    : '450',
		height   : '253',
		video    : "/assets/site/<?php echo 'canuckcoder'; ?>/videos/<?php echo $VideoName ?>.f4v", 
		autoStart: 'false'
	});
	</script>
	<?php
	// Figure out the time layout
	$VideoTime = array_reverse(explode(':', $VideoTime));
	$TimeParts = '';
	$vidHours = '';
	$vidMinutes = '';
	$vidSeconds = '';
	for ($i=1;$i<=count($VideoTime);$i++) {
		if ($i == 1) {
			$vidSeconds = $VideoTime[$i-1] . 's ';
		} else if ($i == 2) {
			$vidMinutes = $VideoTime[$i-1] . 'm ';
		} else {
			$vidHours = $VideoTime[$i-1] . 'h ';
		}
	}
	$TimeParts .= $vidHours . ' ' . $vidMinutes . ' ' . $vidSeconds;
	echo '<p><strong>Time:</strong>&nbsp;' . $TimeParts . '<br />' . PHP_EOL;
	if ((isset($VideoDesc)) && ($VideoDesc > '')) {
		echo '<strong>Description:&nbsp;</strong>' . $VideoDesc . '</p>' . PHP_EOL;
	}
	echo '<br class="clear" /><br />';
	echo $downloadLink;
	?>
	<br /><br />
<?php
} else {
	echo 'No Videos are available right now.' . PHP_EOL;
}
?>
<?php 
$PageTitle = 'The Blog of Steven Scharf';
$description = 'The Blog of Steven Scharf is the first blog written by me (Steven Scharf) and is mostly relating to the CMS I am building and the plugin\'s it will have available.';
// Possible redirect from pull-downs
if (($_SERVER['REQUEST_METHOD'] == "POST") && (isset($_POST['category']))) {
	if (strlen($_POST['category']) > 4) {
		header('Location: /blog/category/' . $_POST['category'] . '');
	}
}
// getting blog and social objects
$Blog = new Blog();
$GetLinkedIn = new Socials();
// Var Checks
// id Checks
if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	$id = 0;
}
// Page checks
if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}
// Category checks
if (isset($_GET['category']) && $_GET['category'] > '') {
	$category = $_GET['category'];
} else {
	$category = '';
}
if (isset($id)) {
	$id = $id;
} else {
	$id = 0;
}
if (isset($page)) {
	$page = $page;
} else {
	$page = 1;
}
$BlogContentIndexLimit = 250;
$RecordLimiter = 5; // Limit Blogs on index...
// Get category if it exists
if ((isset($category)) && ($category > '')) {
	$CatTextPull = $Blog->GetBlogCategories(0, 'TitlePull', $category);
	$title = 'Blog Category: ' . $CatTextPull['name'];
	$description = strip_tags($CatTextPull['description']);
	$description = str_replace(PHP_EOL, ' ', $description);
	$description = str_replace("&#39;","'",$description);
	$description = trim($description);
	$description = $Blog->StringCheck->StringLimiter($description, 125);
}

// Get data from blog id
if ($data = $Blog->ShowBlog($id, $title, $page, $category, $RecordLimiter, 0)) {
	if (isset($data['rowset']) && $data['rowset'] !== false) { // Show All
		$row = $data['rowset'];	
		$title = $data['title'];
		$description = strip_tags($row['content']);
		$description = str_replace(PHP_EOL, ' ', $description);
		$description = str_replace("&#39;","'",$description);
		$description = trim($description);
		$description = $Blog->StringCheck->StringLimiter($description, 125);
		$description = $title . ': ' . $description;
	}
}
// Title
echo '<h1>' . $title . '</h1>';
$html = '';
// Check rowset
if (isset($data['rowset']) && $data['rowset'] === false) { // Show All
	// Check rows
	if ($data['allRows'] > 0) {
		// Start Categories / Pagination
		$html .= '<div class="MainContainer">';
		$html .= '<div class="LCol">';
		if ($data['Paginator'] > '') {
			$html .= '<strong>Pages:</strong>&nbsp;' . $data['Paginator'] . '<br />';
		}
		$html .= '</div>';
		$html .= '<div class="RCol nopad">';
		// Show Blog Category Options
		$html .= $Blog->GetBlogCategories(0, 'FormSelects', $category);
		$html .= '</div>';
		$html .= '</div>';
		// End Categories / Pagination
		// Loop
		foreach ($data['repeatRows'] as $row) {
			if ($category > '') {
				if (isset($row['bid'])) {
					$BlogEntryID = $row['bid'];
				} else {
					$BlogEntryID = 0;
				}
			} else {
				$BlogEntryID = $row['id'];
			}
			$html .= '<h2 class="Blog"><a href="/blog/' . $BlogEntryID . '/' . $Blog->friendlyUrl($row['title']) . '" title="' . $row['title'] . '">' . $row['title'] . '</a></h2>';
			// Begin Blog Container
			$html .= '<div class="BlogEntry">';
			// Begin Main Container
			$html .= '<div class="MainContainer">';
			// Begin Left Column
			$html .= '<div class="LCol smalltext">';
			$html .= '<strong>Author:</strong> <a href="' . $this->config['site_absolute_url'] . 'profile/?uid=2" title="' . $row['author'] . '"><span class="underline">' . $row['author'] . '</span></a><br />';
			$html .= '<span class="date"><strong>Posted:</strong> ' . $row['time_stamp'] . '</span>';
			$html .= '</div>';
			// End Left Column
			// Begin Right Column
			$html .= '<div class="RCol smalltext">';
			$html .= '<span class="date">' . $Blog->GetBlogCategories($row['id']) . '</span>';
			$html .= '<span class="date"><strong>Comments:</strong> ' . $Blog->CountBlogComments($row['id']) . '</span>';
			$html .= '</div>';
			// End Right Column
			$html .= '</div>';
			// End Left Column
			$html .= '<p>' . $Blog->StringCheck->StringLimiter($row['content'], $BlogContentIndexLimit) . '</p>';
			$html .= '</div>';
			// End Blog Container
			// Check for user
			$html .= '<div class="ReadMore"><a href="/blog/' . $BlogEntryID . '/' . $Blog->friendlyUrl($row['title']) . '" class="ReadMore" title="Read More">Read More</a></div>';
			$html .= '<br />';
		}
		// Pagination
		if ($data['Paginator'] > '') {
			$html .= '<div class="alignRight">Pages:&nbsp;';
			$html .= $data['Paginator'];
			$html .= '</div>';
		}
	} else {
		$html .= '<p>There are no blog entries, check back later.</p>';
	}
} else { // Show Specific ID
	if ($data['allRows'] > 0) {
		$row = $data['rowset'];			
		$html .= '<h2 class="Blog">' . $row['title'] . '</h2>';
		$html .= '<div class="BlogEntry">';
		$html .= '<div class="MainContainer">';
		$html .= '<div class="LCol">';
		$html .= '<strong class="indent1">Author:</strong> <a href="' . $this->config['site_absolute_url'] . 'profile/?uid=2" title="' . $row['author'] . '">' . $row['author'] . '</a><br />';
		$html .= '</div>';
		$html .= '<div class="RCol">';
		$html .= '<span>';
		$html .= $Blog->GetBlogCategories($id); // Show categories
		$html .= '</span>';
		$html .= '<span><strong>Comments:</strong> ' . $Blog->CountBlogComments($row['id']) . '</span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="indent1 BlogContent">' . $row['content'] . '</div>';
		$html .= '<br />';
		$html .= '<span class="indent1"><strong>Posted:</strong> ' . $row['time_stamp'] . '</span>';
		$html .= '<p class="indent1"><strong>Keywords:</strong> ' . $row['keywords'] . '</p>';
		$html .= '</div>';
		// Submit comment
		if (($_SERVER['REQUEST_METHOD'] == "POST") && (isset($_POST['commentarea']))) {
			$comment = $_POST['commentarea'];
			if ($comment > "") {
				if ($Blog->SubmitBlogComment($id, $_SESSION[$this->config['session_prefix'] . 'username'], $_SESSION[$this->config['session_prefix'] . 'id'], $comment) === true) {
					$html .= '<p id="comment"><strong class="red">Comment added.</strong></p>';
				}
			} else {
				$html .= '<p id="comment"><strong class="red">No comment entered, please try again.</strong></p>';
			}
		}
		// Is javascript on or off?
		$html .= $Blog->ShowBlogComments($id, 0);
		$html .= '<div class="ReadMore"><a href="/blog/" class="GoBack" title="Go Back" rel="nofollow">Go Back</a></div>';
		$html .= '<br /><br />';
	} else {
		$html .= '<p>No entries exist.</p>';
	}
}
echo $html;
?>
<?php
class RSSDisplay
{
	//Setting up a Global Array accessible to all methods...
	protected $SelectYear =  2012;
	protected $IgnoreYear =  0;
	protected $domain_sandbox = "localhost";
	protected $domain_live = "canuckcoder.com";
	protected $url = '/rss/news.xml';
	protected $size = 5;
	protected $sortby = "sortorder";
	protected $RSS_Content = array();
	protected $YearsCollected = array();
	// Constructor
	public function __construct($RSSUrl, &$MaxItemAmount = 5, &$sortby = "pubDate", &$IgnoreYear = 0, &$Year = 2012, &$domain_sandbox = "localhost", &$domain_live = "canuckcoder.com") {
		// Class Variables settings
		if (isset($RSSUrl)) {
			$this->url = $RSSUrl;
		}
		// Optional Class Variables settings
		if (isset($IgnoreYear)) {
			$this->IgnoreYear = $IgnoreYear;
		} else {
			$this->IgnoreYear = 0;
		}
		if ($this->IgnoreYear == 0) {
			if (isset($Year)) {
				$this->SelectYear = $Year;
			}
		} else {
			if (isset($Year)) {
				$this->SelectYear = date("Y");
			}
		}
		if (isset($domain_sandbox)) {
			$this->domain_sandbox = $domain_sandbox;
		}
		if (isset($domain_live)) {
			$this->domain_live = $domain_live;
		}
		
		if (isset($MaxItemAmount)) {
			$this->size = $MaxItemAmount;
		}
		if (isset($sortby)) {
			$this->sortby = $sortby;
		}
		// URL Parse
		$this->url = str_replace("http://".$this->domain_sandbox, "", $this->url);
		$this->url = str_replace("http://".$this->domain_live, "", $this->url);
		// Is this a full URL?
		if (!preg_match('#http:#', $this->url)) { // Not a full url
			$this->url = 'http://'.$_SERVER['HTTP_HOST'].$this->url;
		}
	}
	// Trim string method
	public function neat_trim($str, $length, $delim = '...') { 
		$len = strlen($str); 
		if ($len > $length) { 
		   preg_match('/(.{' . $length . '}.*?)\b/', $str, $matches); 
		   return rtrim($matches[1]) . $delim; 
		} else { 
		   return $str; 
		}
	}
	//Finding the tags and putting them into an array to be returned for future functions to call...
	protected function RSS_Tags($item, $type, $category = "none") {
		// Here we decide what tags we want in our array for later parsing...
		$y = array();
		// RSS Tag to Array Builder
		// Date stuff
		// pubDate
		$tnl = $item->getElementsByTagName("pubDate");
		$tnl = $tnl->item(0);
		$y["pubDate"] = $tnl->firstChild->textContent;
		// Check if date exists
		if ((isset($y["pubDate"])) && ($y["pubDate"] > '')) {
			// Split into an array (YYYY-MM-DD)
			$ItemDateSplit = explode(' ', $y["pubDate"]);
			// Check the array
			if ((!empty($ItemDateSplit)) && (is_array($ItemDateSplit))) {
				$ItemYear = $ItemDateSplit[3]; // Year
				$ItemMonth = $ItemDateSplit[2]; // Month
				$ItemDay = $ItemDateSplit[1]; // Day
				$this->YearsCollected[] .= $ItemYear;
			}
		}
		// Category stuff
		// Category
		$tnl = $item->getElementsByTagName("category");
		$tnl = $tnl->item(0);
		if ((isset($tnl->firstChild->textContent)) && ($tnl->firstChild->textContent > '')) {
			$y["category"] = $tnl->firstChild->textContent;
		}
		// Check if this is the year we want
		if (($ItemYear == $this->SelectYear) && ($this->IgnoreYear == 0)) { // Year matches
			$Continue = 1;
		} else if ($this->IgnoreYear == 1) {
			$Continue = 1;
		} else {
			$Continue = 0;
		}
		if ($Continue == 1) {
			// Type
			$y["type"] = $type;
			// Title
			$tnl = $item->getElementsByTagName("title");
			$tnl = $tnl->item(0);
			$y["title"] = $tnl->firstChild->textContent;
			// Link
			$tnl = $item->getElementsByTagName("link");
			$tnl = $tnl->item(0);
			$y["link"] = $tnl->firstChild->textContent;
			// Description
			$tnl = $item->getElementsByTagName("description");
			$tnl = $tnl->item(0);
			$y["description"] = $tnl->firstChild->textContent;
			// Category
			if ($category == "none") {
				return $y;
			} else if ($y["category"] == $category) {
				return $y;
			}
		} else { // Year does not match
			// Returning null
			return NULL;
		}
	}
	// Build array via rss channels...
	protected function RSS_Channel($channel, $category = "none", $ForItemGroup = "item") {
		$items = $channel->getElementsByTagName($ForItemGroup);
		// Processing channel
		$y = $this->RSS_Tags($channel, 0, $category);		// get description of channel, type 0
		array_push($this->RSS_Content, $y);
		// Processing articles
		foreach($items as $item) {
			$y = $this->RSS_Tags($item, 1, $category);	// get description of article, type 1
			array_push($this->RSS_Content, $y);
		}
	}
	// Get RSS URL and start Array building...
	protected function RSS_Retrieve($category = "none", $LookWithin = "channel") {
		$doc = new DOMDocument();
		$doc->load($this->url);
		$channels = $doc->getElementsByTagName($LookWithin);
		foreach($channels as $channel) {
			 $this->RSS_Channel($channel, $category);
		}
	}
	// Keep building the Array and get the links (pulls from the link channel of the RSS)
	protected function RSS_RetrieveLinks($category = "none", $LookWithin = "channel", $ForItemGroup = "item") {
		$doc = new DOMDocument();
		$doc->load($this->url);
		$channels = $doc->getElementsByTagName($LookWithin);
		foreach($channels as $channel) {
			$items = $channel->getElementsByTagName($ForItemGroup);
			foreach($items as $item) {
				$y = $this->RSS_Tags($item, 1, $category);	// get description of article, type 1
				array_push($this->RSS_Content, $y);
			}
		}
	}
	// Sort Array buy SubKey
	protected function sksort(&$array, $subkey = "id", $sort_ascending = false) {
		// Count the Array, Get a Key, and Shift...
		if ((is_array($array)) && (count($array))) {
			$temp_array[key($array)] = array_shift($array);
		}
		//Loop through the array keys and values
		foreach($array as $key => $val) {
			$offset = 0;
			$found = false;
			//Loop through the array keys and values
			foreach($temp_array as $tmp_key => $tmp_val) {
				if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
					// MERGE arrays
					$temp_array = array_merge(
											  (array)array_slice($temp_array, 0, $offset), 
											  array($key => $val), 
											  array_slice($temp_array,$offset)
											 );
					$found = true;
				}
				$offset++;
			}
			if(!$found) {
				$temp_array = array_merge($temp_array, array($key => $val));
			}
		}
		//Sorting
		if ((isset($temp_array)) && (is_array($temp_array))) {
			if ($sort_ascending) {
				$array = array_reverse($temp_array);
			} else {
				$array = $temp_array;
			}
		}
	}
	// Build RSS links...
	protected function RSS_Links($category = "none") {
		$page = "<ul>";
		$this->RSS_RetrieveLinks($category, $this->url);
		if($this->size > 0) {
			$recents = array_slice($this->RSS_Content, 0, $this->size);
		}
		foreach($recents as $article) {
			$type = $article["type"];
			if ($type == 0) continue;
			$title = $article["title"];
			$link = $article["link"];
			$page .= '<li><a href="' . $link . '">' . $title . '</a></li>';			
		}
		$page .="</ul>";
		return $page;
	}
	// Get the RSS Data
	public function RSSArrayReturn($category) {
		//Retrieving our Array
		$recents = $this->RSS_Retrieve($category);
		// Check the size and slice the array up according to size requested
		/*if ($this->size > 0) {
			// We are slicing the array based on the size from the function variable...
			$recents = array_slice($this->RSS_Content, 1, $this->size);
			if ($this->sortby == "sortorder") {
				$sorthelper = false;
			} elseif ($this->sortby == "DateOfNewsItem") {
				$sorthelper = false;
			}
			$this->sksort($recents, $this->sortby, $sorthelper);*/	
		//} elseif ($this->size == 0) { // The size is 0, so we want everything in the XML minus the title information
			// First, lets reverse the array, so we can only slice off the end of an array
			$NewRSS_Content = array_reverse($this->RSS_Content);
			// Slice 1 entry from the array (title information) from the reversed array
			$recents = array_slice($NewRSS_Content, 0, -1);
			// Lets reverse the array again, reverting to its original state minus the slice above.
			$recents = array_reverse($recents);
			$this->sksort($recents, $this->sortby, false);
		//}
		// Returning the Data Array
		return $recents;
	}
	// Build External Link Image
	public function ExternalLinkImageBuild() {
		// External Link image Build (English)
		$HTML = '';
		// Return
		return $HTML;
	}
	// Get RSS Div Layout **(INTRANET MAIN "What's New" TAB)**
	public function RSSDivLayout($grab_category = "none", $show_description = true, $description_length = 0) {
		// Vars
		$page = "";
		$Counter = 0;
		$CounterCat = 0;
		$recents = $this->RSSArrayReturn($grab_category);
		//Determine row class
		$row_num = 0;
		// Loop through item tags
		foreach($recents as $article) {
			// Setting some variables to carry into our output...
			$title = $article["title"];
			$link = $article["link"];
			$description = $article["description"];
			if (isset($article["category"])) {
				$category = $article["category"];
			}
			$pubDate = $article["pubDate"];
			$type = $article["type"];
			$checkstring = strpos($article["link"], $this->domain_sandbox); // Lets do a DEV server check here so LIVE reflects dev...if this returns false we must add an image to the url
			$checkstring_dev = strpos($article["link"], $this->domain_live); // Lets do a LIVE server check here so DEV reflects live...if this returns false we must add an image to the url
			// Check for external links
			// Are the links linking within the site, or going to an outside domain? (Sandbox Check)
			if ((strpos($article["link"], $this->domain_sandbox) === false) && (strpos($article["link"], $this->domain_live) === false)) {
				$TheExternalImageLink = $this->ExternalLinkImageBuild();
			} else {
				$TheExternalImageLink = '';
			}
			// Fix the URL to the Current Domain we are on...
			$link = str_replace("http://".$this->domain_sandbox, "", $link); 
			$link = str_replace("http://".$this->domain_live, "", $link);
			$link = $link;
			$more = "More";
			// What row class are we going to use? (for alternating row colors)
			if ($row_num == 0) {
				$therowclass = 'news_bg_color_first';
				$row_num = 1;
			} else {
				$therowclass = 'news_bg_color_second';
				$row_num = 0;
			}
			// DIV LAYOUT WITH EXTERNAL LINK IMAGE
			$page .= '<div class="' . $therowclass . '">';
			$page .= '<div class="padding_Top3">';
			$page .= '' . $TheExternalImageLink . ' <a href="' . $link . '" class="newslinks" title="' . $title . '">' . $title . '</a>';
			$page .= '<br /><br />' . $pubDate . '<br /><br />';
			// Check for description length and if we require it
			if (($description_length > 0) && ($show_description === true)) {
				$description = $this->neat_trim($description, $description_length);
				$description = preg_replace('/\(([A-Z]{2,})\)/', '(<abbr>$1</abbr>)', $description);
				$page .= '<span class="fontSize105">' . $description . '&nbsp;<a href="' . $link . '" class="more_link" title="' . $title . '">' . $more . '</a></span>';
				$page .= '<br /><br />';
			} else if ($show_description === true) {
				$description = preg_replace('/\(([A-Z]{2,})\)/', '(<abbr>$1</abbr>)', $description);
				$page .= '<span class="fontSize105">' . $description . '&nbsp;<a href="' . $link . '" class="more_link" title="' . $title . '">' . $more . '</a></span>';
				$page .= '<br /><br />';
			}
			$page .= '<div class="padding_Top3"></div>';
			$page .= '</div>';
			$page .= '<div class="seperator"></div>';
			$page .= '</div>';
			$Counter++; // Increment counter
			// Check Counter...for the size to return
			if (($this->size > 0) && ($Counter == $this->size)) {
				break;
			}
		}
		// Return the page layout.
		return $page;
	}
	// Get RSS Image Left Layout
	/*public function RSSImageLayout($grab_category = "none", $description_length = 0) {
		// Vars
		$page = "";
		$Counter = 0;
		$CounterCat = 0;
		$recents = $this->RSSArrayReturn($grab_category);
		//Determine row class
		$row_num = 0;
		// Loop through item tags
		foreach($recents as $article) {
			// Setting some variables to carry into our output...
			$title = $article["title"];
			$link = $article["link"];
			$description = $article["description"];
			$category = $article["category"];
			$pubDate = $article["pubDate"];
			$type = $article["type"];
			$image = $article["image"];
			if (isset($article["sortorder"])) {
				$sortorder = $article["sortorder"];
			}
			$checkstring = strpos($article["link"], $this->domain_dev); // Lets do a DEV server check here so LIVE reflects dev...if this returns false we must add an image to the url
			$checkstring_dev = strpos($article["link"], $this->domain_live); // Lets do a LIVE server check here so DEV reflects live...if this returns false we must add an image to the url
			// Check for external links
			// Are the links linking within the site, or going to an outside domain? (Sandbox Check)
			if ((strpos($article["link"], $this->domain_sandbox) === false) && (strpos($article["link"], $this->domain_live) === false)) {
				$TheExternalImageLink = $this->ExternalLinkImageBuild();
			} else {
				$TheExternalImageLink = '';
			}
			// Fix the URL to the Current Domain we are on...
			$link = str_replace("http://".$this->domain_sandbox, "", $link); 
			$link = str_replace("http://".$this->domain_live, "", $link);
			$link = $link;
			$more = "More";
			// What row class are we going to use? (for alternating row colors)
			if ($row_num == 0) {
				$therowclass = 'news_bg_color_first';
				$row_num = 1;
			} else {
				$therowclass = 'news_bg_color_second';
				$row_num = 0;
			}
			// Northern Region and PACCB News Layout
			$page .= '<div class="' . $therowclass . '">';
			$page .= '<div class="padding_Top3">';
			$page .= '<div class="image-left"><a href="' . $link . '"><img src="' . $image . '" alt="' . $title . '" class="image-center" width="58" height="58" /></a></div>';
			$page .= '<div class="right_news_nr"><p>' . $TheExternalImageLink . '<a href="' . $link . '" class="newslinks" title="' . $title . '">' . $title . '</a><br />';
			$description = $this->neat_trim($description, $description_length);
			$description = preg_replace('/\(([A-Z]{2,})\)/', '(<abbr>$1</abbr>)', $description);
			$page .= $description . '&nbsp;&nbsp;<a href="' . $link . '" class="more_link" title="' . $title . '">' . $more . '</a></p>';
			$page .= '</div>';
			$page .= '</div>';
			$page .= '</div>';
			$page .= '<div class="clearBoth"></div>';
			$Counter++; // Increment counter
			// Check Counter...for the size to return
			if (($this->size > 0) && ($Counter == $this->size)) {
				break;
			}
		}
		// Return the page layout.
		return $page;
	}*/
	// Get RSS Unordered List Layout
	public function RSSListLayout($grab_category = "none", $ul_class = "", $show_description = true, $description_length = 0) {
		// Vars
		$page = "";
		$Counter = 0;
		$CounterCat = 0;
		$recents = $this->RSSArrayReturn($grab_category);
		//Determine row class
		$row_num = 0;
		// Loop through item tags
		foreach($recents as $article) {
			// Setting some variables to carry into our output...
			$title = $article["title"];
			$link = $article["link"];
			$description = $article["description"];
			if (isset($article["category"])) {
				$category = $article["category"];
			}
			$pubDate = $article["pubDate"];
			$type = $article["type"];
			$checkstring = strpos($article["link"], $this->domain_sandbox); // Lets do a DEV server check here so LIVE reflects dev...if this returns false we must add an image to the url
			$checkstring_dev = strpos($article["link"], $this->domain_live); // Lets do a LIVE server check here so DEV reflects live...if this returns false we must add an image to the url
			// Check for external links
			// Are the links linking within the site, or going to an outside domain? (Sandbox Check)
			if ((strpos($article["link"], $this->domain_sandbox) === false) && (strpos($article["link"], $this->domain_live) === false)) {
				$TheExternalImageLink = $this->ExternalLinkImageBuild();
			} else {
				$TheExternalImageLink = '';
			}
			// Fix the URL to the Current Domain we are on...
			$link = str_replace("http://".$this->domain_sandbox, "", $link); 
			$link = str_replace("http://".$this->domain_live, "", $link);
			$link = $link;
			// Language Determination for common text
			$more = "More";
			// Northern Region and PACCB News Layout
			$title = preg_replace('/\(([A-Z]{2,})\)/', '(<abbr>$1</abbr>)', $title);
			$page .= '<li>' . $TheExternalImageLink . '<a href="' . $link . '" target="_blank" class="newslinks">' . $title . '</a>';
			// Description
			if ($show_description === true) {
				$description = $this->neat_trim($description, $description_length);
				$description = preg_replace('/\(([A-Z]{2,})\)/', '(<abbr>$1</abbr>)', $description);
				$page .= '<br />' . $description . '<a href="' . $link . '" class="more_link" title="' . $title . '">' . $more . '</a><br /><br />';
			}
			$page .= '</li>';
			$Counter++; // Increment counter
			// Check Counter...for the size to return
			if (($this->size > 0) && ($Counter == $this->size)) {
				break;
			}
		}
		// Building the final HTML
		if ((isset($ul_class)) && ($ul_class > '')) {
			$PageFinal = '<ul class="' . $ul_class . '">';
		} else {
			$PageFinal = '<ul>';
		}
		$PageFinal .= $page;
		$PageFinal .= '</ul>';
		// Return the page layout.
		return $PageFinal;
	}
	// Get RSS Archival Layout
	public function RSSArchivalLayout($grab_category = "none", $show_description = false, $description_length = 0) {
		// Grab the entire RSS Array
		$recents = $this->RSSArrayReturn($grab_category);
		// Begin month array...
		$month_array = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec");
		// This is the start of the months_exist_array which determines which months exist in our XML so we dont build empty months...
		$months_exist_array = array();
		$YearsAvailable = array();
		// Loop through all the months in a calendar year based off month_array
		if (count($month_array) > 0) {
			foreach($month_array as $value) {
				if (count($recents) > 0) {
					// Need to find out which months are populated and then build the $month_array
					foreach($recents as $article) {
						// Lets get the publish date and category only, to help build the month_array
						$pubDate = $article["pubDate"];
						if (isset($article["category"])) {
							$category = $article["category"];
						} else {
							$category = "none";
						}
						// Find the Month...
						$findmonth = strpos($pubDate, $value);
						// We only want a specific category, lets filter out the ones we dont want here.
						if (($grab_category == $category) && ($grab_category != "none")) { // If we didn't find the month do nothing...except set a variable of not found
							if ($findmonth === false) {
								$not_found = 1;
							} else {
								// We found the month, add it to the array and set not_found to 0
								if (!in_array($value, $months_exist_array)) {
									$months_exist_array[] = $value;
								}
								$not_found = 0;
							}
						// We decided we want all categories, so lets do that here.
						} else if ($grab_category == "none") { // Grabbing all categories...
							// If we didn't find the month do nothing...except set a variable of not found
							if ($findmonth === false) {
								$not_found = 1;
							} else {
								// We found the month, add it to the array and set not_found to 0
								if (!in_array($value, $months_exist_array)) {
									$months_exist_array[] = $value;
								}
								$not_found = 0;
							}
						}				
					}
				}
			}
		}
		// Reverse the month array so show last month first
		$months_exist_array = array_reverse($months_exist_array);
		// Build Month Menu Items
		$JanMenu = '<li><a href="#' . $value . '">January</a></li>' . PHP_EOL;
		$FebMenu = '<li><a href="#' . $value . '">February</a></li>' . PHP_EOL;
		$MarMenu = '<li><a href="#' . $value . '">March</a></li>' . PHP_EOL;
		$AprMenu = '<li><a href="#' . $value . '">April</a></li>' . PHP_EOL;
		$MayMenu = '<li><a href="#' . $value . '">May</a></li>' . PHP_EOL;
		$JunMenu = '<li><a href="#' . $value . '">June</a></li>' . PHP_EOL;
		$JulMenu = '<li><a href="#' . $value . '">July</a></li>' . PHP_EOL;
		$AugMenu = '<li><a href="#' . $value . '">August</a></li>' . PHP_EOL;
		$SeptMenu = '<li><a href="#' . $value . '">September</a></li>'. PHP_EOL;
		$OctMenu = '<li><a href="#' . $value . '">October</a></li>'. PHP_EOL;
		$NovMenu = '<li><a href="#' . $value . '">November</a></li>'. PHP_EOL;
		$DecMenu = '<li><a href="#' . $value . '">December</a></li>'. PHP_EOL;
		// Begin Table Row Entry Build
		$TableRowEntries = '';
		// Get month long names...
		foreach($months_exist_array as $value) {
			switch ($value) {
				case "Jan":
					$month_long = "January";
					break;
				case "Feb":
					$month_long = "February";
					break;
				case "Mar":
					$month_long = "March";
					break;
				case "Apr":
					$month_long = "April";
					break;
				case "May":
					$month_long = "May";
					break;
				case "Jun":
					$month_long = "June";
					break;
				case "Jul":
					$month_long = "July";
					break;
				case "Aug":
					$month_long = "August";
					break;
				case "Sept":
					$month_long = "September";
					break;
				case "Oct":
					$month_long = "October";
					break;
				case "Nov":
					$month_long = "November";
					break;
				case "Dec":
					$month_long = "December";
					break;
			}
			// List Items starter
			$ListItems = '';
			// Get Data from XML and begin to populate the table...
			foreach($recents as $article) {
				// Setting some variables to carry into our output...
				$type = $article["type"];
				$title = $article["title"];
				$link = $article["link"];
				$description = $article["description"];
				if (isset($article["category"])) {
					$category = $article["category"];
				}
				$pubDate = $article["pubDate"];
				$link = str_replace("http://".$this->domain_sandbox, "", $link); 
				$link = str_replace("http://".$this->domain_live, "", $link);
				// Is the current XML item part of the current month?
				$findmonth = strpos($pubDate, $value);
				if ($findmonth === false) {
					$return = 0;	
				} else {
					$return = 1;
				}
				// If this XML item is part of the current month, display it...
				if ($return == 1) {
					// Link checking
					// Are the links linking within the site, or going to an outside domain?
					if ((strpos($article["link"], $this->domain_sandbox) === false) && (strpos($article["link"], $this->domain_live) === false)) {
						$TheExternalImageLink = $this->ExternalLinkImageBuild();
					} else {
						$TheExternalImageLink = '';
					}
					// List Item Build
					if ($TheExternalImageLink > '') {
						$ListItems .= '<li>' . $TheExternalImageLink . '<a href="' . $link . '" title="' . $title . '">' . $title . '</a>'. PHP_EOL;
					} else {
						$ListItems .= '<li><a href="' . $link . '" title="' . $title . '" target="_blank">' . $title . '</a>'. PHP_EOL;
					}
					// Do we want to show the news description? (Based of the function variable)
					if ($show_description == true) {
						$ListItems .= '<br />'. PHP_EOL;
						$description = $this->neat_trim($description, $description_length);
						$description = preg_replace('/\(([A-Z]{2,})\)/', '(<abbr>$1</abbr>)', $description);
						$ListItems .= $description . PHP_EOL;
					}
					$ListItems .= '</li>'. PHP_EOL;
				}
			}
			// Make sure we have list items to show with the header
			if ($ListItems > '') {
				// Begin table rows...
				$TableRowEntries .= '<h2><a name="' . $value . '" title="' . $month_long . '" id="' . $value . '"></a>' . $month_long . '</h2>'. PHP_EOL;
				// Open an unordered list
				$TableRowEntries .= '<ul>'. PHP_EOL;
				$TableRowEntries .= $ListItems;
				// Close the unordered list
				$TableRowEntries .= '</ul>'. PHP_EOL;
				// Check if we should change the month menu link
				switch ($value) {
					case "Jan":
						$JanMenu = '<li><a href="#' . $value . '">January</a></li>' . PHP_EOL;
						break;
					case "Feb":
						$FebMenu = '<li><a href="#' . $value . '">February</a></li>' . PHP_EOL;
						break;
					case "Mar":
						$MarMenu = '<li><a href="#' . $value . '">March</a></li>' . PHP_EOL;
						break;
					case "Apr":
						$AprMenu = '<li><a href="#' . $value . '">April</a></li>' . PHP_EOL;
						break;
					case "May":
						$MayMenu = '<li><a href="#' . $value . '">May</a></li>' . PHP_EOL;
						break;
					case "Jun":
						$JunMenu = '<li><a href="#' . $value . '">June</a></li>' . PHP_EOL;
						break;
					case "Jul":
						$JulMenu = '<li><a href="#' . $value . '">July</a></li>' . PHP_EOL;
						break;
					case "Aug":
						$AugMenu = '<li><a href="#' . $value . '">August</a></li>' . PHP_EOL;
						break;
					case "Sept":
						$SeptMenu = '<li><a href="#' . $value . '">September</a></li>'. PHP_EOL;
						break;
					case "Oct":
						$OctMenu = '<li><a href="#' . $value . '">October</a></li>'. PHP_EOL;
						break;
					case "Nov":
						$NovMenu = '<li><a href="#' . $value . '">November</a></li>'. PHP_EOL;
						break;
					case "Dec":
						$DecMenu = '<li><a href="#' . $value . '">December</a></li>'. PHP_EOL;
						break;
				}
			}
		}
		// Begin the month menu
		$MonthMenu = '<div class="inlineList">'. PHP_EOL;
		$MonthMenu .= '<ul>'. PHP_EOL;
		$MonthMenu .= $JanMenu;
		$MonthMenu .= $FebMenu;
		$MonthMenu .= $MarMenu;
		$MonthMenu .= $AprMenu;
		$MonthMenu .= $MayMenu;
		$MonthMenu .= $JunMenu;
		$MonthMenu .= $JulMenu;
		$MonthMenu .= $AugMenu;
		$MonthMenu .= $SeptMenu;
		$MonthMenu .= $OctMenu;
		$MonthMenu .= $NovMenu;
		$MonthMenu .= $DecMenu;
		$MonthMenu .= '<li class="inlineListArchive"><a href="#archives">Archives</a></li>'. PHP_EOL;
		$MonthMenu .= '</ul>'. PHP_EOL;
		$MonthMenu .= '</div>'. PHP_EOL;
		// Month Collections
		$YearArray = array_unique($this->YearsCollected);
		$YearArray = array_reverse($YearArray);
		// Layout for year array
		$YearMenu = '<br /><h2>Archives</h2>'. PHP_EOL;
		$YearMenu .= '<div class="inlineList">'. PHP_EOL;
		$YearMenu .= '<a id="archives"></a>'. PHP_EOL;
		$YearMenu .= '<ul>'. PHP_EOL;
		foreach ($YearArray as $YearGrabbed) {
			if ((isset($_GET['year'])) && ($_GET['year'] == $YearGrabbed)) {
				$YearMenu .= '<li>' . $YearGrabbed . '</li>'. PHP_EOL;
			} else if ((!isset($_GET['year'])) && ($YearGrabbed == date("Y"))) {
				$YearMenu .= '<li>' . $YearGrabbed . '</li>'. PHP_EOL;
			} else {
				$YearMenu .= '<li><a href="?year=' . $YearGrabbed . '">' . $YearGrabbed . '</a></li>'. PHP_EOL;
			}
		}
		$YearMenu .= '</ul>'. PHP_EOL;
		$YearMenu .= '</div>'. PHP_EOL;
		// Begin Month Menu...
		$page = $MonthMenu;
		$page .= $TableRowEntries;
		$page .= $YearMenu;
		// Show Archive Layout
		if ($TableRowEntries > '') {
			return $page;
		} else {
			return '<br /><div class="alignCenter"><strong class="red">There is no news for this selection.</strong></div>';
		}
	}
}
?>
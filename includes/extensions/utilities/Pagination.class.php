<?php
class Pagination extends CCTemplate
{
	// Vars
	protected $PageIncrements;
	protected $FirstLastPages;
	protected $JustPages;
	public function __construct($PageIncrements = 0, $FirstLastPages = 0, $JustPages = 1) {
		parent::__construct();
		// Building default vars
		$this->PageIncrements = $PageIncrements;
		$this->FirstLastPages = $FirstLastPages;
		$this->JustPages = $JustPages;
		// DB Connection Settings
		$this->GetDB = $this->db_conn;
	}
	// Pagination Builder
	public function PagesBuilder($query_string, $limit, $page, $ajax = 1, $ajaxFuncName = 'LoadBlogPage', $SEOFriendly = '') {
		// Vars
		$stop = 0;
		// Check querystring
		if ($query_string == '') {
			$stop = 1;
		}
		// Check limit
		if ($limit == '') {
			$stop = 1;
		}
		// Check page
		if ($page == '') {
			$page = 0;
		} else if (!is_numeric($page)) {
			$page = 0;
		}
		// Check if we should stop
		if ($stop != 1) {
			// Pagination helper
			$query = $this->GetDB->query($query_string); // This returns true if successful
			$allRows = $this->GetDB->affected_rows; // Row Checker
			$pages = ceil($allRows / $limit);
			// Vars
			$html = '';
			$start = $page + 1;
			$stopPages = 0;
			// Check where we are
			if ($start > $stopPages) {
				$realStart = $page + 1;
				$realStart = $realStart - 7;
				// Make sure we don't bottom out
				if ($realStart < 1) {
					$realStart = 1;
				}
			}
			$stopPages = $realStart + 14;
			// Loop through our pages
			for ($i = $realStart; $i <= $pages; $i++) {
				// Where we are now variable
				$getPage = $page + 1;
				// Make sure this is not the current page
				if ($getPage != $i) { // Not current page
					// Ajax?
					if ($ajax == 1) { // Ajax version
						$html .= '<span class="underline"><a href="javascript:' . $ajaxFuncName . '(' . $i . ', ' . $limit . ');">';
					} else { // HTML Version
						// Query string handler
						if (isset($_SERVER['QUERY_STRING'])) { // Check for query string
							$qs = explode('&', $_SERVER['QUERY_STRING']); // create array
							// Vars
							$new_qs = '';
							// Loop through querystring
							foreach($qs as $qr) {
								if ($qr > '') { // check to make sure we have a value
									if (preg_match('/page=(.*)/', $qr)) {
										$qr = preg_replace('/page=(.*)/', '', $qr);
										$new_qs .= $qr;
									} else {
										$new_qs .= '&amp;' . $qr;
									}
								}
							}
						}
						if ($SEOFriendly > '') {
							// Create beginning of html string
							$html .= '<span class="underline"><a href="' . $SEOFriendly . '/' . $i . '">';
						} else {
							// Create beginning of html string
							$html .= '<span class="underline"><a href="?page=' . $i . '' . str_replace('&&', '&', $new_qs) . '">';
						}
					}
				}
				// Page name
				$html .=  $i;
				// Make sure this is not the current page
				if ($getPage != $i) { // Not current page
					// Check which page we are on or if we should stop due to the break
					if (($i == $pages) or ($i == $stopPages)) { // Create end tag with no comma
						$html .= '</a></span> ';
					} else { // Create end tag with comma
						$html .= '</a></span>, ';
					}
				} else { // Current page
					// Where we are now check
					if (($i != $pages) && ($i != $stopPages)) { // Create end tag with comma
						$html .= ', ';
					}
				}
				// Is this the final page?
				if ($i == $stopPages) { // Yes
					break;
				}
			}
			if (isset($_SERVER['QUERY_STRING'])) { // Check for query string
				$qs = explode('&', $_SERVER['QUERY_STRING']); // create array
				// Vars
				$new_qs = '';
				// Loop through querystring
				foreach($qs as $qr) {
					if ($qr > '') { // check to make sure we have a value
						if (preg_match('/page=(.*)/', $qr)) {
							$qr = preg_replace('/page=(.*)/', '', $qr);
							$new_qs .= $qr;
						} else {
							$new_qs .= '&amp;' . $qr;
						}
					}
				}
			}
			if ($this->FirstLastPages == 1) {
				if ($pages > 1) {
					$NewHTML = '<div class="alignCenter">';
					$NewHTML .= '';
					if ($this->FirstLastPages == 1) {
						// First page link
						if ($start == 1) {
							$NewHTML .= '[&nbsp;<u>First&nbsp;Page</u>&nbsp;]&nbsp;&nbsp;';
						} else {
							$NewHTML .= '[&nbsp;<a href="' . str_replace('&&', '&', '?page=1' . $new_qs) . '"><span class="underline">First&nbsp;Page</span></a>&nbsp;]&nbsp;&nbsp;';
						}
					}
					if (($this->PageIncrements == 1) && ($pages > 10)) {
						// Previous ten link
						$PrevTen = $start - 10;
						if ($start == 1) {
							$NewHTML .= '[&nbsp;<u>Previous&nbsp;10</u>&nbsp;]&nbsp;&nbsp;';
						} else if ($PrevTen < 1) {
							$NewHTML .= '[&nbsp;<a href="' . str_replace('&&', '&', '?page=1' . $new_qs) . '"><span class="underline">Previous&nbsp;10</span></a>&nbsp;]&nbsp;&nbsp;';
						} else {
							$NewHTML .= '[&nbsp;<a href="' . str_replace('&&', '&', '?page=' . $PrevTen . '' . $new_qs) . '"><span class="underline">Previous&nbsp;10</span></a>&nbsp;]&nbsp;&nbsp;';
						}
					}
					$NewHTML .= '&nbsp;' . $html . '&nbsp;';
					if (($this->PageIncrements == 1) && ($pages > 10)) {
						// Next ten link
						$NextTen = $start + 10;
						if ($start == $pages) {
							$NewHTML .= '[&nbsp;<u>Next&nbsp;10</u>&nbsp;]&nbsp;&nbsp;';
						} else if ($NextTen > $pages) {
							$NewHTML .= '[&nbsp;<a href="' . str_replace('&&', '&', '?page=' . $pages . '' . $new_qs) . '"><span class="underline">Next&nbsp;10</span></a>&nbsp;]&nbsp;&nbsp;';
						} else {
							$NewHTML .= '[&nbsp;<a href="' . str_replace('&&', '&', '?page=' . $NextTen . '' . $new_qs) . '"><span class="underline">Next&nbsp;10</span></a>&nbsp;]&nbsp;&nbsp;';
						}
					}
					if ($this->FirstLastPages == 1) {
						// Last page link
						if ($start == $pages) {
							$NewHTML .= '[&nbsp;<u>Last&nbsp;Page</u>&nbsp;]';
						} else {
							$NewHTML .= '[&nbsp;<a href="' . str_replace('&&', '&', '?page=' . $pages . '' . $new_qs) . '"><span class="underline">Last&nbsp;Page</span></a>&nbsp;]';
						}
					}
					$NewHTML .= '<br />';
					$NewHTML .= '<br /><br />';
					$NewHTML .= '</div>';
				} else {
					$NewHTML = '<strong>Pages: </strong>&nbsp;' . $html;
				}
			} else {
				$NewHTML = $html;
			}
			// Return
			return $NewHTML;
		}
	}
}
?>
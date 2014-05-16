<?php
class Sorting extends CCTemplate 
{
	public function QueryLinkBuilder($TheGet = array(), $NiceLinkName = 'Sort by', $FriendlyCol = 'Title', $FriendlySortNames = 'Ascending,Descending', $ColName = 'col', $GetCol = 'title', $SortName = 'sort', $GetSort = 'ASC,DESC') {
		// Vars
		$html = '';
		// Getting Sort Names
		$FriendlySortNames = explode(',', $FriendlySortNames);
		$GetSort = explode(',', $GetSort);
		// Set Nice Link Name
		$LinkName = $NiceLinkName . ' ' . $FriendlyCol . ' ' . $FriendlySortNames[0];
		// Being query string
		$QueryString = '?' . $ColName . '=' . $GetCol . '&amp;' . $SortName . '=' . $GetSort[0];
		// Do we have a proper $_GET array
		if ((isset($TheGet)) && (count($TheGet) >= 1)) {
			// Check Sort
			if ((isset($TheGet[$SortName])) && ($TheGet[$SortName] == $GetSort[0])) {
				$GetNewSort = $GetSort[1];
				$LinkName = $NiceLinkName . ' ' . $FriendlyCol . ' ' . $FriendlySortNames[1];
			} elseif ((isset($TheGet[$SortName])) && ($TheGet[$SortName] == $GetSort[1])) {
				$GetNewSort = $GetSort[0];
				$LinkName = $NiceLinkName . ' ' . $FriendlyCol . ' ' . $FriendlySortNames[0];
			}
			// QueryString Build.
			$count = 1; // Counter
			$limit = count($TheGet); // Find the count limit
			$NewQueryString = '';
			$ColFound = 0;
			$SortTypeFound = 0;
			// Loop through get query string and rebuild
			foreach ($TheGet as $kqs => $qs) {
				// Check if beginning of loop
				if ($count == 1) {
					$NewQueryString .= '?';
				}
				// Check on the col for the switch
				if (isset($TheGet[$SortName])) {
					if ($kqs == $TheGet[$SortName]) {
						if ($qs == $GetSort[0]) {
							// Adding Key and Value
							$NewQueryString .= $kqs . '=' . $GetSort[1];
						} else {
							// Adding Key and Value
							$NewQueryString .= $kqs . '=' . $GetSort[0];
						}
					} else {
						// Adding Key and Value
						if ((isset($qs)) && ($qs > '')) {
							if ($qs == $GetSort[0]) {
								$NewQueryString .= $kqs . '=' . $GetSort[1];
							} elseif ($qs == $GetSort[1]) {
								$NewQueryString .= $kqs . '=' . $GetSort[0];
							} else {
								$NewQueryString .= $kqs . '=' . $qs;
							}
						} else {
							$NewQueryString .= $kqs . '=' . 'error';
						}
					}
				}
				// Do we need an ampersand?
				if ($count != $limit) {
					$NewQueryString .= '&amp;';
				}
				// Check if our sort column exists
				if ($kqs == $ColName) {
					$ColFound = 1;
				}
				// Check if our sort type exists
				if ($kqs == $SortName) {
					$SortTypeFound = 1;
				}
				$count++; // Increment
			}
			if (($ColFound == 0) or ($SortTypeFound == 0)) {
				$NewQueryString .= '&amp;';
				if (isset($ColName)) {
					 $NewQueryString .= $ColName . '=';
				}
				if (isset($GetCol)) {
					$NewQueryString .= $GetCol . '&amp;';
				}
				if (isset($SortName)) {
					$NewQueryString .= $SortName . '=';
				}
				if (isset($GetNewSort)) {
					$NewQueryString .= $GetNewSort;
				}
			}
			$html .= '<a href="' . $NewQueryString . '" title="' . $LinkName . '">' . $LinkName . '</a>';
		} else { // Default
			$html .= '<a href="' . $QueryString . '" title="' . $LinkName . '">' . $LinkName . '</a>';
		}
		return $html;
	}
}
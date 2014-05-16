<?php
if (($delete_blog == 1) && ($add_edit_blog == 1)) {
	// Get Class and Information from method
	$BlogAct = new BlogActions();
	if ((isset($_GET['col1'])) && (isset($_GET['orderby']))) {
		$Info = $BlogAct->GetAllBlogs($_GET['col1'], $_GET['orderby']);
	} else if ((isset($_GET['col2'])) && (isset($_GET['orderby']))) {
		$Info = $BlogAct->GetAllBlogs($_GET['col2'], $_GET['orderby']);
	} else {
		$Info = $BlogAct->GetAllBlogs();
	}
	// String Checker Help
	$StringCheck = new StringCheckers();
	// Sorting Help
	$Sorter = new Sorting();
	// Sorting Variables
	$TheGet = $_GET;
	$NiceLinkName = 'Sort by';
	$FriendlySortNamesTitle = 'Ascending,Descending';
	$GetSortTitle = 'ASC,DESC';
	$GetColTitle = 'title';
	$FriendlyColTitle = 'Title';
	$ColNameTitle = 'col1';
	$SortNameTitle = 'orderby';
	$GetColDate = 'date';
	$FriendlyColDate = 'Date';
	$ColNameDate = 'col2';
	$SortNameDate = 'orderby';
	$GetSortDate = 'DESC,ASC';
	$FriendlySortNamesDate = 'Descending,Ascending';
	// HTML Build for Date Sort
	$html = '<div class="alignCenter">';
	if (isset($_GET['col1'])) {
		$html .= $Sorter->QueryLinkBuilder($_GET, $NiceLinkName, $FriendlyColTitle, $FriendlySortNamesTitle, $ColNameTitle, $GetColTitle, $SortNameTitle, $GetSortTitle);
	} else {
		$html .= $Sorter->QueryLinkBuilder(array(), $NiceLinkName, $FriendlyColTitle, $FriendlySortNamesTitle, $ColNameTitle, $GetColTitle, $SortNameTitle, $GetSortTitle);
	}
	$html .= ' | ';
	if (isset($_GET['col2'])) {
		$html .= $Sorter->QueryLinkBuilder($_GET, $NiceLinkName, $FriendlyColDate, $FriendlySortNamesDate, $ColNameDate, $GetColDate, $SortNameDate, $GetSortDate);
	} else {
		$html .= $Sorter->QueryLinkBuilder(array(), $NiceLinkName, $FriendlyColDate, $FriendlySortNamesDate, $ColNameDate, $GetColDate, $SortNameDate, $GetSortDate);
	}
	
	$html .= '<br /><br />';
	$html .= '<div class="MainContainer">';
	$html .= '<h2>Blog Entry List</h2>';
	$i = 1; // Incrementer
	$repeat_rows = $Info['RowArray']; // Array of rows from class method
	if ((isset($Info['TotalRows'])) && ($Info['TotalRows'] !== false)) { // checking for the total rows
		if ($Info['TotalRows'] > 0) {
			foreach($repeat_rows as $row) {
				// Alternating row colors
				if ($i == 1) {
					$html .= '<div class="FirstColor">';
				} else {
					$html .= '<div class="SecondColor">';
				}
				// Left Column
				$html .= '<div class="LCol';
				$html .= '"><a href="/blog/?id=' . $row['id'] . '" title="' . $row['title'] . '"><span class="FontSize14 underline">' . $row['title'] . '</span></a>';
				$html .= '<br />';
				$html .=  '<div class="FontSize10">' . $StringCheck->StringLimiter($row['content'], 55) . '';
				$html .=  '<br />';
				$html .=  '<strong class="FontSize9 underline">Keywords:</strong> ' . $row['keywords'] . '</div>';
				$html .= '</div>';
				// Right Column
				$html .= '<div class="RCol FontSize14';
				$html .= '">';
				if ($add_edit_blog == 1) {
					$html .= '<a href="?act=edit&amp;id=' . $row['id'] . '" title="Edit"><span class="underline">Edit</span></a>';
				}
				if ($delete_blog == 1) {
					$html .= ' | <a href="?act=delete&amp;id=' . $row['id'] . '" title="Delete"><span class="underline">Delete</span></a>';
				}
				$html .= '</div>';
				$html .= '<br class="clear" />';
				// Change alternating row
				if ($i == 1) {
					$i = 0;
				} else {
					$i = 1;
				}
				$html .= '</div>';
			}
		} else {
			$html .= '<div class="FirstColor"><p>No entries exist.</p></div>';
		}
	} else {
		$html .= '<div class="FirstColor"><p>No entries exist.</p></div>';
	}
	// Close DIVs
	$html .= '</div>';
	$html .= '</div>';
	// Return
	echo $html;
} else {
	echo 'Access Denied.';
}
?>
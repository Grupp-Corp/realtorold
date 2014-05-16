<?php
// Get Class and Information from method
$CatAct = new CategoryActions();
if ((isset($_GET['col1'])) && (isset($_GET['orderby']))) {
	$Info = $CatAct->CategoryList($_GET['col1'], $_GET['orderby']);
} else if ((isset($_GET['col2'])) && (isset($_GET['orderby']))) {
	$Info = $CatAct->CategoryList($_GET['col2'], $_GET['orderby']);
} else {
	$Info = $CatAct->CategoryList();
}
// String Checker Help
$StringCheck = new StringCheckers();
// Sorting Help
$Sorter = new Sorting();
// Sorting Variables
$TheGet = $_GET;
$NiceLinkName = 'Sort by';
$FriendlySortNamesTitle = 'Descending,Ascending';
$GetSortTitle = 'DESC,ASC';
$GetColTitle = 'name';
$FriendlyColTitle = 'Title';
$ColNameTitle = 'col1';
$SortNameTitle = 'orderby';
// HTML Build for Date Sort
$html = '<div class="alignCenter">';
$html .= $Sorter->QueryLinkBuilder($_GET, $NiceLinkName, $FriendlyColTitle, $FriendlySortNamesTitle, $ColNameTitle, $GetColTitle, $SortNameTitle, $GetSortTitle);
$html .= '<br /><br />';
$html .= '<div class="MainContainer">';
$html .= '<h2>Category List</h2>';
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
			$html .= '"><span class="FontSize14 underline">' . $row['name'] . '</span>';
			$html .= '<br />';
			$html .=  '<div class="FontSize10">' . $row['description'] . '</div>';
			$html .= '</div>';
			// Right Column
			$html .= '<div class="RCol FontSize14';
			$html .= '"><a href="?act=edit&amp;id=' . $row['id'] . '" title="Edit"><span class="underline">Edit</span></a> | <a href="?act=delete&amp;id=' . $row['id'] . '" title="Delete"><span class="underline">Delete</span></a></div>';
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
?>
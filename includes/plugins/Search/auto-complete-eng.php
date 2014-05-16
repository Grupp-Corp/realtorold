<?php
// Get DB Class
include('../../classes/MySQL.php');
// DB Obtain
if ($_SERVER['HTTP_HOST'] == 'canuckcoder.com') { // Sandbox
	$ajax_conn = DBMySQL::obtain('localhost', 'canuckc_sscharf', 'oem429opmi@STEVE', 'canuckc_newcms');
} else {
	$ajax_conn = DBMySQL::obtain('localhost', 'intranet', 'canada2010@STEVE', 'intradb');
}
// connect to the server 
$ajax_conn->connect();
$ajax_conn->query('SET NAMES "latin1"');
// Query
$query_string = 'SELECT DISTINCT(ise_search_keywords_eng.lid), ise_search_keywords_eng.keyword, ise_search_links_eng.title, SUM(ise_search_keywords_eng.count) AS totalWeight
				FROM ise_search_keywords_eng, ise_search_links_eng
				WHERE ise_search_keywords_eng.keyword LIKE "%' . $_GET['term'] . '%" 
				OR ise_search_links_eng.title LIKE "' . $_GET['term'] . '%" 
				GROUP BY keyword 
				ORDER BY totalWeight DESC 
				LIMIT 0, 7';
				echo $query_string;
$query = $ajax_conn->query($query_string);
$ttra = $ajax_conn->affected_rows;
if ($ttra > 0) {
	$return_arr = array();
	$WeightTotal = 0;
	/* Retrieve and store in array the results of the query.*/
	$repeat_rows = $ajax_conn->fetch_array($query_string);
	foreach ($repeat_rows as $row) {
		$WeightTotal = $row['totalWeight'] + $WeightTotal;
	}
	foreach ($repeat_rows as $row) {
		// Build percentage
		$Percentage = round($row['totalWeight'] / $WeightTotal * 100, 1);
		$html_label = '
		<table width="100%">
		  <tr>
		    <td width="50%"><label title="Term: ' . $row['keyword'] . '">' . ucfirst($row['keyword']) . '</label></td>
			<td width="50%" style="text-align:right;"><label title="' . $Percentage . '% Site-wide term relevancy (compared with other listed terms)">' . $Percentage . '%</label>' . '</td>
		  </tr>
		</table>		
		';
		// Build array
		$row_array['id'] = $row['lid'];
		$row_array['label'] = $html_label;
		$row_array['value'] = $row['keyword'];
		if (!isset($_GET['no_json'])) {
			array_push($return_arr, $row_array);
		}
	}
	if (!isset($_GET['no_json'])) {
		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
}
$ajax_conn->close();
?>
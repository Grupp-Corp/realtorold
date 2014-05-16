<h2>Field(s) List</h2>
<div class="FieldBoxPad alignLeft"></div>
<?php
$Fields_HTML = '<table id="fieldListTable">' . PHP_EOL;
// Loop through fields
foreach($FormFieldRows as $frow) {
	// Check if set and is array?
	if ((isset($frow)) && (is_array($frow))) {
		$i = 1;
		$Fields_HTML .= '<tr>';
		$Fields_HTML .= '<th colspan="2"><a href="?act=add&amp;id=' . $_GET['id'] . '&amp;fid=' . $row['field_id'] . '&amp;addfield=1" title="Add Field">Add Field</a></th>';
		$Fields_HTML .= '<tr>';
		// Loop through data
		foreach($frow as $row) {
			if ($i&1) {
				$bgClass = 'bg1';
			} else {
				$bgClass = 'bg2';
			}
			$Fields_HTML .= '<tr class="' . $bgClass . '">' . PHP_EOL;
			$Fields_HTML .= '<td><a href="?act=edit&amp;id=' . $row['fid'] . '&amp;fid=' . $row['field_id'] . '">' . $row['LabelName'] . '</a><br />(<em>' . ucfirst($row['Type']) . ' Field</em>)</td>' . PHP_EOL;
			$Fields_HTML .= '<td class="centerCol width37Percent"><a href="?act=edit&amp;id=' . $row['fid'] . '&amp;fid=' . $row['field_id'] . '">Edit</a> | <a href="?act=delete&amp;id=' . $row['fid'] . '&amp;fid=' . $row['field_id'] . '">Delete</a></td>' . PHP_EOL;
			$Fields_HTML .= '</tr>' . PHP_EOL;
			$i++;
		}
	}
}
// Close the list
$Fields_HTML .= '</table>' . PHP_EOL;
echo $Fields_HTML;
?>
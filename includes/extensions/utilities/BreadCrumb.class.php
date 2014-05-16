<?php
class BreadCrumbBuilder extends CCTemplate
{
	public function get_crumbs($InitialBreadCrumb, $title, $homelinks = 'index.php', $therequesturi, $BreadCrumbDepth = 0) {
		$therequesturi = $therequesturi;
		$thereturn = '';
		$trail = '';
		$directory = '../';
		$parts = explode("/", $therequesturi);
		$url_folder_count = count($parts) - 1;
		$trail_array = array();
		$end_link = array();
		if (isset($BreadCrumbDepth)) {
			$BreadCrumbDepth = $BreadCrumbDepth;
		} else {
			$BreadCrumbDepth = 0;
		}
		if ((isset($InitialBreadCrumb)) && ($InitialBreadCrumb === false)) {
			$BreadCrumbDepth = $BreadCrumbDepth + 1;
		}
		for ($i = ($BreadCrumbDepth + 1); $i <= $url_folder_count; $i++) {
			if ($i != 1) {
				$directory .= '../';
			}
			if (!strpos($parts[$i], '.php')) {
				$fileTitle = $this->filehandle($directory, 'index.php');
				$fileTitle = preg_replace('#\$title = \'#i', '', $fileTitle);
				$fileTitle = preg_replace('#\';#i', '', $fileTitle);
				$trail_array[] = array('link' => $directory . 'index.php', 'filetitle' => $fileTitle);
			} else {
				$path = explode("/", $_SERVER['REQUEST_URI']);
				$get_file_name = count($path)-1;
				$file_name = $path[$get_file_name];
				$file_location = str_replace($file_name, '', $therequesturi);
				$fileTitle = $this->filehandle('', $homelinks);
				$fileTitle = preg_replace('#\$title = \'#i', '', $fileTitle);
				$fileTitle = preg_replace('#\';#i', '', $fileTitle);
				//$end_link[] = array('link' => $file_location . $homelinks, 'filetitle' => $fileTitle);
				//$end_link[] = array('link' => $file_location . $file_name, 'filetitle' => $title);
				$end_link[] = array('link' => $file_location, 'filetitle' => $fileTitle);
				$end_link[] = array('link' => $file_location, 'filetitle' => $title);
			}
		}
		$final_trail_array = array();
		foreach (array_reverse($trail_array) as $breadcrumb) {
			$final_trail_array[] = array('link' => $breadcrumb['link'], 'filetitle' => $breadcrumb['filetitle']);
		}
		foreach ($end_link as $breadcrumb) {
			$final_trail_array[] = array('link' => $breadcrumb['link'], 'filetitle' => $breadcrumb['filetitle']);
		}
		$count_array = count($final_trail_array);
		$i = 1;
		$itsadup = 0;
		$title_count = 1;
		$pastTitle = '';
		foreach ($final_trail_array as $breadcrumb) {
			if ($i != $count_array) {
				$nextTitle = $final_trail_array[$title_count]['filetitle'];
				$title_count++;
			} else {
				$nextTitle = 1;
			}
			if ($nextTitle == 1) {
				$itsadup = 1;
				break;
			} else {
				if ($i == 1) {
					$trail .= '<span class="redLink"><a href="' . $breadcrumb['link'] . '" title="' . $breadcrumb['filetitle'] . '">' . $breadcrumb['filetitle'] . '</a></span>';
				} else {
					$trail .= ' &gt; <span class="redLink"><a href="' . $breadcrumb['link'] . '" title="' . $breadcrumb['filetitle'] . '">' . $breadcrumb['filetitle'] . '</a></span>';
				}
			}
			$i++;
		}
		return urldecode($trail);
	}
}
?>
<?php
class SlideShowHTML5
{
	// Vars
	private $db_conn;
	
	public function createthumb($dest, $filename, $desired_width) {
		/* read the source image */
		$source_image = imagecreatefromjpeg($filename);
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height*($desired_width/$width));
		
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width,$desired_height);
		
		/* copy source image at a resized size */
		imagecopyresized($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);
		
		/* create the physical thumbnail image to its destination */
		imagejpeg($virtual_image,$dest);
	}
	// Building slideshowpro images into a list item with album information
	public function IntoListItems($album_id) {
		global $db_conn;
		$checkStrings = new StringCheckers();
		if ($checkStrings->CheckInteger($album_id) === true) {
			$query_string = "SELECT * FROM cms_ss_albums, cms_ss_images WHERE (cms_ss_albums.id= cms_ss_images.aid) AND cms_ss_albums.id = " . $album_id . ""; // SQL Query String
			$query = $db_conn->query($query_string); // This returns true if successful
			$row = $db_conn->fetch($query); // Data array based on column names
			$allRows = $db_conn->affected_rows; // Row Checker
			// Make sure we have data
			if ($allRows > 0) {
				$repeat_rows = $db_conn->fetch_array($query_string); // MySQL Fetch Array for row iteration
				// Raw album data
				$build = '<h2>' . $row['title'] . '</h2>';
				$build .= '<p>' . $row['description'] . '</p>';
				// Build Values for thumbs and slider
				$build_top_insert = '';
				$build_bottom_insert = '';
				$i = 1; // Initializing Counter
				foreach ($repeat_rows as $row_read) {
					$thumbnail = $this->createthumb(''.$_SERVER['DOCUMENT_ROOT'].'temp/'.$row_read['src'].'', $_SERVER['DOCUMENT_ROOT'] . '/images/plugins/slideshow/album-' . $album_id . '/lg/' . $row_read['src'] . '', 550);
					$build_top_insert .= '<li id="'.$i.'"><img src="/images/plugins/slideshow/album-' . $album_id . '/lg/' . $row_read['src'] . '" width="550" height="733" alt="' . $row_read['alt'] . ' " /><p><span>' . $row_read['alt'] . '</span></p></li>'; // Slider List Item
					$build_bottom_insert .= '<li><a href="#'.$i.'"><img src="/images/plugins/slideshow/album-' . $album_id . '/lg/' . $row_read['src'] . '" width="75" height="100" alt="' . $row_read['alt'] . ' " /></a></li>'; // Thumbnail List Item
					$i++; // Increment Counter
				}
				// Top Slider Build
				$build_top = '<div id="slideshow_slider_box">';
				$build_top .= '<ul id="slideshow_slider">';
				$build_top .= $build_top_insert;
				$build_top .= '</ul>';
				// Thumbs Build
				$build_bottom = '<ul id="slideshow_thumb">';
				$build_bottom .= $build_bottom_insert;
				$build_bottom .= '</ul>';
				$build_bottom .= '</div>';
				$build .= $build_top . '' . $build_bottom;
			}
			return $build;
		} else {
			return "Error: invalid id";	
		}
	}
	public function IntoImages($album_id) {
		global $db_conn;
		$checkStrings = new StringCheckers();
		if ($checkStrings->CheckInteger($album_id) === true) {
			$query_string = "SELECT * FROM cms_ss_albums, cms_ss_images WHERE (cms_ss_albums.id= cms_ss_images.aid) AND cms_ss_albums.id = " . $album_id . ""; // SQL Query String
			$query = $db_conn->query($query_string); // This returns true if successful
			$row = $db_conn->fetch($query); // Data array based on column names
			$allRows = $db_conn->affected_rows; // Row Checker
			$html = '<div class="PicColContainer">';
			// Make sure we have data
			if ($allRows > 0) {
				$repeat_rows = $db_conn->fetch_array($query_string); // MySQL Fetch Array for row iteration
				$i = 1; // Initializing Counter
				foreach ($repeat_rows as $row_read) {
					// Ceate new size for image
					$img = ''; // File
					$thumbnail = $this->createthumb(''.$_SERVER['DOCUMENT_ROOT'].'temp/'.$row_read['src'].'', $_SERVER['DOCUMENT_ROOT'] . '/images/plugins/slideshow/album-' . $album_id . '/lg/' . $row_read['src'] . '', 450);
					$html .= '<div class="PicCol' . $i . '"><a href="/images/plugins/slideshow/album-' . $album_id . '/lg/' . $row_read['src'] . '" target="_blank"><img src="/images/plugins/slideshow/album-' . $album_id . '/lg/' . $row_read['src'] . '" width="450" height="600" alt="' . $row_read['alt'] . ' " /></a><br /><div class="PicCaption">' . $row_read['alt'] . '</div></div>' . PHP_EOL;
					if ($i == 1) {
						$i = 2;
					} else {
						$i = 1;
					}
				}
			}
			$html .= '</div><br class="clearfix" />';
			return $html;
		} else {
			return "Error: invalid id";	
		}
	}
}
?>
<?php
class MenuBuild extends CCTemplate
{
	// Simple Inline Unordered List Menu with Tab Order (Value Accepted: array())
	public function SimpleInlineMenu($array = array(), $withoutClass = 0) {
		if (is_array($array)) {
			if (!empty($array)) {
				if ($withoutClass == 0) {
					$html = '<ul class="nav">' . PHP_EOL;
				} else {
					$html = '<ul>' . PHP_EOL;
				}
				$i = 1;
				$url_parts = explode("/", $_SERVER['REQUEST_URI']);
				foreach($array as $name => $url) {
					if (is_array($url)) {
						if ($withoutClass == 0) {
							$html .= '<li class="dropdown">' . PHP_EOL;
							$html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#">More... <b class="caret"></b></a>' . PHP_EOL;
							$html .= '<ul class="dropdown-menu">' . PHP_EOL;
						} else {
							$html .= '<ul>' . PHP_EOL;
						}
						foreach($url as $nme => $uri) {
							$html .= '<li><a href="' . $uri . '" tabindex="' . $i . '" title="' . strip_tags($nme) . '" target="_blank">' . $nme . '</a></li>' . PHP_EOL;
						}
						$html .= '</ul></li>' . PHP_EOL;
					} else {
						if (in_array(str_replace("/", '', $url), $url_parts)) {
							$html .= '<li class="active"><a href="' . $url . '" tabindex="' . $i . '" title="' . strip_tags($name) . '">' . $name . '</a></li>' . PHP_EOL;
						} else {
							$html .= '<li><a href="' . $url . '" tabindex="' . $i . '" title="' . strip_tags($name) . '">' . $name . '</a></li>' . PHP_EOL;
						}
					}
					$i++;
				}
				$html .= '</ul>' . PHP_EOL;
				return $html;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>
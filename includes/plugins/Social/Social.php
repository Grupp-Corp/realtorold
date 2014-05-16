<?php
class Socials
{
	public function GetDelicious() {
		global $ShowLink_Delicious;
		if ($ShowLink_Delicious == 1) {
			return '<a href="http://www.delicious.com/save" onclick="window.open(\'http://www.delicious.com/save?v=5&noui&jump=close&url=\'+encodeURIComponent(location.href)+\'&title=\'+encodeURIComponent(document.title), \'delicious\',\'toolbar=no,width=550,height=550\'); return false;" title="Save this on Delicious"><img src="' . ABS_PATH . 'images/delicious.gif" height="24" width="24" alt="Save this on Delicious" /> Save this on Delicious</a>';
		} else {
			return NULL;
		}	
	}
	public function GetFacebook($which = 'like', $ajax = 1) {
		global $ShowLink_Facebook;
		if ($ShowLink_Facebook == 1) {
			if ($ajax == 1) { // On
				if ($which == 'like') {
					return '<div id="facebook"><img src="/images/loading.gif" width="10" height="10" alt="Loading" /></div>';
				} elseif ($which == 'box') {
					return '<div id="facebook"><img src="/images/loading.gif" width="18" height="18" alt="Loading" /></div>';
				} elseif ($which == 'recommend') {
					return '<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-like" data-href="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '" data-send="false" data-layout="box_count" data-width="75" data-show-faces="true" data-action="recommend" data-font="arial"></div>';
				}
			} else {
				if ($which == 'like') {
					return '<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-like" data-href="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '" data-send="true" data-layout="button_count" data-width="10" data-show-faces="true"></div>';
				} elseif ($which == 'box') {
					return '<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-like" data-href="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '" data-send="false" data-layout="box_count" data-width="75" data-show-faces="true" data-action="recommend" data-font="arial"></div>';
				} elseif ($which == 'recommend') {
					return '<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-like" data-href="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '" data-send="false" data-layout="box_count" data-width="75" data-show-faces="true" data-action="recommend" data-font="arial"></div>';
				}
			}
		} else {
			return NULL;
		}
	}
	public function GetTwitter($blogid = 0, $ajax = 1) {
		global $ShowLink_Twitter;
		if ($ShowLink_Twitter == 1) {
			if ($ajax == 1) { // On
				if ($blogid != 0) {
					return '<a href="https://twitter.com/share" class="twitter-share-button" title="Tweet" data-text="http://' . $_SERVER['HTTP_HOST'] . '/blog/index.php?id=' . $blogid . '"><img src="/images/loading.gif" width="10" height="10" alt="Loading" /></a>';
				} else {
					return '<a href="https://twitter.com/share" class="twitter-share-button" title="Tweet" data-text="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '"><img src="/images/loading.gif" width="10" height="10" alt="Loading" /></a>';
				}
			} else {
				return '<a href="https://twitter.com/share" class="twitter-share-button"><img src="/images/loading.gif" width="12" height="12" alt="Loading" /></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';	
			}
		} else {
			return NULL;
		}
	}
	public function GetLinkedIn($which = 'right') {
		global $ShowLink_LinkedIn;
		if ($ShowLink_LinkedIn == 1) {
			if ($which == 'right') {
				return '<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script><script type="IN/Share" data-counter="right"></script>';
			} else {
				return '<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script><script type="IN/Share" data-counter="top"></script>';
			}
		} else {
			return NULL;
		}
	}
}
?>
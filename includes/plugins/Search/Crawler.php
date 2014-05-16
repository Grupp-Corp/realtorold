<?php
class Crawler extends CCTemplate
{
	/****************************************************/
	/* 					INITIATION						*/
	/****************************************************/
	////////////////
	// Class Vars //
	////////////////
	protected $XSSCheck;
	protected $Search;
	protected $Stem;
	protected $GetDB;
	protected $RobotsCheck;
	protected $UserAgent = 'CanuckCoder Crawler';
	protected $lang = '';
	protected $Weight_Title = 100;
	protected $Weight_DateModified = 5;
	protected $Weight_Subject = 25;
	protected $Weight_Author = 15;
	protected $Weight_Desc = 15;
	protected $Weight_Keywords = 50;
	protected $Weight_HeaderOne = 35;
	protected $Weight_HeaderTwo = 25;
	protected $Weight_HeaderThree = 5;
	protected $Weight_HeaderFour = 2;
	protected $Weight_HeaderFive = 2;
	protected $Weight_HeaderSix = 2;
	protected $Weight_WordBody = 1;
	protected $LimitBody = 0;
	protected $GetImages = 0;
	protected $DynamicLinks = 0;
	protected $LinkFollowInternal = 0;
	protected $PorterStemming = 0;
	///////////////
	// Construct //
	///////////////
	public function __construct($UserAgent, $lang, $Weight_Title, $Weight_DateModified, $Weight_Subject,  $Weight_Author, $Weight_Desc, $Weight_Keywords, $Weight_HeaderOne, $Weight_HeaderTwo, $Weight_HeaderThree, $Weight_HeaderFour, $Weight_HeaderFive, $Weight_HeaderSix, $Weight_WordBody, $LimitBody, $GetImages, $DynamicLinks, $LinkFollowInternal, $PorterStemming) {
		// Parents Construct
		parent::__construct();
		// Get Search class
		include('plugins/Search/Search.php');
		// DB Connection Settings
		$this->GetDB = $this->db_conn;
		// Vars
		$this->UserAgent = $UserAgent;
		$this->lang = $lang;
		$this->Weight_Title = $Weight_Title;
		$this->Weight_DateModified = $Weight_DateModified;
		$this->Weight_Subject = $Weight_Subject;
		$this->Weight_Author = $Weight_Author;
		$this->Weight_Desc = $Weight_Desc;
		$this->Weight_Keywords = $Weight_Keywords;
		$this->Weight_HeaderOne = $Weight_HeaderOne;
		$this->Weight_HeaderTwo = $Weight_HeaderTwo;
		$this->Weight_HeaderThree = $Weight_HeaderThree;
		$this->Weight_HeaderFour = $Weight_HeaderFour;
		$this->Weight_HeaderFive = $Weight_HeaderFive;
		$this->Weight_HeaderSix = $Weight_HeaderSix;
		$this->Weight_WordBody = $Weight_WordBody;
		$this->LimitBody = $LimitBody;
		$this->GetImages = $GetImages;
		$this->DynamicLinks = $DynamicLinks;
		$this->LinkFollowInternal = $LinkFollowInternal;
		$this->PorterStemming = $PorterStemming;
		// Object variables
		$this->Search = new Search(1, $this->lang);
		// Check if porter stemming is on
		if ($this->PorterStemming == 1) {
			$this->Stem = new Stemmer();
		}
	}
	/****************************************************/
	/* 						EMPTY						*/
	/****************************************************/
	///////////////
	// Empty DBs //
	///////////////
	public function DeleteCrawlData() {
		// Check Language
		if ($this->lang > '') {
			$langsql = '_' . str_replace('-', '', $this->lang);
		} else {
			$langsql = '';
		}
		// Perform queries
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_links' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword0' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword1' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword2' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword3' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword4' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword5' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword6' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword7' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword8' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyword9' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyworda' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keywordb' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keywordc' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keywordd' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keyworde' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_keywordf' . $langsql . '');
		$this->GetDB->query('TRUNCATE TABLE ' . $this->config['table_prefix'] . 'search_images');
	}
	/****************************************************/
	/* 						BUILDS						*/
	/****************************************************/
	///////////////////////
	// Build Proper Path //
	///////////////////////
	protected function PathBuild($path, $file) {
		// Vars
		$PathBuild = str_replace('./', '', $path . '/' . $file);
		$PathBuild = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $PathBuild;
		return $PathBuild;
	}
	///////////////////////////
	// Convert Bytes to Size //
	///////////////////////////
	public function GetBytes($bytes, $precision = 0) {  
		// Vars/Calcs
		$kilobyte = 1024;
		$megabyte = $kilobyte * 1024;
		$gigabyte = $megabyte * 1024;
		$terabyte = $gigabyte * 1024;
		// Check
		if ($bytes == 0) {
			return $bytes . '';
		} else if (($bytes >= 0) && ($bytes < $kilobyte)) {
			return $bytes . ' B';
		} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
			return round($bytes / $kilobyte, $precision) . ' KB';
		} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
			return round($bytes / $megabyte, $precision) . ' MB';
		} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
			return round($bytes / $gigabyte, $precision) . ' GB';
		} elseif ($bytes >= $terabyte) {
			return round($bytes / $terabyte, $precision) . ' TB';
		} else {
			return $bytes . ' B';
		}
	}
	/****************************************************/
	/* 						CHECKS						*/
	/****************************************************/
	//////////////////////////
	// Check File Extension //
	//////////////////////////
	public function GetFileExtension($file) {
		$qpos = strpos($file, "?");
		if ($qpos !== false) {
			$file = substr($file, 0, $qpos);
		}
		$extension = pathinfo($file, PATHINFO_EXTENSION);	
		return $extension;
	}
	///////////////////////
	// Check/Remove HTML //
	///////////////////////
	public function remove_HTML($s , $keep = '' , $expand = 'script|style|noframes|select|option'){
        //prep the string
        $s = ' ' . $s;
        $k = array();
        //initialize keep tag logic
        if(strlen($keep) > 0) {
            $k = explode('|',$keep);
            for($i = 0; $i<count($k); $i++) {
                $s = str_replace('<' . $k[$i], '[{(' . $k[$i], $s);
                $s = str_replace('</' . $k[$i], '[{(/' . $k[$i], $s);
            }
        }
        // Begin removal
        // Remove comment blocks
        while (stripos($s,'<!--') > 0) {
            $pos[1] = stripos($s,'<!--');
            $pos[2] = stripos($s,'-->', $pos[1]);
            $len[1] = $pos[2] - $pos[1] + 3;
            $x = substr($s, $pos[1], $len[1]);
            $s = str_replace($x, ' ', $s);
        }
        // Remove tags with content between them
        if(strlen($expand) > 0) {
            $e = explode('|',$expand);
            for($i = 0; $i < count($e); $i++) {
                while(stripos($s,'<' . $e[$i]) > 0){
                    $len[1] = strlen('<' . $e[$i]);
                    $pos[1] = stripos($s,'<' . $e[$i]);
                    $pos[2] = stripos($s,$e[$i] . '>', $pos[1] + $len[1]);
                    $len[2] = $pos[2] - $pos[1] + $len[1];
                    $x = substr($s,$pos[1], $len[2]);
                    $s = str_replace($x, ' ', $s);
                }
            }
        }
        // Remove remaining tags
        while ((stripos($s,'<') > 0) && (stripos($s,'<') !== false)){
            $pos[1] = stripos($s, '<');
            $pos[2] = stripos($s, '>', $pos[1]);
            $len[1] = $pos[2] - $pos[1] + 1;
            $x = substr($s, $pos[1], $len[1]);
            $s = str_replace($x, ' ', $s);
        }
        // Finalize keep tag
        for($i = 0; $i < count($k); $i++){
            $s = str_replace('[{(' . $k[$i], '<' . $k[$i], $s);
            $s = str_replace('[{(/' . $k[$i], '</' . $k[$i], $s);
        }
        return trim($s);
    }
	///////////////////////
	// Check word/string //
	///////////////////////
	private function CheckWord($word) {
		// Vars
		$passed = 0;
		// Checks
		if (isset($word)) {
			if (!empty($word)) {
				if ($word > '') {
					if (strlen($word) > 2) {
						$passed = 1; // Passed validation
					}
				}
			}
		}
		// easier way to check if we pass or fail...
		if ($passed == 1) {
			$pass = true; // pass
		} else {
			$pass = false; // fail
		}
		return $pass;
	}
	////////////////
	// Check Body //
	////////////////
	private function CheckBody($body) {
		// Check entry
		if ((isset($body)) && ($body > '')) {
			// Start some replacements...
			// Pre-html removal
			$body = preg_replace('#<h1 (.|\n)*>(.*)<\/h1>#', '<h1>$1<\/h1>', $body); // Find H1 replace class
			$body = preg_replace('/<footer>(.*)<\/footer>/s', '', trim($body)); // Special case for HTML5 Footers
			$body = trim($body); // trimming whitespace
			$body = $this->remove_HTML($body); // Remove HTML from page
			// Post-html removal
			$body = str_replace('&nbsp;', '', $body);
			$body = preg_replace('/[^\p{L}\p{N}-. \/\\\$#&!?:;,\[\]\{\}@\(\)\+=\*%©\"\'\s]\'\s]/', '$1' . '', $body);
			//$body = preg_replace('/[\r\n\s\t]+/xms', ' ', trim($body));
			$body = preg_replace('/[\s\t]+/xms', ' ', trim($body));
			// Return
			return $body;
		} else { // body not set
			return false;
		}
	}
	//////////////////////
	// Check URL status //
	//////////////////////
	public function URLStatus($url) {
		// Vars
		$urlparts = parse_url($url);
		$path = $urlparts['path'];
		$host = $urlparts['host'];
		$fsocket_timeout = 30;
		$errno = 0;
		$errstr = "";
		$linkstate = "ok";
		if (isset($port)) {
			$portq = ":$port";
		} else {
			$portq = "";
		}
		// Check if query is set
		if (isset($urlparts['query'])) {
			$path .= "?" . $urlparts['query'];
		}
		// Check port
		if (isset($urlparts['port'])) {
			$port = (int) $urlparts['port']; // ensuring integer for port
		} else {
			// Checking url protocol/scheme
			if ($urlparts['scheme'] == "http") {
				$port = 80;
				$portq = "";
			} else {
				if ($urlparts['scheme'] == "https") {
					$port = 443;
				} else {
					$port = 80;
					$portq = "";
				}
			}
		}
		// sending probe header
		$request = "HEAD " . $path . " HTTP/1.1" . PHP_EOL . "Host: " . $host . "" . $portq . "" . PHP_EOL . "Accept: */*" . PHP_EOL . "User-Agent: " . $this->UserAgent. "" . PHP_EOL . "" . PHP_EOL . "";
		// Check if https://
		if (substr($url, 0, 5) == "https") {
			$target = "ssl://".$host;
		} else {
			$target = $host;
		}
		// Opening socket connection
		$fp = fsockopen($target, $port, $errno, $errstr, $fsocket_timeout);
		// Show error string from socket connection
		print $errstr;
		// Checking socket connection state
		if (!$fp) { // closed
			$status['state'] = "NOHOST";
		} else { // open
			// Socket variables
			socket_set_timeout($fp, 30);
			fputs($fp, $request);
			$answer = fgets($fp, 4096);
			$regs = array();
			// checking our file request type/outputting to array
			if (preg_match("/HTTP\/[0-9.]+ (([0-9])[0-9]{2})/", $answer, $regs)) {
				// preg_match array vars
				$httpcode = $regs[2];
				$full_httpcode = $regs[1];
				// Checking http codes
				if (($httpcode <> 2) && ($httpcode <> 3)) {
					$status['state'] = "Unreachable: http $full_httpcode";
					$linkstate = "Unreachable";
				}
			}
			// Checking link state
			if ($linkstate <> "Unreachable") { // not unreachable
				// Loop through our file answer lines
				while ($answer) {
					// reset answer until content-type is found
					$answer = fgets($fp, 4096);
					// Check for location redirection (stop loop if found)
					if ((preg_match("/Location: *([^\n\r ]+)/i", $answer, $regs) && ($httpcode == 3) && ($full_httpcode != 302))) {
						$status['path'] = $regs[1];
						$status['state'] = "Relocation: http " . $full_httpcode;
						fclose($fp);
						return $status;
					}
					// Get date modified
					if (preg_match("/Last-Modified: *([a-z0-9,: ]+)/i", $answer, $regs)) {
						$status['date'] = $regs[1];
					}
					// Get content-type from the answer and stop loop
					if (preg_match("/Content-Type:/i", $answer)) {
						$content = $answer;
						$answer = '';
						break;
					}
				}
				// Getting socket status
				$socket_status = socket_get_status($fp);
				// Finding the content type
				if (preg_match("/Content-Type: *([a-z\/.-]*)/i", $content, $regs)) { // getting content type
					// Matching content type
					if ($regs[1] == 'text/html') { 					// Text/HTML
						$status['content'] = 'html';
						$status['state'] = 'HTML';
					} else if ($regs[1] == 'text/plain') {			// Text/Plain
						$status['content'] = 'text';
						$status['state'] = 'PLAIN';
					} else if ($regs[1] == 'text/xml') {			// Text/XML
						$status['content'] = 'xml';
						$status['state'] = 'XML';
					} else if ($regs[1] == 'text/css') {			// Text/CSS
						$status['content'] = 'css';
						$status['state'] = 'CSS';
					} else if ($regs[1] == 'text/') { 				// Text Only
						$status['content'] = 'text';
						$status['state'] = 'TEXT';
					} else if ($regs[1] == 'application/pdf') {		// PDF
						$status['content'] = 'pdf';
						$status['state'] = 'PDF';                                 
					} else if (($regs[1] == 'application/msword') or ($regs[1] == 'application/vnd.ms-word')) {					// Word
						$status['content'] = 'doc';
						$status['state'] = 'DOC';
					} else if ((($regs[1] == 'application/excel') or ($regs[1] == 'application/vnd.ms-excel') or ($regs[1] == 'application/x-msexcel'))) {		// Excel
						$status['content'] = 'xls';
						$status['state'] = 'XLS';
					} else if (($regs[1] == 'application/mspowerpoint') or ($regs[1] == 'application/vnd.ms-powerpoint')) {		// Powerpoint
						$status['content'] = 'ppt';
						$status['state'] = 'PPT';
					} else if (($regs[1] == 'application/javascript') or ($regs[1] == 'text/javascript')) {						// Javascript
						$status['content'] = 'js';
						$status['state'] = 'JS';
					} else if ($regs[1] == 'application/rss+xml') {		// RSS
						$status['content'] = 'rss';
						$status['state'] = 'RSS';
					} else if ($regs[1] == 'application/atom+xml') {	// Atom RSS
						$status['content'] = 'atom';
						$status['state'] = 'ATOM XML';
					} else if ($regs[1] == 'application/json') {		// JSON Data
						$status['content'] = 'json';
						$status['state'] = 'JSON';
					} else if ($regs[1] == 'application/zip') {			// ZIP
						$status['content'] = 'zip';
						$status['state'] = 'ZIP';
					} else if ($regs[1] == 'image/jpeg') {				// Image/JPEG
						$status['content'] = 'jpeg';
						$status['state'] = 'IMAGE';
					} else if ($regs[1] == 'image/gif') {				// Image/GIF
						$status['content'] = 'gif';
						$status['state'] = 'IMAGE';
					} else if ($regs[1] == 'image/png') {				//Image/PNG
						$status['content'] = 'png';
						$status['state'] = 'IMAGE';
					} else if ($regs[1] == 'image/wbmp') {				//Image/WBMP
						$status['content'] = 'wbmp';
						$status['state'] = 'IMAGE';
					} else {											// Unknown
						$status['content'] = 'unknown';
						$status['state'] = 'Unknown';
					}
				} else { // Content type not found
					// Checking for server timeout
					if ($socket_status['timed_out'] == 1) { // time out
						$status['state'] = "Server timed out";
					} else { // no reply
						$status['state'] = "No reply from Server";
					}
				}
			}
		}
		fclose($fp);
		return $status;
	}
	//////////////////////
	// Robots Pre-check //
	//////////////////////
	protected function RobotsFileCheck() {
		// Vars
		$BotAllowed = 0;
		// Opening robots.txt, reading, exploding into array
		$myFile ='http://' . $_SERVER['HTTP_HOST'] . '/robots.txt';
		$status = $this->URLStatus($myFile);
		// Getting file status
		if ((($status['state'] == "PLAIN") or ($status['state'] == "TEXT") or ($status['state'] == "HTML"))) {
			// Opening file for read
			$fh = fopen($myFile, 'r');
			$theData = fread($fh, 8192);
			fclose($fh);
			// Getting file line parts
			$Parts = explode(PHP_EOL, $theData);
			// Building our current path
			//$Path = $this->PathBuild($path, $file);
			// Get first line of Robots.txt and match user agent
			if (preg_match("/^User-agent: *([^#]+) */i", $Parts[0], $regs)) {
				$RobotAgent = trim($regs[1]);
				// Checking agent
				if (($RobotAgent == '*') or ($RobotAgent == $this->UserAgent)) {
					$BotAllowed = 1;
				} else {
					$BotAllowed = 0;
				}
			} else {
				$BotAllowed = 1;
			}
		} else {
			$BotAllowed = 1;
		}
		// Check parts for return
		if ((isset($Parts)) && (is_array($Parts))) { // RobotsCheck is array and exists
			$Parts = $Parts;
		} else {
			$Parts = false;
		}
		return array('BotAllowed' => $BotAllowed, 'Parts' => $Parts);
	}
	/////////////////////////
	// Check Robot Entries //
	/////////////////////////
	protected function CheckRobotsEntries($path, $file) {
		// Vars
		$RobotsCheck = $this->RobotsFileCheck();
		$skipnext = 0;
		$counter = 0;
		$Path = $this->PathBuild($path, $file);
		// Checking RobotsCheck
		if ((isset($RobotsCheck['Parts'])) && (is_array($RobotsCheck['Parts']))) { // RobotsCheck is array and exists
			// Check bot allowance
			if ($RobotsCheck['BotAllowed'] == 1) { //bot allowed
				// Check for parts
				if ((isset($RobotsCheck['Parts'])) && (is_array($RobotsCheck['Parts']))) { // parts is array and exists
					// Loop through Robots.txt
					foreach($RobotsCheck['Parts'] as $RobotEntry) {
						// Setting folder name
						$realRobotsfolder = str_replace('Disallow: ', '', $RobotEntry);
						$CurrentRealEntryURLDIR = 'http://' . $_SERVER['HTTP_HOST'] . '' . $realRobotsfolder;
						$CurrentEntryURLDIR = str_replace('/', '\/', 'http://' . $_SERVER['HTTP_HOST'] . '' . $realRobotsfolder);
						$CurrentEntryURLDIR = str_replace('http:', '', $CurrentEntryURLDIR);
						if (!preg_match('/^User-agent:(.*)/', $realRobotsfolder)) {
							// Seeing if it matches something we want to avoid
							if (preg_match('/^(http|https|ftp):' . $CurrentEntryURLDIR . '/i', $Path)) { // Folder
								$skipnext = 1;
								break; // Break this loop
							} else if ($CurrentRealEntryURLDIR == $Path) { // File
								$skipnext = 1;
								break; // Break this loop
							}
						}
					}
					// Check if we should skip or not
					if ($skipnext == 1) { // Skip
						return true;
					} else { // Don't skip
						return false;
					}
				} else { // no parts
					return false; // Don't skip
				}
			} else { // bot not allowed
				return true; // Skip
			}
		} else { // No robots
			return false; // Don't skip
		}
	}
	///////////////////////
	// Check text langth //
	///////////////////////
	public function CheckStringLength($str, $length = 250, $trailing = '...') {
		  // take off chars for the trailing
		  $length -= strlen($trailing);
		  if (strlen($str) > $length) {
			 // string exceeded length, truncate and add trailing dots
			 return substr($str,0,$length).$trailing;
		  } else  { 
			 // string was already short enough, return the string
			 $res = $str; 
		  }
		  return $res;
	} 
	/****************************************************/
	/* 						GETS						*/
	/****************************************************/
	///////////////////////////////
	// Get directories and files //
	///////////////////////////////
	public function GetSearchEngineData($path = '.') {
		// Global Directories to ignore when listing output
		$ignore = array('400.php', '401.php', '403.php', '403.html', '404.php', '406.php', '500.php', 'robots.txt', 'search-test.php', 'favicon.ico', '.htaccess', '.htaccess.bak', 'css', 'temp', 'admin', 'zerozone', 'templates', 'includes', 'images', 'js', '.ftpquota', '_notes', 'cgi-bin', '.', '..'); 
		// Directory Handle
		$dh = @opendir($path);
		// Open the directory to the handle $dh
		while (false !== ($file = readdir($dh))) { // Loop through the directory (hate while loops but waddyagunnado)
			// Loop variables
			$skipnext = 0;
			// Checking ignore array
			if (!in_array($file, $ignore)) { // Check that this file/folder is not to be ignored
				// Checking the URL Status
				$BuiltPath = $this->PathBuild($path, $file);
				$URLStatus = $this->URLStatus($BuiltPath);
				// Loop through Robots.txt
				if ($this->CheckRobotsEntries($path, $file) === true) {
					$skipnext = 1;
				}
				// Should we continue or skip?
				if ($skipnext == 0) {
					// show the directory tree.
					if (is_dir($path . '/' . $file)) { // Its a directory, so we need to keep reading down...
						$this->GetSearchEngineData($path . '/' . $file); // Re-call this same function but on a new directory. (recursive)
					} else if ((is_array(@getimagesize($path . '/' . $file))) && ($URLStatus['state'] == "IMAGE")) { // Images found in Directory (just reporting)
						//Skip
					} elseif ($URLStatus['state'] == "HTML") {
						// checking extension just to be extra sure
						if ((($this->GetFileExtension(strtolower($file)) == "php") or ($this->GetFileExtension(strtolower($file)) == "html") or ($this->GetFileExtension(strtolower($file)) == "htm"))) {
							// Vars
							$LinkIDSubmitted = 0; // Submitted Link ID (Start)
							$SubLinkIDSubmitted = 0;
							// Language check
							if ($this->lang == '') { // No language (all pages crawled)
								// Get page data (Static Links)
								$DataReturn = $this->GetPageData($path, $file, 0);
								$LinkIDSubmitted = $DataReturn['LinkID'];
								echo $DataReturn['return'];
								// Loop through extra images and index
								if ($this->GetImages == 1) {
									if (($DataReturn['ExtraImages'] !== false) && (is_array($DataReturn['ExtraImages']))) {
										echo $this->GetImagesFromPage($DataReturn['ExtraImages'], $LinkIDSubmitted);
									}
								}
								// Loop through extra links and return data (Dynamic Links)
								if ($this->DynamicLinks == 1) {
									if ((isset($DataReturn['return'])) && (is_array($DataReturn['ExtraLinks']))) {
										echo $this->GetDynamicLinks($path, $file, $DataReturn['ExtraLinks']);
									}
								}
								echo '<br />'; // Linebreak
							} elseif ($this->lang > '') { // Language is set (all pages crawled with '-lang' in their page file name)
								// check the file language type
								if (preg_match('/.php$/i', $path . $file)) { // file matches language
									// Get page data (Static Links)
									$DataReturn = $this->GetPageData($path, $file, 0);
									$LinkIDSubmitted = $DataReturn['LinkID'];
									echo $DataReturn['return'];
									// Loop through extra images and index
									if ($this->GetImages == 1) {
										if (($DataReturn['ExtraImages'] !== false) && (is_array($DataReturn['ExtraImages']))) {
											echo $this->GetImagesFromPage($DataReturn['ExtraImages'], $LinkIDSubmitted);
											echo '<br />'; // Linebreak
										}
									}
									// Loop through extra links and return data (Dynamic Links)
									if ($this->DynamicLinks == 1) {
										if ((isset($DataReturn['return'])) && (is_array($DataReturn['ExtraLinks']))) {
											echo $this->GetDynamicLinks($path, $file, $DataReturn['ExtraLinks']);
											echo '<br />'; // Linebreak
										}
									}
									echo '<br />'; // Linebreak
								}
							}
						}
					}
				}
			}
		}
		closedir($dh); // Close the directory handle
	}
	///////////////////////////////////
	// Get Dynamic Links (Recursive) //
	///////////////////////////////////
	protected function GetDynamicLinks($path, $file, $ExtraLinks) {
		$html = '';
		if (is_array($ExtraLinks)) {
			foreach($ExtraLinks as $DynamicLink) {
				$DataReturnDynamic = $this->GetPageData($path, $file, $DynamicLink);
				if ($DataReturnDynamic['return'] !== false) {
					$html .= $DataReturnDynamic['return'];
					$SubLinkIDSubmitted = $DataReturnDynamic['LinkID'];
					$MoreExtraLinks = $DataReturnDynamic['ExtraLinks'];
					if (is_array($DataReturnDynamic['ExtraLinks'])) {
						$html .= $this->GetDynamicLinks($path, $file, $DataReturnDynamic['ExtraLinks']); // Recursive function
					}
					// Loop through extra images and index
					if (($DataReturnDynamic['ExtraImages'] !== false) && (is_array($DataReturnDynamic['ExtraImages']))) {
						$html .= $this->GetImagesFromPage($DataReturnDynamic['ExtraImages'], $SubLinkIDSubmitted);
					}
				}
			}
		}
		return $html;
	}
	////////////////
	// Get Images //
	////////////////
	protected function GetImagesFromPage($ExtraImages, $LinkIDSubmitted) {
		foreach($ExtraImages as $ImageLink) {
			$DataReturnImages = $this->InsertUpdateIMG($LinkIDSubmitted, $ImageLink);
			if ($DataReturnImages['return'] > '') {
				return $DataReturnImages['return'];
			} else {
				return 'Image already exists.<br />';
			}
		}
	}
	/////////////////
	// Get Headers //
	/////////////////
	protected function GetHeaders($headerNum, $Body, $id) {
		// Vars
		$html = '';
		$i = 1;
		// Checking which header for weighting
		switch ($headerNum) {
			case 1:
				$weight = $this->Weight_HeaderOne;
				break;
			case 2:
				$weight = $this->Weight_HeaderTwo;
				break;
			case 3:
				$weight = $this->Weight_HeaderThree;
				break;
			case 4:
				$weight = $this->Weight_HeaderFour;
				break;
			case 5:
				$weight = $this->Weight_HeaderFive;
				break;
			default:
				$weight = $this->Weight_HeaderSix;
				break;
		}
		// Check for match.
		if (preg_match_all('#<h'.$headerNum.'(.*?)>(.*?)<\/h'.$headerNum.'>#', $Body, $matches)) { // Match HX class
			// Did the match result in an array?
			if (is_array($matches[0])) { // array
				foreach($matches[0] as $match) {
					$this->InsertUpdateKW($this->remove_HTML($match), $id, $weight);
					$html .= '<strong>H'.$headerNum.'</strong>: ' . $this->remove_HTML($match) . '<br />';
					$i++;
				}
			} else { // String
				$this->InsertUpdateKW($this->remove_HTML($matches[0]), $id, $weight);
				$html .= '<strong>H'.$headerNum.'</strong>: ' . $this->remove_HTML($matches[0]) . '<br />';
			}
			return $html;
		} else {
			return false;
		}
	}
	///////////////////
	// Get meta data //
	///////////////////
	protected function GetMetaData($html) {
		$meta = '';
		// Matching Title tag
		if (preg_match('/<title>(.*?)<\/title>/', $html, $matches)) {
			$meta['title'] = $matches[1];
		} else {
			$meta['title'] = NULL;
		}
		// Matching meta tags and creating an array
		if (preg_match_all("|<meta[^>]+name=\"([^\"]*)\"[^>]" . "+content=\"([^\"]*)\"[^>]+>|i", $html, $out, PREG_PATTERN_ORDER)) {
			// loops through meta data
			for ($i = 0; $i < count($out[1]); $i++) {
				// loop through the meta data - [add your own tags here if you need]
				if (strtolower($out[1][$i]) == "keywords") { // Keywords meta
					$meta['keywords'] = $out[2][$i];
				}
				if (strtolower($out[1][$i]) == "description") { // Description meta
					$meta['description'] = $out[2][$i];
				}
				if (strtolower($out[1][$i]) == "modified") { // Date Modified meta
					$meta['modified'] = $out[2][$i];
				}
				if (strtolower($out[1][$i]) == "subject") { // Date Modified meta
					$meta['subject'] = $out[2][$i];
				}
				if (strtolower($out[1][$i]) == "creator") { // Creator/Author meta
					$meta['author'] = $out[2][$i];
				}
				if (strtolower($out[1][$i]) == "owner") { // Creator/Author meta
					$meta['owner'] = $out[2][$i];
				}
				if (strtolower($out[1][$i]) == "review_date") { // Creator/Author meta
					$meta['review_date'] = $out[2][$i];
				}
				if (strtolower($out[1][$i]) == "issued") { // Creator/Author meta
					$meta['issued'] = $out[2][$i];
				}
				if (strtolower($out[1][$i]) == "language") { // Creator/Author meta
					$meta['language'] = $out[2][$i];
				}
			}
		}
		// Matching body
		// Sample for below if statement...matching section id or body tag
		// preg_match('/<section id="Content">(.*)<\/section>/s', $html, $matches)
		// preg_match('/<body>(.*)<\/body>/s', $html, $matches)
		if (preg_match('/<section id="Content">(.*)<\/section>/s', $html, $matches)) {
			// Limit our body
			if ($this->LimitBody > 0) { // set limit
				$Strings = new StringCheckers();
				$meta['body'] = $Strings->StringLimiter($matches[1], $this->LimitBody);
			} else { // no limit
				$meta['body'] = $matches[1];
			}
		} else { // No data found
			$meta['body'] = NULL;
		}
		return $meta;
	}
	///////////////////
	// Get page data //
	///////////////////
	private function GetPageData($path = 0, $file = 0, $FullURL = 0) {
		// Vars
		$html = '';
		$QueryLinks = array();
		$QueryImages = array();
		$LinkSet = 0;
		// Check/Set Language
		if ($this->lang > '') {
			$langsql = '_' . str_replace('-', '', $this->lang);
			$langFile = '-' . $this->lang;
		} else {
			$langsql = '';
			$langFile = '';
		}
		// Checking full URL
		if ($FullURL == '') {
			$BuiltPath = $this->PathBuild($path, $file);
			$contents = @file_get_contents($BuiltPath);
			// Filesize
			$RelPath = str_replace('http://' . $_SERVER['HTTP_HOST'] . '/', $_SERVER['DOCUMENT_ROOT'], $BuiltPath);
			$RelPath = explode('?', $RelPath);
			if ((isset($RelPath[0])) && ($RelPath[0] > '')) {
				$RelPath = $RelPath[0];
			} else {
				$RelPath = $RelPath[1];
			}
			$FileSize = $this->GetBytes(@filesize($RelPath), 2);
			if (!isset($FileSize)) {
				$FileSize = '';
			}
		} else {
			$BuiltPath = $FullURL;
			$contents = @file_get_contents($BuiltPath);
			// Filesize
			$RelPath = str_replace('http://' . $_SERVER['HTTP_HOST'] . '/', $_SERVER['DOCUMENT_ROOT'] . '/', $BuiltPath);
			$RelPath = explode('?', $RelPath);
			if ((isset($RelPath[0])) && ($RelPath[0] > '')) {
				$RelPath = $RelPath[0];
			} else {
				$RelPath = $RelPath[1];
			}
			$FileSize = $this->GetBytes(@filesize($RelPath), 2);
			if (!isset($FileSize)) {
				$FileSize = '';
			}
		}
		// Get link from db
		$KWrow = $this->GetDB->query('SELECT * FROM ' . $this->config['table_prefix'] . 'search_links' . $langsql . ' WHERE link="' . $BuiltPath . '"');
		$TotalRows = $this->GetDB->affected_rows;
		// See if link exists
		if ($TotalRows == 0) {
			// Getting meta data
			$meta = $this->GetMetaData($contents, $this->LimitBody);
			// Checking if meta is array
			if (is_array($meta)) {
				// HTML Build/Error Check with Meta filtering/limiting
				// Title
				if ((isset($meta['title'])) && ($meta['title'] > '')) { // Title set
					// Check text length
					$TitleMeta = $this->CheckStringLength($meta['title'], 195, '...');
					// Set HTML to output from crawl
					$html .= '<strong><u>' . $TitleMeta . '</u></strong><br />';
				} else {
					$TitleMeta = '';
				}
				// Link
				$html .= '<strong>Link:</strong> <span class="underline">' . $BuiltPath . '</span><br />';
				// Date Modified
				if ((isset($meta['modified'])) && ($meta['modified'] > '')) { // Modified set
					// Check text length
					$DateModified = $this->CheckStringLength($meta['modified'], 10, '');
					if (!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/', $DateModified)) {
						$DateModified = '0000-00-00'; // defaulting date for db appeasement
					}
					// Set HTML to output from crawl
					$html .= '<strong>Date Modified: </strong> ' . $DateModified . '<br />';
				} else {
					$DateModified = '0000-00-00'; // defaulting date for db appeasement
				}
				// Review Date
				if ((isset($meta['review_date'])) && ($meta['review_date'] > '')) { // Modified set
					// Check text length
					$ReviewDate = $this->CheckStringLength($meta['review_date'], 10, '');
					if (!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/', $ReviewDate)) {
						$ReviewDate = '0000-00-00'; // defaulting date for db appeasement
					}
					// Set HTML to output from crawl
					$html .= '<strong>Review Date: </strong> ' . $ReviewDate . '<br />';
				} else {
					$ReviewDate = '0000-00-00'; // defaulting date for db appeasement
				}
				// Date Issued
				if ((isset($meta['issued'])) && ($meta['issued'] > '')) { // Modified set
					// Check text length
					$DateIssued = $this->CheckStringLength($meta['issued'], 10, '');
					if (!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/', $v)) {
						$DateIssued = '0000-00-00'; // defaulting date for db appeasement
					}
					// Set HTML to output from crawl
					$html .= '<strong>Date Issued: </strong> ' . $DateIssued . '<br />';
				} else {
					$DateIssued = '0000-00-00'; // defaulting date for db appeasement
				}
				// Subject
				if ((isset($meta['subject'])) && ($meta['subject'] > '')) { // Modified set
					// Check text length
					$Subject = $this->CheckStringLength($meta['subject'], 95, '...');
					// Set HTML to output from crawl
					$html .= '<strong>Subject: </strong> ' . $Subject . '<br />';
				} else {
					$Subject = ''; // defaulting date for db appeasement
				}
				// Author
				if ((isset($meta['author'])) && ($meta['author'] > '')) { // Author set
					// Check text length
					$NewAuthor = $this->CheckStringLength($meta['author'], 240, '...');
					// Set HTML to output from crawl
					$html .= '<strong>Page Author: </strong> ' . $NewAuthor . '<br />';
				} else {
					$NewAuthor = '';
				}
				// Owner
				if ((isset($meta['owner'])) && ($meta['owner'] > '')) { // Author set
					// Check text length
					$NewOwner = $this->CheckStringLength($meta['owner'], 125, '...');
					// Set HTML to output from crawl
					$html .= '<strong>Page Author: </strong> ' . $NewOwner . '<br />';
				} else {
					$NewOwner = '';
				}
				// Description
				if ((isset($meta['description'])) && ($meta['description'] > '')) { // Description set
					// Check text length
					$DescriptionMeta = $this->CheckStringLength($meta['description'], 250, '...');
					// Set HTML to output from crawl
					$html .= '<strong>Page Description: </strong> ' . $DescriptionMeta . '<br />';
				} else {
					$DescriptionMeta = '';
				}
				// Keywords
				if ((isset($meta['keywords'])) && ($meta['keywords'] > '')) { // Keywords set
					// Set HTML to output from crawl
					$html .= '<strong>Page Keywords: </strong> ' . $meta['keywords'] . '<br />';
				} else {
					$meta['keywords'] = '';
				}
				// Language
				if ((isset($meta['language'])) && ($meta['language'] > '')) { // Keywords set
					$NewLang = $this->CheckStringLength($meta['language'], 15, '');
					// Set HTML to output from crawl
					$html .= '<strong>Page Language: </strong> ' . $NewLang . '<br />';
				} else {
					$NewLang = '';
				}
				// Body
				if ((isset($meta['body'])) && ($meta['body'] > '')) { // Content set
					// Check text length *mediumtext max = 16777215
					$meta['body'] = $this->CheckStringLength($meta['body'], 16777210, '...');
					// Set HTML to output from crawl
					//$html .= '<strong>Page Content: </strong> ' . $this->CheckBody($meta['body']) . '<br />';
				} else {
					$meta['body'] = '';
				}
				// Insert links into links db
				$BodySQL = $this->CheckBody($meta['body']);
				// Query
				$this->GetDB->query('INSERT INTO ' . $this->config['table_prefix'] . 'search_links' . $langsql . ' (link, description, title, body, author, date_modified, subject, owner, review_date, date_issued, language, pagesize) VALUES ("' . $BuiltPath . '", "' . $this->GetDB->real_escape_string(strip_tags($DescriptionMeta)) . '", "' . $this->GetDB->real_escape_string(strip_tags($TitleMeta)) . '", "' . $this->GetDB->real_escape_string($BodySQL) . '", "' .  $this->GetDB->real_escape_string(strip_tags($NewAuthor)) . '", "' . $this->GetDB->real_escape_string($DateModified) . '", "' . $this->GetDB->real_escape_string($Subject) . '", "' . $this->GetDB->real_escape_string($NewOwner) . '", "' . $this->GetDB->real_escape_string($ReviewDate) . '", "' . $this->GetDB->real_escape_string($DateIssued) . '", "' . $this->GetDB->real_escape_string($NewLang) . '", "' . $this->GetDB->real_escape_string($FileSize) . '")');
				// Inserted ID
				$InsertID = $this->GetDB->insert_id; // Inserted ID
				///////////
				// Title //
				///////////
				if ($meta['title'] > '') { // Keywords set
					// InsertUpdate Keyword
					$this->InsertUpdateKW($meta['title'], $InsertID, $this->Weight_Title);
				}
				///////////////////
				// Date Modified //
				///////////////////
				if ((isset($meta['modified'])) && ($meta['modified'] > '')) { // Keywords set
					// InsertUpdate Keyword
					$this->InsertUpdateKW($meta['modified'], $InsertID, $this->Weight_DateModified);
				}
				/////////////
				// Subject //
				/////////////
				if ((isset($meta['subject'])) && ($meta['subject'] > '')) { // Keywords set
					// InsertUpdate Keyword
					$this->InsertUpdateKW($meta['subject'], $InsertID, $this->Weight_Subject);
				}
				////////////
				// Author //
				////////////
				if ((isset($meta['author'])) && ($meta['author'] > '')) { // Keywords set
					// InsertUpdate Keyword
					$this->InsertUpdateKW($meta['author'], $InsertID, $this->Weight_Author);
				}
				/////////////////
				// Description //
				/////////////////
				if ((isset($meta['description'])) && ($meta['description'] > '')) { // Keywords set
					$newDesc = $this->CheckBody($meta['description']);
					$keywordsDescription = explode(' ', $newDesc); // Create array
					foreach($keywordsDescription as $keywordDescription) { // Loop through array
						// InsertUpdate Keyword
						$this->InsertUpdateKW($keywordDescription, $InsertID, $this->Weight_Desc);
					}
				}
				//////////////
				// Keywords //
				//////////////
				if ((isset($meta['keywords'])) && ($meta['keywords'] > '')) { // Keywords set
					// Check if data has spacial split
					$keywordArray = str_replace(", ", ",", $meta['keywords']);
					// Split keyword array
					$keywordArray = explode(',', $keywordArray);
					// Loop through keyword array (Enter Terms)
					foreach($keywordArray as $keyword) {
						// InsertUpdate Keyword
						$this->InsertUpdateKW($keyword, $InsertID, $this->Weight_Keywords);
						// Enter Split Words
						$keyword = str_replace('-', ' ', $keyword); // Replace Hiphens
						$keyword = strpos($keyword, ' '); // Split entry
						// Check keyword for space...
						if ($keyword !== false) { // space found...
							$keywordArrayFine = explode(' ', $keyword); // Create array
							foreach($keywordArrayFine as $keywordFine) { // Loop through array
								// InsertUpdate Keyword
								$this->InsertUpdateKW($keywordFine, $InsertID, $this->Weight_Keywords - 1);
							}
						}
					}
				}
				//////////
				// Body //
				//////////
				if ((isset($meta['body'])) && ($meta['body'] > '')) { // Body is set
					//Get headers from body (HTML Exists)
					// Header one with default
					$HOneChecker = $this->GetHeaders(1, $meta['body'], $InsertID); // Checking false
					if ($HOneChecker === false) {
						$html .= '<strong>H1</strong>: ' . $meta['title'] . ' <em>(H1 tag not found. Title set as replacement)</em><br />';
					} else {
						$html .= $this->GetHeaders(1, $meta['body'], $InsertID);
					}
					// Other headers (not so important)
					$html .= $this->GetHeaders(2, $meta['body'], $InsertID);
					$html .= $this->GetHeaders(3, $meta['body'], $InsertID);
					$html .= $this->GetHeaders(4, $meta['body'], $InsertID);
					$html .= $this->GetHeaders(5, $meta['body'], $InsertID);
					$html .= $this->GetHeaders(6, $meta['body'], $InsertID);
					// Check if Full URL Was entered (not collecting more then one level of page links - THIS IS PAGE LINKS LEVEL 2)
					// Check if we are getting page links (dynamic and static internal links only)
					if ($this->LinkFollowInternal == 1) { // Get page links (dynamic and static)
						// Matching <a href>
						$hrefBody = $meta['body'];
						// Matching document links (<a href="$1">lname</a>|<a href='$1'>lname</a>)
						preg_match_all('/<a(?:[^>]*)href=\"([^\"]*)\"(?:[^>]*)>(?:[^<]*)<\/a>/is', $hrefBody, $outputURL, PREG_SET_ORDER);
						// HTML - Links area title
						$html .= "<strong>Links Found:</strong><br />";
						// Check our output
						if (((isset($outputURL)) && (is_array($outputURL)) && (!empty($outputURL)))) {
							// Loop through matches
							foreach($outputURL as $item) {
								// Finding the parts we need such as the exact link
								if (preg_match('/^<a.*?href=(["\'])(.*?)\1.*$/', $item[0], $m)) {
									// Printing full link with title
									$html .= '<strong>Full Link</strong> ' . $item[0] . '<br />';
									// Setting our link
									$NewLink = $m[2];
									$RebuildLink = array();
									$counterz = 0;
									$NewLink = str_replace('&amp;', '&', $NewLink);
									$ParseLink = explode('?', $NewLink);
									$RLink = $ParseLink[0];
									//$kj = 0;
									foreach($ParseLink as $TheLink) {
										if ($counterz == 0) {
											$counterz = 1;
										} else if ($counterz == 1) {
											$RLink .=  '?' . $TheLink;
											$counterz = 2;
										} elseif ($counterz > 1) {
											$RLink .= '&' . $TheLink;
										}
										//$kj++;
										//if ($kj == 50) {
											//break;
										//}
									}
									$NewLink = $RLink;
									// Checking the url
									if (preg_match('/http:/', $NewLink)) { // This is full url
										$NewLink = $m[2];
										// Content
										$html .= '<strong>Link:</strong> ' . $NewLink . '<br />';
									} else if ($NewLink == '/') { // is link just a forward slash?
										$NewLink = $BuiltPath;
										// Content
										$html .= '<strong>Link:</strong> ' . $NewLink . '<br />';
									} else if (preg_match('/\/(.*?)\/(.*?).[a-zA-Z]{3}$/', $NewLink)) { // Extension found
										$NewLink = 'http://' . $_SERVER['HTTP_HOST'] . $NewLink;
										// Content
										$html .= '<strong>Link:</strong> ' . $NewLink . '<br />';
									} else if (preg_match('/\?/', $NewLink)) { // Question mark found/Crawler
										// Check if we want dynamic links
										if ($this->DynamicLinks == 1) { // Get dynamic links
											// Split string
											$NewLink = explode('?', $NewLink);
											// Rebuild path
											if ((isset($NewLink[0])) && ($NewLink[0] > '')) { // Check if path exists before the query
												$NewLink = 'http://' . $_SERVER['HTTP_HOST'] . $NewLink[0] . '?' . $NewLink[1];
											} else { // No path existed, grab current URL we are parsing
												$NewLink =  $BuiltPath . '?' . $NewLink[1];
												$RBLink = array();
												$counterx = 0;
												$NewLink = str_replace('&amp;', '&', $NewLink);
												$PrsLink = explode('?', $NewLink);
												$RBsLink = $PrsLink[0];
												//$kj = 0;
												foreach($ParseLink as $DynLink) {
													if ($counterx == 0) {
														$counterx = 1;
													} else if ($counterx == 1) {
														$RBsLink .=  '?' . $DynLink;
														$counterx = 2;
													} elseif ($counterx > 1) {
														$RBsLink .= '&' . $DynLink;
													}
													//$kj++;
													//if ($kj == 50) {
														//break;
													//}
												}
												$NewLink = $RBsLink;
											}
											// Only want one level of dynamic links
											// Building extra link array
											$QueryLinks[] = $NewLink;
											// Content
											$html .= '<strong>Link:</strong> ' . $NewLink . '<br />';
										} else {
											$html .= '<em>Dynamic links indexing disabled.</em><br />';
										}
									} else { // Just a folder
										$NewLink = 'http://' . $_SERVER['HTTP_HOST'] . $m[2] . 'index' . $langFile . '.php';
										$html .= '<strong>Link:</strong> ' . $NewLink . '<br />';
									}
								}
							}
						} else {
							$html .= '<em>Link(s) not found.</em><br />';
						}
					} else { // No links search allowed
						$html .= '<em>Internal link search disabled.</em><br />';
					}
					// Check if we are getting page images
					if ($this->GetImages == 1) { // Get page images
						// Matching images
						$imgBody = $meta['body'];
						preg_match_all('/<img(?:[^>]*)src=\"([^\"]*)\"(?:[^>]*) \/>/is', $imgBody, $outputIMGs, PREG_SET_ORDER);
						// HTML - Links area title
						$html .= "<strong>Images Found:</strong><br />";
						// Check our output
						if (((isset($outputIMGs)) && (is_array($outputIMGs)) && (!empty($outputIMGs)))) {
							// Loop through matches
							foreach($outputIMGs as $img) {
								// Finding the parts we need such as the exact image link
								if (preg_match('/^<img.*?src=(["\'])(.*?)\1.*$/', $img[0], $image)) {
									// Printing full image with title with html
									//$html .= '<strong>Full Image Link</strong> ' . $img[0] . '<br />';
									// Setting our link
									$NewIMG = $image[2];
									// Checking the url
									if (preg_match('/http:/', $NewIMG)) { // This is full image url
										$NewIMG = $image[2];
									} else if ($NewIMG == '/') { // is link just a forward slash?
										$NewIMG = $BuiltPath;
									} else if (preg_match('/\/(.*?)\/(.*?).[a-zA-Z]{3}$/', $NewIMG)) { // Extension found
										$NewIMG = 'http://' . $_SERVER['HTTP_HOST'] . $NewIMG;
									} else { // Just a folder
										$NewIMG = 'http://' . $_SERVER['HTTP_HOST'] . $image[2] . 'index' . $langFile . '.php';
									}
									$html .= '<strong>Image:</strong> ' . $NewIMG . '<br />';
									// Building extra link array
									$QueryImages[] = $NewIMG;
									$LinkSet = 1;
								}
							}
						} else {
							$html .= '<em>Images(s) not found.</em><br />';
						}
					} else { // No image search allowed
						$html .= '<em>Image search disabled.</em><br />';
					}
					// Set new body (HTML stripped)
					$newBody = $this->CheckBody($meta['body']);
					// Split Keywords off body
					$keywordArrayFine = explode(' ', $newBody); // seperate words into array
					// Pretty sure we got some real words here...
					foreach($keywordArrayFine as $keywordFine) {
						// InsertUpdate Keyword
						$this->InsertUpdateKW($keywordFine, $InsertID, $this->Weight_WordBody);
					}
					// Check QueryImage Array
					if ($LinkSet != 1) {
						$QueryImages = false;
					}
					$html .= '<br />';
					// Return
					return array('return' => $html, 'ExtraLinks' => $QueryLinks, 'ExtraImages' => $QueryImages, 'LinkID' => $InsertID);
				} else {
					return  array('return' => $html, 'ExtraLinks' => false, 'ExtraImages' => false, 'LinkID' => false);
				}
			} else {
				return  array('return' => false, 'ExtraLinks' => false, 'ExtraImages' => false, 'LinkID' => false);
			}
		}
	}
	/****************************************************/
	/* 						INSERTS						*/
	/****************************************************/
	//////////////////////////////////////////////////
	// 			   InsertUpdate Keyword				//
	//////////////////////////////////////////////////
	protected function InsertUpdateKW($keyword, $InsertID, $weight = 1) {
		// Check Language
		if ($this->lang > '') {
			$langsql = '_' . str_replace('-', '', $this->lang);
		} else {
			$langsql = '';
		}
		// Checking InsetID
		if (!isset($InsertID)) {
			$InsertID = 0;
		} else if (!is_numeric($InsertID)) {
			$InsertID = 0;
		} else if ($InsertID == '') {
			$InsertID = 0;
		}
		// Checking...
		if ($InsertID > 0) {
			// Checking keyword
			if ((isset($keyword)) && ($keyword > '')) {
				// Cleanse keyword
				$keyword = strtolower($keyword); // lower case keyword
				$keyword = preg_replace("/&?[a-z0-9]{2,8};/i"," ", $keyword); // remove entities
				$keyword = preg_replace('/[^\p{L}\p{N}- &#%\*\/\\\$\'@\(\)\{\}\[\]\s]/', '$1' . ' ', $keyword); // characters to allow
				$keyword = preg_replace('/[\r\n\s\t]+/xms', ' ', trim($keyword)); // remove excess whitespace/tabs
				// Check if we should stop (default 0)
				$stopproc = 0;
				// Check if the keyword is just an integer
				if ($keyword == '0000-00-00') { // date modified is default
					$stopproc = 1;    // Stop
				} else if (is_int($keyword)) { // it's an integer
					$stopproc = 1;    // Stop
				} else if ($this->Search->StopWords($keyword) === true) { // Check stop words
					$stopproc = 1;    // Stop
				} else if ($this->CheckWord($keyword) === false) { // Check word method
					$stopproc = 1;    // Stop
				}
				// Check stop process
				if ($stopproc == 0) {
					// Check if we got multiples one last time
					$keyword = explode(" ", $keyword);
					// Loop through values
					foreach ($keyword as $kword) {
						// Cleanse keyword
						$kword = strtolower($kword); // lower case keyword
						$kword = preg_replace("/&?[a-z0-9]{2,8};/i"," ", $kword); // remove entities
						$kword = preg_replace('/[^\p{L}\p{N}- &#%\*\/\\\$\'@\(\)\{\}\[\]\s]/', '$1' . ' ', $kword); // characters to allow
						$kword = preg_replace('/[\r\n\s\t]+/xms', ' ', trim($kword)); // remove excess whitespace/tabs
						// Check if we should stop (default 0)
						$stopproc2 = 0;
						// Check if the keyword is just an integer
						if (is_int($kword)) { // it's an integer
							$stopproc2 = 1;    // Stop
						} else if ($this->Search->StopWords($kword) === true) { // Check stop words
							$stopproc2 = 1;    // Stop
						} else if ($this->CheckWord($kword) === false) { // Check word method
							$stopproc2 = 1;    // Stop
						}
						// Check stop process
						if ($stopproc2 == 0) {
							// Getting first character from hexadecimal conversion
							$HEXWordFirstChar = substr(md5($kword), 0, 1);
							// Find keyword (Description: joining 3 tables together to determine if the entry exists)
							$QsKeywordAndLink = 'SELECT 
												' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.lid, 
												' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.weight 
												FROM ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . ' 
												LEFT JOIN ' . $this->config['table_prefix'] . 'search_links' . $langsql . ' 
												ON ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.lid = ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.lid 
												WHERE keyword = "' . $this->GetDB->real_escape_string($kword) . '" 
												AND ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.lid = ' . $InsertID . ' ';
							$KWrow = $this->GetDB->query($QsKeywordAndLink);
							$TotalRows = $this->GetDB->affected_rows;
							///////////////////////////////
							// Check Rows From Data Pull //
							///////////////////////////////
							if ($TotalRows == 0) { // Enter this keyword for the first time
								// Query
								$query = $this->GetDB->query('INSERT INTO ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . ' (lid, keyword, weight) VALUES (' . $InsertID . ', "' . $this->GetDB->real_escape_string($kword) . '", ' . $weight . ')');
								////////////////
								// Stem Entry //
								////////////////
								if ($this->PorterStemming == 1) {
									// Stemming the keyword
									$kword = $this->Stem->Stem($kword);
									// Getting first character from hexadecimal conversion
									$HEXStemWordFirstChar = substr(md5($kword), 0, 1);
									// Checking stop words
									if ($this->Search->StopWords($kword) === false) {
										// Query
										$QsKeywordAndLinkSec = 'SELECT 
												' . $this->config['table_prefix'] . 'search_keyword' . $HEXStemWordFirstChar . '' . $langsql . '.lid, 
												' . $this->config['table_prefix'] . 'search_keyword' . $HEXStemWordFirstChar . '' . $langsql . '.weight 
												FROM ' . $this->config['table_prefix'] . 'search_keyword' . $HEXStemWordFirstChar . '' . $langsql . ' 
												LEFT JOIN ' . $this->config['table_prefix'] . 'search_links' . $langsql . ' 
												ON ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.lid = ' . $this->config['table_prefix'] . 'search_keyword' . $HEXStemWordFirstChar . '' . $langsql . '.lid 
												WHERE keyword = "' . $this->GetDB->real_escape_string($kword) . '" 
												AND ' . $this->config['table_prefix'] . 'search_keyword' . $HEXStemWordFirstChar . '' . $langsql . '.lid = ' . $InsertID . ' ';
										$KWStemRow = $this->GetDB->query_first($QsKeywordAndLinkSec);
										$TotalStemRows = $this->GetDB->affected_rows;
										// Find stemmed keyword
										if ($TotalStemRows == 0) { // Enter this stemmed keyword for the first time
											// Query
											$this->GetDB->query('INSERT INTO ' . $this->config['table_prefix'] . 'search_keyword' . $HEXStemWordFirstChar . '' . $langsql . ' (lid, keyword, weight) VALUES (' . $InsertID . ', "' . $this->GetDB->real_escape_string($kword) . '", ' . $weight . ')');
										} else { // Update stemmed counter
											if (isset($KWStemRow['keyword'])) {
												// Updating the stemmed count
												$StemWeight = $KWStemRow['weight'] + $weight;
												$query = $this->GetDB->query('UPDATE ' . $this->config['table_prefix'] . 'search_keyword' . $HEXStemWordFirstChar . '' . $langsql . ' SET weight="' . $StemWeight . '" WHERE keyword="' . $this->GetDB->real_escape_string($KWStemRow['keyword']) . '" AND lid=' . $KWStemRow['lid'] . '');
											}
										}
									}
								}
							} else { // Update counter
								if ((isset($KWrow->keyword)) && (isset($KWrow->lid))) {
									// Updating the count
									$NewWeight = $KWrow['weight'] + $weight;
									$query = $this->GetDB->query('UPDATE ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . ' SET weight=' . $NewWeight . ' WHERE keyword="' . $this->GetDB->real_escape_string($KWrow['keyword']) . '" AND lid=' . $KWrow['lid'] . '');
									// Check the query
									if ($query) {
										return true; // query succeeded
									} else {
										return false; // query failed
									}
								} else {
									// Check the query
									return false; // query failed
								}
							}
						} else {
							return false;
						}
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	//////////////////////////////////////////////////
	//				Insert/Update Image			    //
	//////////////////////////////////////////////////
	protected function InsertUpdateIMG($LinkIDSubmitted, $img) {
		// Vars
		$BuiltLinkPath = '';
		// Check
		if ($this->CheckWord($img) === true) {			
			// Check/Set language
			if ($this->lang > '') {
				$langsql = '_' . str_replace('-', '', $this->lang);
			} else {
				$langsql = '';
			}
			// Getting Image information for dumping to DB
			$ImageInfo = getimagesize($img);
			$imgPageLink = $BuiltLinkPath;
			// Image type info
			if (isset($ImageInfo[2])) { 			// Image type set
				// Image types
				if ($ImageInfo[2] == 1) {			// GIF
					$imgType = 'GIF';
				} elseif ($ImageInfo[2] == 2) {		// JPEG
					$imgType = 'JPG';
				} elseif ($ImageInfo[2] == 3) {		// PNG
					$imgType = 'PNG';
				} elseif ($ImageInfo[2] == 4) {		// WBMP
					$imgType = 'WBMP';
				} elseif ($ImageInfo[2] == 5) {		// XPM???
					$imgType = 'XPM';
				}
			} else {
				$imgType = '';
			}
			// Mime type
			if (isset($ImageInfo['mime'])) {		// Mime-type
				$imgMimeType = $ImageInfo['mime'];
			} else {
				$imgMimeType = '';
			}
			// Width
			if (isset($ImageInfo[0])) {				// Width
				$imgWidth = $ImageInfo[0];
			} else {
				$imgWidth = '';
			}
			// Height
			if (isset($ImageInfo[1])) {
				$imgHeight = $ImageInfo[1];
			} else {
				$imgHeight = '';
			}
			// Bits
			if (isset($ImageInfo['bits'])) {
				$imgBits = $ImageInfo['bits'];
			} else {
				$imgBits = '';
			}
			// Channels
			if ((isset($ImageInfo['channels'])) && ($ImageInfo['channels'] == 3)) { 			// RGB
				$imgChannels = 'RGB';
			} elseif ((isset($ImageInfo['channels'])) && ($ImageInfo['channels'] == 4)) {		// CMYK
				$imgChannels = 'CMYK';
			} else {																			// None
				$imgChannels = '';
			}
			// Finding images page link to see if it exists in the database
			$Linkrow = $this->GetDB->query('SELECT * FROM ' . $this->config['table_prefix'] . 'search_links' . $langsql . ' WHERE lid=' . $LinkIDSubmitted . '');
			$TotalLinkRows = $this->GetDB->affected_rows;
			// Check if links exists
			if ($TotalLinkRows > 0) { // link exists
				// Find img
				$IMGrow = $this->GetDB->query('SELECT * FROM ' . $this->config['table_prefix'] . 'search_images WHERE link="' . $this->GetDB->real_escape_string($img) . '" AND lid=' . $LinkIDSubmitted . '');
				$TotalRows = $this->GetDB->affected_rows;
				///////////////////////////////
				// Check Rows From Data Pull //
				///////////////////////////////
				if ($TotalRows == 0) { // Enter this image for the first time
					// Query
					$qs = 'INSERT INTO ' . $this->config['table_prefix'] . 'search_images (lid, link, type, `mime-type`, width, height, bits, channels) VALUES (' . $LinkIDSubmitted . ', "' . $this->GetDB->real_escape_string($img) . '", "' . $this->GetDB->real_escape_string($imgType) . '", "' . $this->GetDB->real_escape_string($imgMimeType) . '", ' . $this->GetDB->real_escape_string($imgWidth) . ', ' . $this->GetDB->real_escape_string($imgHeight) . ', ' . $this->GetDB->real_escape_string($imgBits) . ', "' . $this->GetDB->real_escape_string($imgChannels) . '")';
					// Query
					$this->GetDB->query($qs);
					// Check the query
					return array('return' => '<strong>Image Added:</strong> ' . $img . '<br />'); // query succeeded
				} else {
					return array('return' => ''); // failed validation
				}
			} else {
				return array('return' => ''); // failed validation
			}
		} else {
			return array('return' => ''); // failed validation
		}
	}
}
?>
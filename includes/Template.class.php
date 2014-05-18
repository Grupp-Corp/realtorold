<?php
/**
 * This is the main parent class that drives the entire CMS the TemplateHelper and TemplateDesign Classes are also main children components
 * @name CCTemplate
 * @example No methods in this class can be called directly
 * @author Steven Scharf
 * @copyright (c) 2012, Steven Scharf
 */
class CCTemplate 
{
	// LIVE CONFIG
    const CONFIG_FILE = '/Applications/XAMPP/htdocs/CMSConfig/Version 2.5.ini';
    protected $config = array(
        'debug' => false,
        'session_prefix' => "dd_",
        'server_folder_path' => "/Applications/XAMPP/htdocs/",
        'include_path' => "/Applications/XAMPP/htdocs/includes/",
        'php_plugins_folder' => "/Applications/XAMPP/htdocs/includes/plugins/",
        'main_images_location' => "/images/",
        'main_images_plugin_location' => "/images/plugins/",
        'main_css_location' => "/css/",
        'main_css_plugin_location' => "/css/plugins/",
        'main_js_location' => "/js/",
        'main_js_plugin_location' => "/js/plugins/",
        'template_directory' => "/templates/",
        'template_name' => "responsive",
        'template_folder' => "responsive/",
        'WebSiteTitle' => "Cliqable",
		'WebSiteDomain' => "myrealtorcliq.com",
        'site_absolute_url' => "/",
        'index_file' => "index.php",
        'db_type' => "MySQL",
        'db_host_name' => "localhost",
        'db_name' => "canuckc_cliqable",
        'table_prefix' => "cms_",
        'db_username' => "canuckc_sscharf",
        'db_password' => "cNZywTbX@STEVE"
    );
    // Constant
   	//const CONFIG_FILE = 'M:/Development/DaDaCliq/Development/CMSConfig/Version 2.5.ini';
    /**
    * Method Variables
    * @access static
    */  
    /* Config Array with some defaults (CONFIG_FILE if successfully loaded overrides these values) */
    /*protected $config = array(
        'debug' => false,
        'session_prefix' => "dd_",
        'server_folder_path' => "M:/Development/DaDaCliq/Development/",
        'include_path' => "M:/Development/DaDaCliq/Development/includes/",
        'php_plugins_folder' => "M:/Development/DaDaCliq/Development/includes/plugins/",
        'main_images_location' => "/images/",
        'main_images_plugin_location' => "/images/plugins/",
        'main_css_location' => "/css/",
        'main_css_plugin_location' => "/css/plugins/",
        'main_js_location' => "/js/",
        'main_js_plugin_location' => "/js/plugins/",
        'template_directory' => "/templates/",
        'template_name' => "responsive",
        'template_folder' => "responsive/",
        'WebSiteTitle' => "DaDaCliq",
		'WebSiteDomain' => "dadacliq",
        'site_absolute_url' => "/",
        'index_file' => "index.php",
        'db_type' => "MySQL",
        'db_host_name' => "localhost",
        'db_name' => "canuckc_dadacliq",
        'table_prefix' => "cms_",
        'db_username' => "canuckc_sscharf",
        'db_password' => "oem429opmi@STEVE"
    );*/
    /* Error Related */
    protected $CCCMS = '2.5';
    protected $appError;
    protected $appErrorMessage;
    /* Log Related */
    protected $appLog;
    /* Database Related */
    protected $db_conn;
    /* Template/URL related */
    protected $URLSelected = 'index.php';
    /* checkFile() protected Vars */
    protected $fileType;
	protected $fileExists = 0;
	protected $subSite = 0;
    /**
    * Method Variables
    * @access public
    */  
    /* Content Return Related */
    public $contentReturn;
    /* Template Related */
    public $template = 'responsive';
    public $WebSiteTitle = '';
    /* Initialization */
    public $urlParts = '';
    public $urlExtraParts = '';
    public $templateArray = array();
    public $SelectedTemplate = '';
    public $TemplateDirectory = '';
    public $TemplatePath;
    public $JSDirectory = '';
    public $CSSDirectory = '';
    public $JSPluginsLocation;
    /* checkFile() public Vars */
    public $SiteTemplateDir = 'main';
	public $fileReturn = '';
    public $PageTitle = 'Home';
	public $SessionPrefix = '';
    /**
    * Class Methods
    * @access protected
    */ 
    /**
    * __construct   Connects to the DB, calls the URLPartBuilder Method
    * @access protected
    * 
    * @throws new CMSException
    * @return true/false
    */ 
    public function __construct() {
        try {
            if (self::dbConn()) {
                $this->URLPartBuilder();
                return true;
            } else {
                return false;
            }
        } catch (CMSException $e) {
            echo $e;
        }
    }
    /**
    * getINI   Checks for and parses INI into an array
    * @access protected
    * 
    * @throws CMSException Error
    * @return true/false
    */ 
    protected function getINI() {
        // Get INI
        if (is_file(self::CONFIG_FILE)) {
            $ini_data = parse_ini_file(self::CONFIG_FILE, __CLASS__);
            // Load INI into variable
            $this->config = array_merge($this->config, $ini_data);
			$this->config['templates_path'] = $this->config['template_directory'] . '/' . $this->config['template_name'] . '/' . $this->config['template_folder'];
			$this->SessionPrefix = $this->config['session_prefix'];
            if (isset($this->config) && is_array($this->config) && count($this->config)) {
                $this->appError = 0;
                $this->appLog = 'Config found.';
                return true;
            } else {
                $this->appError = 1;
                $this->appErrorMessage = 'The config was invalid.';
                throw new CMSException("Fatal Error.");
                return false;
            }
        } else {
            $this->appError = 1;
            $this->appErrorMessage = 'The config was not found.';
            throw new CMSException("Fatal Error.");
            return false;
        }
    }
	/**
    * getPublicVariables   Passes public variable to the application
    * @access public
    * 
    * @throws CMSException Error
    * @return array
    */ 
	public function getPublicVariables() {
		try {
			$pubConfig = array();
			$pubConfig['admin_access_folder'] = $this->config['admin_access_folder'];
			$pubConfig['main_images_location'] = $this->config['main_images_location'];
			$pubConfig['main_images_plugin_location'] = $this->config['main_images_plugin_location'];
			$pubConfig['main_css_location'] = $this->config['main_css_location'];
			$pubConfig['main_css_plugin_location'] = $this->config['main_css_plugin_location'];
			$pubConfig['main_js_location'] = $this->config['main_js_location'];
			$pubConfig['main_js_plugin_location'] = $this->config['main_js_plugin_location'];
			$pubConfig['template_directory'] = $this->config['template_directory'];
			$pubConfig['template_folder'] = $this->config['template_folder'];
			$pubConfig['index_file'] = $this->config['index_file'];
			$pubConfig['site_absolute_url'] = $this->config['site_absolute_url'];
			return $pubConfig;
		} catch (CMSException $e) {
            echo $e;
        }
	}
    /**
    * dbConn   Connects to the DB
    * @access protected
    * 
    * @throws CMSException Error
    * @return true/false
    */ 
    protected function dbConn() {
        // Get the INI
        if (self::getINI()) {
            // Check DB Type to connect to
            if ($this->config['db_type'] == "MySQLi") {
                $this->appError = 1;
                $this->appErrorMessage = 'Database connection error. MySQLi not available.';
                throw new CMSException("Fatal Error.");
                return false;
            } else {
                $this->db_conn = DBMySQLi::SQL($this->config['db_host_name'], $this->config['db_username'], $this->config['db_password'], $this->config['db_name']);
                if ($this->db_conn) {
                    // connected to the server 
                    $this->appError = 0;
                    $this->appLog = 'Connected.';
                    return true;
                } else {
                    $this->appError = 1;
                    $this->appErrorMessage = 'Database connection error.';
                    throw new CMSException("Fatal Error.");
                    return false;
                }
            }
        } else {
            $this->appError = 1;
            $this->appLog = 'Configuration error.';
            throw new CMSException("Fatal Error.");
            return false;
        }
    }
    /**
    * URLPartBuilder   URLPartBuilder Array Builder from $_GET['url']
    * @access protected
    * 
    * @throws None
    * @return $this->urlParts Array
    */ 
    protected function URLPartBuilder() {
        // Check if URL is set
        if (isset($_GET['url']) && $_GET['url'] > '') {
            $this->URLSelected = $_GET['url']; // Cleansing needed
            $urlParts = explode('/', $_GET['url']);
            foreach ($urlParts as $urlPartReset) {
                if (isset($urlPartReset) && $urlPartReset > '') {
                    $this->urlParts[] = $urlPartReset;
                }
            }
        } else {
            $this->urlParts[] = array(0 => '/');
        }
        // Extra Parts from Get Queries
        if (isset($_GET['category']) && $_GET['category'] > '') {
            $this->urlExtraParts[] = $_GET['category'];
        }
        if (isset($_GET['title']) && $_GET['title'] > '') {
            $this->urlExtraParts[] = $_GET['title'];
        }
        if (isset($_GET['song']) && $_GET['song'] > '') {
            $this->urlExtraParts[] = $_GET['song'];
        }
    }
    /**
    * readMyLine   Read a line from the file
    * @access static
    * 
    * @param (int) $lineNum line number of file
    * @param (string) $handle file handler
    * @param (int) $length length of file to be read from handler
    * @throws None
    * @return string read line
    */
    protected static function readMyLine($lineNum, $handle, $length = 1024) {
		$line = false;
		while($lineNum-- && !feof($handle)) {
			$line = fgets($handle, $length);
		}
		return (-1 !== $lineNum) ? false : $line;
	}
    /**
    * filehandle   File handler
    * @access protected
    * 
    * @param (string) $directory directory of the file (relative)
    * @param (string) $file the file within the directory
    * @param (int) $lineNumber the line number to read from the file
    * @param (int) $stripPHPWrap strip PHP wrapper and grab $PageTitle
    * @param (int) $useFileName make the title of the page the file name 1 = FileName, 2 = Folder Name, 3 = Read First Line
    * @throws None
    * @return string
    */ 
	public function filehandle($directory = '', $file = '', $lineNumber = 1, $stripPHPWrap = 0, $useFileName = 2) {
        // Check if this has a directory and file
        if ($directory == '' && $file == '') {
            if (!empty($this->urlParts) && count($this->urlParts)) {
                $i = 0;
                foreach ($this->urlParts as $url) {
                    if (!is_array($url)) {
                        if ($i == 0) {
                            $urlBuild = $url;
                        } else {
                            $urlBuild .= '/' . $url;
                        }
                    }
                }
                if (!isset($urlBuild)) {
                    $urlBuild = '';
                }
            } else {
                $urlBuild = '';
            }
            $directory = $this->config['template_directory'] . '/' . $this->config['template_name'] . '/' . $this->config['template_folder'] . '/' . $urlBuild;
            $file = $this->urlParts[count($this->urlParts)-1];
            if (is_array($file)) {
                $file = '';
            }
            $fileExtInfo = pathinfo($directory . $file, PATHINFO_EXTENSION);
            if (isset($fileExtInfo) && $fileExtInfo == 'php') {
                $this->fileType = '.php';
            } else {
                $file = 'index.php';
            }
        }
        // Check the directory
        if (!isset($directory)) {
			$directory = '';
		} else {
			if ($directory > '') {
				$directory = $directory . '/';
			} else {
				$directory = '';
			}
		}
        // Setting the path
		$path = $directory . '' . $file;
		$path = str_replace('//', '/', $path);
        // Grab file for reading and return the file handler
		$fileHandle = @fopen($path, 'r');
		if (!$fileHandle) {
			return '';
		}
        // Check if we should strip PHP wrap
        if ($stripPHPWrap == 1) {
            $val = $this->readMyLine($lineNumber, $fileHandle);
            $PageTitle = str_replace('\'; ?>', '', str_replace('<?php $PageTitle = \'', '', $val));
			$PageTitle = str_replace('\';', '', str_replace('$PageTitle = \'', '', $val));
            if(preg_match('/^[a-zA-Z0-9-_ \']/', $PageTitle)) {
                return $PageTitle;
            } else {
                if ($useFileName == 1) {
                    $NewFileName = explode('.', $file);
					if (isset($NewFileName) && $NewFileName > '' && preg_match('/^[a-zA-Z0-9-_ \']/', $PageTitle)) {
                    	return ucfirst($NewFileName);
					} else {
						$NewFileName = $this->urlParts[count($this->urlParts)-1];
						return ucfirst($NewFileName);
					}
                } else {
                    $NewFileName = $this->urlParts[count($this->urlParts)-1];
                    return ucfirst($NewFileName);
                }
            }
        } else {
            if ($useFileName == 1) {
                $NewFileName = explode('.', $file);
                return ucfirst($NewFileName);
            } else if ($useFileName == 2) {
                if (!is_array($this->urlParts[count($this->urlParts)-1])) {
                    $NewFileName = $this->urlParts[count($this->urlParts)-1];
                    if (!isset($NewFileName) || $NewFileName == '') {
                        $NewFileName = 'Home';
                        return ucfirst($NewFileName);
                    } else {
                        $NewFileName = str_replace('_', ' ', $NewFileName);
                        $NewFileName = str_replace('-', ' ', $NewFileName);
                        $NewFileNameSplit = explode(' ', $NewFileName);
                        $NewFileName = '';
                        foreach ($NewFileNameSplit as $FName) {
                            $NewFileName .= ucfirst($FName);
                        }
                        if (is_array($this->urlExtraParts)) {
                            foreach ($this->urlExtraParts as $Xtras) {
                                if (!is_array($Xtras)) {
                                    $Xtras = str_replace('_', ' ', $Xtras);
                                    $Xtras = str_replace('-', ' ', $Xtras);
                                    $XtrasSplit = explode(' ', $Xtras);
                                    $Xtras = '';
                                    foreach ($XtrasSplit as $FName) {
                                        $Xtras .= ' ' . ucfirst($FName);
                                    }
                                    if (trim($Xtras) == '') {
                                        $Xtras = 'Home';
                                    }
                                    $NewFileName .= ' - ' . $Xtras;
                                }
                            }
                        }
                        return ucfirst($NewFileName);
                    }
                } else {
                    $NewFileName = 'Home';
                    return ucfirst($NewFileName);
                }
            } else {
                $val = self::readMyLine($lineNumber, $fileHandle);
                return $val;
            }
        }
	}
    /**
    * checkSubSite   Checks for subsite in DB
    * @access protected
    * 
    * @throws None
    * @return array('result' => $sqlFirstRow['url'], 'success' => true|false);
    */ 
    protected function checkSubSite($url) {
        // Get url and strip index file
        $url = str_replace('index.php', '', $url);
        $urlArray = explode('/', $url); // Split url into parts
        $FinalURL = $urlArray[0]; // Grab only first part of the url
        // SQL Grab
        $sql = 'SELECT sub_site_title, url FROM ' . $this->config['table_prefix'] . 'websites WHERE url = "' . $FinalURL . '" AND active = 1';
        $query = $this->db_conn->query($sql);
        $TotalRows = $this->db_conn->affected_rows;
        // Check rows
        if ($TotalRows > 0) {
            while ($QueryRows = $query->fetch_array(MYSQLI_ASSOC)) {
                $row[] = $QueryRows;
            }
            return array('result'=>$row[0]['url'], 'sitetitle'=>$row[0]['sub_site_title'], 'success' => true);
        } else {
            return array('result' => '', 'success' => false);
        }
    }
    /**
    * GetTemplate   Gets the required template
    * @access protected
    * 
    * @param (string) URLSet
    * @param (int) filetype 1 = within template framework, <>1 = straight call
    * @throws None
    * @return array(1) OR true/false(<>1)
    */ 
	protected function GetTemplate($URLSet = '', $filetype = 1) {
        // Check URLSet
        if ($URLSet == '') {
            $URLSet = $this->URLSelected;
        }
        // What type of file check are we doing
        if ($filetype == 1) { // CMS File Check
            // Check for sub site selection
            $SubSiteCheck = $this->checkSubSite($URLSet);
            if ($SubSiteCheck['success']) {
                $this->subSite = 1;
                $this->WebSiteTitle = $SubSiteCheck['sitetitle'];
                $this->SiteTemplateDir = $SubSiteCheck['result'];
                $this->config['template_name'] = $this->SiteTemplateDir;
                $URLSet = str_replace($this->SiteTemplateDir, '', $URLSet);
                $this->TemplatePath = $this->config['server_folder_path'] . $this->config['template_directory'] . '/' . $this->config['template_name'] . '/' . $this->config['template_folder'] . '/';
            } else {
                $this->WebSiteTitle = $this->config['WebSiteTitle'];
                $this->subSite = 0;
                $this->TemplatePath = $this->config['server_folder_path'] . $this->config['template_directory'] . '/' . $this->config['template_name'] . '/' . $this->config['template_folder'] . '/';
            }
            // Files to check
            $fileExtInfo = pathinfo($this->TemplatePath . $URLSet, PATHINFO_EXTENSION);
            // Get extensions
            if (isset($fileExtInfo) && $fileExtInfo == 'php') {
                    $this->fileType = '.php';
            } else {
                    $this->fileType = '.php';
                    $URLSet = $URLSet . '/index.php';
            }
            // Check if file exists
            if (file_exists($this->TemplatePath . $URLSet)) {
                    $this->fileExists = 1;
                    $this->URLSelected = $this->TemplatePath . $URLSet;
            } else {
                    $this->fileExists = 0;
            }
            // Get page title
            $this->PageTitle = $this->filehandle($this->TemplatePath, $URLSet, 2, 1, 1);
            // Defined global variables from Config
            $this->SelectedTemplate     =   $this->config['server_folder_path'] . $this->config['template_directory'] . '/' . $this->config['template_name'] . '/';
            $this->TemplateDirectory    =   $this->config['server_folder_path'] . '/' . $this->config['template_directory'] . '/';
            $this->JSDirectory          =   $this->config['server_folder_path'] . '/' . $this->config['main_js_location'] . '/';
            $this->JSPluginsLocation    =   $this->config['server_folder_path'] . '/' . $this->config['main_js_plugin_location'] . '/';
            $this->CSSDirectory         =   $this->config['server_folder_path'] . '/' . $this->config['main_css_location'] . '/';
            $this->CSSPluginsLocation   =   $this->config['server_folder_path'] . '/' . $this->config['main_css_plugin_location'] . '/';
        } else { // Straight file check
            // Check if the file exists
            if (file_exists($URLSet)) {
                return true;
            } else {
                return false;
            }
        }
	}
    /**
    * ParseTemplate   Grab an file and make it a variable and pass php variables
    * @access protected
    * 
    * @param string $file Filename
    * @param array $VarsToPass array to pass variable(s) into include
    * @param integer $filetype What type of file this is (1 to 6)
    * @param integer $wrapJS wrap file with js script tag
    * @param integer $wrapCSS wrap file with css style tag
    * @throws new CMSException
    * @return include content into echoed variable
    */ 
    protected function ParseTemplate($file, $VarsToPass = array(), $filetype = 0, $wrapJS = 0, $wrapCSS = 0) {
        // Start
        ob_start();
        // Get/Extract paramaters array
        if (is_array($VarsToPass)) {
            extract($VarsToPass);
        }
		// Session Start
		if(!isset($_SESSION)) {
			session_start();
		}
        /*
         * FileTypes
         * 1 - Selected Template
         * 2 - Template Directory
         * 3 - JS Directory
         * 3.5 - JS Plugins Location
         * 4 - CSS Directory
         * 4.5 - CSS Plugins Location
         * 5 or 0 - Straight file call (follows ini_set in php.ini)
         * 6 - Inlcude Path (From Config)
         * other - Exception Error
         * 
         */
        if ($filetype == 1) { // Current Selected Template
            $finaleFile = $this->SelectedTemplate . $file;
        } else if ($filetype == 2) { // Current Selected Template's Location
            $finaleFile = $this->TemplateDirectory . $file;
        } else if ($filetype == 3) { // Current JavaScript Location
            $finaleFile = $this->JSDirectory . $file;
        } else if ($filetype == 3.5) { // Current JavaScript Plugin Location
            $finaleFile = $this->JSPluginsLocation . $file;
        } else if ($filetype == 4) { // Current CSS Location
            $finaleFile = $this->CSSDirectory . $file;
        } elseif ($filetype == 4.5) { // Current CSS Plugin Location
            $finaleFile = $this->CSSPluginsLocation . $file;
        } else if ($filetype == 5 || $filetype == 0) { // Straight file call without a prefix
            $finaleFile = $file;
        } else if ($filetype == 6) { // include file (based on include_path in config)
            $finaleFile = $this->config['include_path'] . $file;
        } else {  // Error
            $finaleFile = -1;
        }
		// Ensure the file exists, otherwise skip loading it
		if (file_exists($finaleFile)) {
			// Check if the include exists
			if ($this->GetTemplate($finaleFile, 0) && $finaleFile != -1 && $finaleFile > '' && $finaleFile != 1) { // exists
				// Get include contents
				include($finaleFile);
				// Check file type
				if ($filetype != 6) {
					// Send to $content from the include
					$content = ob_get_contents();
					ob_end_clean();
				}
				// Wrapping/Return
				if ($wrapJS == 1) { // Wrap with Script
					echo "\t" . '<script type="text/javascript">' . PHP_EOL;
					echo $content . PHP_EOL;
					echo "\t" . '</script>' . PHP_EOL;
				} else if ($wrapCSS == 1) { // Wrap with Style
					echo "\t" . '<style type="text/css">' . PHP_EOL;
					echo $content . PHP_EOL;
					echo "\t" . '</style>' . PHP_EOL;
				} else if ($filetype != 6) { // Default/No Wrap
					echo $content . PHP_EOL;
				}
			} else { // error/exception
				$this->appError = 1;
				$this->appLog = "Include file not found.<br />File: (" . $finaleFile . ")<br />File Type: " . $filetype;
				throw new CMSException("Include file not found.<br />File: (" . $finaleFile . ")<br />File Type: " . $filetype);
			}
		}
    }
    /**
    * SiteExtensions   Include site extensions
    * @access protected
    * 
    * @throws CMSException Error
    * @return Includes site extensions
    */ 
    protected function SiteExtensions() {
        $filetype = 6; // include file
        $LoadFiles = array(
                            'extensions/user/UserActions.class.php',
                            'extensions/user/UserChecks.class.php'
                          );
        $LoadVideoFiles = array(
							'extensions/utilities/Uploader.class.php',
							'extensions/user/UserProfile.class.php'
							);
        // Try/Catch
        try {
            $this->contentReturn = '';
            // Get all files in $LoadFiles array
            foreach ($LoadFiles as $getFiles) {
                $this->contentReturn .= $this->ParseTemplate($getFiles, array(), $filetype);
            }
            // Check urlParts
            if ($this->urlParts > '' && is_array($this->urlParts)) {
                // Get user administration includes
                if (isset($this->urlParts[0]) && $this->urlParts[0] == 'admin') {
                    if (isset($this->urlParts[2]) && $this->urlParts[2] == 'video') {     // video administration
                        // Get all files in $LoadFiles array
                        foreach ($LoadVideoFiles as $getFiles) {
                            $this->contentReturn .= $this->ParseTemplate($getFiles, array(), $filetype);
                        }
                    } else if (isset($this->urlParts[1]) && $this->urlParts[1] == 'user') {
                        // user profile class
                        $this->contentReturn .= $this->ParseTemplate('extensions/user/UserProfile.class.php', array(), $filetype);
					} else if (isset($this->urlParts[1]) && $this->urlParts[1] == 'perms') {
                        // user profile class
                        $this->contentReturn .= $this->ParseTemplate('extensions/user/UserProfile.class.php', array(), $filetype);
                    }
                } else {
                    if (isset($this->urlParts[0]) && $this->urlParts[0] == 'videos' || $this->urlParts[0] == 'profile') {     // public video check
                        // user profile class
                        $this->contentReturn .= $this->ParseTemplate('extensions/user/UserProfile.class.php', array(), $filetype);
                    }
                }
                // Subsite Extensions
                if (isset($this->urlParts[0]) && $this->urlParts[0] == 'kickface' && isset($this->urlParts[1]) && $this->urlParts[1] == 'music') {     // public video check
                    // user profile class
                    $this->contentReturn .= $this->ParseTemplate('extensions/user/UserProfile.class.php', array(), $filetype);
                }
            }
        } catch (CMSException $e) {
            echo $e;
        }
    }
    /**
    * SitePlugIns   Include site plugins
    * @access protected
    * 
    * @throws CMSException Error
    * @return Includes site plugins
    */ 
    protected function SitePlugIns() {
        $filetype = 6; // include file  
        $LoadSearchFiles = array(
                            'plugins/Search/Search.php',
                            'plugins/Search/Stemming.php',
                            'plugins/Search/StemmingStringBuilder.php'
                          );
        $LoadFormFiles = array(
                            'plugins/FormBuilder/GetFormData.php',
                            'plugins/FormBuilder/BuildForm.php'
                          );      
        // Try/Catch
        try {
            // Check what we need to load
            if ($this->urlParts > '' && is_array($this->urlParts)) {
                // Other plugins/includes
                //if (isset($this->urlParts[0]) && $this->urlParts[0] == 'blog') {       // public blog check
                    // public blog include
                    $this->contentReturn = $this->ParseTemplate('plugins/Blog/Blog.php', array(), $filetype);
                //} else 
				if (isset($this->urlParts[0]) && $this->urlParts[0] == 'videos') {     // public video check
                    // public video include
                    $this->contentReturn = $this->ParseTemplate('plugins/CCVideo/CCVideo.php', array(), $filetype);
                } else if (isset($this->urlParts[0]) && $this->urlParts[0] == 'search') {     // public search check
                    foreach ($LoadSearchFiles as $getFiles) {
                        $this->contentReturn .= $this->ParseTemplate($getFiles, array(), $filetype);
                    }
                } else if (isset($this->urlParts[0]) && $this->urlParts[0] == 'contact' || $this->urlParts[0] == 'ccpfg' || (isset($this->urlParts[1]) && $this->urlParts[1] == 'contact')) { // public ccfg class form builder
                    foreach ($LoadFormFiles as $getFiles) {
                        $this->contentReturn .= $this->ParseTemplate($getFiles, array(), $filetype);
                    }
                }
                // Subsite Loader
                if (isset($this->urlParts[0]) && $this->urlParts[0] == 'kickface' && isset($this->urlParts[1]) && $this->urlParts[1] == 'music') {     // public video check
                    // Get CC Video Class
                    $this->contentReturn = $this->ParseTemplate('plugins/CCVideo/CCVideo.php', array(), $filetype);
                }
            }
        } catch (CMSException $e) {
            echo $e;
        }
    }
   /**
    * SitePlugInsAdmin   Include site plugins for the administration
    * @access protected
    * 
    * @throws CMSException Error
    * @return Includes site plugins for the administration
    */ 
    protected function SitePlugInsAdmin() {
        $filetype = 6; // include file
        $LoadUserFiles = array(
                                    'admin/classes/UserAdmin.php',
                                    'admin/classes/Perms.php',
                                    'admin/classes/AdminActions.php'
                                );
        $LoadBlogFiles = array(
                                    'admin/plugins/Blog/config.php',
                                    'admin/plugins/Blog/BlogActions.php',
                                    'admin/plugins/Blog/categories/CategoryActions.php'
                                );
        // Try/Catch
        try {
            // Check what we need to load
            if ($this->urlParts > '' && is_array($this->urlParts)) {
                // Get administration includes
                if (isset($this->urlParts[0]) && $this->urlParts[0] == 'admin') {
                    // Always load these for permissions/authorization
                    foreach ($LoadUserFiles as $getFiles) {
                        $this->contentReturn .= $this->ParseTemplate($getFiles, array(), $filetype);
                    }
                    // Check for other admin includes
                    if (isset($this->urlParts[2]) && $this->urlParts[2] == 'blog') {      // blog admin check
                       foreach ($LoadBlogFiles as $getFiles) {
                           $this->contentReturn .= $this->ParseTemplate($getFiles, array(), $filetype);
                       }
                    } else if (isset($this->urlParts[2]) && $this->urlParts[2] == 'ccpfg') {    // form administration
                        // form admin class
                        $this->contentReturn = $this->ParseTemplate('admin/plugins/ccpfg/FormAdmin.php', array(), $filetype);
                    } else if (isset($this->urlParts[2]) && $this->urlParts[2] == 'video') {    // video administration
                        // video administration class
                        $this->contentReturn = $this->ParseTemplate('admin/plugins/video/CCVideoAdmin.php', array(), $filetype);
                    }
                }
            }
        } catch (CMSException $e) {
            echo $e;
        }
    }
	/**
    * Redirect script
    * @access protected
    * 
    * @throws CMSException Error
    * @return N/A (Redirects)
    */ 
	public static function redirect($url) {
		try {
			if (!headers_sent()) {    
				header('Location: '.$url);
				exit;
			} else {  
				echo '<script type="text/javascript">' . PHP_EOL;
				echo 'window.location.href="'.$url.'";' . PHP_EOL;
				echo '</script>' . PHP_EOL;
				echo '<noscript>' . PHP_EOL;
				echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />' . PHP_EOL;
				echo '</noscript>' . PHP_EOL;
				exit;
			}
		} catch (CMSException $e) {
            echo $e;
        }
	}
    /**
    * __call   Magic Method
    * @access public
    * 
    * @throws new CMSException
    * @return CMSException Error
    */ 
    public function __call($method, $arguments) {
		if ($this->config['debug'] == 1) {
			$html = '<span class="red"><strong>Fatal Error Unknown Method </span> ' . $method . '</strong>';
			$html .= '<span class="red"><br />Please contact the web site administrator for help with this problem.</span>';
			echo $html . '<br /><br />';
		} else {
			// logging
		}
    }
	/**
    * __toString   Magic Method
    * @access public
    * 
    * @throws N/A
    * @return Object as String
    */ 
	public function __toString() {
		return $this->name . " (" . $this->species . ")" . PHP_EOL;
	}
}
?>
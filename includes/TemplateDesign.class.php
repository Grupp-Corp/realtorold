<?php
/**
 * This is the main Design Class which loads the final results of the template calls
 * @name TemplateDesign
 * @example $Build = new TemplateDesign(); $Build->FullSite($url); //$url = content folder/file you wish to display
 * @author Steven Scharf
 * @copyright (c) 2012, Steven Scharf
 */
class TemplateDesign extends CCTemplate
{
    // Doctype / Scripts / Metas
    private function SiteDoctypeTitle() {
        // Try/Catch
        try {
            // Send parameters to the eventual include
            $param = array();
            $param['WebSiteTitle']  = $this->WebSiteTitle;
            $param['title']         = $this->PageTitle;
            $param['template_name'] = $this->config['template_name'];
            $param['site_absolute_url'] = $this->config['site_absolute_url'];
            // Parse template
            $templateFile = 'doctype-title.php';
            $this->contentReturn = $this->ParseTemplate($templateFile, $param, 1);
            // Return
            return $this->contentReturn;
        } catch (CMSException $e) {
            echo $e;
        }
    }
    // Get metas
    private function GetMetas($filetype = 2) {
        // Try/Catch
        try {
            $param = array();
            $param['title']             = $this->PageTitle;
            $param['WebSiteTitle']      = $this->WebSiteTitle;
            $param['template_name']     = $this->config['template_name'];
            $param['SelectedTemplate']  = $this->SelectedTemplate;
            $param['site_absolute_url'] = $this->config['site_absolute_url'];
            // Parse Template
            $templateFile = 'metas.php';
            $this->contentReturn = $this->ParseTemplate($templateFile, $param, $filetype);
            // Return
            return $this->contentReturn;
        } catch (CMSException $e) {
            echo $e;
        }
    }
    // Get javascript
    private function GetJS($filetype = 3) {
        // Try/Catch
        try {
            $filetypePlugins = 3.5;
            $param = array();
            $param['template_name'] = $this->config['template_name'];
			$param['site_absolute_url'] = $this->config['site_absolute_url'];
			$param['admin_access_folder'] = $this->config['admin_access_folder'];
            // JavaScript
            $templateFile = 'index.php';
            $contentReturn = $this->ParseTemplate($templateFile, $param, $filetype);
            // if url parts exists and is array
            if ($this->urlParts > '' && is_array($this->urlParts)) {
                // Other js plugins
                if (isset($this->urlParts[0]) && $this->urlParts[0] == 'admin' && isset($this->urlParts[2]) && $this->urlParts[2] == 'blog') {       // admin blog check
                    // CKEditor Plugin
                    $templateFile = 'ckeditor/ckeditor_js.php';
                    $contentReturn .= $this->ParseTemplate($templateFile, $param, $filetypePlugins);
                } else if (isset($this->urlParts[0]) && $this->urlParts[0] == 'admin' && isset($this->urlParts[2]) && $this->urlParts[2] == 'ccpfg') {       // admin blog check
                    // Form Generator Plugin
                    $templateFile = 'ccpfg/main.js';
                    $wrapJS = 1;
                    $contentReturn .= $this->ParseTemplate($templateFile, $param, $filetypePlugins, $wrapJS);

                }
            }
            return $contentReturn;
        } catch (CMSException $e) {
            echo $e;
        }
    }
    // Get css
    private function GetCSS($filetype = 4) {
        // Try/Catch
        try {
            $filetypePlugins = 4.5;
            $param = array();
            $param['template_name'] = $this->config['template_name'];
            $param['site_absolute_url'] = $this->config['site_absolute_url'];
            // JavaScript
            $templateFile = 'index.php';
            $contentReturn = $this->ParseTemplate($templateFile, $param, $filetype);
            // Check URL Parts for JS includes
            if ($this->urlParts > '' && is_array($this->urlParts)) {
               if (isset($this->urlParts[2]) && $this->urlParts[2] == 'ccpfg') { // form generation additions
                    $templateFile = 'ccpfg/main.css';
                    $wrapCSS = 1;
                    $contentReturn .= $this->ParseTemplate($templateFile, $param, $filetypePlugins, 0, $wrapCSS);
                } else if (isset($this->urlParts[0]) && $this->urlParts[0] == 'search') { // search additions
                    $templateFile = 'Search/main.css';
                    $wrapCSS = 1;
                    $contentReturn .= $this->ParseTemplate($templateFile, $param, $filetypePlugins, 0, $wrapCSS);
                } else if (isset($this->urlParts[0]) && $this->urlParts[0] == 'blog') { // search additions
                    $templateFile = 'Blog/main.css';
                    $wrapCSS = 1;
                    $contentReturn .= $this->ParseTemplate($templateFile, $param, $filetypePlugins, 0, $wrapCSS);
                }
            }
            return $contentReturn;
        } catch (CMSException $e) {
            echo $e;
        }
    }
    // Get header html
    private function GetHeaderHTML($filetype = 1) {
        // Try/Catch
        try {
            $param = array();
            $param['template_name'] = $this->config['template_name'];
            $param['site_absolute_url'] = $this->config['site_absolute_url'];
            // JavaScript
            $templateFile = 'header.php';
            $contentReturn = $this->ParseTemplate($templateFile, $param, $filetype);
            return $contentReturn;
        } catch (CMSException $e) {
            echo $e;
        }
    }
    // Site Header
    private function SiteHeader() {
        // Metas
        $this->contentReturn = $this->GetMetas();
        // Javascripts
        $this->contentReturn .= $this->GetJS();
        // CSS
        $this->contentReturn .= $this->GetCSS();
        // Header
        $this->contentReturn .= $this->GetHeaderHTML();
        // Begin Build
        return $this->contentReturn;
    }
    // Site top menu
    private function SiteTopMenu() {
        $param = array();
        $templateFile = 'top-menu.php';
		$param['SelectedTemplate'] = $this->TemplatePath;
        $param['sess_prefix'] = $this->config['session_prefix'];
        $param['site_absolute_url'] = $this->config['site_absolute_url'];
        $contentReturn = $this->ParseTemplate($templateFile, $param, 1);
        return $contentReturn;
    }
	// Left Menu
    private function SiteLeftMenu() {
        $param = array();
        $param['SelectedTemplate'] = $this->TemplatePath;
        $param['sess_prefix'] = $this->config['session_prefix'];
        $param['site_absolute_url'] = $this->config['site_absolute_url'];
        $templateFile = 'left-menu.php';
        $contentReturn = $this->ParseTemplate($templateFile, $param, 1);
        return $contentReturn;
    }
    // Right Menu
    private function SiteRightMenu() {
        $param = array();
        $param['SelectedTemplate'] = $this->TemplatePath;
        $param['sess_prefix'] = $this->config['session_prefix'];
        $param['site_absolute_url'] = $this->config['site_absolute_url'];
        $templateFile = 'right-menu.php';
        $contentReturn = $this->ParseTemplate($templateFile, $param, 1);
        return $contentReturn;
    }
    // Site Pre-Content
    private function PreContentWrap() {
        $param = array();
        $param['site_absolute_url'] = $this->config['site_absolute_url'];
        $templateFile = 'pre-content-wrap.php';
        $contentReturn = $this->ParseTemplate($templateFile, $param, 1);
        return $contentReturn;
    }
    // Site Body
    private function SiteBody() {
        // Vars
        $param = array();
        $param['site_absolute_url'] = $this->config['site_absolute_url'];
        $param['title'] = $this->PageTitle;
        $param['template_name'] = $this->config['template_name'];
        // Check for URL
        if (!isset($this->URLSelected) || $this->URLSelected == '') {
            $this->URLSelected = 'index.php';
        } else {
            $this->URLSelected = $this->URLSelected;
        }
        // Blog Helper (SEO)
        if ($this->urlParts > '' && is_array($this->urlParts)) {
            if (isset($this->urlParts[0]) && $this->urlParts[0] == 'blog') {
                $this->URLSelected = $this->TemplatePath . 'blog/index.php';
            }
        }
        // Check file type and if the file exists
        if ($this->fileType == '.php' && $this->fileExists == 1) {
            $contentReturn = $this->ParseTemplate($this->URLSelected, $param, 5);
            return $contentReturn;
        } else {
             print '<strong class="red">The page was not found.</strong>';
        }
    }
    // Site Post Content
    private function PostContentWrap() {
        $templateFile = 'post-content-wrap.php';
        $param = array();
        $param['site_absolute_url'] = $this->config['site_absolute_url'];
        $contentReturn = $this->ParseTemplate($templateFile, $param, 1);
        return $contentReturn;
    }
    // Site Footer
    private function SiteFooter() {
        $templateFile = 'footer.php';
        $param = array();
        $param['site_absolute_url'] = $this->config['site_absolute_url'];
        $param['VersionNumber'] = $this->CCCMS;
        $contentReturn = $this->ParseTemplate($templateFile, $param, 1);
        return $contentReturn;
    }
    // Site Post Body
    private function EndContent() {
        $templateFile = 'end-content.php';
        $param = array();
        $param['site_absolute_url'] = $this->config['site_absolute_url'];
        $contentReturn = $this->ParseTemplate($templateFile, $param, 1);
        return $contentReturn;
    }
    // Load Site
    public function FullSite($url, $redirect = false, $debugOverride = 0, $phpinfo = 0) {
        // Show PHP Version
		if ($phpinfo == 1) {
			phpinfo();
		} else {
			// Check/Set Debug Options
			if ($this->config['debug'] == 1 || $debugOverride == 1) {
				ini_set('display_errors', 1);
				error_reporting(E_ALL);
			} else {
				error_reporting(0);
			}
			// Get Extensions
			# CMGrupp Notes 5/18 - This looks like it is loading the different PHP class files
			# With the ParseTemplate() function.
			# Why don't we just load the whole application??? It's so tiny.
			$this->SiteExtensions();
			// Get PlugIns
			# CMGrupp Notes 5/18 - This looks like it is loading the different PHP class files
			# With the ParseTemplate() function.
			# Why don't we just load the whole application??? It's so tiny.
			$this->SitePlugIns();
			// Get administration includes
			if (isset($this->urlParts[0]) && $this->urlParts[0] == 'admin') {
				$this->SitePlugInsAdmin();
			}
			// Get Site Template
			$this->GetTemplate();
			// Content Return
			$TheReturn = '';
			if (!$redirect) {
				$TheReturn .= $this->SiteDoctypeTitle();
				$TheReturn .= $this->SiteHeader();
				$TheReturn .= $this->SiteTopMenu();
				$TheReturn .= $this->SiteRightMenu();
				$TheReturn .= $this->SiteLeftMenu();
				$TheReturn .= $this->PreContentWrap();
				$TheReturn .= $this->SiteBody();
				$TheReturn .= $this->PostContentWrap();
				$TheReturn .= $this->SiteFooter();
				$TheReturn .= $this->EndContent();
			} else { // if a redirect is required we exclude all but the content of the page
				$TheReturn .= $this->SiteBody();
			}
			// Return content
			return $TheReturn;
		}
    }
	// Get template file
	public function GetTemplateFile($templateFile = '') {
		try {
			if (!isset($templateFile) || $templateFile == '') {
				print '<strong class="red">Error including your file.</strong>';
			} else {
				include($this->config['server_folder_path'] . $this->config['template_directory'] . '/' . $this->config['template_name'] . '/' . $this->config['template_folder'] . $templateFile);
			}
		} catch (CMSException $e) {
            echo $e;
        }
	}
}
?>
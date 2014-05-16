<?php $PageTitle = 'Search'; ?>
<h1><?php echo $PageTitle; ?></h1>
<?php
// Objects
$XSSCheck = new InputFilter(1, 1);
$PorterStemming = 1;
$Srch = new Search($PorterStemming, 'eng');
// Allow header submission
ob_start();
// Build HTML pre-variable
$html = '';
// Vars
$SearchPull = '';
$category = NULL;
// Check/Cleanse Search String
if (isset($_GET['SearchString'])) {
	$SearchString = $Srch->CleanseSearchString($_GET['SearchString']);
} else {
	$SearchString = '';
}
// Search Type
if (isset($_GET['SearchType'])) {
	$SearchType = $Srch->CleanseSearchType($_GET['SearchType']);
} else {
	$SearchType = 1;
}
// Search All/First
if (isset($_GET['SearchNow'])) {
	$SearchNow = $Srch->CleanseSearchButton($_GET['SearchNow']);
} else {
	$SearchNow = 'All Results';
}
// Check the request method
if ($_SERVER['REQUEST_METHOD'] == "GET") {
	// Check the get
	if (isset($_GET)) {
		// See if we want only first result
		if ($SearchNow == 'First Result') {
			$firstResult = 1;
		} else {
			$firstResult = 0;
		}
		// Get page
		if ((isset($_GET['page'])) && (is_numeric($_GET['page']))) {
			$page = $_GET['page'] - 1;
		} else {
			$page = 0;
		}
		// Category
		if ((isset($_GET['Category'])) && (is_numeric($_GET['Category']))) {
			$category = $_GET['Category'];
		} else {
			$category = NULL;
		}
		// Build paginator vars
		$limit = 5; // pagination end limit
		$BeginLimit = $page * $limit; // pagination begin limit
		$ajax = 0; // pagination ajax
		// Check if search string exists....
		if ($SearchString > '') {
			// Search Method Call
			$SearchPull = $Srch->SearchSite($_GET, 'SearchString', $SearchType, $category, array($BeginLimit,$limit), $firstResult, 1, 1);
		}
	}
}
?>
<form action="/search" method="GET" id="Search" name="Search" class="alignCenter span12" autocomplete="off" >
    <form class="form-horizontal">
            <!-- Form Name -->
            <legend>Advanced Search</legend>
            <div class="row">
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label" for="name">Name</label>
                        <div class="controls">
                            <input id="name" name="name" type="text" placeholder="Name" class="input-xlarge">

                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="control-group">
                        <label class="control-label" for="broker_affiliation">Broker Affiliation</label>
                        <div class="controls">
                            <input id="broker_affiliation" name="broker_affiliation" type="text" placeholder="Broker Affiliation" class="input-xlarge">

                        </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="control-group">
                        <label class="control-label" for="lang">Language</label>
                        <div class="controls">
                            <select id="lang" name="lang" class="input-large">
                                <option>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <!-- Text input-->
                    <div class="control-group">
                        <label class="control-label" for="zipcode">Zip Code</label>
                        <div class="controls">
                            <input id="zipcode" name="zipcode" type="text" placeholder="0000" class="input-small">

                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="control-group">
                        <label class="control-label" for="city">City</label>
                        <div class="controls">
                            <input id="city" name="city" type="text" placeholder="city" class="input-xlarge">

                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="control-group">
                        <label class="control-label" for="county">County</label>
                        <div class="controls">
                            <input id="county" name="county" type="text" placeholder="County" class="input-xlarge">

                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="control-group">
                        <label class="control-label" for="state">State</label>
                        <div class="controls">
                            <input id="state" name="state" type="text" placeholder="State" class="input-xlarge">
                            <p class="help-block">E.g. Ca, California</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Text input-->


    </form>

</form>
    <div class="clearfix"></div>
<?php
if ((isset($_GET['SearchString'])) && ($SearchString > '')) {
	// Get results
	$html = $Srch->SearchReturn($SearchPull, $limit, $page, $SearchString, $SearchType, $SearchNow, $category, $ajax);
	// Show HTML Content
	echo $html;
}
?>
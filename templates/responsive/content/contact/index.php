<?php
$PageTitle = 'Contact Us';
?>
<h1><?php echo $title; ?></h1>
<?php
// Form ID
$FormID = 1;
// Get Form Data
$FormData = new GetFormData($FormID); // Return form data options
// Form 1 Vars
$SubmitButtonName = 'Send Message';
$PostMethod = 'POST';
$FormOptions = $FormData->GetFormOptions();
$Fields = $FormData->GetFormFields();// Getting Object
$BuildForm = new BuildForm($FormOptions, $PostMethod, $Fields, $SubmitButtonName);
// Print Form
echo $BuildForm->PrintForm();
?>
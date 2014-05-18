<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-alert.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-modal.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-dropdown.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-scrollspy.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-tab.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-tooltip.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-popover.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-button.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-collapse.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-carousel.js"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/responsive/bootstrap-typeahead.js"></script>
<script type="text/javascript">
//<![CDATA[
  $("#signUpForm").attr("action", '/regnow');
//]]>
</script>
<script>
//<![CDATA[
$.noConflict();
//]]>
</script>

<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/libs/mailer/contact-us.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/form/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/validate/1.7.0/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/ui/1.8.5/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/formtowizard/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/validate/password/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/captcha/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/ajaxGateway/validate/validate_profile.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/ajaxGateway/validate/registration.js"></script>
<script type="text/javascript" src="<?php echo $VarsToPass['site_absolute_url']; ?>js/jquery/libs/user/index.js"></script>
<!-- External libraries: GreenSock -->
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/plugins/slider/jquery.js" type="text/javascript"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/plugins/slider/greensock.js" type="text/javascript"></script>
<!-- LayerSlider script files -->
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/plugins/slider/layerslider.transitions.js" type="text/javascript"></script>
<script src="<?php echo $VarsToPass['site_absolute_url']; ?>js/plugins/slider/layerslider.kreaturamedia.jquery.js" type="text/javascript"></script>
<script>
     $(document).ready(function(){
 
        // Calling LayerSlider on the target element
        $('#layerslider').layerSlider({
            skin: 'v5',
            skinsPath: '<?php echo $VarsToPass['site_absolute_url']; ?>templates/responsive/content/slider/skin/',
			responsiveUnder : 960,
            layersContainer : 960,
			pauseOnHover: false,
			thumbnailNavigation: 'always',
			tnContainerWidth:'50%', 	
			tnWidth: 190,
			tnHeight: 185
        });
    });
</script>
</body>
</html>
// JavaScript Document

// Running the code when the document is ready
    $(document).ready(function(){
 
        // Calling LayerSlider on the target element
        $('#layerslider').layerSlider({
            skin: 'minimal',
            skinsPath: '/templates/responsive/content/slider/skin/',
			responsiveUnder : 960,
            layersContainer : 960,
			pauseOnHover: false
        });
    });
 
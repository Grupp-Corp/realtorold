$(document).ready(function() {

    var $input_zip = $('#ZIP'),
        $input_state = $('#State'),
        $input_city = $('#City');

    // first we want to hide the state and city field
    $input_city.hide().attr('disabled');
    $input_city.siblings('label[for="City"]').hide();
    $input_state.hide().attr('disabled');
    $input_state.siblings('label[for="State"]').hide();

    $input_zip.keyup(function() {
        var $el = $(this);

        // more validation
        if( ($el.val().length == 5) && (is_int($el.val())) ) {
            $.ajax({
                url: "http://zip.elevenbasetwo.com",
                cache: false,
                dataType: "json",
                type: "GET",
                data: "zip=" + $el.val(),
                success: function(result, success) {

                    var city = result.city.toLowerCase().replace(/^[a-z]/, function(m){ return m.toUpperCase() });

                    $input_city.val(city).show();
                    $input_city.siblings('label[for="City"]').show();
                    $input_state.val(result.state).show();
                    $input_state.siblings('label[for="State"]').show();

                },
                error: function(result, success) {

                    $input_city.hide().removeAttr('disabled');
                    $input_city.siblings('label[for="City"]').show();
                    $input_state.hide().removeAttr('disabled');
                    $input_state.siblings('label[for="State"]').show();


                }

            });
        }
    });

    // profile edits
    var $editProfile = $('#editProfile'),
        $editProfile_inputs = $editProfile.find('input'),
        $progress_bar = $('#ProfileContent').find('.progress .bar'),
        totalInputs = $editProfile_inputs.length,
        inputs_completed = 0,
        percent = 0;

    $.each($editProfile_inputs, function(index, value) {
        var $self = $(value);
        console.log($self);

        if($self.val() != '') {
            inputs_completed++;
        }

        percent = inputs_completed / totalInputs * 100;
        $progress_bar.css('width',percent +'%');
    });

});

// helpers
function is_int(value){
    if ((parseFloat(value) == parseInt(value)) && !isNaN(value)) {
        return true;
    } else {
        return false;
    }
}
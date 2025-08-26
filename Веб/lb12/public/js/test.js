$(document).ready(function () {
    var $form = $('form[name="test"]');
    var $formElements = $form[0].elements;

    $($formElements['resetButton']).click(function(event) {
        event.preventDefault(); 
        let $checkedFieldsInvalid = $form.find(".inputInvalid"); 
        let $errorMessages = $form.find(".error-message"); 
        $errorMessages.each(function() { 
            $(this).css("visibility", "hidden"); 
        }); 
        $checkedFieldsInvalid.each(function() { 
            $(this).removeClass("inputInvalid").addClass("inputUncheck"); 
        }); 
        

        $form.find('select').each(function() {
            $(this).prop('selectedIndex', 0);
        });

        $form.find('input[type="text"]').each(function() {
            $(this).val('');
        });
        
        $form.find('input[type="radio"]').each(function() {
            $(this).prop('checked', false);
        });
        
        $form.find('textarea').each(function() {
            $(this).val('');
        });

        //$form[0].reset();
    });
});

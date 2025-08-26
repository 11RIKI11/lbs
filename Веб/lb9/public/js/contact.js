function initCalendar(){
    const $birthdateInput = $("#birthdate");
    const $calendar = $("#calendar");
    const $monthSelect = $("#birth-month");
    const $yearSelect = $("#birth-year");
    const $daysContainer = $("#days-container");
    const $weekdaysContainer = $("#weekdays-container");

    const months = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", 
        "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
    const weekdays = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];
    
    months.forEach((month, index) => { 
        let $option = $("<option>"); 
        $option.val(index); 
        $option.text(month); 
        $monthSelect.append($option);
    });

    for (let i = 1900; i <= new Date().getFullYear(); i++) { 
        let $option = $("<option>"); 
        $option.val(i); 
        $option.text(i); 
        $yearSelect.append($option); 
    }

    $birthdateInput.focus(function () { 
        $calendar.removeClass("calendar-hidden").addClass("calendar-visible");
    });

    $birthdateInput.blur(function (event) { 
        if (!$calendar[0].contains(event.relatedTarget)) {
            $calendar.addClass("calendar-hidden").removeClass("calendar-visible");
        }
    });

    $calendar.on("focusin", function () { 
        $calendar.removeClass("calendar-hidden").addClass("calendar-visible");
    }).on("focusout", function (event) { 
        if (!$calendar[0].contains(event.relatedTarget)) { 
            $calendar.addClass("calendar-hidden").removeClass("calendar-visible"); 
        } 
    });

    $monthSelect.change(updateCalendarDays); 
    $yearSelect.change(updateCalendarDays);

    function updateCalendarDays(){
        const selectedYear = $yearSelect.val();
        const selectedMonth = $monthSelect.val();
        const daysInMonth = new Date(selectedYear, parseInt(selectedMonth) + 1, 0).getDate();
        const firstDayOfMonth = (new Date(selectedYear, selectedMonth, 1).getDay() + 6) % 7;

        $weekdaysContainer.empty(); 
        weekdays.forEach(weekday => { 
            let $weekdayElement = $("<div>"); 
            $weekdayElement.text(weekday); 
            $weekdayElement.addClass("weekday"); 
            $weekdaysContainer.append($weekdayElement); 
        });

        $daysContainer.empty(); 

        for(let i = 0; i < firstDayOfMonth; i++){
            let $emptyDiv = $("<div>"); 
            $emptyDiv.addClass("day-item"); 
            $daysContainer.append($emptyDiv);
        }

        for(let day = 1; day <= daysInMonth; day++){
            let $dayElement = $("<div>"); 
            $dayElement.text(day).addClass("day-item");
            $dayElement.mousedown(function () { 
                console.log(`Selected date: ${day}/ ${months[selectedMonth]} ${selectedYear}`); 
                $birthdateInput.val(`${String(parseInt(selectedMonth)+1).padStart(2, '0')}/${String(parseInt(day)).padStart(2, '0')}/${selectedYear}`).focus(); 
            }).mouseup(function () { 
                $calendar.addClass("calendar-hidden").removeClass("calendar-visible"); 
            });
            $daysContainer.append($dayElement);
        }
    }
    updateCalendarDays();
}

function checkForm(){
    var $form = $("[name='contact']");

    // $form.find("[name='inputName']").blur(checkInputName);
    // $form.find("[name='birthdate']").blur(birthdateCheck);
    // $form.find("[name='phone-number']").blur(phoneNumberCheck);
    // $form.find("[name='email']").blur(emailCheck);
    // $form.find("[name='message']").blur(messageCheck);

    // $form.find("[name='submitButton']").click(function(event){
    //     event.preventDefault();
    //     if($form.find(".inputUncheck").length === 0 && $form.find(".inputInvalid").length === 0){
    //         $('.modal-overlay').fadeIn();
    //     }
    // });

    // $form.find('.confirmModalButton').click(function(event){
    //     event.preventDefault();
    //     console.log($form.find(".inputUncheck"));
    //     console.log($form.find(".inputInvalid"));
    //     $form.submit();
    //     $('.modal-overlay').fadeOut();
    // })

    // $form.find('.closeModalButton').click(function(event){
    //     event.preventDefault();
    //     $('.modal-overlay').fadeOut();
    // })

    $form.find("[name='resetButton']").click(function(event) { 
        event.preventDefault(); 
        let $checkedFieldsInvalid = $form.find(".inputInvalid"); 
        let $checkedFieldsCorrect = $form.find(".input-correct"); 
        let $errorMessages = $form.find(".error-message"); 
        $errorMessages.each(function() { 
            $(this).css("visibility", "hidden"); 
        }); 
        $checkedFieldsInvalid.each(function() { 
            $(this).removeClass("inputInvalid").addClass("inputUncheck"); 
        }); 
        $checkedFieldsCorrect.each(function() { 
            $(this).removeClass("input-correct").addClass("inputUncheck"); 
        }); 

        //$form[0].reset();

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
    });


}

$(document).ready(function(){
    initCalendar();
    var $form = $("form[name='contact']");
    var $formElements = $form[0].elements;
    console.log($form);
    $($formElements['resetButton']).click(function(event) { 
        console.log($formElements['resetButton']);
        event.preventDefault(); 
        let $checkedFieldsInvalid = $form.find(".inputInvalid"); 
        let $checkedFieldsCorrect = $form.find(".input-correct"); 
        let $errorMessages = $form.find(".error-message"); 
        $errorMessages.each(function() { 
            $(this).css("visibility", "hidden"); 
        }); 
        $checkedFieldsInvalid.each(function() { 
            $(this).removeClass("inputInvalid").addClass("inputUncheck"); 
        }); 
        $checkedFieldsCorrect.each(function() { 
            $(this).removeClass("input-correct").addClass("inputUncheck"); 
        }); 

        //$form[0].reset();

        $form.find('select').each(function() {
            $(this).prop('selectedIndex', 0);
        });

        $form.find('input[type="text"]').each(function() {
            $(this).val('');
        });

        $form.find('input[type="email"]').each(function() {
            $(this).val('');
        });
        
        $form.find('input[type="radio"]').each(function() {
            $(this).prop('checked', false);
        });
        
        $form.find('textarea').each(function() {
            $(this).val('');
        });
    });
    //checkForm();
    //var timeout;
    // $('.popover-trigger').on('mouseenter', function(){
    //     $('.popover').removeClass('hidden');
    //     clearTimeout(timeout);
    // });
    // $('.popover-trigger').on('mouseleave', function(){
    //     timeot = setTimeout(function(){
    //         $('.popover').addClass('hidden');
    //     }, 2000)
    // });
    

});

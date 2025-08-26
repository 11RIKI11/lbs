function setCookie(name, value, expirationDays){
    const date = new Date();
    date.setTime(date.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + encodeURIComponent(value) + ";" + "expires=" + expires + ";path=/";
    console.log(document.cookie);
}

function getCookie(name) {
    const nameEq = name + "=";
    const cookiesList = document.cookie.split(';');
    for(let i = 0; i < cookiesList.length; i++){
        let c = cookiesList[i];
        while(c.charAt(0) == ' ') c = c.substring(1, c.length);
        if(c.indexOf(nameEq) == 0) return decodeURIComponent(c.substring(nameEq.length, c.length));
    }
    return null;
}

function updateHistory(pageName) {
    let sessionHistory = JSON.parse(sessionStorage.getItem("sessionHistory")) || {};
    sessionHistory[pageName] = (sessionHistory[pageName] || 0) + 1;
    sessionStorage.setItem("sessionHistory", JSON.stringify(sessionHistory));
    console.log(sessionHistory);

    let allTimeHistory = JSON.parse(localStorage.getItem("allTimeHistory")) || {};
    allTimeHistory[pageName] = (allTimeHistory[pageName] || 0) + 1;
    localStorage.setItem("allTimeHistory", JSON.stringify(allTimeHistory));
    console.log(allTimeHistory);

    let allTimeHistoryCookie = JSON.parse(getCookie("allTimeHistory")) || {};
    allTimeHistoryCookie[pageName] = (allTimeHistoryCookie[pageName] || 0) + 1;
    setCookie("allTimeHistory", JSON.stringify(allTimeHistoryCookie), 365);
}

function toggleDropdownMenu(event){
    event.preventDefault();
    let $menuItem = $(event.currentTarget);
    let $dropdownMenu = $menuItem.find("[class*='dropdown']");
    console.log($dropdownMenu);
    if ($dropdownMenu.hasClass("dropdown-visible")){
        $dropdownMenu.attr("class", "dropdown-hidden");
        $menuItem.find("a").removeAttr("style");
    }
    else {
        $dropdownMenu.attr("class", "dropdown-visible");
        $menuItem.find(".drop-down-menu-title").css("background", "#5882eb");
    }
}

function stopPropagation(event){
    event.stopPropagation();
}

const months = ["января", "февраля", "марта", "апреля", "мая", "июня", "июля", 
    "августа", "сентября", "октября", "ноября", "декабря"];

function updateDate(){
    const today = new Date();
    const day = String(today.getDate()).padStart(2, '0');
    const month = months[today.getMonth()];
    const year = today.getFullYear();
    const currentDate = `${day} ${month} ${year}`;
    $(".current-date").text(currentDate);
}

function initPopoverElements(){
    const $hoverElement = $('.hover-element'); 
    const $popover = $('.popover'); 
    let timer; 
    $hoverElement.on('mouseenter', function(event) { 
        clearTimeout(timer); 
        const offset = $(this).offset(); 
        $popover.css({ 
            top: offset.top + $(this).outerHeight(), 
            left: offset.left 
        }).removeClass('hidden'); 
    }); 
    $hoverElement.on('mouseleave', function() { 
        timer = setTimeout(function() { 
            $popover.addClass('hidden'); 
        }, 2000);
    }); 
    $popover.on('mouseenter', function() { 
        clearTimeout(timer); 
    }); 
    $popover.on('mouseleave', function() { 
        timer = setTimeout(function() { 
            $popover.addClass('hidden'); 
        }, 2000); 
    });
}

$(document).ready(function() {
    let pageName = document.title;
    console.log(pageName);
    updateHistory(pageName);
    let $menuItems = $(".main-dropable");
    console.log($menuItems);
    $menuItems.on("click", toggleDropdownMenu);
    $menuItems.find("[class*='dropdown'] a").on("click", stopPropagation);
    updateDate();
    
    // Обработчик события reset для всех форм
    $('form').on('reset', function() {
        const form = this;
        
        // Используем setTimeout, чтобы дать стандартному reset сработать первым
        setTimeout(function() {
            // Очищаем все поля ввода
            $(form).find('input').each(function() {
                $(this).val('').removeClass('input-error').addClass('inputUncheck');
            });
            
            // Очищаем сообщения об ошибках
            $('.error-message').html('<br><br>');
            $('.general-error').remove();
        }, 0);
    });
});

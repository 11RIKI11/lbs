function displayHistory(){
    let sessionHistory = JSON.parse(sessionStorage.getItem("sessionHistory")) || {};
    let allTimeHistory = JSON.parse(localStorage.getItem("allTimeHistory")) || {};

    let $sessionHistoryTable = $("#session-history tbody");
    let $allTimeHistoryTable = $("#all-time-history tbody");

    $sessionHistoryTable.empty();
    $allTimeHistoryTable.empty();

    $.each(sessionHistory, function(pageName, visits){
        let $row = $("<tr>");
        $row.append($("<td>").text(pageName));
        $row.append($("<td>").text(visits));
        $sessionHistoryTable.append($row);
    });

    $.each(allTimeHistory, function(pageName, visits){
        let $row = $("<tr>");
        $row.append($("<td>").text(pageName));
        $row.append($("<td>").text(visits));
        $allTimeHistoryTable.append($row);
    });
}

$(document).ready(function(){
    displayHistory();
});

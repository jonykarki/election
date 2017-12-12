// javascript for main page: Written in JQuery

// get the current hostname and create the base url for later
var hostname = location.hostname;
const BASE_URL = "http://" + hostname + "/election/";

var stateName;
var districtName;
var electionType = 1;

// get the election type selected by the user
$("#federal-button").click(function(){
    if($("#provincial-button").hasClass("btn-danger")){
        $("#provincial-button").removeClass("btn-danger");
        $("#provincial-button").addClass("btn-primary");
    }

    electionType = 1;
    $("#election-title").html("Federal Parliament Election 2017");

    $("#federal-button").removeClass("btn-primary");
    $(this).addClass("btn-danger");
});

$("#provincial-button").click(function(){
    if($("#federal-button").hasClass("btn-danger")){
        $("#federal-button").removeClass("btn-danger");
        $("#federal-button").addClass("btn-primary");
    }

    electionType = 2;
    $("#election-title").html("Provincial Assembly Election 2017");

    $("#provincial-button").removeClass("btn-primary");
    $(this).addClass("btn-danger");
});


// function fired when the VIEW RESULTS button is clicked on the main page
function showTables(){

    // get the currently selected district and state name from the select tag in html
    stateName = $('#states').find(":selected").text();
    districtNo = $('#districts').find(":selected").val();



    //TODO: IF THE USER DOESN'T SELECT ANYTHING THEN SHOW ALL THE DATA, FOR NOW USER HAS! TO SELECT BOTH

    url = BASE_URL + "extras/tables.php";

    $.post( url, { state: stateName, district: districtNo, election: electionType })
        .done(function( data ) {
            $('#tables').html(data);
        });

}


// function for showing results when the map is clicked
function showTablesMap(stateName, districtNo){

    // get those stateName and districtNo from the map
    console.log(stateName);
    console.log(districtNo);


    //TODO: IF THE USER DOESN'T SELECT ANYTHING THEN SHOW ALL THE DATA, FOR NOW USER HAS! TO SELECT BOTH

    url = BASE_URL + "extras/tables.php";

    $.post( url, { state: stateName, district: districtNo, election: electionType })
        .done(function( data ) {
            $('#tables').html(data);
        });

}

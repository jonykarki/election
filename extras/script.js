

var state;
var district;
// get the selected state
function getSelectedState(selectedState){
    state = selectedState;

    switch (state){
        case "1":
            district = "Bhojpur";
            break;
        case "2":
            district = "Bara";
            break;
        case "3":
            district = "Bhaktapur";
            break;
        case "4":
            district = "Baglung";
            break;
        case "5":
            district = "Arghakhanchi";
            break;
        case "6":
            district = "Dailekh";
            break;
        case "7":
            district = "Achham";
            break;
    }
}

// get the selected district
function getSelectedDistricts(selectedDistrict){
    district = selectedDistrict;
}

function showAddedCandidates(str) {

    var constituencyNo = str;
    var constituencyName = district + " -" + constituencyNo;
    var hostname = location.hostname;

    //alert(constituencyName);

    var url = "http://" + hostname + "/election/extras/showAddedCandidates.php?q=" + constituencyName + "&eltype=" + eltype;
    //alert(url);

    $.ajax({url: url, success: function(result) {
        $("#loading").fadeOut();
        $("#database-candidates").html(result);
    }});

}

function showCandidates(str){
    var area = str;

    var eltype = $('#election-type').find(":selected").val();
    var constituencyNo = $('#constituency').find(":selected").val();
    var constituencyName = district + " -" + constituencyNo + "(" + area +")";
    var hostname = location.hostname;


    //alert(constituencyName);

    var url = "http://" + hostname + "/election/extras/showAddedCandidates.php?q=" + constituencyName + "&eltype=" + eltype;
    //alert(url);

    $.ajax({url: url, success: function(result) {
        $("#loading").fadeOut();
        $("#database-candidates").html(result);
    }});

}




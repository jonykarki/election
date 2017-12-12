
<?php 
// get the districtNO
function getDistrictNo($conn, $district_name){
    $sql = "SELECT districtno FROM district WHERE districtname='".mysqli_real_escape_string($conn, $district_name)."'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            return $row["districtno"];
        }
    } else {
        return "0 results";
    }
}

// get the no of tables we need
function getNoOfTables($conn, $districtNo){
    $sql = "SELECT constituencyno, constituencyname FROM constituency WHERE districtno='".mysqli_real_escape_string($conn, $districtNo)."'";
    $result = mysqli_query($conn, $sql);
    $no_of_tables = mysqli_num_rows($result);
    return $no_of_tables;
}

// create the table Dynamically
function createTable($table_req = array()){
    // extract all the data from the given array
    extract($table_req);
    include 'extras/data_table.php';
}

//get the result based on the name of counstituency
function getResultOfConstituency($conn, $constituency_name){
    $sql = "SELECT * FROM result WHERE constituencyname='".mysqli_real_escape_string($conn, $constituency_name)."' ORDER BY votes DESC";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// add candidates to the database
function addCandidateToDatabase($conn, $candidate_data){
    extract($candidate_data);

    // sql query to add to the database
    $sql = "INSERT INTO `result` (`resultno`, `constituencyname`, `stateno`, `districtno`, `partyname`, `candidate`, `gender`, `votes`) VALUES (NULL, '".mysqli_real_escape_string($conn, $constituency)."', $state , $district, '".mysqli_real_escape_string($conn, $party)."', '".mysqli_real_escape_string($conn, $candidate_name)."', '".mysqli_real_escape_string($conn, $gender)."', '0');";

    if($result = mysqli_query($conn, $sql)){
        return "Successfully added " . $candidate_name . " to the Database";
    } else {
        return "Couldnot add " . $candidate_name . " to the Database";
    }
}

// add candidates to the database for the provincial election
// add candidates to the database
function addCandidateToProvincial($conn, $candidate_data){
    extract($candidate_data);

    // sql query to add to the database
    $sql = "INSERT INTO `provincialresult` (`resultno`, `constituencyname`, `stateno`, `districtno`, `partyname`, `candidate`, `gender`, `votes`) VALUES (NULL, '".mysqli_real_escape_string($conn, $constituency)."', $state , $district, '".mysqli_real_escape_string($conn, $party)."', '".mysqli_real_escape_string($conn, $candidate_name)."', '".mysqli_real_escape_string($conn, $gender)."', '0');";

    if($result = mysqli_query($conn, $sql)){
        return "Successfully added " . $candidate_name . " to the Database";
    } else {
        return "Couldnot add " . $candidate_name . " to the Database";
    }
}

// check for the candidate
function candidateInDatabase($conn, $candidate_name, $party_name){
    $sql = "SELECT candidate, partyname FROM result WHERE candidate='".mysqli_real_escape_string($conn, $candidate_name)."'AND partyname='".mysqli_real_escape_string($conn, $party_name)."'";

    if($result = mysqli_query($conn, $sql)){
        return true;
    } 

    return false;
}


// get the names of the people from a constituency who have been already added to the result database
function getAddedCandidates($conn, $constituencyName){
    $sql ="SELECT * FROM result WHERE constituencyname='".$constituencyName."'";
    $result = mysqli_query($conn, $sql);

    return $result;
}

function getAddedCandidatesProvincial($conn, $constituencyName){
    $sql ="SELECT * FROM provincialresult WHERE constituencyname='".$constituencyName."'";
    $result = mysqli_query($conn, $sql);

    return $result;
}

// update the candidate in the database
function updateCandidateData($conn, $candidate_data){
    extract($candidate_data);

    $sql = "UPDATE result 
            SET candidate='$candidate_name', constituencyname='$constituency', partyname='$party', gender='$gender', votes='$votes', districtno='$district', stateno='$state'
            WHERE resultno='$result_no'";

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

}


function updateCandidateDataProvincial($conn, $candidate_data){
    extract($candidate_data);

    $sql = "UPDATE provincialresult 
            SET candidate='$candidate_name', constituencyname='$constituency', partyname='$party', gender='$gender', votes='$votes', districtno='$district', stateno='$state'
            WHERE resultno='$result_no'";

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

}

// get resultno(unique) of the candidate whose data is wrong
function getResultsIncorrect($conn, $incorrectName){
    $sql = "SELECT resultno FROM result WHERE candidate='".mysqli_real_escape_string($conn, $incorrectName)."'";
    $result = mysqli_query($conn, $sql);
    $results = mysqli_fetch_assoc($result);
    return $results["resultno"];
}


// get resultno(unique) of the candidate whose data is wrong
function getResultsIncorrectProvincial($conn, $incorrectName){
    $sql = "SELECT resultno FROM provincialresult WHERE candidate='".mysqli_real_escape_string($conn, $incorrectName)."'";
    $result = mysqli_query($conn, $sql);
    $results = mysqli_fetch_assoc($result);
    return $results["resultno"];
}


/*
 * Auto-fill Crap
 * Get the Data of the current person
 *
 */
function DataAutoFill($conn, $Incorrect_name){
    $sql = "SELECT * FROM result WHERE candidate='".mysqli_real_escape_string($conn, $Incorrect_name)."'";
    $result = mysqli_query($conn, $sql);
    $results = mysqli_fetch_assoc($result);
    return $results;
}

function DataAutoFillProvincial($conn, $Incorrect_name){
    $sql = "SELECT * FROM provincialresult WHERE candidate='".mysqli_real_escape_string($conn, $Incorrect_name)."'";
    $result = mysqli_query($conn, $sql);
    $results = mysqli_fetch_assoc($result);
    return $results;
}

function getLastEnteredData($conn){
    $sql = "select * from result order by resultno desc limit 5";
    $result = mysqli_query($conn, $sql);
    $results = mysqli_fetch_assoc($result);
    return $results;
}

// get last entered data for provincial
function getLastEnteredProvincial($conn){
    $sql = "select * from provincialresult order by resultno desc limit 5";
    $result = mysqli_query($conn, $sql);
    $results = mysqli_fetch_assoc($result);
    return $results;
}

// search feature for adding votes to the databasee!
function getSearchCandidate($conn, $state, $constituencyName){
    $sql = "SELECT * FROM result WHERE stateno=$state AND constituencyname='$constituencyName'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// add the votes to the candidate
function addVotesToCandidate($conn, $votes, $partyname, $candidateName){
    $sql = "UPDATE result SET votes=$votes WHERE partyname='$partyname' AND candidate='$candidateName'";
    if($result = mysqli_query($conn, $sql)){
        return "Succesfully ADDED " . $votes . " votes to " . $candidateName;
    } else {
        return "Couldnot add the votes to " . $candidateName;
    }
}

function getDistrictName($districtNo){
    if($districtNo == 1){
        return "Bhojpur";
    } else if($districtNo == 2){
        return "Dhankuta";
    } else if($districtNo == 3){
        return "Ilam";
    } else if($districtNo == 4){
        return "Jhapa";
    } else if($districtNo == 5){
        return "Khotang";
    } else if($districtNo == 6){
        return "Morang";
    } else if($districtNo == 7){
        return "Okhaldhunga";
    } else if($districtNo == 8){
        return "Sankhuwasabha";
    } else if($districtNo == 9){
        return "Solukhumbu";
    } else if($districtNo == 10){
        return "Sunsari";
    } else if($districtNo == 11){
        return "Taplejung";
    } else if($districtNo == 12){
        return "Tehrathum";
    } else if($districtNo == 13){
        return "Udayapur";
    } else if($districtNo == 14){
        return "Panchthar";
    } else if($districtNo == 15){
        return "Bara";
    } else if($districtNo == 16){
        return "Dhanusa";
    } else if($districtNo == 17){
        return "Mahottari";
    } else if($districtNo == 18){
        return "Parsa";
    } else if($districtNo == 19){
        return "Rautahat";
    } else if($districtNo == 20){
        return "Saptari";
    } else if($districtNo == 21){
        return "Sarlahi";
    } else if($districtNo == 22){
        return "Siraha";
    } else if($districtNo == 23){
        return "Bhaktapur";
    } else if($districtNo == 24){
        return "Chitwan";
    } else if($districtNo == 25){
        return "Dhading";
    } else if($districtNo == 26){
        return "Dolakha";
    } else if($districtNo == 27){
        return "Kathmandu";
    } else if($districtNo == 28){
        return "Kavrepalanchok";
    } else if($districtNo == 29){
        return "Lalitpur";
    } else if($districtNo == 30){
        return "Makwanpur";
    } else if($districtNo == 31){
        return "Nuwakot";
    } else if($districtNo == 32){
        return "Ramechhap";
    } else if($districtNo == 33){
        return "Rasuwa";
    } else if($districtNo == 34){
        return "Sindhuli";
    } else if($districtNo == 35){
        return "Sindhupalchok";
    } else if($districtNo == 36){
        return "Baglung";
    } else if($districtNo == 37){
        return "Gorkha";
    } else if($districtNo == 38){
        return "Kaski";
    } else if($districtNo == 39){
        return "Lamjung";
    } else if($districtNo == 40){
        return "Manang";
    } else if($districtNo == 41){
        return "Mustang";
    } else if($districtNo == 42){
        return "Myagdi";
    } else if($districtNo == 43){
        return "Nawalparasi East";
    } else if($districtNo == 44){
        return "Parbat";
    } else if($districtNo == 45){
        return "Syangja";
    } else if($districtNo == 46){
        return "Tanahu";
    } else if($districtNo == 47){
        return "Arghakhanchi";
    } else if($districtNo == 48){
        return "Banke";
    } else if($districtNo == 49){
        return "Bardiya";
    } else if($districtNo == 50){
        return "Dang";
    } else if($districtNo == 51){
        return "Gulmi";
    } else if($districtNo == 52){
        return "Kapilvastu";
    } else if($districtNo == 53){
        return "Nawalparasi West";
    } else if($districtNo == 54){
        return "Palpa";
    } else if($districtNo == 55){
        return "Pyuthan";
    } else if($districtNo == 56){
        return "Rolpa";
    } else if($districtNo == 57){
        return "Rukum East";
    } else if($districtNo == 58){
        return "Rupandehi";
    } else if($districtNo == 59){
        return "Dailekh";
    } else if($districtNo == 60){
        return "Dolpa";
    } else if($districtNo == 61){
        return "Humla";
    } else if($districtNo == 62){
        return "Jajarkot";
    } else if($districtNo == 63){
        return "Jumla";
    } else if($districtNo == 64){
        return "Kalikot";
    } else if($districtNo == 65){
        return "Mugu";
    } else if($districtNo == 66){
        return "Surkhet";
    } else if($districtNo == 67){
        return "Rukum West";
    } else if($districtNo == 68){
        return "Salyan";
    } else if($districtNo == 69){
        return "Achham";
    } else if($districtNo == 70){
        return "Baitadi";
    } else if($districtNo == 71){
        return "Bajhang";
    } else if($districtNo == 72){
        return "Bajura";
    } else if($districtNo == 73){
        return "Dadeldhura";
    } else if($districtNo == 74){
        return "Darchula";
    } else if($districtNo == 75){
        return "Doti";
    } else if($districtNo == 76){
        return "Kailali";
    } else if($districtNo == 77){
        return "Kanchanpur";
    }
}

/*
 * Auto-Fill Crap End
 */



// FUNCTIONS FOR THE PROVINCIAL ELECTION
function getNoOfTablesProvincial($conn, $district_no){
    $sql = "SELECT constituencyno, constituencyname FROM provincialconstituency WHERE districtno='".mysqli_real_escape_string($conn, $district_no)."'";
    $result = mysqli_query($conn, $sql);
    $no_of_tables = mysqli_num_rows($result);
    return $no_of_tables;
}

function getResultOfProvincial($conn, $constituency_name){
    $sql = "SELECT * FROM provincialresult WHERE constituencyname='".mysqli_real_escape_string($conn, $constituency_name)."' ORDER BY votes DESC";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// UNNECESSARY FUNCTIONS

// // get the constituency name
// function getConstituencyName($conn, $districtNo){
//     $sql = "SELECT constituencyno, constituencyname FROM constituency WHERE districtno='".mysqli_real_escape_string($conn, $districtNo)."'";
//     $result = mysqli_query($conn, $sql);

//     return $result;
// //    if (mysqli_num_rows($result) > 0) {
// //        // output data of each row
// //        while ($row = mysqli_fetch_assoc($result)) {
// //            return $row;
// //        }
// //    } else {
// //        return "0 results";
// //    }
// }

// // get the results
// function getConstituencyResults($conn, $constituency_no){
//     $sql = "SELECT partyname, candidate, gender, votes FROM result WHERE constituencyname='".mysqli_real_escape_string($conn, $constituency_no)."'";
//     $result = mysqli_query($conn, $sql);
//     return $result;

// //    if (mysqli_num_rows($result) > 0) {
// //        // output data of each row
// //        while ($row = mysqli_fetch_assoc($result)) {
// //            return $row;
// //        }
// //    } else {
// //        return "0 results";
// //    }
// }

// // get the current constituency's result and display in its table
// function getCurrentResult($constituency_no){
//     $sql = "SELECT partyname, candidate, gender, votes FROM result WHERE constituencyno='".mysqli_real_escape_string($conn, $constituency_no)."'";
//     $result_1 = mysqli_query($conn, $sql);
//     return $result_1;
// }

?>
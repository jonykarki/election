<?php

require 'database.inc.php';
require 'functions.inc.php';

// get the name of the district and state
$state_name = $_POST["state"];
$district_no = $_POST["district"];
$election_type = $_POST["election"];


// TODO: COMPLETELY CUSTOMIZE THE TABLES ON THE BASIS OF WHAT IS SELECTED BY THE USER


$district_name = getDistrictName($district_no);

if($election_type == 1){
    $no_of_tables = getNoOfTables($conn, $district_no);
} else if($election_type == 2){
    // get no of tables from the provincialtable
    $no_of_tables = getNoOfTablesProvincial($conn, $district_no);
}

echo "<div class=\"py-2 my-2\" ></div>";

echo "<h3 class='text-center' style=\"color: rgb(139, 136, 136)\">";
echo $state_name . " | " . $district_name;
echo "</h3>";

echo "<div class=\"py-2 my-2\" ></div>";

for ($i=1; $i <= @$no_of_tables; $i++) {
    global $election_type;

    // variable ($i+1) is also the constituency name of that district
    $consti_number = $i;


    if ($consti_number % 2 !== 0) {
        echo "<div class='row'>";
    }

    // create an array with necessary requirements for creating tables
    // $table_req = array("constituency_number"=>$consti_number, "conn" =>$conn, "district_name" => $district_name, "state_name"=>$state_name);


    if ($election_type == 1) {
        $constituency_name = $district_name . " -" . $consti_number;

        $result = getResultOfConstituency($conn, $constituency_name);

        echo "<div class='col-md-6'>
            <table class='table table-hover'>
                <thead>";
        echo $constituency_name;

        printHead();
        printRows($result);
    } else if ($election_type == 2) {
        for($j=1; $j <= 2; $j++){
            global $district_name, $i;
            // "A" if odd and "B" if even
            if($j % 2 != 0){
                $k = "(A)";
            } else if($j % 2 == 0){
                $k = "(B)";
            }
            // print the name of the constituency
            $constituency_name = $district_name . " -" . $i . $k;
            $result = getResultOfProvincial($conn, $constituency_name);

            echo "<div class='col-md-6'>
            <table class='table table-hover'>
                <thead>";
            echo $constituency_name;

            printHead();
            printRows($result);
        }
    }


    // if(isset($_GET)&&!empty($_GET)){ include 'extras/data_table.php';}

    if ($consti_number % 2 === 0) {
        echo "</div>";
    }
}

function printHead(){
    echo "</thead>";
    echo "<thead class=\"text-white\" style=\"background-color: #BE0815;\">
                <tr>
                    <th>Logo</th>
                    <th>Party Name</th>
                    <th>Candidate Name</th>
                    <th>Votes</th>
                </tr>
           </thead>
           <tbody>";
}

function printRows($result){
    while($results = mysqli_fetch_assoc($result)){
        echo "<tr>";
        $partylogo = "election-images/" . $results["partyname"] . ".png";

        echo "<th scope=\"row\">";
        echo "<img src='$partylogo' alt='party-logo' width='30'/>";
        echo "</th>";

        echo "<td>";
        echo $results["partyname"];
        echo "</td>";

        echo "<td>";
        echo $results["candidate"];
        echo "</td>";

        echo "<td>";
        echo $results["votes"];
        echo "</td>";

        echo "</tr>";
    }

    echo "</tbody>
          </table>
          </div>";
}


?>
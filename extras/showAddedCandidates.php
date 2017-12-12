<?php

require 'functions.inc.php';
require 'database.inc.php';

// get the candidates that have been added to the database from the current constituency
$constituenNam = $_GET["q"];

// get different ones according to the eltype
$eltype = $_GET['eltype'];


if($eltype == 1){
    $results = getAddedCandidates($conn, $constituenNam);
} else if ($eltype == 2){
    $results = getAddedCandidatesProvincial($conn, $constituenNam);
}



while($result = mysqli_fetch_assoc($results)){

    echo "<a target=\"_self\" href='../extras/edit_candidates.php?name=". $result["candidate"] . "&eltype=" . $eltype . "'><li>" . $result["candidate"] . " (" . $result["partyname"] .  ")  :   " .  $result["constituencyname"] . "</li></a>";
}


mysqli_close($conn);

?>
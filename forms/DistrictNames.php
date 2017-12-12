<?php

require '../extras/functions.inc.php';
require '../extras/database.inc.php';

@$eltype = $_GET['eltype'];

if($eltype == 1){
    $data = getLastEnteredData($conn);
    @$Constituency_Name = $data["constituencyname"];
} else{
    $data = getLastEnteredProvincial($conn);
    @$Constituency_Name = $data["constituencyname"];
}

//echo $District_Number;
echo $Constituency_Name;

?>
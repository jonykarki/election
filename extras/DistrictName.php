<?php

require 'functions.inc.php';
require 'database.inc.php';

@$incorrectName = $_POST['incorrect'];
@$eltype = $_POST['election'];

if($eltype == 1){
    @$District_Number = DataAutoFill($conn, $incorrectName)["districtno"];
} else if ($eltype == 2){
    @$District_Number = DataAutoFillProvincial($conn, $incorrectName)["districtno"];
}


if($District_Number < 10){
    $District_Number = "0" . $District_Number;
}

//echo $District_Number;
echo $District_Number . getDistrictName($District_Number);

?>
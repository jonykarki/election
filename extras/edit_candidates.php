
<?php

require 'functions.inc.php';
require 'database.inc.php';
require 'header.php';

if (isset($_POST) && !empty($_POST)) {
    $eltype = $_POST['election-type'];

    if (isset($_POST["states"]) && !empty($_POST["states"])) {
        $state = @$_POST["states"];
    } else {
        echo "Please Select the State" . "<br>";
    }

    if (isset($_POST["district"]) && !empty($_POST["district"])) {
        $district = @$_POST["district"];
    } else {
        echo "Please Select the district" . "<br>";
    }

    $district_name = getDistrictName($district);

    // change the name according to the election type selected
    if($eltype == 1){
        if (isset($_POST["constituency"]) && !empty($_POST["constituency"])) {
            $constituency = $district_name . " -" . $_POST["constituency"];
        } else {
            echo "Please Select the constituency" . "<br>";
        }
    } else if ($eltype == 2){
        // get the area selected by the user
        if (isset($_POST["area"]) && !empty($_POST["area"])) {
            $area = $_POST['area'];
        } else {
            echo "Please Select the constituency" . "<br>";
        }

        if (isset($_POST["constituency"]) && !empty($_POST["constituency"])) {
            global $district_name;
            $constituency = $district_name . " -" . $_POST["constituency"] . "(" . $area . ")";
        } else {
            echo "Please Select the constituency" . "<br>";
        }
    }


    if (isset($_POST["gender"]) && !empty($_POST["gender"])) {
        $gender = @$_POST["gender"];
    } else {
        echo "Please Select the gender" . "<br>";
    }

    if (isset($_POST["party"]) && !empty($_POST["party"])) {
        $party = @$_POST["party"];
    } else {
        echo "Please Select the Party" . "<br>";
    }

    if (isset($_POST["candidate-name"]) && !empty($_POST["candidate-name"])) {
        $candidate_name = @$_POST["candidate-name"];
    } else {
        echo "Please Enter the Candidate's Name" . "<br>";
    }

    $votes = 0;

    $incorrectName = $_POST["candidate-incorrect-name"];

    // get ResultNo from database for editing purpose for the election type

    if($eltype == 1){
        $resultNo = getResultsIncorrect($conn, $incorrectName);
    } else if ($eltype == 2){
        $resultNo = getResultsIncorrectProvincial($conn, $incorrectName);
    }



    // save all to the database
    $candidate_data = array("state" => $state, "result_no" => $resultNo, "votes" => $votes, "district" => $district, "constituency" => $constituency, "gender" => $gender, "party" => $party, "candidate_name" => $candidate_name);


    // if(candidateInDatabase($conn, $candidate_name, $party) == true){
    //     global $candidate_name, $party;
    //     echo "Candidate ". $candidate_name . " of " . $party . " party " . "is already on the database";
    // } else {


    if($eltype == 1){
        if (isset($_POST["candidate-name"]) && !empty($_POST["candidate-name"]) && isset($_POST["party"]) && !empty($_POST["party"]) && isset($_POST["gender"]) && !empty($_POST["gender"]) && isset($_POST["constituency"]) && !empty($_POST["constituency"]) && isset($_POST["states"]) && !empty($_POST["states"])) {
            $updateResult = updateCandidateData($conn, $candidate_data);
            echo $updateResult;
        }
    } else if ($eltype == 2){
        if (isset($_POST["candidate-name"]) && !empty($_POST["candidate-name"]) && isset($_POST["party"]) && !empty($_POST["party"]) && isset($_POST["gender"]) && !empty($_POST["gender"]) && isset($_POST["constituency"]) && !empty($_POST["constituency"]) && isset($_POST["states"]) && !empty($_POST["states"])) {
            $updateResult = updateCandidateDataProvincial($conn, $candidate_data);
            echo $updateResult;
        }
    }

    header('Location: ../forms/add_candidate_form.php');

    // }
}

@$Incorrect_Name = $_GET["name"];
@$eltype = $_GET["eltype"];

if($eltype == 1){
    @$Constituency = DataAutoFill($conn, $Incorrect_Name)["constituencyname"];
    @$State_No = DataAutoFill($conn, $Incorrect_Name)["stateno"];

    if($Constituency == "Kathmandu -10"){
        $con_rev = strrev($Constituency);
        $Con_no = substr($con_rev, 0, 2);
        $Constituency_Number = strrev($Con_no);
        //echo $Constituency_Number;
    } else {
        $con_rev = strrev($Constituency);
        $Constituency_Number = substr($con_rev, 0, 1);
        //echo $Constituency_Number;

    }

    @$District_Name = DataAutoFill($conn, $incorrectName)["districtno"];
//echo $District_Name;

    @$Gender = DataAutoFill($conn, $Incorrect_Name)["gender"];
    @$Party_Name = DataAutoFill($conn, $Incorrect_Name)["partyname"];

} else if($eltype == 2){

    @$Constituency = DataAutoFillProvincial($conn, $Incorrect_Name)["constituencyname"];
    @$State_No = DataAutoFillProvincial($conn, $Incorrect_Name)["stateno"];

    if($Constituency == "Kathmandu -10(A)" || $Constituency == "Kathmandu -10(B)"){
        $con_rev = strrev($Constituency);
        $Con_no = substr($con_rev, 3, 2);
        $Constituency_Number = strrev($Con_no);
        //echo $Constituency_Number;
        $area = substr($con_rev, 1 , 1);
    } else {
        $con_rev = strrev($Constituency);
        $Constituency_Number = substr($con_rev, 3, 1);
        //echo $Constituency_Number;
        $area = substr($con_rev, 1 , 1);
    }

    @$District_Name = DataAutoFillProvincial($conn, $incorrectName)["districtno"];
//echo $District_Name;

    @$Gender = DataAutoFillProvincial($conn, $Incorrect_Name)["gender"];
    @$Party_Name = DataAutoFillProvincial($conn, $Incorrect_Name)["partyname"];

}


?>


    <div class="container">
        <h1>Edit Candidates</h1>

        <div class="row" style="margin-top: 50px;">

            <div class="col-md-6">
                <form action="edit_candidates.php" method="POST">

                    <div class="form-group row">
                        <label for="candidate-name">Candidate Name:</label>
                        <div class="col">
                            <?php echo "<input name='candidate-name' type='text' id='incorrect-candidate-name' value='" . @$_GET["name"] . "'>"; ?>
                            <br>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="states">Select States</label>
                        <div class="col">
                            <select id="states" name="states" class="custom-select"
                                    onchange="getSelectedState(this.value)">
                                <option selected disabled>--- States ---</option>
                                <option <?php if($State_No == "1"){ echo "selected "; } ?> value="1">State - 1</option>
                                <option <?php if($State_No == "2"){ echo "selected "; } ?> value="2">State - 2</option>
                                <option <?php if($State_No == "3"){ echo "selected "; } ?> value="3">State - 3</option>
                                <option <?php if($State_No == "4"){ echo "selected "; } ?> value="4">State - 4</option>
                                <option <?php if($State_No == "5"){ echo "selected "; } ?> value="5">State - 5</option>
                                <option <?php if($State_No == "6"){ echo "selected "; } ?> value="6">State - 6</option>
                                <option <?php if($State_No == "7"){ echo "selected "; } ?> value="7">State - 7</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="districts">Select District</label>
                        <div class="col">
                            <select id="districts" name="district" class="custom-select"
                                    onchange="getSelectedDistrict(this.value)">
                                <option selected disabled>--- Districts ---</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="districts">Constituency</label>
                        <div class="col">
                            <select id="constituency" name="constituency" class="custom-select"
                                    onchange="showAddedCandidates(this.value)">
                                <option selected disabled>Constituency Number</option>
                                <option <?php if($Constituency_Number == "1"){ echo "selected "; } ?> value="1">1</option>
                                <option <?php if($Constituency_Number == "2"){ echo "selected "; } ?> value="2">2</option>
                                <option <?php if($Constituency_Number == "3"){ echo "selected "; } ?> value="3">3</option>
                                <option <?php if($Constituency_Number == "4"){ echo "selected "; } ?> value="4">4</option>
                                <option <?php if($Constituency_Number == "5"){ echo "selected "; } ?> value="5">5</option>
                                <option <?php if($Constituency_Number == "6"){ echo "selected "; } ?> value="6">6</option>
                                <option <?php if($Constituency_Number == "7"){ echo "selected "; } ?> value="7">7</option>
                                <option <?php if($Constituency_Number == "8"){ echo "selected "; } ?> value="8">8</option>
                                <option <?php if($Constituency_Number == "9"){ echo "selected "; } ?> value="9">9</option>
                                <option <?php if($Constituency_Number == "10"){ echo "selected "; } ?> value="10">10</option>
                            </select>
                        </div>
                    </div>

                    <?php
                    if($eltype == 2){
                        echo "<div class=\"form-group row\" id=\"area-area\">
                        <label for=\"area\">Select Area</label>
                        <div class=\"col\">
                            <select id=\"area\" name=\"area\" class=\"custom-select\">
                                <option";

                        if($area == "A"){ echo " selected "; }

                        echo " value=\"A\" >A</option>";

                        echo "<option";

                        if($area == "B"){ echo " selected "; };

                        echo " value=\"B\" >B</option>
                                </select>
                            </div>
                        </div>";

                    }
                    ?>

                    <div class="form-group row">
                        <label for="districts">Gender</label>
                        <div class="col">
                            <select id="gender" name="gender" class="custom-select">
                                <option selected disabled> Gender</option>
                                <option <?php if($Gender == "Male"){ echo "selected "; } ?> value="Male">Male</option>
                                <option <?php if($Gender == "Female"){ echo "selected "; } ?> value="Female">Female</option>
                                <option <?php if($Gender == "Other"){ echo "selected "; } ?> value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="districts">Party Name</label>
                        <div class="col">
                            <select id="party" name="party" class="custom-select">
                                <option selected disabled> Party Name</option>
                                <option <?php if($Party_Name== "Nepali Congress"){ echo "selected "; } ?> value="Nepali Congress">Nepali Congress</option>
                                <option <?php if($Party_Name== "CPN-UML"){ echo "selected "; } ?> value="CPN-UML">CPN-UML</option>
                                <option <?php if($Party_Name== "Maoist Center"){ echo "selected "; } ?> value="Maoist Center">Maoist Center</option>
                                <option <?php if($Party_Name== "RPP"){ echo "selected "; } ?> value="RPP">RPP</option>
                                <option <?php if($Party_Name== "FSFN"){ echo "selected "; } ?> value="FSFN">FSFN</option>
                                <option <?php if($Party_Name== "RJP"){ echo "selected "; } ?> value="RJP">RJP</option>

                                <option <?php if($Party_Name== "Bibekshil Sajha Party"){ echo "selected "; } ?> value="Bibekshil Sajha Party">Bibekshil Sajha Party</option>
                                <option <?php if($Party_Name== "Naya Shakti"){ echo "selected "; } ?> value="Naya Shakti">Naya Shakti</option>
                                <option <?php if($Party_Name== "Janmorcha"){ echo "selected "; } ?> value="Janmorcha">Janmorcha</option>
                                <option <?php if($Party_Name== "RPP(D)"){ echo "selected "; } ?> value="RPP(D)">RPP(D)</option>
                                <option <?php if($Party_Name== "Nepal Majdur Kisan"){ echo "selected "; } ?> value="Nepal Majdur Kisan">Nepal Majdur Kisan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <!--                        <!--label for="candidate-incorrect-name">Candidate's Incorrect Name:</label>-->
                        <div class="col">
                            <?php echo "<input name='candidate-incorrect-name' type='text' value='" . @$_GET["name"] . "' style='display:none;'>"; ?>
                            <br>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!--                        <!--label for="candidate-incorrect-name">Candidate's Incorrect Name:</label>-->
                        <div class="col">
                            <?php echo "<input id='election-type' name='election-type' type='text' value='" . $eltype . "' style='display:none;'>"; ?>
                            <br>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-md btn-success">Update</button>
                    <a href="../forms/add_candidate_form.php" class="btn btn-md btn-primary">Add More Candidates</a>
                    <button class="btn btn-md btn-danger">Cancel</button>
                </form>
            </div>

            <!-- Show the candidates already in the database by using ajax request-->
            <div class="col-md-6">
                <h1 id="Content"></h1>
            </div>

        </div>
    </div>


    <!-- end bootstrap form -->
    <script type="text/javascript">
        $(document).ready(function(){

            //var result;
            var districtName;
            var Name;

            var incorrectName = $("#incorrect-candidate-name").val();
            var electionType = $("#election-type").val();
            //alert(incorrectName);

            $.post('DistrictName.php', { incorrect: incorrectName, election: electionType }, function(result) {
                // result is the districtNo of the candidate
                districtName = result.trim();
                //document.getElementById('Content').innerHTML = districtName;

                districtNo = districtName.substr(0,2);
                Name = districtName.substr(2, districtName.length);
                jd(Name, districtNo);
                //alert(districtName);
            });

            function jd(district, value) {
                var phtml = "<option selected " + "value='" + value + "'>" + district + "</option>";
                return $("#districts").append(phtml);
            }


//            $("#districts option").each(function(){
//                if ($(this).text() == districtName)
//                    $(this).attr("selected","selected");
//            });

        });
        $("#states").change(function () {
            $("#districts option").remove();
            var el = $(this);
            if (el.val() === "1") {
                pd("Bhojpur", "01");
                pd("Dhankuta", "02");
                pd("Ilam", "03");
                pd("Jhapa", "04");
                pd("Khotang", "05");
                pd("Morang", "06");
                pd("Okhaldhunga", "07");
                pd("Sankhuwasabha", "08");
                pd("Solukhumbu", "09");
                pd("Sunsari", 10);
                pd("Taplejung", 11);
                pd("Tehrathum", 12);
                pd("Udayapur", 13);
                pd("Panchthar", 14);
            }
            if (el.val() === "2") {
                pd("Bara", 15);
                pd("Dhanusa", 16);
                pd("Mahottari", 17);
                pd("Parsa", 18);
                pd("Rautahat", 19);
                pd("Saptari", 20);
                pd("Sarlahi", 21);
                pd("Siraha", 22);
            }
            if (el.val() === "3") {
                pd("Bhaktapur", 23);
                pd("Chitwan", 24);
                pd("Dhading", 25);
                pd("Dolakha", 26);
                pd("Kathmandu", 27);
                pd("Kavrepalanchok", 28);
                pd("Lalitpur", 29);
                pd("Makwanpur", 30);
                pd("Nuwakot", 31);
                pd("Ramechhap", 32);
                pd("Rasuwa", 33);
                pd("Sindhuli", 34);
                pd("Sindhupalchok", 35);
            }
            if (el.val() === "4") {
                pd("Baglung", 36);
                pd("Gorkha", 37);
                pd("Kaski", 38);
                pd("Lamjung", 39);
                pd("Manang", 40);
                pd("Mustang", 41);
                pd("Myagdi", 42);
                pd("Nawalparasi East", 43);
                pd("Parbat", 44);
                pd("Syangja", 45);
                pd("Tanahu", 46);
            }
            if (el.val() === "5") {
                pd("Arghakhanchi", 47);
                pd("Banke", 48);
                pd("Bardiya", 49);
                pd("Dang", 50);
                pd("Gulmi", 51);
                pd("Kapilvastu", 52);
                pd("Nawalparasi West", 53);
                pd("Palpa", 54);
                pd("Pyuthan", 55);
                pd("Rolpa", 56);
                pd("Rukum East", 57);
                pd("Rupandehi", 58);
            }
            if (el.val() === "6") {
                pd("Dailekh", 59);
                pd("Dolpa", 60);
                pd("Humla", 61);
                pd("Jajarkot", 62);
                pd("Jumla", 63);
                pd("Kalikot", 64);
                pd("Mugu", 65);
                pd("Surkhet", 66);
                pd("Rukum West", 67);
                pd("Salyan", 68);
            }
            if (el.val() === "7") {
                pd("Achham", 69);
                pd("Baitadi", 70);
                pd("Bajhang", 71);
                pd("Bajura", 72);
                pd("Dadeldhura", 73);
                pd("Darchula", 74);
                pd("Doti", 75);
                pd("Kailali", 76);
                pd("Kanchanpur", 77);
            }

            // pd = print district
            function pd(district, value) {
                var phtml = "<option " + "value='" + value + "'>" + district + "</option>";
                return $("#districts").append(phtml);
            }
        });


    </script>


<?php
require 'footer.php';
?>
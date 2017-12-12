<?php
require '../extras/functions.inc.php';
require '../extras/database.inc.php';


if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST["election-type"]) && !empty($_POST["election-type"])) {
        $election_type = $_POST["election-type"];
    } else {
        echo "Please Select a election type" . "<br>";
    }

    if (isset($_POST["states"]) && !empty($_POST["states"])) {
        $state = $_POST["states"];
    } else {
        echo "Please Select the State" . "<br>";
    }

    if (isset($_POST["district"]) && !empty($_POST["district"])) {
        $district = $_POST["district"];
    } else {
        echo "Please Select the district" . "<br>";
    }

    // get the District Name According to the district number
    @$districtName = getDistrictName($district);


    /*
     * Set the constituency Name according to the electionType
     */
    if($election_type == 1){
        if (isset($_POST["constituency"]) && !empty($_POST["constituency"])) {
            $constituency = $districtName . " -" . $_POST["constituency"];
        } else {
            echo "Please Select the constituency" . "<br>";
        }
    } else if ($election_type == 2){
        // get the area selected by the user
        if (isset($_POST["area"]) && !empty($_POST["area"])) {
            $area = $_POST['area'];
        } else {
            echo "Please Select the constituency" . "<br>";
        }

        if (isset($_POST["constituency"]) && !empty($_POST["constituency"])) {
            $constituency = $districtName . " -" . $_POST["constituency"] . "(" . $area . ")";
        } else {
            echo "Please Select the constituency" . "<br>";
        }
    }


    if (isset($_POST["gender"]) && !empty($_POST["gender"])) {
        $gender = $_POST["gender"];
    } else {
        echo "Please Select the gender" . "<br>";
    }

    if (isset($_POST["party"]) && !empty($_POST["party"])) {
        $party = $_POST["party"];
    } else {
        echo "Please Select the Party" . "<br>";
    }

    if (isset($_POST["candidate-name"]) && !empty($_POST["candidate-name"])) {
        $candidate_name = $_POST["candidate-name"];
    } else {
        echo "Please Enter the Candidate's Name" . "<br>";
    }

    $votes = 0;

    // save all to the database
    @$candidate_data = array("state" => $state, "district" => $district, "constituency" => $constituency, "gender" => $gender, "party" => $party, "candidate_name" => $candidate_name);
    // if(candidateInDatabase($conn, $candidate_name, $party) == true){
    //     global $candidate_name, $party;
    //     echo "Candidate ". $candidate_name . " of " . $party . " party " . "is already on the database";
    // } else {


    // add the candidate data into the database on the basis of the type of election selected by the user
    if($election_type == 1){
        if (isset($_POST["candidate-name"]) && !empty($_POST["candidate-name"]) && isset($_POST["party"]) && !empty($_POST["party"]) && isset($_POST["gender"]) && !empty($_POST["gender"]) && isset($_POST["constituency"]) && !empty($_POST["constituency"]) && isset($_POST["states"]) && !empty($_POST["states"])) {
            $addResult = addCandidateToDatabase($conn, $candidate_data);
            echo $addResult;
        }
    } else if($election_type == 2){
        if (isset($_POST["candidate-name"]) && !empty($_POST["candidate-name"]) && isset($_POST["party"]) && !empty($_POST["party"]) && isset($_POST["gender"]) && !empty($_POST["gender"]) && isset($_POST["constituency"]) && !empty($_POST["constituency"]) && isset($_POST["states"]) && !empty($_POST["states"])) {
            $addResult = addCandidateToProvincial($conn, $candidate_data);
            echo $addResult;
        }
    }

    // } 

}

// auto-fill crap

?>


<?php require '../extras/header.php' ?>

<h1 class="display-4 text-center">Add Candidates</h1>

<div class="container">
    <div class="row" style="margin-top: 50px;">

        <div class="col-md-6">
            <form action="add_candidate_form.php" method="POST" autocomplete="on">

                <div class="form-group row">
                    <label for="election">Select Election</label>
                    <div class="col">
                        <select id="election-type" name="election-type" class="custom-select" onchange="formChange(this.value)">
                            <option <?php if(isset($_POST["election-type"]) && ($_POST["election-type"] == "1")){ echo "selected "; } ?> value="1">Federal Parliament</option>
                            <option <?php if(isset($_POST["election-type"]) && ($_POST["election-type"] == "2")){ echo "selected "; } ?>value="2" >Provincial Election</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="states">Select States</label>
                    <div class="col">
                        <select id="states" name="states" class="custom-select" onchange="getSelectedState(this.value)">
                            <option selected disabled>--- States ---</option>
                            <option <?php if(isset($_POST["states"]) && ($_POST["states"] == "1")){ echo "selected "; } ?> value="1">State - 1</option>
                            <option <?php if(isset($_POST["states"]) && ($_POST["states"] == "2")){ echo "selected "; } ?> value="2">State - 2</option>
                            <option <?php if(isset($_POST["states"]) && ($_POST["states"] == "3")){ echo "selected "; } ?> value="3">State - 3</option>
                            <option <?php if(isset($_POST["states"]) && ($_POST["states"] == "4")){ echo "selected "; } ?> value="4">State - 4</option>
                            <option <?php if(isset($_POST["states"]) && ($_POST["states"] == "5")){ echo "selected "; } ?> value="5">State - 5</option>
                            <option <?php if(isset($_POST["states"]) && ($_POST["states"] == "6")){ echo "selected "; } ?> value="6">State - 6</option>
                            <option <?php if(isset($_POST["states"]) && ($_POST["states"] == "7")){ echo "selected "; } ?> value="7">State - 7</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="districts">Select District</label>
                    <div class="col">
                        <select id="districts" name="district" class="custom-select" onchange="getSelectedDistricts(this.options[this.selectedIndex].text)">
                            <option selected disabled>--- Districts ---</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="constituencies">Constituency</label>
                    <div class="col">
                        <select id="constituency" name="constituency" class="custom-select" onchange="showAddedCandidates(this.value)">
                            <option selected disabled>Constituency Number</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "1")){ echo "selected "; } ?> value="1">1</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "2")){ echo "selected "; } ?> value="2">2</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "3")){ echo "selected "; } ?> value="3">3</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "4")){ echo "selected "; } ?> value="4">4</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "5")){ echo "selected "; } ?> value="5">5</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "6")){ echo "selected "; } ?> value="6">6</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "7")){ echo "selected "; } ?> value="7">7</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "8")){ echo "selected "; } ?> value="8">8</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "9")){ echo "selected "; } ?> value="9">9</option>
                            <option <?php if(isset($_POST["constituency"]) && ($_POST["constituency"] == "10")){ echo "selected "; } ?> value="10">10</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row" id="area-area" style="display: none;">
                    <label for="area">Select Area</label>
                    <div class="col">
                        <select id="area" name="area" class="custom-select" onchange="showCandidates(this.value)">
                            <option value="A" selected>A</option>
                            <option value="B" selected>B</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="districts">Gender</label>
                    <div class="col">
                        <select id="gender" name="gender" class="custom-select">
                            <option selected disabled> Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="party-name">Party Name</label>
                    <div class="col">
                        <select id="party" name="party" class="custom-select">
                            <option selected disabled> Party Name</option>
                            <option value="Nepali Congress">Nepali Congress</option>
                            <option value="CPN-UML">CPN-UML</option>
                            <option value="Maoist Center">Maoist Center</option>
                            <option value="RPP">RPP</option>
                            <option value="FSFN">FSFN</option>
                            <option value="RJP">RJP</option>
                            <option value="Bibekshil Sajha Party">Bibekshil Sajha Party</option>
                            <option value="Naya Shakti">Naya Shakti</option>
                            <option value="Janmorcha">Janmorcha</option>
                            <option value="RPP(D)">	RPP(D)</option>
                            <option value="Nepal Majdur Kisan">Nepal Majdur Kisan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="candidate-name">Candidate Name:</label>
                    <div class="col">
                        <input name="candidate-name" class="form-control" type="text" id="candidate-name">
                    </div>
                </div>

                <button type="submit" class="btn btn-md btn-primary">Save</button>
                <button class="btn btn-md btn-danger">Cancel</button>
            </form>
        </div>

        <!-- Show the candidates already in the database by using ajax request-->
        <div class="col-md-6">
            <h1>Candidates In Database</h1>
            <ul id="database-candidates">
                <h3 id="loading">Loading...</h3>
            </ul>
        </div>

    </div>
</div>



<!-- end bootstrap form -->
    <script type="text/javascript">
        var electionType;

        $(document).ready(function(){
            // get the added candidates on the basis of the election type
            var eltype = $('#election-type').find(":selected").val();

            $.ajax({url: "DistrictNames.php?eltype=" + eltype, success: function(result){

                constituency = result.trim();
//                //document.getElementById('Content').innerHTML = districtName

//                var eltype = $('#area-area').find(":selected").val();
                // add data to the #database-candidates id
                //$("#database-candidates").append(constituency);

                $.ajax({url: "../extras/showAddedCandidates.php?q=" + constituency + "&eltype=" + eltype, success: function(result) {
                    $("#loading").fadeOut();
                    $("#database-candidates").append(result);
                }});

            }});


        });

// $(document).ready(function(){
//
//            //var result;
//            var districtName;
//            var Name;
//
//            //alert(incorrectName);
//
//
//            $.post('DistrictNames.php', function(result) {
//                // result is the districtNo of the candidate
//                districtName = result.trim();
//                //document.getElementById('Content').innerHTML = districtName;
//
//                districtNo = districtName.substr(0,2);
//                Name = districtName.substr(2, districtName.length);
//                jd(Name, districtNo);
//                //alert(districtName);
//            });
//
//            function jd(district, value) {
//                var phtml = "<option selected " + "value='" + value + "'>" + district + "</option>";
//                return $("#districts").append(phtml);
//            }
//
//
////            $("#districts option").each(function(){
////                if ($(this).text() == districtName)
////                    $(this).attr("selected","selected");
////            });
//
//        });

        if ($('#election-type').find(":selected").val() == 1){
            $("#area-area").css("display", "none");
        } else if ($('#election-type').find(":selected").val() == 2){
            $("#area-area").css("display", "block");
        }

        // when the constituency changes
        function formChange(value){
            electionType = value;

            if(electionType == 2){
                $("#area-area").css("display", "block");
            } else if(electionType == 1){
                $("#area-area").css("display", "none");
            }
        }


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
                pd("Terathum", 12);
                pd("Udayapur", 13);
                pd("Panchthar", 14);
            }
            if (el.val() === "2") {
                pd("Bara", 15);
                pd("Dhanusha", 16);
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
                pd("Makawanpur", 30);
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
                pd("Kapilbastu", 52);
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
                // var phtml = "<option>" + district + "</option>";
                var phtml = "<option " + "value='" + value + "'>" + district + "</option>";
                return $("#districts").append(phtml);
            }
        });

    </script>

<?php include '../extras/footer.php';?>
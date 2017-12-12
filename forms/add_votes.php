

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

<h1 class="text-center">Add Votes</h1>
<div class="py-3"></div>

<div class="container">
    <div class="form">

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
                <select id="states" name="states" class="custom-select">
                    <option selected disabled>--- States ---</option>
                    <option value="1">State - 1</option>
                    <option value="2">State - 2</option>
                    <option value="3">State - 3</option>
                    <option value="4">State - 4</option>
                    <option value="5">State - 5</option>
                    <option value="6">State - 6</option>
                    <option value="7">State - 7</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="districts">Select District</label>
            <div class="col">
                <select id="districts" name="district" class="custom-select">
                    <option selected disabled>--- Districts ---</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="districts">Constituency</label>
            <div class="col">
                <select id="constituency" name="constituency" class="custom-select"
                        onchange="searchq();">
                    <option selected disabled>Constituency Number</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </div>
        </div>

<!--        <div class="form-group row">-->
<!--            <label for="candidate">Select Candidate</label>-->
<!--            <div class="col">-->
<!--                <select id="output-result" name="output-result" class="custom-select">-->
<!--                    <option selected disabled>Select Candidates</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <label for="votes">Votes</label> <input id="votes" type="text" name="votes" />-->


<!--        tables for candidates  -->

        <table class="table table-condensed table-hover" id="candidates-table">
            <thead>
                <tr>
                    <th>Candidate Name</th>
                    <th>Party Name</th>
                    <th>Votes</th>
                    <th>Final Votes</th>
                </tr>
            </thead>

            <tbody id="candidates-table-body">

            </tbody>
        </table>

        <div>
            <input type="submit" value="Add Votes" class="btn btn-primary" onclick="addVotesToCandidate()">
        </div>

    </div>
</div>

<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

<script type="text/javascript">
    function searchq(){

        var state = $('#states').find(":selected").val();
        var constituency = $('#constituency').find(":selected").val();
        var district = $('#districts').find(":selected").text();

        var constituencyname = district + " -" + constituency;

        $.post("search.php", {State: state, Constituencyname: constituencyname}, function(output){
            $("#candidates-table-body").html(output);
        });
    }

    function addVotesToCandidate(){

        var state = $('#states').find(":selected").val();
        var constituency = $('#constituency').find(":selected").val();
        var candidateName = $('#output-result').find(":selected").val();
        var district = $('#districts').find(":selected").text();

        var constituencyname = district + " -" + constituency;
        var votes = $('#votes').val();

//        $('#candidates-table > tbody  > tr').each(function(td) {
//            var candidate_data = [];
//
//            console.log($(this));
//
////            if($(this).text() != ""){
////                console.log($(this).text());
////            } else {
////                console.log($(this).find("input").val());
////            }
//
//        });
        var candidate_data = [];

        $('#candidates-table > tbody  > tr > td').each(function(td) {

            if($(this).text() != ""){
                var data = $(this).text();
                candidate_data.push(data);
            } else {
                var data = $(this).find("input").val();
                if(data == ""){
                    data = candidate_data[3];
                }
                candidate_data.push(data);
            }

            if(candidate_data.length == 4){
                addVotes(candidate_data);
                candidate_data.splice(0, candidate_data.length);
            }

        });

        // add data to the database
        function addVotes(data){
            $.post("add_votes2.php", {Votes: data[3], PartyName: data[1], CandidateName: data[0] }, function(output){

            });
        }

        alert("Successfully added Votes To the DATABASE");
        searchq();
    }

    function checkCheck(value, that){
        currentTD = that.closest('td');
        nearestTD = currentTD.closest('tr');
        nearestTD.each(function() {
            var aa = $(this).find(".votess").html();
        });
        console.log(aa);
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

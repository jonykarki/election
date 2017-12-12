<!-- get the header of the page -->
<?php
include 'extras/database.inc.php';
include 'extras/header.php';
include 'extras/map.php';
?>


<div class="search-area" style="background-color: #eee !important;">
    <div class="container">
        <div class="py-4"></div>
        <div class="row text-center">
            <div class="col-md-6">
                <button id="federal-button" class="btn btn-primary btn-lg">Federal Election</button>
            </div>

            <div class="col-md-6">
                <button id="provincial-button" class="btn btn-primary btn-lg">Provincial Assembly</button>
            </div>
        </div>

        <!-- search-area-title -->
        <div class="py-4"></div>
        <div class="search-area-title text-center" style="color: rgb(139, 136, 136)">
            <h3 id="election-title">Federal Parliament Election 2017</h3>
            <h4>Search Candidates</h4>
        </div>
        <div class="py-3"></div>

<!--       On form submission use ajax-->

        <div class="form-row">
            <div class="col-md-2"></div>
            <div class="col">
                <select id="states" name="states" class="custom-select" onchange="sendState(this.value)">
                    <option selected disabled>States</option>
                    <option <?php if(isset($_GET["states"]) && ($_GET["states"] == 1)){ echo "selected";} ?> value="1">State - 1</option>
                    <option <?php if(isset($_GET["states"]) && ($_GET["states"] == 2)){ echo "selected";} ?> value="2">State - 2</option>
                    <option <?php if(isset($_GET["states"]) && ($_GET["states"] == 3)){ echo "selected";} ?> value="3">State - 3</option>
                    <option <?php if(isset($_GET["states"]) && ($_GET["states"] == 4)){ echo "selected";} ?> value="4">State - 4</option>
                    <option <?php if(isset($_GET["states"]) && ($_GET["states"] == 5)){ echo "selected";} ?> value="5">State - 5</option>
                    <option <?php if(isset($_GET["states"]) && ($_GET["states"] == 6)){ echo "selected";} ?> value="6">State - 6</option>
                    <option <?php if(isset($_GET["states"]) && ($_GET["states"] == 7)){ echo "selected";} ?> value="7">State - 7</option>
                </select>
            </div>
            <div class="col">
                <select id="districts" name="district" class="custom-select" onchange="sendDistrict(this.value)">
                    <option selected disabled>Districts</option>
                </select>
            </div>
            <div class="col">

                <!-- Whenever the user clicks the View Results button call the showTables Function in the database -->
                <button type="submit" class="btn btn-primary" onclick="showTables()">View Results</button>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="py-3"></div>

        <div id="tables">
            <!-- The tables with the data from the database `result` Goes Here -->

        </div>
        <!-- add the table dynamically -->

        <div class="py-4"></div>
    </div>
</div>




<!-- Search Area JavaScript -->
<script type="text/javascript">
    // $(document).ready(function () {
    $("#states").change(function () {
        $("#districts option").remove();
        var el = $(this);
        if (el.val() === "1") {
            pd("Bhojpur", 1);
            pd("Dhankuta", 2);
            pd("Ilam", 3);
            pd("Jhapa", 4);
            pd("Khotang", 5);
            pd("Morang", 6);
            pd("Okhaldhunga", 7);
            pd("Sankhuwasabha", 8);
            pd("Solukhumbu", 9);
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
    // });
</script>
<script src="extras/main-script.js"></script>

<!-- close the dabase connection once the page finishes loading -->
<?php 
mysqli_close($conn);
?>


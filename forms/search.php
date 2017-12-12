<?php

include '../extras/database.inc.php';
include '../extras/functions.inc.php';

if(isset($_POST['State'])){
    $state = intval($_POST['State']);
    $constituencyName = $_POST['Constituencyname'];

    $results = getSearchCandidate($conn, $state, $constituencyName);
    while($result = mysqli_fetch_assoc($results)){
        echo "<tr>";
        echo "<td>" . $result['candidate'] ."</td>";
        echo "<td>" . $result['partyname'] ."</td>";
        echo "<td class='votess'>" . $result['votes'] ."</td>";
        echo "<td><input type=\"text\" placeholder=\"final votes\" class=\"form-control\" onkeyup='checkCheck(this.value, this);'></td>";
        echo "</tr>";
    }
}

?>
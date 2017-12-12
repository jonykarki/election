<?php

include '../extras/database.inc.php';
include '../extras/functions.inc.php';

@$votes = $_POST['Votes'];
@$partyname = $_POST['PartyName'];
@$candidateName = $_POST['CandidateName'];

$result = addVotesToCandidate($conn, $votes, $partyname, $candidateName);
echo $result;

?>
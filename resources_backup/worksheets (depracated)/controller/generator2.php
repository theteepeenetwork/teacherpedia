<?php

require_once "classes/Number.php";
require_once "classes/Measurement.php";


$_SESSION['year'] = $_POST['year'];


$questions = $_POST['id'];

//remove zero values and reassign array index. 
//$qty = array_values(array_filter($_GET['qty']));

$number = New Number();

$all_questions = [];
$all_answers = [];



//get pass questions from form to class and get returned array
//index of qty to match questions array index
$index = 0;
$qtnNum = 1;
foreach ($questions as $question => $qty) {
    // Pass $question to class
    for($i = 0; $i < $qty[0]; $i++) {
        $result = $number->$question();
        //add question numbers
        $qtn = $qtnNum . ") " . $result["qtn"];
        $aswr = $qtnNum . ") " . $result["aswr"]; 
        //add to questions and answers array
        $all_questions[] .= $qtn;
        $all_answers[] .= $aswr;
        //increase qtn number
        $qtnNum++;
    }
    // increase qty index
    $index++;
}

?>

<!DOCTYPE html>
<html>
<body>

<h1>Place value</h1>
    <ul>
        <? 
        foreach($all_questions as $c) {
            echo "<li>" . $c . "</li><br/>";    
        }
        ?>
    </ul>
    
    <ul>
        <? 
        foreach($all_answers as $d) {
            echo "<li>" . $d . "</li><br/>";    
        }
        ?>
    </ul>
    
</body>
</html>

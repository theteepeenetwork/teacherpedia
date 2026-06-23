<?php

include_once ("Number.php");
//include_once ("classes/Measurement.php");

$_SESSION['year'] = "y5";
$_SESSION['sheetName'] = $_POST['sheetName'];
if($_SESSION['sheetName'] === "") {
    $_SESSION['sheetName'] = "Mental Starter";
}

$all_questions = [];
$all_answers = [];

if(!isset($_POST['id'])) {
    $_SESSION['sheetName'] = "<h1>No Questions selected, please go back and choose at least one question using the drop down boxes.</h1>";
} else {

$questions = $_POST['id'];

//Declare arrays


//$qty = array_values(array_filter($_GET['qty']));

$number = New Number();

//randomise question order

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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Last Minute Teacher</title>


<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
        <!-- Latest compiled and minified CSS -->
<link media="print" type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/print.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/a4-worksheet.css') ?>">
<style>

    

</style>

</head>

<body>
        <div id="infobox">
            <FORM>
                <INPUT TYPE="button" class="button" onClick="history.go(0)" VALUE="Generate new questions">
            </FORM>
        </div>
        <div>
            <page class="page-break-within" size="A4">
                <h1><?php echo $_SESSION['sheetName'] ?></h1>
                <img id="char" src="<?php echo base_url(); ?>images/superboy.png" alt=""></img>

                <!--<img id="mathsboy" src="images/mathsboy.jpg"></img>-->
                <ul>
                    <?php 
                      foreach($all_questions as $c) 
                      {
                        echo "<li>" . $c . "</li><br/>";    
                      }
                    ?>
                </ul>
                <div class="logo">
                    <img id="logo" src="<?php echo base_url(); ?>images/logo.png" alt="Smiley face"></img>
                </div>
                <div class="date">
                    <ul>
                        <?php echo date("Y"); ?>
                    </ul>
                </div>
            </page>

            <page class="break-before"   size="A4">
                <h1>Answers</h1>
                <ul>
                    <?php
                foreach($all_answers as $d) 
                {
                    echo "<li>" . $d . "</li><br/>";    
                }
            ?>
                </ul>
                <div class="logo">
                    <img id="logo" src="<?php echo base_url(); ?>images/logo.png" alt="Smiley face"></img>
                </div>
                <div class="date">
                    <ul>
                        <?php echo date("Y"); ?>
                    </ul>
                </div>
            </page>
        </div>
</body>
</html>

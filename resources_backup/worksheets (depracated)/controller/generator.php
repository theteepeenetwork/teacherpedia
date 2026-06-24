<?php

include_once ("classes/Number.php");
//include_once ("classes/Measurement.php");

$_SESSION['year'] = $_POST['year'];
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

<style>

body {
  background: rgb(204,204,204); 
  font-family: arial;
}
page {
  background: white;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
}
page[size="A4"] {  
  width: 21cm;
  height: 29.7cm; 
}

.innerPage {
    padding: 10px;
}

h1 {
    text-align: center;
    margin-top: 50px;
}

ul li {
    list-style: none;
}

#infobox {
  text-align: center;
  margin: 0;
  font-size: 1.2em;
}

  #logo {
    float-left;
    margin-left:600px; 
    position: absolute; 
    margin: none;
    max-width: 150px;
  }

#mathsboy {
    width: 100px;
    position: relative;
    left: 600px;
    bottom: 20;
    border: 3px solid #73AD21;
}

.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}

#refreshbutton  {
  margin: 50px;
}


@media print {
    h6 {
        page-break-before: always;
    }
  page {
    margin: 0;
    box-shadow: 0;
    page-break-before: always;
  }
}

#infobox,#header,#header2 .noprint {display:none}

</style>

<link rel="stylesheet" type="text/css" media="print" href="print.css" />

</head>
<body>
    <div id="infobox">
        <FORM>
            <INPUT id="refreshbutton" TYPE="button" class="button" onClick="history.go(0)" VALUE="Generate new questions">
        </FORM>
    </div>

<page size="A4">
    <div class=innerPage>
        <img id="logo" src="../../images/logo.png" alt="Smiley face">
        <h1><?php echo $_SESSION['sheetName'] ?></h1>
        
        <!--<img id="mathsboy" src="images/mathsboy.jpg"></img>-->
        <ul>
            <?php 
                foreach($all_questions as $c) 
                {
                    echo "<li>" . $c . "</li><br/>";    
                }
            ?>
        </ul>
    </div>
</page>
    
<page size="A4">
    <div class=innerPage>
        <h1>Answers</h1>
        <ul>
            <?php
                foreach($all_answers as $d) 
                {
                    echo "<li>" . $d . "</li><br/>";    
                }
            ?>
        </ul>
    </div>
</page>
    
</body>
</html>

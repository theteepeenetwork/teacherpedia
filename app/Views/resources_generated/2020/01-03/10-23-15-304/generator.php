<?php

include_once ("Number.php");
//include_once ("classes/Measurement.php");

$_SESSION['year'] = "y3";
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

<style>

    body {
      background: rgb(204,204,204); 
      font-family: arial;
      overflow: visible;
    }

    page {
      font-size: 0.75em;
      position: relative;
      background: white;
      display: block;
      margin: 0 auto;
      margin-bottom: 0.5cm;
      box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    }
    page[size="A4"] {  
      width: 21cm;
      height: 29.7cm; 
    }

    @media print {
      body, page {
        margin: 0;
        box-shadow: 0 0 0cm rgba(0,0,0,0);
      }
      #infobox {
        display:none;
      }
      div.footer {
        display: none;
      }
      .break-before {
      break-before: page;
}
      .page-break-within {
        break-inside: avoid;
      }
nav {
  display:none;
}
    }

    h1 {
        text-align: center;
    }

    ul li {
        list-style: none;
    }

    #infobox {
      text-align: center;
      margin: 0;
      font-size: 1.2em;
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
      margin: 50px;
    }

    div.date {
      position: absolute;
      bottom: 10px;
      right: 100px;
    }
    div.image {
      position: absolute;
      right: 50px;
      top: 10px;
    }

    #logo {
      position: absolute;
      bottom: 40px;
      right: 50px;
      max-width: 100px;
    }

    #char {
      position: absolute;
      right: 10px;
      top: 10px;
      max-width: 150px;
    }

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
                <img id="char" src="<?php echo base_url(); ?>images/superboy.png" alt="Smiley face"></img>

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

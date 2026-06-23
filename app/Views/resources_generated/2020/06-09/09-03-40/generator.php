<?php

use App\Libraries\Resources;
use App\Libraries\Number;

$resources_library = new Resources();
$number = new Number();


//include_once ("classes/Measurement.php");

$_SESSION['year'] = "y5";
$_SESSION['sheetName'] = $_POST['sheetName'];
if ($_SESSION['sheetName'] === "") {
  $_SESSION['sheetName'] = "Mental Starter";
}

$all_questions = [];
$all_answers = [];
$all_answers_for_arrray = [];
$answer_array;

if (!isset($_POST['id'])) {
  $_SESSION['sheetName'] = "<h1>No Questions selected, please go back and choose at least one question using the drop down boxes.</h1>";
} else {

  $questions = $_POST['id'];

  //Declare arrays


  //$qty = array_values(array_filter($_GET['qty']));

  $number = new Number;

  //randomise question order

  //get pass questions from form to class and get returned array
  //index of qty to match questions array index
  $index = 0;
  $qtnNum = 1;

  foreach ($questions as $question => $qty) {
    // Pass $question to class
    for ($i = 0; $i < $qty[0]; $i++) {
      $result = $number->$question();
      //add question numbers
      $qtn = $qtnNum . ") " . $result["qtn"];
      $aswr = $qtnNum . ") " . $result["aswr"];
      $aswr_for_array = $result["aswr"];
      //add to questions and answers array
      $all_questions[] .= $qtn;
      $all_answers[] .= $aswr;
      $all_answers_for_arrray[] .= $aswr_for_array;
      //increase qtn number
      $qtnNum++;
    }
    // increase qty index
    $index++;
  }
}

if (isset($_POST["answer_array"])) {
  if ($_POST["answer_array"] === "yes")
    $answer_array = $resources_library->answer_array($all_answers_for_arrray);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Teacherpedia - Y5 Objectives Worksheet</title>


  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
  <!-- Latest compiled and minified CSS -->
  <link media="print" type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/print.css') ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Kite+One&display=swap" rel="stylesheet">

  <style>
    body {
      background: rgb(204, 204, 204);
      font-family: 'Kite One', sans-serif;
      font-size: 1.3em;
      overflow: visible;
    }

    page {
      font-size: 0.75em;
      position: relative;
      background: white;
      display: block;
      margin: 0 auto;
      margin-bottom: 0.5cm;
      box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
    }

    page[size="A4"] {
      width: 21cm;
      height: 29.7cm;
    }

    @media print {

      body,
      page {
        margin: 0;
        box-shadow: 0 0 0cm rgba(0, 0, 0, 0);
      }

      #infobox {
        display: none;
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
        display: none;
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
      background-color: #4CAF50;
      /* Green */
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

    div ul {
      line-height: 1.6;
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

    .answer_array th,
    td {
      border: 1px black solid;
      margin: 20px;
      padding: 5px;
    }

    .answer_array table {
      width: 80%;
      font-size: 0.8rem;

      max-width: 80%;
    }

    .answer_array td {
      width: 25%;

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
      <img id="char" src="https://res.cloudinary.com/teacherpedia/image/upload/v1598610912/characters/girl2-2_nxgrwb.png" alt="Smiley face"></img>

      <!--<img id="mathsboy" src="images/mathsboy.jpg"></img>-->
      <div>
        <ul>
          <?php
          foreach ($all_questions as $c) {
            echo "<li>" . $c . "</li>";
          }
          ?>
        </ul>
      </div>
      <div class="logo">
        <img id="logo" src="<?php echo base_url(); ?>images/logo.png" alt="Smiley face"></img>
      </div>
      <div class="answer_array">

        <?php if (isset($answer_array)) {
          echo "<h2>Check for your answer below</h2>";
          echo $answer_array;
        }
        ?>
      </div>
      <div class="date">
        <ul>
          <?php echo date("Y"); ?>
        </ul>
      </div>
    </page>

    <page class="break-before" size="A4">
      <h1>Answers</h1>
      <div>
        <ul>
          <?php
          foreach ($all_answers as $d) {
            echo "<li>" . $d . "</li>";
          }
          ?>
        </ul>
      </div>
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
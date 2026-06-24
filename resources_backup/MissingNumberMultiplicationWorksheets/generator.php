<?php

$_SESSION['topNumber'] = $_POST['topNumber'];
$_SESSION['bottomNumber'] = $_POST['bottomNumber'];
$_SESSION['calculation'] = $_POST['calculation'];
$_SESSION['$remove'] = $_POST['remove'];

  $questions = array();
  $question = array();
  $answers = array();
  $calculation = "multiplication";
  $noOfQuestions = 32; 
   //placeholder for user choice
   //placeholder for user choice



  function createNumberString($array) {
    $questionString = "";
      foreach($array as $a) {
        $questionString .= '<div class="digit">' . $a . '</div>';
      }
      return $questionString;
    }


    function createQuestion($calculation, $counter) { 
      $numberOfDigitsTop = $_SESSION['topNumber'];
      $numberOfDigitsBottom = $_SESSION['bottomNumber'];
  
      switch($numberOfDigitsTop) {
        case 1:
        $a = rand(1,9);
        break;
        case 2:
        $a = rand(9,99);
        break;
        case 3:
        $a = rand(99,999);
        break;
        case 4:
        $a = rand(99,9999);
        break;
        case 5:
        $a = rand(9999,99999);
        break;
        case 6:
        $a = rand(99999,999999);
        break;
        case 7:
        $a = rand(999999,9999999);
        break;
  
      }
  
      switch($numberOfDigitsBottom) {
        case 1:
        $b = rand(1,9);
        break;
        case 2:
        $b = rand(9,99);
        break;
        case 3:
        $b = rand(99,999);
        break;
        case 4:
        $b = rand(999,9999);
        break;
        case 5:
        $b = rand(9999,99999);
        break;
        case 6:
        $b = rand(99999,999999);
        break;
        case 7:
        $b = rand(999999,9999999);
        break;
  
      }
  
      while($b > $a) {
        $b = $b - floor($a / 2);
      }
  


    $answer;
    $symbol;

    switch($calculation) {
      case "addition": 
      $answer = $a + $b;
      $symbol = "+";
      break;
      case "subtraction": 
      $answer = $a - $b;
      $symbol = "-";
      break;
      case "multiplication": 
      $answer = $a * $b;
      $symbol = "x";
      break;
    }

    $arrayA = array_reverse(str_split($a));
    $answerArrayA = array_reverse(str_split($a));
    $arrayB = array_reverse(str_split($b));
    $answerArrayB = array_reverse(str_split($b));
    $arrayAnswer = array_reverse(str_split($answer));
    $calculationArray = array();

    $toRemove = $_SESSION['$remove'];
    $storedNumbers = array();
    while ($toRemove > 0) {
      $remove1 = rand(0, count($arrayA) - 1);
      
      if(!in_array($remove1, $storedNumbers)) {
        array_push($storedNumbers, $remove1);
        $arrayA[$remove1] = "_";
        $toRemove--;
      }
      if($toRemove > 1) {
        $remove2 = rand(0, count($arrayB) - 1);
        if(!in_array($remove2, $storedNumbers)) {
          array_push($storedNumbers, $remove2);
          $arrayB[$remove2] = "_";
          $toRemove--;
        }
      }
    }

    $calculationArray[0] = '<div class="questionContainer">'. $counter . ") " . createNumberString($arrayA) . "<br />";
    $calculationArray[1] = $symbol . createNumberString($arrayB) . "<br /><div class='line'></div>";
    $calculationArray[2] = createNumberString($arrayAnswer) . "<br /></div>";

    $calculationArray[3] = '<div class="questionContainer">'. $counter . ") " . createNumberString($answerArrayA) . "<br />";
    $calculationArray[4] = $symbol . createNumberString($answerArrayB) . "<br /><div class='line'></div>";
    $calculationArray[5] = createNumberString($arrayAnswer) . "<br /></div>";

    

    return $calculationArray;
    }

//run program. Create question generates numnbers and build string by calling function createNumberString
    for($i = 0; $i < $noOfQuestions; $i++) {
      $question = createQuestion($calculation, $i+1);
      $question2 = array_splice($question, 3, 3);
      foreach($question as $line) {
        array_push($questions, $line);
      }
      foreach($question2 as $line) {
      array_push($answers, $line);
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
<link rel="stylesheet" type="text/css" href=<?php '"' . base_url() . 'assets/css/print.css' . '"'?> />

<style>

body {
      background: rgb(204,204,204); 
      font-family: arial;
      overflow: visible;
    }

    page {
      font-size: 1em;
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

    #infobox {
      text-align: center;
      margin: 0;
      font-size: 1.2em;
    }

    .outerContainer {
      position: inherit;
      
    }

    .questionContainer {
      
      display: inline-block;
      width: 140px;
      height: 80px;
      margin: 20px;
    }

    .digit {
      width: 12px;
      float: right;

    }

    .line {
      border: 2px solid black;
      width: 100%;
    }

</style>

</head>

<body>
    <page class="page-break-within" size="A4">
      <div id="infobox">
        <h1> <?php echo "Missing " . ucfirst($calculation) . " Problems" ?></h1>
      </div>
      <div class=outerContainer>

      <?php 
                foreach($questions as $question) {
                  echo $question;
                }
                ?>
        
              </div>
            </page>

            <page class="break-before"   size="A4">
              <div id="infobox">
        <h1> <?php echo ucfirst($calculation) . " Answers" ?></h1>
      </div>
              <?php 
                foreach($answers as $answer) {
                  echo $answer;
                }
                ?>
               
            </page>
</body>
</html>

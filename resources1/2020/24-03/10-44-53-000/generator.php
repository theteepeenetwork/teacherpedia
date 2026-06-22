<?php

$topNumber = intval($_POST['topNumber']);
$bottomNumber = intval($_POST['bottomNumber']);
$code = $_POST['code'];
$code = cleanString($code);
$codeArray = str_split($code);
$counter = 2;
$question = $_POST['question'];

$title = "Can you crack the code?";

if(isset($_POST['question'])) {
    $title = $question;
}
//$_SESSION['calculation'] = $_POST['calculation'];

  $alphabet = array();
  $alphabetTable = "";
  $questionTable = "<td style='border: none'>Word 1</td>";
  $blankAnswerString = "Word " . "1 = ";
  $word = 2;
   $alphabet = linkAlphabetAndNumbers();
   $topNumbers = makeNummbers($topNumber);
   $bottomNumbers = makeNummbers($bottomNumber);
   $bottomNumbers = checkNumber($topNumbers, $bottomNumbers);
   $answers = getAnswers($topNumbers, $bottomNumbers);
   $printAnswers = "";

   function makeNummbers($number) {
    for($i = 0;$i < 26; $i++) {
      $array[$i] = createNumber($number);
    }
    return $array;
  }

   function createNumber($a) {
    switch($a) {
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
      echo "in function: " . $a;
      

    }
    return $a;
  }

   function linkAlphabetAndNumbers() {
    for($i = 0;$i < 26; $i++) {
      $array[$i] = "<div class='letter'>" . chr($i + 65) . "</div>";
    }
    return $array;
  }

  function getAnswers($t, $b) {
    for($i = 0;$i < count($t); $i++) {
      $array[$i] = number_format($t[$i] * $b[$i]);
    }
    return $array;
  }

  function checkNumber($numberA, $numberB) {
    for($i = 0; $i < 26; $i++) {
      while($numberB[$i] > $numberA[$i]) {
        $numberB[$i] = $numberB[$i] - floor($numberA[$i] / 2);
      }
      
    }
    return $numberB;
  }

  function printAnswers($top, $bottom, $answer, $letters) {
    $string = "";
    for($i = 0; $i < count($letters); $i++) {
      $string .= "<tr><td>" . $top[$i] . " x " . $bottom[$i] . " = " . $answer[$i] . "</td><td>" . $letters[$i] . "</td></tr>";
    }
    return $string;
  }

  function cleanString($string) {
    $string = strtolower($string);
    $string = preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    $string = trim($string, " ");
    return $string;
  }

  for($y = 0; $y < 26; $y++) {
    if($y % 10 === 0) {
      $alphabetTable .= "</tr><tr>";
    }
    $alphabetTable .= "<td>" . $alphabet[$y] . "" . $answers[$y] . "</td>";
  }

  //match the letter to the answer array
  
  foreach($codeArray as $letter) {
    $ascii = ord($letter) - 97;
    if($ascii < 0) {
      $questionTable .= "</tr><td style='border: none'>Word " . $counter; 
      $blankAnswerString .= "<br /><br />Word " . $word . " = ";
      $word++;
      $counter++;
    } else {
      $letterNumber = $ascii;
        $questionTable .= "<td>" . " " . $topNumbers[$letterNumber] . " x " . $bottomNumbers[$letterNumber];
        $printAnswers .= "<tr><td>" . $topNumbers[$letterNumber] . " x " . $bottomNumbers[$letterNumber] . " = " . $topNumbers[$letterNumber] * $bottomNumbers[$letterNumber] . "</td><td>" . $letter . "</td></tr>";
        $blankAnswerString .= "___ ";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Teacherpedia</title>


<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" type="text/css" href=<?php '"' . base_url() . 'assets/css/print.css' . '"'?> />

<style>

body {
      background: rgb(204,204,204); 
      font-family: arial;
      overflow: visible;
    }
    h1 {
      font-family: 'Orbitron', sans-serif;
      text-align: center;
    }
    h2 {
      font-family: 'Orbitron', sans-serif;
      text-align: center;
    }
    h4 {
      font-family: 'Geo', sans-serif;
      font-size: 1.1em;
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

      border: black 1px solid;
      padding: 10px;
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
    }

    table {
        width: 100%;
      }
    td {
      border: black 1px solid;
      text-align: center;
    }

    .letter {
      font-size: 1.5em;
    }

    #instructions {
      border: black 4px solid;
      padding: 10px;
    }
    

</style>
<link href="https://fonts.googleapis.com/css?family=Geo|Orbitron&display=swap" rel="stylesheet">
</head>

<body>
  <page class="page-break-within" size="A4">
    <h1><?php echo $title; ?></h1>
<!--    <div id="instructions">
      <h3> Instructions</h3>
        <p>Step 1: Each question is a letter of a word.</p> 
        <p>Step 2: Match the answer to a letter from the table</p> 
        <p>Step 1: Write the letters in the spaces at the bottom.</p> 
    </div>-->
    <h2>The Code</h2>
    <h4>The letters have been replaced with questions. Solve the questions and match the answer to the missing letter</h4>
    <table>
      <?php echo $questionTable; ?>
    </table>
    <h4>The  answers to the above questions should match one of the letters below. </h4>
  <table>
    <?php echo $alphabetTable ?>
  </table>
  <h4>Write your answer below.</h4>
  
  <p><?php echo $blankAnswerString ?></p>
  </page>
  

  <page class="page-break-within" size="A4">
  <h1>Answers</h1>
  <table>
    <?php echo $printAnswers; ?>
  </table>

  </page>
</body>
</html>

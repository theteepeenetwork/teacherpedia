<?php

$_SESSION['topNumber'] = $_POST['topNumber'];
$_SESSION['bottomNumber'] = $_POST['bottomNumber'];
$_SESSION['calculation'] = $_POST['calculation'];

  $questions = array();
  $question = array();
  $answers = array();
  $calculation = $_SESSION['calculation'];
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
    $arrayB = array_reverse(str_split($b));
    $arrayAnswer = array_reverse(str_split($answer));
    $calculationArray = array();

    $calculationArray[0] = '<div class="questionContainer">'. $counter . ") " . createNumberString($arrayA) . "<br />";
    $calculationArray[1] = $symbol . createNumberString($arrayB) . "<br /><div class='line'></div>";
    $calculationArray[2] = createNumberString($arrayAnswer) . "<br /></div>";

    return $calculationArray;
    }

//run program. Create question generates numnbers and build string by calling function createNumberString
    for($i = 0; $i < $noOfQuestions; $i++) {
      $question = createQuestion($calculation, $i+1);
      foreach($question as $line) {
        array_push($questions, $line);
    }
  }
    


?>


<head>

    <style>
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
            width: 16px;
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
        
        <h1> <?php echo ucfirst($calculation) . " Problems" ?></h1>
      <div class=outerContainer>

                <?php 
                 for ($i = 0; $i < count($questions); $i++ ) {
                   if (($i + 1) % 3 == 0) {
                          echo "</div>";
                        }
                        else {
                          echo $questions[$i];
                        }
                   }  
                ?>
        
              </div>
              <?php echo $footer; ?>
            </page>

            <page  class="page-break-within"  size="A4">
        <h1> <?php echo ucfirst($calculation) . " Answers" ?></h1>
              <?php 
                foreach($questions as $question) {
                  echo $question;
                }
                ?>
               <?php echo $footer; ?>
            </page>
</body>
</html>

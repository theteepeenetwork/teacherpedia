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

    <div>
        <page class="page-break-within" size="A4">
            <h1><?php echo $_SESSION['sheetName'] ?></h1>
            <img id="char"
                src="https://res.cloudinary.com/teacherpedia/image/upload/v1598610912/characters/girl2-2_nxgrwb.png"
                alt="Smiley face"></img>

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
            <?php if (isset($answer_array)) {
                echo "<h2>Check for your answer below</h2>";
                echo $answer_array;
            }

            echo $footer;
            ?>
            
        </page>

        <page size="A4">
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
<?php echo $footer ?>
<?php
$mults = $_POST['multipliers'];
$sizeOfMults = count($mults);
$a = [];
$b = [];
$c = [];
$questions = [];
$answers = [];

for ($i = 0; $i < 100; $i++) {
    $a[$i] = rand(1, 12);
    $select = rand(0, $sizeOfMults - 1);
    $b[$i] = $mults[$select];
    $c[$i] = $a[$i] * $b[$i];
    $questions[$i] = $i + 1 . ") " . $a[$i] . " x " . $b[$i] . " = ";
    $answers[$i] = $i + 1 . ") " . $a[$i] . " x " . $b[$i] . " = " . $c[$i];
}


?>

<head>
    <style>
        body {
            font-family: roboto;
        }

        table {
            width: 100%;
            font-size: 1em;
        }

        h1 {
            text-align: center;
        }

        .nameTable {
            top: 40px;
            left: 20%;
            position: absolute;
            width: 60%;
        }

        .questions {
            width: 100%;

            position: absolute;
            top: 150px;
        }

        .date {
            position: absolute;
            bottom: 0px;
        }

        td {
            width: 50%;
            height: 28px;
            border: solid black 1px;
            border-radius: 25px;
        }

        .question {
            width: 15%;
        }

        body {
            background: rgb(204, 204, 204);
            font-family: arial;
            overflow: visible;
            size: ;
        }

        #char-left {
            width: 150px;
            position: absolute;
        }

        #char-right {
            width: 150px;
            position: absolute;
            right: 0px;
            top: 0px;
            transform: scaleX(-1);
        }


    </style>
    <link media="print" type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/print.css') ?>" />
</head>

<body>

    <page class="page-break-within" size="A4">
        <h1><?php //echo $_SESSION['sheetName'] 
            ?></h1>
        <img id="char-left" src="https://res.cloudinary.com/teacherpedia/image/upload/v1598610911/characters/boy1-2_bq4twc.png" alt="Teacherpedia mascot boy1"></img>

        <h1>Times Table Challenge</h1>
        <table class="nameTable">
            <tr>
                <td>
                    <p>Name: </p>
                </td>
                <td>
                    <p>Previous score:</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Date: </p>
                </td>
                <td>
                    <p>Today's Score:</p>
                </td>
            </tr>
        </table>
        <img id="char-right" src="https://res.cloudinary.com/teacherpedia/image/upload/v1598610911/characters/boy1-2_bq4twc.png" alt="teacherpedia mascot boy1"></img>
        <table class="questions">
            <?php for ($x = 0; $x < 100; $x++) { ?>
                <tr>
                    <td class="question"><?php echo $questions[$x];
                                            $x++; ?></td>
                    <td class="question"><?php echo $questions[$x];
                                            $x++; ?></td>
                    <td class="question"><?php echo $questions[$x];
                                            $x++; ?></td>
                    <td class="question"><?php echo $questions[$x];
                                            $x++; ?></td>
                    <td class="question"><?php echo $questions[$x]; ?></td>
                </tr>
            <?php } ?>
        </table>

        <?php echo $footer; ?>
    </page>

    <page size="A4">
        <h1>Answers</h1>
        <table>
            <?php for ($x = 0; $x < 100; $x++) { ?>
                <tr>
                    <td class="question"><?php echo $answers[$x];
                                            $x++; ?></td>
                    <td class="question"><?php echo $answers[$x];
                                            $x++; ?></td>
                    <td class="question"><?php echo $answers[$x];
                                            $x++; ?></td>
                    <td class="question"><?php echo $answers[$x];
                                            $x++; ?></td>
                    <td class="question"><?php echo $answers[$x]; ?></td>
                </tr>
            <?php } ?>
        </table>

        <?php echo $footer; ?>
    </page>

    

</body>
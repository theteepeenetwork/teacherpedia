
<?php 
$mults = $this->input->post('multipliers');
$sizeOfMults = count($mults);
$a = [];
$b = [];
$c = [];
$questions = [];
$answers = [];

for($i = 0; $i < 100; $i++) {
    $a[$i] = rand(1, 12);
    $select = rand(0, $sizeOfMults-1);
    $b[$i] = $mults[$select];
    $c[$i] = $a[$i] * $b[$i];
    $questions[$i] = $i + 1 . ") " . $a[$i] . " x " . $b[$i] . " = ";
    $answers[$i] = $i + 1 . ") " . $a[$i] . " x " . $b[$i] . " = " . $c[$i];
}


?>

<!DOCTYPE html>
<html>
<head>
<style type="text/css">

        body {
            font-family: roboto;
        }

        table {
            width: 100%;
            font-size: 1em;
        }

        h1 {
            text-align:  center;
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
            height: 40px;
            border: solid black 1px;
            border-radius: 25px;
        }

        .question {
            width: 20%;
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
            #char {
                width: 100px;
            }
            #top {
                border: solid black 1px;
            }
        }
    </style>
</head>
<body>

<page class="page-break-within" size="A4">
        <h1><?php //echo $_SESSION['sheetName'] ?></h1>
        <img id="char-left" src="<?php echo base_url(); ?>images/superboy.png" alt="Smiley face"></img>
        
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
        <img id="char-right" src="<?php echo base_url(); ?>images/superboy.png" alt="Smiley face"></img>
        <table class="questions">
            <?php for($x = 0; $x < 100; $x++){ ?>
            <tr>
                <td class="question"><?php echo $questions[$x]; $x++; ?></td>
                <td class="question"><?php echo $questions[$x]; $x++;?></td>
                <td class="question"><?php echo $questions[$x]; $x++;?></td>
                <td class="question"><?php echo $questions[$x]; $x++;?></td>
                <td class="question"><?php echo $questions[$x];?></td>
            </tr>
            <?php } ?>
        </table>

        <div class="date">
            <ul>
                <?php echo "Teacherpedia " . date("Y"); ?>
            </ul>
        </div>
    </page>

    <page class="break-before" size="A4">
        <h1>Answers</h1>
        <table>
            <?php for($x = 0; $x < 100; $x++){ ?>
            <tr>
                <td class="question"><?php echo $answers[$x]; $x++; ?></td>
                <td class="question"><?php echo $answers[$x]; $x++;?></td>
                <td class="question"><?php echo $answers[$x]; $x++;?></td>
                <td class="question"><?php echo $answers[$x]; $x++;?></td>
                <td class="question"><?php echo $answers[$x];?></td>
            </tr>
            <?php } ?>
        </table>
        <div class="logo">
        </div>
        <div class="date">
            <ul>
            <?php echo "Teacherpedia " . date("Y"); ?>
            </ul>
        </div>
    </page>

    <div class="page-break"></div>

</body>

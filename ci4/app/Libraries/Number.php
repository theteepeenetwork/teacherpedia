<?php

namespace App\Libraries;


class Number
{
    public function count_in_twos()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(0, 20);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 50);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 500);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 10000);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 100000);
            $b = $a + 10;
        } else {
            $a = rand(1, 1000000);
            $b = $a + 10;
        }

        $answr = "";
        $qtnstrg = "finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 10 === $a) {
                $qtnstrg .= $a . " ";
            } elseif ($b - 6 === $a) {
                $qtnstrg .= $a . " ";
            } else {
                $qtnstrg .= " _____ &rarr;";
            }

            $answr .= " &rarr; " . $a;
            $a += 2;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    } //year 1-6
    public

    function count_in_twos_zero()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = 0;
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = 0;
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = 0;
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = 0;
            $b = $a + 10;
        } else {
            $a = 0;
            $b = $a + 10;
        }

        $answr = "";
        $qtnstrg = "finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 10 === $a) {
                $qtnstrg .= $a . " ";
            } elseif ($b - 6 === $a) {
                $qtnstrg .= $a . " ";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= " &rarr; " . $a;
            $a += 2;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    } //year 1-6
    public

    function count_in_fives()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(0, 50);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000);
            $b = $a + 25;
        } else {
            $a = rand(1, 1000);
            $b = $a + 25;
        }

        $answr = "";
        $qtnstrg = "<strong>Finish the number line: </strong>";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 25 === $a) {
                $qtnstrg .= $a . " ";
            } elseif ($b - 15 === $a) {
                $qtnstrg .= $a . " ";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= $a . " &rarr; ";
            $a += 5;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    } //year 1-6
    public

    function count_in_fives_zero()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = 0;
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = 0;
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = 0;
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = 0;
            $b = $a + 25;
        } else {
            $a = 0;
            $b = $a + 25;
        }

        $answr = "";
        $qtnstrg = "finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 25 === $a) {
                $qtnstrg .= $a . " ";
            } elseif ($b - 15 === $a) {
                $qtnstrg .= $a . " ";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= " &rarr; " . $a;
            $a += 5;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    } //year 1-6
    public

    function count_in_tens()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(0, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 5000);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000000);
            $b = $a + 50;
        } else {
            $a = rand(1, 1000);
            $b = $a + 50;
        }

        $answr = number_format($a);
        $qtnstrg = "<strong>Finish the number line:</strong> " . number_format($a) . " &rarr; ";
        $a += 10;
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " &rarr; " . number_format($a);
            } elseif ($b - 10 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } elseif ($b - 3 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } else {
                $qtnstrg .= " _____ &rarr; ";
            }

            $answr .= " &rarr; " . number_format($a);
            $a += 10;
        }

        $answr = trim($answr, " ");
        return $this->make_array($qtnstrg, $answr);
    } //year 1-6
    public

    function count_in_tens_zero()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = 0;
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = 0;
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = 0;
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = 0;
            $b = $a + 50;
        } else {
            $a = 0;
            $b = $a + 50;
        }

        $answr = "";
        $qtnstrg = "finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 50 === $a) {
                $qtnstrg .= $a . " ";
            } elseif ($b - 30 === $a) {
                $qtnstrg .= $a . " ";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= " &rarr; " . $a;
            $a += 10;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    } //year 1-6
    public

    function count_in_fifties_multiple_5()
    {
        $a = rand(1, 9) * 5;
        $arraya[0] = $a;
        for ($i = 1; $i < 5; $i++) {
            $arraya[$i] = $a + 50;
            $a += 50;
        }

        $selectorArray = array();
        $selectorArray = [rand(0, sizeof($arraya) - 1), rand(0, sizeof($arraya) - 1)];
        $qtnstrg = "Find the missing numbers: ";
        $answr = "";
        foreach ($arraya as $number) {
            $answr .= $number . " &rarr; ";
        }

        for ($i = 0; $i < sizeof($selectorArray); $i++) {
            $arraya[$selectorArray[$i]] = "___";
        }

        foreach ($arraya as $number) {
            $qtnstrg .= $number . " &rarr; ";
        }
        $qtnstrg = substr($qtnstrg, 0, -7);

        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_hundreds()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(0, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 100000);
            $b = $a + 500;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000000);
            $b = $a + 500;
        } else {
            $a = rand(1, 1000000);
            $b = $a + 500;
        }

        $answr = number_format($a);
        $qtnstrg = "<strong>Finish the number line:</strong> " . number_format($a) . " &rarr; ";
        $a += 100;
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " &rarr; " . number_format($a);
            } elseif ($b - 100 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } elseif ($b - 30 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } else {
                $qtnstrg .= " _____ &rarr; ";
            }

            $answr .= " &rarr; " . number_format($a);
            $a += 100;
        }

        $answr = trim($answr, " &rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_thousands()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(0, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 100000);
            $b = $a + 5000;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000000);
            $b = $a + 5000;
        } else {
            $a = rand(1, 1000000);
            $b = $a + 5000;
        }

        $answr = number_format($a);
        $qtnstrg = "<strong>Finish the number line:</strong> " . number_format($a) . " &rarr; ";
        $a += 1000;
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " &rarr; " . number_format($a);
            } elseif ($b - 1000 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } elseif ($b - 300 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } else {
                $qtnstrg .= " _____ &rarr; ";
            }

            $answr .= " &rarr; " . number_format($a);
            $a += 1000;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_tenthousands()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(0, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 100000);
            $b = $a + 50000;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000000);
            $b = $a + 50000;
        } else {
            $a = rand(1, 1000000);
            $b = $a + 50000;
        }

        $answr = number_format($a);
        $qtnstrg = "<strong>Finish the number line:</strong> " . number_format($a) . " &rarr; ";
        $a += 10000;
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " &rarr; " . number_format($a);
            } elseif ($b - 10000 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } elseif ($b - 3000 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= " &rarr; " . number_format($a);
            $a += 10000;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_hundredthousands()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(0, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 50;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 1000000);
            $b = $a + 500000;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000000);
            $b = $a + 500000;
        } else {
            $a = rand(1, 1000000);
            $b = $a + 500000;
        }

        $answr = number_format($a);
        $qtnstrg = "<strong>Finish the number line:</strong> " . number_format($a) . " &rarr; ";
        $a += 100000;
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " &rarr; " . number_format($a);
            } elseif ($b - 100000 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } elseif ($b - 30000 === $a) {
                $qtnstrg .= number_format($a) . " ";
            } else {
                $qtnstrg .= " _____ &rarr; ";
            }

            $answr .= " &rarr; " . number_format($a);
            $a += 100000;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function rand_thousand_tenthousand_hundredthousand()
    {
        $a = rand(0, 2);
        if ($a === 0) {
            return $this->count_in_thousands();
        } elseif ($a = 1) {
            return $this->count_in_tenthousands();
        } else {
            return $this->count_in_hundredthousands();
        }
    }

    public

    function _count_back_in_tens()
    {
        $a;
        $b;
        $difference = 10;
        $number_line_length = $difference * 5;
        if ($_SESSION['year'] === 'y3') {
            $a = rand(5, 10) * 100;
            $b = $a - $number_line_length;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(5, 10) * 100;
            $b = $a - $number_line_length;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 1000000);
            $b = $a - $number_line_length;
        } else {
            $a = rand(100, 10000000);
            $b = $a - $number_line_length;
        }

        $qtnstrg = "<strong>Finish the number line:</strong> " . number_format($a) . " - ";
        $answr = number_format($a) . " &rarr; ";
        $a -= $difference;
        $qtnstrg .= number_format($a);
        $answr .= number_format($a) . " &rarr; ";
        $a -= $difference;
        while ($a >= $b) {
            if ($a === $b) {
                $qtnstrg .= " - " . number_format($a);
            } elseif ($a > $b) {
                $qtnstrg .= " - _____ &rarr; ";
            }

            $answr .= number_format($a) . " &rarr; ";
            $a -= $difference;
        }

        $answr = trim($answr, " ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function _count_back_in_hundreds()
    {
        $a;
        $b;
        $difference = 100;
        $number_line_length = $difference * 5;
        if ($_SESSION['year'] === 'y3') {
            $a = rand(5, 10) * 100;
            $b = $a - $number_line_length;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(5, 10) * 100;
            $b = $a - $number_line_length;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 1000000);
            $b = $a - $number_line_length;
        } else {
            $a = rand(100, 10000000);
            $b = $a - $number_line_length;
        }

        $qtnstrg = "<strong>Finish the number line: </strong> " . number_format($a) . " &rarr; ";
        $answr = number_format($a) . " &rarr; ";
        $a -= $difference;
        $qtnstrg .= number_format($a) . " &rarr; ";
        $answr .= number_format($a) . " &rarr; ";
        $a -= $difference;
        while ($a >= $b) {
            if ($a === $b) {
                $qtnstrg .=  number_format($a);
            } elseif ($a > $b) {
                $qtnstrg .= " _____ &rarr; ";
            }

            $answr .= number_format($a) . " &rarr; ";
            $a -= $difference;
        }

        $answr = trim($answr, " &rarr; ");
        $qtnstrg = trim($qtnstrg, " &rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function _count_back_in_thousands()
    {
        $a;
        $b;
        $difference = 1000;
        $number_line_length = $difference * 5;
        if ($_SESSION['year'] === 'y3') {
            $a = rand(5, 10) * 100;
            $b = $a - $number_line_length;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(5, 10) * 100;
            $b = $a - $number_line_length;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 1000000);
            $b = $a - $number_line_length;
        } else {
            $a = rand(100, 10000000);
            $b = $a - $number_line_length;
        }

        $qtnstrg = "<strong>Finish the number line:</strong> " . number_format($a) . " - ";
        $answr = number_format($a) . " &rarr; ";
        $a -= $difference;
        $qtnstrg .= number_format($a);
        $answr .= number_format($a) . " &rarr; ";
        $a -= $difference;
        while ($a >= $b) {
            if ($a === $b) {
                $qtnstrg .= " &rarr; " . number_format($a);
            } elseif ($a > $b) {
                $qtnstrg .= " - _____ &rarr; ";
            }

            $answr .= number_format($a) . " &rarr; ";
            $a -= $difference;
        }

        $answr = trim($answr, " - ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function rand_forwardsbackwards_10s_100s()
    {
        $a = rand(0, 3);
        if ($a === 0) {
            return $this->count_in_tens();
        } elseif ($a === 1) {
            return $this->count_in_hundreds();
        } elseif ($a === 2) {
            return $this->_count_back_in_tens();
        } else {
            return $this->_count_back_in_hundreds();
        }
    }

    public

    function count_in_fifties_zero()
    {
        $arraya = [0, 50, 100, 150, 200, 250, 300, 350, 400, 450, 500];
        $selectorArray = [rand(0, sizeof($arraya) - 1), rand(0, sizeof($arraya) - 1), rand(0, sizeof($arraya) - 1)];
        $qtnstrg = "Find the missing numbers: ";
        $answr = "";
        foreach ($arraya as $number) {
            $answr .= $number . " &rarr; ";
        }

        for ($i = 0; $i < sizeof($selectorArray); $i++) {
            $arraya[$selectorArray[$i]] = "___";
        }

        foreach ($arraya as $number) {
            $qtnstrg .= $number . " &rarr; ";
        }

        $qtnstrg = trim($qtnstrg, "&arr; ");
        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_hundreds_zero()
    {
        $arraya = [0, 100, 200, 300, 400, 500, 600, 700, 800, 900];
        $selectorArray = [rand(0, sizeof($arraya) - 1), rand(0, sizeof($arraya) - 1), rand(0, sizeof($arraya) - 1)];
        $qtnstrg = "Find the missing numbers: ";
        $answr = "";
        foreach ($arraya as $number) {
            $answr .= $number . " &rarr; ";
        }

        for ($i = 0; $i < sizeof($selectorArray); $i++) {
            $arraya[$selectorArray[$i]] = "___";
        }

        foreach ($arraya as $number) {
            $qtnstrg .= $number . " &rarr; ";
        }

        $qtnstrg = trim($qtnstrg, "&arr; ");
        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_threes()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + 15;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 15;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 500);
            $b = $a + 15;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 10000);
            $b = $a + 15;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 100000);
            $b = $a + 15;
        } else {
            $a = rand(1, 1000000);
            $b = $a + 15;
        }

        $answr = "";
        $qtnstrg = "finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 15 === $a) {
                $qtnstrg .= $a . " ";
            } elseif ($b - 6 === $a) {
                $qtnstrg .= $a . " ";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= " &rarr; " . $a;
            $a += 3;
        }

        $answr = trim($answr, ",");
        return $this->make_array($qtnstrg, $answr);
    } //year 1-6
    public

    function count_in_fours()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + 20;
            $qtnstrg = "Add 4 to complete the numberline: ";
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 20;
            $qtnstrg = "Add 4 to complete the numberline: ";
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 100);
            $b = $a + 20;
            $qtnstrg = "Add 4 to complete the numberline: ";
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 10000);
            $b = $a + 20;
            $qtnstrg = "Complete the numberline:";
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 100000);
            $b = $a + 20;
            $qtnstrg = "Complete the numberline:";
        } else {
            $a = rand(1, 1000000);
            $b = $a + 20;
            $qtnstrg = "Complete the numberline:";
        }

        $answr = "";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 20 === $a) {
                $qtnstrg .= $a . " &rarr; ";
            } elseif ($b - 6 === $a) {
                $qtnstrg .= $a . " &rarr; ";
            } else {
                $qtnstrg .= " _____ &rarr;";
            }

            $answr .= $a . " &rarr; ";
            $a += 4;
        }

        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    } //year 1-6
    public

    function count_in_sixes()
    {
        $a;
        $b;
        $multiple5 = 30;
        $multiple = 6;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 500);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 100000);
            $b = $a + $multiple5;
        } else {
            $a = rand(1, 1000000);
            $b = $a + $multiple5;
        }

        $answr = "";
        $qtnstrg = "Finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - $multiple5 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } elseif ($b - 24 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= $a . " &rarr; ";
            $a += $multiple;
        }

        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_sevens()
    {
        $a;
        $b;
        $multiple5 = 35;
        $multiple = 7;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 200);
            $b = $a + $multiple5;
        } else {
            $a = rand(1, 1000000);
            $b = $a + $multiple5;
        }

        $answr = "";
        $qtnstrg = "Finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - $multiple5 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } elseif ($b - 28 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= $a . " &rarr; ";
            $a += $multiple;
        }

        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_eights()
    {
        $a;
        $b;
        $multiple5 = 40;
        $multiple = 8;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(10, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 200);
            $b = $a + $multiple5;
        } else {
            $a = rand(1, 1000000);
            $b = $a + $multiple5;
        }

        $answr = "";
        $qtnstrg = "Finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 40 === $a) {
                $qtnstrg .= $a . " &rarr; ";
            } elseif ($b - 32 === $a) {
                $qtnstrg .= $a . " &rarr; ";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= $a . " &rarr; ";
            $a += 8;
        }

        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_nines()
    {
        $a;
        $b;
        $multiple5 = 45;
        $multiple = 9;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 200);
            $b = $a + $multiple5;
        } else {
            $a = rand(1, 1000000);
            $b = $a + $multiple5;
        }

        $answr = "";
        $qtnstrg = "Finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - $multiple5 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } elseif ($b - 36 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= $a . " &rarr; ";
            $a += $multiple;
        }

        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_elevens()
    {
        $a;
        $b;
        $multiple5 = 55;
        $multiple = 11;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 200);
            $b = $a + $multiple5;
        } else {
            $a = rand(1, 1000000);
            $b = $a + $multiple5;
        }

        $answr = "";
        $qtnstrg = "Finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - $multiple5 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } elseif ($b - 44 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= $a . " &rarr; ";
            $a += $multiple;
        }

        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_twelves()
    {
        $a;
        $b;
        $multiple5 = 60;
        $multiple = 12;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 200);
            $b = $a + $multiple5;
        } else {
            $a = rand(1, 1000000);
            $b = $a + $multiple5;
        }

        $answr = "";
        $qtnstrg = "Finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - $multiple5 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } elseif ($b - 48 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= $a . " &rarr; ";
            $a += $multiple;
        }

        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_twentyfives()
    {
        $a;
        $b;
        $multiple5 = 60;
        $multiple = 12;
        if ($_SESSION['year'] === 'y1') {
            $a = 0;
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 200);
            $b = $a + $multiple5;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 200);
            $b = $a + $multiple5;
        } else {
            $a = rand(1, 1000000);
            $b = $a + $multiple5;
        }

        $answr = "";
        $qtnstrg = "Finish the number line: ";
        while ($a <= $b) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - $multiple5 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } elseif ($b - 100 === $a) {
                $qtnstrg .= $a . " &rarr;";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= $a . " &rarr; ";
            $a += $multiple;
        }

        $answr = trim($answr, "&rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function random_count_x()
    {
        $a = rand(0, 6);
        if ($a === 0) {
            return $this->count_in_sixes();
        } elseif ($a === 1) {
            return $this->count_in_sevens();
        } elseif ($a === 2) {
            return $this->count_in_eights();
        } elseif ($a === 3) {
            return $this->count_in_nines();
        } elseif ($a === 4) {
            return $this->count_in_elevens();
        } elseif ($a === 5) {
            return $this->count_in_twelves();
        } elseif ($a === 6) {
            return $this->count_in_twentyfives();
        }
    }



    public
    function random_25_50_11()
    { {
            $a = rand(0, 2);
            if ($a === 0) {
                return $this->count_in_twentyfives();
            } else if ($a === 1) {
                return $this->count_in_elevens();
            } else {
                return $this->count_in_thousands();
            }
        }
    }

    public
    function random_7_9()
    { {
            $a = rand(0, 1);
            if ($a === 0) {
                return $this->count_in_sevens();
            } else {
                return $this->count_in_nines();
            }
        }
    }

    // year 1-6

    public

    function one_more()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 150);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 1000);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 2000);
            $b = $a + 1;
        } else {
            $a = rand(1, 5000);
            $b = $a + 1;
        }

        $qtn = "What is 1 more than " . $a . "?";
        $answr = $b;
        return $this->make_array($qtn, $answr);
    } //year 1-6
    public

    function one_less()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $b = $a - 1;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 150);
            $b = $a - 1;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a - 1;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 1000);
            $b = $a - 1;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 2000);
            $b = $a - 1;
        } else {
            $a = rand(1, 5000);
            $b = $a - 1;
        }

        $qtn = "What is 1 less than " . $a . "?";
        $answr = $b;
        return $this->make_array($qtn, $answr);
    } //year 1-6
    public

    function ten_more()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 150);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 1000);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 2000);
            $b = $a + 10;
        } else {
            $a = rand(1, 5000);
            $b = $a + 10;
        }

        $qtn = "What is 10 more than " . $a . "?";
        $answr = $b;
        return $this->make_array($qtn, $answr);
    } //year 1-6
    public

    function ten_less()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $b = $a - 10;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 150);
            $b = $a - 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a - 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 1000);
            $b = $a - 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 2000);
            $b = $a - 10;
        } else {
            $a = rand(1, 5000);
            $b = $a - 10;
        }

        $qtn = "What is 10 less than " . $a . "?";
        $answr = $b;
        return $this->make_array($qtn, $answr);
    } //year 1-6
    public

    function rand_ten_more_less()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->ten_more();
        } else {
            return $this->ten_less();
        }
    }

    public

    function count_in_whole_steps()
    {
        $a;
        $b;
        $number_array;
        $difference = rand(1, 20);
        $number_line_length = $difference * 8;
        $answr = "";
        if ($_SESSION['year'] === 'y3') {
            $a = rand(5, 10) * 100;
            $b = $a + $number_line_length;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(5, 10) * 100;
            $b = $a + $number_line_length;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 50000);
            $b = $a + $number_line_length;
        } else {
            $a = rand(100, 1000000);
            $b = $a + $number_line_length;
        }

        unset($number_array);
        for ($i = 0; $i <= 7; $i++) {
            $number_array[$i] = number_format($a);
            $answr .= $number_array[$i] . " - ";
            $a += $difference;
        }

        $qtnstrg = "Find the missing numbers: ";

        // randomly select nth chaarcter of array

        $questions = [0, 1, 2, 3, 4, 5, 6, 7];
        $selectorA = $questions[rand(0, 6)];
        unset($questions[$selectorA]);
        $questions = array_values($questions);
        $selectorB = $questions[rand(0, 5)];
        unset($questions[$selectorB]);
        $questions = array_values($questions);
        $selectorC = $questions[rand(0, 4)];
        unset($questions[$selectorC]);
        $questions = array_values($questions);
        $selectorD = $questions[rand(0, 3)];
        unset($questions[$selectorD]);
        $questions = array_values($questions);

        // replace nth character with ?

        $number_array[$selectorA] = " _____ ";
        $number_array[$selectorB] = " _____ ";
        $number_array[$selectorC] = " _____ ";
        $number_array[$selectorD] = " _____ ";
        foreach ($number_array as $number) {
            $qtnstrg .= $number . " &rarr; ";
        }

        $answr = trim($answr, " &rarr' ");
        $qtnstrg = trim($qtnstrg, " &rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_1decimal_steps()
    {
        $a;
        $b;
        $number_array;
        $difference = rand(1, 19) / 10;
        $number_line_length = $difference * 8;
        $answr = "";
        if ($_SESSION['year'] === 'y3') {
            $a = rand(5, 10) * 100;
            $b = $a + $number_line_length;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(5, 10) * 100;
            $b = $a + $number_line_length;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(0, 10);
            $b = $a + $number_line_length;
        } else {
            $a = rand(0, 10);
            $b = $a + $number_line_length;
        }

        unset($number_array);
        for ($i = 0; $i <= 7; $i++) {
            $number_array[$i] = $a;
            $answr .= $number_array[$i] . " &rarr; ";
            $a += $difference;
        }

        $qtnstrg = "Find the missing numbers: ";

        // randomly select nth chaarcter of array

        $questions = [0, 1, 2, 3, 4, 5, 6, 7];
        $selectorA = $questions[rand(0, 6)];
        unset($questions[$selectorA]);
        $questions = array_values($questions);
        $selectorB = $questions[rand(0, 5)];
        unset($questions[$selectorB]);
        $questions = array_values($questions);
        $selectorC = $questions[rand(0, 4)];
        unset($questions[$selectorC]);
        $questions = array_values($questions);
        $selectorD = $questions[rand(0, 3)];
        unset($questions[$selectorD]);
        $questions = array_values($questions);

        // replace nth character with ?

        $number_array[$selectorA] = " _____ ";
        $number_array[$selectorB] = " _____ ";
        $number_array[$selectorC] = " _____ ";
        $number_array[$selectorD] = " _____ ";
        foreach ($number_array as $number) {
            $qtnstrg .= $number . " &rarr; ";
        }

        $answr = trim($answr, " &rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function count_in_2decimal_steps()
    {
        $a;
        $b;
        $number_array;
        $difference = rand(1, 19) / 100;
        $number_line_length = $difference * 8;
        $answr = "";
        if ($_SESSION['year'] === 'y3') {
            $a = rand(5, 10) * 100;
            $b = $a + $number_line_length;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(5, 10) * 100;
            $b = $a + $number_line_length;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(0, 10);
            $b = $a + $number_line_length;
        } else {
            $a = rand(0, 10);
            $b = $a + $number_line_length;
        }

        unset($number_array);
        for ($i = 0; $i <= 7; $i++) {
            $number_array[$i] = $a;
            $answr .= $number_array[$i] . " &rarr; ";
            $a += $difference;
        }


        $qtnstrg = "Find the missing numbers: ";

        // randomly select nth chaarcter of array

        $questions = [0, 1, 2, 3, 4, 5, 6, 7];
        $selectorA = $questions[rand(0, 6)];
        unset($questions[$selectorA]);
        $questions = array_values($questions);
        $selectorB = $questions[rand(0, 5)];
        unset($questions[$selectorB]);
        $questions = array_values($questions);
        $selectorC = $questions[rand(0, 4)];
        unset($questions[$selectorC]);
        $questions = array_values($questions);
        $selectorD = $questions[rand(0, 3)];
        unset($questions[$selectorD]);
        $questions = array_values($questions);

        // replace nth character with ?

        $number_array[$selectorA] = " _____ ";
        $number_array[$selectorB] = " _____ ";
        $number_array[$selectorC] = " _____ ";
        $number_array[$selectorD] = " _____ ";
        foreach ($number_array as $number) {
            $qtnstrg .= $number . " &rarr; ";
        }

        $answr = trim($answr, " &rarr; ");
        $qtnstrg = trim($qtnstrg, " &rarr; ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function rand_count_whole_and_decimal()
    {
        $a = rand(0, 2);
        if ($a === 0) {
            return $this->count_in_whole_steps();
        } elseif ($a === 1) {
            return $this->count_in_1decimal_steps();
        } else {
            return $this->count_in_2decimal_steps();
        }
    }

    public

    function hundred_more()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $b = $a + 100;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 150);
            $b = $a + 100;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 100;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 1000);
            $b = $a + 100;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 2000);
            $b = $a + 100;
        } else {
            $a = rand(1, 5000);
            $b = $a + 100;
        }

        $qtn = "What is 100 more than " . $a . "?";
        $answr = $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function hundred_less()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y2') {
            $a = rand(100, 150);
            $b = $a - 100;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 500);
            $b = $a - 100;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(100, 1000);
            $b = $a - 100;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 2000);
            $b = $a - 100;
        } else {
            $a = rand(100, 5000);
            $b = $a - 100;
        }

        $qtn = "What is 100 less than " . $a . "?";
        $answr = $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function thousand_more()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $b = $a + 1000;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 150);
            $b = $a + 1000;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 5000);
            $b = $a + 1000;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 10000);
            $b = $a + 1000;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 20000);
            $b = $a + 1000;
        } else {
            $a = rand(1, 500000);
            $b = $a + 1000;
        }

        $qtn = "What is 1000 more than " . $a . "?";
        $answr = $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function thousand_less()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y2') {
            $a = rand(1001, 10000);
            $b = $a - 1000;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1000, 5000);
            $b = $a - 1000;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1000, 10000);
            $b = $a - 1000;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1000, 200000);
            $b = $a - 1000;
        } else {
            $a = rand(100, 5000);
            $b = $a - 1000;
        }

        $qtn = "What is 1000 less than " . $a . "?";
        $answr = $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function rand_hundred_more_less()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->hundred_more();
        } else {
            return $this->hundred_less();
        }
    }

    public

    function rand_ten_hundred_more_less()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->hundred_more();
        } else {
            return $this->hundred_less();
        }
    }

    public

    function count_through_zero()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y4') {
            $a = rand(1, 25);
            $b = rand(1, 25);
            while ($a - $b > 0) {
                $a = rand(1, 10);
                $b = rand(1, 15);
            }
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 50);
            $b = rand(1, 100);
            while ($a - $b > 0) {
                $a = rand(1, 50);
                $b = rand(1, 100);
            }
        } else {
            $a = rand(-0, -99);
            $b = rand(0, 99);
        }


        $answr = $a - $b;
        $qtnstrg = $a . " - " . $b . "?";
        if ($_SESSION['year'] === 'y6') {
            $answr = $a - $b;
            $qtnstrg = $b . " - " . "____" . " = " . $a;
        }
        return $this->make_array($qtnstrg, $answr);
    }

    // year 1-6

    public

    function count_to_100()
    {
        $a = rand(1, 120);
        $b = $a + 10;
        $rand_selector = rand(1, 3);
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 120);
            $b = $a + 10;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 20);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(10, 100);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 1000);
            $b = 10;
        } else {
            $a = rand(500, 3000);
            $b = 10;
        }

        // add if

        $answr = "";
        $qtnstrg = "finish the number line: ";
        for ($b; $a <= $b; $a++) {
            if ($a === $b) {
                $qtnstrg .= " " . $a;
            } elseif ($b - 10 === $a) {
                $qtnstrg .= $a . " ";
            } elseif ($b - 5 === $a) {
                $qtnstrg .= $a . " ";
            } else {
                $qtnstrg .= "_____ &rarr; ";
            }

            $answr .= " " . $a;
        }

        return $this->make_array($qtnstrg, $answr);
    }

    public

    function number_bond_100()
    {
        $a = rand(1, 99);
        $b = 100 - $a;
        $answr = $b;
        $qtnstrg = $a . " + ? = 100";
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function number_bond_1000()
    {
        $a = rand(1, 999);
        $b = 1000 - $a;
        $answr = $b;
        $qtnstrg = $a . " + ? = 1000";
        return $this->make_array($qtnstrg, $answr);
    }

    // year 1-6
    // comparing and ordering

    public

    function more_or_less_than()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(10, 100);
            $b = rand($a - 10, $a + 10);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(10, 100);
            $b = rand($a - 10, $a + 10);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 1000);
            $b = rand($a - 100, $a + 100);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1000, 10000);
            $b = rand($a - 1000, $a + 1000);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100000, 1000000);
            $b = rand($a - 100000, $a + 100000);
        } else {
            $a = rand(1000000, 10000000);
            $b = rand($a - 1000000, $a + 100000);
        }

        $answr = "";
        $qtnstrg = "Is " . $a . " >, < or = to " . $b . "?";
        if ($a > $b) {
            $answr = "> (more than)";
        } elseif ($a < $b) {
            $answr = "< (less than)";
        } else {
            $answr = "= (equal to)";
        }

        return $this->make_array($qtnstrg, $answr);
    }

    public

    function below_size_order()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] === 'y2') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] == 'y3') {
            $a = array(
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000)
            );
        } elseif ($_SESSION['year'] === 'y4') {
            $a = array(
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000)
            );
        } elseif ($_SESSION['year'] === 'y5') {
            $a = array(
                rand(10000, 500000),
                rand(10000, 500000),
                rand(10000, 500000),
                rand(10000, 500000),
                rand(10000, 500000)
            );
        } elseif ($_SESSION['year'] === 'y6') {
            $a = array(
                rand(10, 5000000),
                rand(10, 5000000),
                rand(10, 5000000),
                rand(10, 5000000),
                rand(10, 5000000)
            );
        }

        $qtnstrg = "<strong>Order these numbers, smallest first: </strong>";
        foreach ($a as $number) {
            $format = number_format($number);
            $qtnstrg .= $format .= " - ";
        }

        sort($a);
        $answr = "";
        foreach ($a as $sorted) {
            $format = number_format($sorted);
            $answr .= $format .= " - ";
        }

        $qtnstrg = substr_replace($qtnstrg, '.', -2);
        $answr = substr_replace($answr, '.', -2);
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function _order_size_order()
    {
        $a = [];
        if ($_SESSION['year'] === 'y1') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] === 'y2') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] == 'y3') {
            $a = array(
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000)
            );
        } elseif ($_SESSION['year'] === 'y4') {
            $a = array(
                rand(1000, 10000),
                rand(1000, 10000),
                rand(1000, 10000),
                rand(1000, 10000),
                rand(1000, 10000)
            );
        } elseif ($_SESSION['year'] === 'y5') {
            $a = array(
                rand(10000, 999999),
                rand(10000, 999999),
                rand(10000, 999999),
                rand(10000, 999999),
                rand(10000, 999999)
            );
        } elseif ($_SESSION['year'] === 'y6') {
            $a = array(
                rand(10, 10000000),
                rand(10, 10000000),
                rand(10, 10000000),
                rand(10, 10000000),
                rand(10, 10000000)
            );
        }

        $qtnstrg = "<strong>Order these numbers, smallest first: </strong>";
        foreach ($a as $number) {
            $format = number_format($number);
            $qtnstrg .= $format .= " - ";
        }

        sort($a);
        $answr = "";
        foreach ($a as $sorted) {
            $format = number_format($sorted);
            $answr .= $format .= " &rarr; ";
        }

        $qtnstrg = substr_replace($qtnstrg, '.', -2);
        $answr = substr($answr, 0, -7);
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function exceeding_size_order()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] === 'y2') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] == 'y3') {
            $a = array(
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000)
            );
        } elseif ($_SESSION['year'] === 'y4') {
            $a = array(
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000)
            );
        } elseif ($_SESSION['year'] === 'y5') {
            $a = array(
                rand(1000000, 9999999),
                rand(1000000, 9999999),
                rand(1000000, 9999999),
                rand(1000000, 9999999),
                rand(1000000, 9999999)
            );
        } elseif ($_SESSION['year'] === 'y6') {
            $a = array(
                rand(10, 1000000),
                rand(10, 1000000),
                rand(10, 1000000),
                rand(10, 1000000),
                rand(10, 1000000)
            );
        }

        $qtnstrg = "<strong>Order these numbers, smallest first: </strong>";
        foreach ($a as $number) {
            $format = number_format($number);
            $qtnstrg .= $format .= " - ";
        }

        sort($a);
        $answr = "";
        foreach ($a as $sorted) {
            $format = number_format($sorted);
            $answr .= $format .= " - ";
        }

        $qtnstrg = substr_replace($qtnstrg, '.', -2);
        $answr = substr_replace($answr, '.', -2);
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function size_order_words()
    {
        $a;
        $wordArray;
        $wordArraySorted;
        $words = new Resources();
        if ($_SESSION['year'] === 'y1') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] === 'y2') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] === 'y3') {
            $a = array(
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000)
            );
        } elseif ($_SESSION['year'] === 'y4') {
            $a = array(
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000),
                rand(10, 10000)
            );
        } elseif ($_SESSION['year'] === 'y5') {
            $a = array(
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 10000)
            );
        } else {
            $a = array(
                rand(10, 10000000),
                rand(10, 10000000),
                rand(10, 10000000),
                rand(10, 10000000),
                rand(10, 10000000)
            );
        }

        foreach ($a as $number) {
            $wordArray[$number] = $words->numbers_in_words($number);
        }

        $answr = "";
        $qtnstrg = "<strong>Order in digits, smallest first: </strong>";
        foreach ($wordArray as $word) {
            $qtnstrg .= $word .= " - ";
        }

        sort($a);
        foreach ($a as $number) {
            $wordArraySorted[$number] = $words->numbers_in_words($number);
        }

        foreach ($wordArraySorted as $sorted) {
            $answr .= $sorted .= " - ";
        }

        $qtnstrg = trim($qtnstrg, " - ");
        $answr = trim($answr, " - ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function exceeding_word_size_order()
    {
        $a;
        $wordArray;
        $wordArraySorted;
        $words = new Resources();
        if ($_SESSION['year'] === 'y1') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] === 'y2') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] === 'y3') {
            $a = array(
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000),
                rand(10, 1000)
            );
        } elseif ($_SESSION['year'] === 'y4') {
            $a = array(
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 100000)
            );
        } elseif ($_SESSION['year'] === 'y5') {
            $a = array(
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 100000),
                rand(10, 10000)
            );
        } else {
            $a = array(
                rand(10, 1000000),
                rand(10, 1000000),
                rand(10, 1000000),
                rand(10, 1000000),
                rand(10, 1000000)
            );
        }

        foreach ($a as $number) {
            $wordArray[$number] = $words->numbers_in_words($number);
        }

        $answr = "";
        $qtnstrg = "<strong>Write these numbers as digits and put them in size order, smallest first: </strong>";
        foreach ($wordArray as $word) {
            $qtnstrg .= $word .= " - ";
        }

        sort($a);
        foreach ($a as $number) {
            $number_format = number_format($number);
            $answr .= $number_format .= " - ";
        }

        /*foreach($wordArraySorted as $sorted)
        {
        $answr.= $sorted.= " &rarr; ";
        }*/
        $qtnstrg = trim($qtnstrg, " - ");
        $answr = trim($answr, " - ");
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function decimals_size_order()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = array(
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100)
            );
        } elseif ($_SESSION['year'] === 'y2') {
            $number = (rand(10, 100));
            $a = array(
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10
            );
        } elseif ($_SESSION['year'] === 'y3') {
            $number = (rand(10, 100));
            $a = array(
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10
            );
        } elseif ($_SESSION['year'] === 'y4') {
            $number = (rand(10, 100));
            $a = array(
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10,
                rand($number, $number + 10) / 10
            );
        } elseif ($_SESSION['year'] === 'y5') {
            $number = (rand(10, 10000));
            $a = array(
                rand($number, $number + 10) / 100,
                rand($number, $number + 10) / 100,
                rand($number, $number + 10) / 100,
                rand($number, $number + 10) / 100,
                rand($number, $number + 10) / 100
            );
        } else {
            $number = (rand(10, 10000));
            $a = array(
                rand($number, $number + 10) / 100,
                rand($number, $number + 10) / 100,
                rand($number, $number + 10) / 100,
                rand($number, $number + 10) / 100,
                rand($number, $number + 10) / 100
            );
        }

        $answr = "";
        $qtnstrg = "<strong>Order these numbers, smallest first: </strong>";
        foreach ($a as $number) {
            $qtnstrg .= $number .= " - ";
        }

        sort($a);
        foreach ($a as $sorted) {
            $answr .= $sorted .= " - ";
        }

        $qtnstrg = trim($qtnstrg, " - ");
        $answr = trim($answr, " - ");
        return $this->make_array($qtnstrg, $answr);
    }

    public function rand_read_write_order_compare_y6()
    {
        $a = rand(0, 5);
        switch ($a) {
            case 0:
                size_order_words();
            case 1:
                _order_size_order();
            case 2:
                count_in_whole_steps();
            case 3:
                words_write_in_numerals();
            case 4:
                write_numerals_in_words();
            case 5:
                more_or_less_than();
        }
    }

    // place value

    public

    function place_value_below()
    {
        $a;
        $selector;
        $place = ["ones", "tens", "hundreds", "thousands", "ten thousands", "hundred thousands", "millions"];
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $selector = 0;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
            $selector = rand(0, 1);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
            $selector = rand(0, 2);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
            $selector = rand(0, 3);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 500000);
            $selector = rand(0, 6);
        } else {
            $a = rand(999, 9999999);
            $selector = rand(0, 6);
        }


        $array = str_split($a);
        while (!isset($array[$selector])) {
            $selector = rand(0, 8);
        }
        $length = strlen($a);
        $qtn = "In " . number_format($a) . " what is the digit in the " . $place[$selector] . " column?";
        $answr = $array[$length - $selector - 1];
        return $this->make_array($qtn, $answr);
    }

    public

    function place_value()
    {
        $a;
        $selector;
        $place = ["ones", "tens", "hundreds", "thousands", "ten thousands", "hundred thousands", "millions"];
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $selector = 0;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
            $selector = rand(0, 1);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
            $selector = rand(0, 2);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
            $selector = rand(0, 3);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 500000);
            $selector = rand(0, 6);
        } else {
            $a = rand(999, 10000000);
            $selector = rand(0, 6);
        }


        $array = str_split($a);
        while (!isset($array[$selector])) {
            $selector = rand(0, 8);
        }
        $length = strlen($a);
        $qtn = "In " . number_format($a) . " what is the digit in the " . $place[$selector] . " column?";
        $answr = $array[$length - $selector - 1];
        return $this->make_array($qtn, $answr);
    }

    public

    function rand_place_value_order()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->place_value();
        } else {
            return $this->_order_size_order();
        }
    }

    public

    function place_value_exceeding()
    {
        $a;
        $selector;
        $place = ["ones", "tens", "hundreds", "thousands", "ten thousands", "hundred thousands", "millions"];
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $selector = 0;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
            $selector = rand(0, mb_strlen($a) - 1);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
            $selector = rand(0, mb_strlen($a) - 1);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(99999, 999999);
            $selector = rand(1, mb_strlen($a) - 1);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 1000000);
            $selector = rand(0, mb_strlen($a) - 1);
        } else {
            $a = rand(10000000, 100000000);
            $selector = rand(0, 6);
        }

        $array = str_split($a);
        $length = strlen($a);

        $qtn = "In " . number_format($a) . " what is the digit in the " . $place[$selector] . " column?";

        $answr = $array[$length - $selector - 1];
        return $this->make_array($qtn, $answr);
    }

    public

    function place_value_decimal()
    {

        $place = ["thousandsths", "hundredths", "tenths"];

        $a = rand(1000, 300000);
        $a = $a / 100;
        $selector = rand(0, mb_strlen($a) - 1);

        $a = number_format($a, 3, '.', '');
        $array = str_split($a);
        $length = strlen($a);

        $qtn = "In " . $a . " what is the digit in the " . $place[$selector] . " column?";

        $answr = $array[$length - $selector - 1];
        return $this->make_array($qtn, $answr);
    }

    public

    function place_value_decimal_confidently()
    {

        $place = ["thousandsths", "hundredths", "tenths"];

        $a = rand(1000, 300000);
        $a = $a / 100;
        $selector = rand(0, mb_strlen($a) - 1);

        $a = number_format($a, rand(1, 5), '.', '');
        $array = str_split($a);
        $length = strlen($a);

        $qtn = "In " . $a . " what is the digit in the " . $place[$selector] . " column?";

        $answr = $array[$length - $selector - 1];
        return $this->make_array($qtn, $answr);
    }


    public

    function words_write_in_numerals_below()
    {
        $a = 0;
        $words = new Resources();
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 1000);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        }

        $aWords = $words->numbers_in_words($a);
        $qtn = "<strong>Write in digits: </strong>" . $aWords;
        $answr = number_format($a);
        return $this->make_array($qtn, $answr);
    }

    public

    function words_write_in_numerals()
    {
        $words = new Resources();
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        } elseif ($_SESSION['year'] === 'y6') {
            $a = rand(999, 10000000);
        }

        $aWords = $words->numbers_in_words($a);
        $qtn = "<strong>Write in digits: </strong>" . $aWords;
        $answr = number_format($a);
        return $this->make_array($qtn, $answr);
    }

    public

    function words_write_in_numerals_exceeding()
    {
        $words = new Resources();
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 100000);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        }

        $aWords = $words->numbers_in_words($a);
        $qtn = "<strong>Write in digits: </strong>" . $aWords;
        $answr = number_format($a);
        return $this->make_array($qtn, $answr);
    }

    public

    function write_numerals_in_words()
    {
        $words = new Resources();
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 99999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        } else {
            $a = rand(999, 10000000);
        }

        $aWords = $words->numbers_in_words($a);
        $qtn = "<strong>Write in digits: </strong>" . $aWords;
        $answr = $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function write_words_numerals_exceeding()
    {
        $words = new Resources();
        $a = 0;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 99999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999999, 5000000);
        } else {
            $a = rand(999999, 99999999);
        }

        $aWords = $words->numbers_in_words($a);
        $qtn = "<strong>Write in digits: </strong>" . $aWords;
        $answr = number_format($a);
        return $this->make_array($qtn, $answr);
    }

    public

    function write_numerals_words_exceeding()
    {
        $words = new Resources();
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 99999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999999, 5000000);
        } else {
            $a = rand(999999, 99999999);
        }

        $aWords = $words->numbers_in_words($a);
        $qtn = "<strong>Write in words: </strong>" . number_format($a);
        $answr = $aWords;
        return $this->make_array($qtn, $answr);
    }

    public

    function rand_write_exceeding()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->write_words_numerals_exceeding();
        } else {
            return $this->write_numerals_words_exceeding();
        }
    }

    public

    function rand_nums_words()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->write_numerals_in_words();
        } else {
            return $this->words_write_in_numerals();
        }
    }

    public

    function d_exceeding_rand_nums_words()
    {
        $a = rand(0, 2);
        if ($a === 0) {
            return $this->write_words_numerals_exceeding();
        } elseif ($a === 1) {
            return $this->write_numerals_words_exceeding();
        } else {
            return $this->exceeding_word_size_order();
        }
    }

    public

    function number_line()
    {
        $a = rand(1, 11);
        $answr = 'Mark accuracy to within 5 either way on the number line.<br /><img style="max-width: 400px;" src="' . base_url() . '/assets/images/worksheets/images/numberlines/a' . $a . '.png">';
        $qtnstrg = 'What number is the arrow pointing to? <br /><img style="max-width: 400px;" src="' . base_url() . '/assets/images/worksheets/images/numberlines/q' . $a . '.png">';
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function number_liney3()
    {
        $a = rand(1, 10);
        $answr = 'Mark accuracy to within 5 either way on the number line.<br /><img style="max-width: 400px;" src="' . base_url() . '/assets/images/worksheets/images/numberlinesy3/a' . $a . '.png">';
        $qtnstrg = 'What number is the arrow pointing to? <br /><img style="max-width: 400px;" src="' . base_url() . '/assets/images/worksheets/images/numberlinesy3/q' . $a . '.png">';
        return $this->make_array($qtnstrg, $answr);
    }

    // partition numbers

    public

    function partition_number_2digit()
    {
        $a;
        if ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
        } else {
            $a = rand(1, 99);
        }

        $number = str_split($a);
        $multiplier = 1;
        for ($i = sizeof($number); $i > 0; $i--) {
            $number[$i - 1] *= $multiplier;
            $multiplier *= 10;
        }

        $qtn = "Partition " . $a;
        $answr = "";
        foreach ($number as $valuess) {
            $answr .= $valuess . " + ";
        }

        $answr = substr_replace($answr, '', -2);
        return $this->make_array($qtn, $answr);
    }

    public

    function partition_number_3digit()
    {
        $a;
        if ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
        } else {
            $a = rand(100, 999);
        }

        $number = str_split($a);
        $multiplier = 1;
        for ($i = sizeof($number); $i > 0; $i--) {
            $number[$i - 1] *= $multiplier;
            $multiplier *= 10;
        }

        $qtn = "Partition " . $a;
        $answr = "";
        foreach ($number as $valuess) {
            $answr .= $valuess . " + ";
        }

        $answr = substr_replace($answr, '', -2);
        return $this->make_array($qtn, $answr);
    }

    public

    function partition_number_4digit()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1000, 9999);
        } else {
            $a = rand(1000, 9999);
        }

        $number = str_split($a);
        $multiplier = 1;
        for ($i = sizeof($number); $i > 0; $i--) {
            $number[$i - 1] *= $multiplier;
            $multiplier *= 10;
        }

        $qtn = "Partition " . $a;
        $answr = "";
        foreach ($number as $valuess) {
            $answr .= $valuess . " + ";
        }

        $answr = substr_replace($answr, '', -2);
        return $this->make_array($qtn, $answr);
    }

    public

    function partition_number_5digit()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(10000, 99999);
        } else {
            $a = rand(10000, 99999);
        }

        $number = str_split($a);
        $multiplier = 1;
        for ($i = sizeof($number); $i > 0; $i--) {
            $number[$i - 1] *= $multiplier;
            $multiplier *= 10;
        }

        $qtn = "Partition " . $a;
        $answr = "";
        foreach ($number as $valuess) {
            $answr .= $valuess . " + ";
        }

        $answr = substr_replace($answr, '', -2);
        return $this->make_array($qtn, $answr);
    }

    public

    function partition_number_500000()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1000, 500000);
        } else {
            $a = rand(1000, 500000);
        }

        $number = str_split($a);
        $multiplier = 1;
        for ($i = sizeof($number); $i > 0; $i--) {
            $number[$i - 1] *= $multiplier;
            $multiplier *= 10;
        }

        $qtn = "Partition " . $a;
        $answr = "";
        foreach ($number as $valuess) {
            $answr .= $valuess . " + ";
        }

        $answr = substr_replace($answr, '', -2);
        return $this->make_array($qtn, $answr);
    }

    public

    function partition_number_7digit()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1000000, 10000000);
        } else {
            $a = rand(1000000, 10000000);
        }

        $number = str_split($a);
        $multiplier = 1;
        for ($i = sizeof($number); $i > 0; $i--) {
            $number[$i - 1] *= $multiplier;
            $multiplier *= 10;
        }

        $qtn = "Partition " . $a;
        $answr = "";
        foreach ($number as $valuess) {
            $answr .= $valuess . " + ";
        }

        $answr = substr_replace($answr, '', -2);
        return $this->make_array($qtn, $answr);
    }

    // partition numbers and add

    public

    function _add_partition_number()
    {
        $a;
        $b;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
            $b = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 99);
            $b = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(100, 999);
            $b = rand(100, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
            $b = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(0, 1000000);
            $b = rand(0, 1000000);
        } else {
            $a = rand(0, 9999999);
            $b = rand(0, 9999999);
        }

        // make array

        $numbera = str_split($a);
        $numberb = str_split($b);
        $numberabase = str_split($a);
        $numberbbase = str_split($b);

        // partition

        $multiplier = 1;
        for ($i = sizeof($numbera); $i > 0; $i--) {
            $numbera[$i - 1] *= $multiplier;
            $multiplier *= 10;
        }

        $multiplier = 1;
        for ($i = sizeof($numberb); $i > 0; $i--) {
            $numberb[$i - 1] *= $multiplier;
            $multiplier *= 10;
        }

        $randoma = rand(0, sizeof($numbera) - 1);
        $randomb = rand(0, sizeof($numberb) - 1);
        $aWord;
        $bWord;
        if ($_SESSION['year'] === 'y1' || $_SESSION['year'] === 'y2') {
            if ($randoma === 0) {
                $aWord = "tens";
            } elseif ($randoma === 1) {
                $aWord = "ones";
            }

            if ($randomb === 0) {
                $bWord = "tens";
            } elseif ($randomb === 1) {
                $bWord = "ones";
            }
        } elseif ($_SESSION['year'] === 'y3') {
            if ($randoma === 0) {
                $aWord = "hundreds";
            } elseif ($randoma === 1) {
                $aWord = "tens";
            } elseif ($randoma === 2) {
                $aWord = "ones";
            }

            if ($randomb === 0) {
                $bWord = "hundreds";
            } elseif ($randomb === 1) {
                $bWord = "tens";
            } elseif ($randomb === 2) {
                $bWord = "ones";
            }
        } elseif ($_SESSION['year'] === 'y4') {
            if ($randoma === 0) {
                $aWord = "thousands";
            } elseif ($randoma === 1) {
                $aWord = "hundreds";
            } elseif ($randoma === 2) {
                $aWord = "tens";
            } elseif ($randoma === 3) {
                $aWord = "ones";
            }

            if ($randomb === 0) {
                $bWord = "thousands";
            } elseif ($randomb === 1) {
                $bWord = "hundreds";
            } elseif ($randomb === 2) {
                $bWord = "tens";
            } elseif ($randomb === 3) {
                $bWord = "ones";
            }
        } elseif ($_SESSION['year'] === 'y5') {
            if ($randoma === 0) {
                $aWord = "hundred thousands";
            } elseif ($randoma === 1) {
                $aWord = "ten thousands";
            } elseif ($randoma === 2) {
                $aWord = "thousands";
            } elseif ($randoma === 3) {
                $aWord = "hundreds";
            } elseif ($randoma === 4) {
                $aWord = "tens";
            } elseif ($randoma === 5) {
                $aWord = "ones";
            }

            if ($randomb === 0) {
                $bWord = "hundred thousands";
            } elseif ($randomb === 1) {
                $bWord = "ten thousands";
            } elseif ($randomb === 2) {
                $bWord = "thousands";
            } elseif ($randomb === 3) {
                $bWord = "hundreds";
            } elseif ($randomb === 4) {
                $bWord = "tens";
            } elseif ($randomb === 5) {
                $bWord = "ones";
            }
        } elseif ($_SESSION['year'] === 'y6') {
            if ($randoma === 0) {
                $aWord = "milions";
            } elseif ($randoma === 1) {
                $aWord = "hundred thousands";
            } elseif ($randoma === 2) {
                $aWord = "ten thousands";
            } elseif ($randoma === 3) {
                $aWord = "thousands";
            } elseif ($randoma === 4) {
                $aWord = "hundreds";
            } elseif ($randoma === 5) {
                $aWord = "tens";
            } elseif ($randoma === 6) {
                $aWord = "ones";
            }

            if ($randomb === 0) {
                $bWord = "milions";
            } elseif ($randomb === 1) {
                $bWord = "hundred thousands";
            } elseif ($randomb === 2) {
                $bWord = "ten thousands";
            } elseif ($randomb === 3) {
                $bWord = "thousands";
            } elseif ($randomb === 4) {
                $bWord = "hundreds";
            } elseif ($randomb === 5) {
                $bWord = "tens";
            } elseif ($randomb === 6) {
                $bWord = "ones";
            }
        }

        $selecta = $numbera[$randoma];
        $selectb = $numberb[$randomb];
        $qtn = "Add the value of the " . $aWord . " column in " . number_format($a) . " to the value of the " . $bWord . " column in " . number_format($b) . ".";
        $answr = $selecta + $selectb;
        $answr = number_format($answr);
        return $this->make_array($qtn, $answr);
    }

    public function parition_more_than_oneway()
    {
        $ones = rand(1, 9);
        $tens = rand(1, 9) * 10;
        $hundreds = rand(1, 9) * 100;
        $total = $ones + $tens + $hundreds;

        $array = [
            $ones,
            $tens,
            $hundreds
        ];
        $selector = rand(0, 2);
        $answer = $total - $array[$selector];

        $qtnstrg = $total . " = " . $answer . " + ?";
        $answr = $array[$selector];
        return $this->make_array($qtnstrg, $answr);
    }

    // Double numbers

    public

    function double_number_10()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 10);
        } else {
            $a = rand(1, 10);
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_20()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 20);
        } else {
            $a = rand(1, 20);
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_multiplesof_10()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9) * 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 99) * 10;
        } else {
            $a = rand(1, 9) * 10;
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_multiplesof100()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9) * 100;
        } else {
            $a = rand(1, 9) * 100;
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_multiplesof100andtens()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99) * 10;
        } else {
            $a = rand(1, 99) * 10;
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function _double_number_1000()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(99, 999);
        } else {
            $a = rand(1, 999);
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_10000()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 10000);
        } else {
            $a = rand(1, 10000);
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_100000()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 10000);
        } else {
            $a = rand(1, 10000);
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_beyond20()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } else {
            $a = rand(1, 99);
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_meeting()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 10);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 20);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(20, 50);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 5000);
            $b = $a + 1;
        } else {
            $a = rand(1, 5000);
            $b = $a + 1;
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_exceeding()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 20);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 50);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(20, 100);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 9999);
            $b = $a + 1;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(9999, 99999);
            $b = $a + 1;
        } else {
            $a = rand(1, 9999);
            $b = $a + 1;
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_1decimal()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 999) / 10;
        } else {
            $a = rand(1, 999) / 10;
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function double_number_2decimal()
    {
        $a;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9999) / 100;
        } else {
            $a = rand(1, 9999) / 100;
        }

        $qtn = "What is double " . $a . " ?";
        $answr = $a * 2;
        return $this->make_array($qtn, $answr);
    }

    // halve numbers

    public

    function halve_number_10()
    {
        $a = rand(1, 5) * 2;
        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_20()
    {
        $a = rand(1, 10) * 2;
        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function _halve_number_answerendingzero()
    {
        $a = 10;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
            if ($a % 2 != 0) {
                $a += 1;
            }

            $a *= 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 9);
            if ($a % 2 != 0) {
                $a += 1;
            }

            $a *= 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 9);
            $a *= 100;
        } else {
            print("fail");
        }

        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_answermultiple5()
    {
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9) * 10;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 9) * 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 9) * 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 9) * 100;
        } else {
            $a = rand(1, 9) * 10;
        }

        $a = rand(1, 9) * 10;
        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_100()
    {
        $a = rand(1, 99);
        if ($a % 2 != 0) {
            $a += 1;
        }

        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_1000()
    {
        $a = rand(1, 1000);
        if ($a % 2 != 0) {
            $a += 1;
        }

        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_10000()
    {
        $a = rand(1, 9999);
        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_100000()
    {
        $a = rand(1, 100000);
        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_multipleof100()
    {
        $a = rand(1, 9) * 100;
        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_multipleof10beyond100()
    {
        $a = rand(1, 99);
        if ($a % 2 != 0) {
            $a += 1;
        }

        $a *= 10;
        $qtn = "What is double " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_multipleof10beyond100answerending5()
    {
        $a = rand(1, 99) * 10;
        $qtn = "What is double " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_answerof1decimal()
    {
        $a = rand(1, 999);
        if ($a % 2 === 0) {
            $a += 1;
        }

        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function halve_number_answerof2decimal()
    {
        $a = rand(1, 1999);
        if ($a % 2 === 0) {
            $a += 1;
        }

        $a /= 2;
        $qtn = "What is half of " . $a . " ?";
        $answr = $a / 2;
        return $this->make_array($qtn, $answr);
    }

    public

    function _rand_double_halve_wt_y5()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->_double_number_1000();
        } else {
            return $this->halve_number_1000();
        }
    }

    public

    function double_halve_aare_y5_rand()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->double_number_10000();
        } else {
            return $this->halve_number_10000();
        }
    }

    public

    function double_halve_aboveare_y5_rand()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->double_number_exceeding();
        } else {
            return $this->halve_number_100000();
        }
    }

    // rounding

    public

    function ten_round_numbers()
    {
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(0.1, 0.9);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        } else {
            $a = rand(999, 9999999);
        }

        $qtn = "Round " . $a . " to the nearest ten.";
        $answr = round($a, -1);
        return $this->make_array($qtn, $answr);
    }

    public

    function round_numbers_hundred()
    {
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(0.1, 0.9);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        } else {
            $a = rand(999, 9999999);
        }

        $qtn = "Round " . $a . " to the nearest hundred.";
        $answr = round($a, -2);
        return $this->make_array($qtn, $answr);
    }

    public

    function round_numbers_thousand()
    {
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(0.1, 0.9);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        } else {
            $a = rand(999, 9999999);
        }

        $qtn = "Round " . $a . " to the nearest thousand.";
        $answr = round($a, -3);
        return $this->make_array($qtn, $answr);
    }

    public

    function round_ten_and_hundred()
    {
        if ($_SESSION['year'] === 'y3') {
            $a = rand(1, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        } else {
            $a = rand(999, 9999999);
        }

        $qtn = "Round " . number_format($a) . " to the nearest ten and hundred.";
        $answr = "Ten: " . number_format(round($a, -1)) . ". Hundred: " . number_format(round($a, -2));
        return $this->make_array($qtn, $answr);
    }

    public

    function round_ten_and_hundred_thousand()
    {
        if ($_SESSION['year'] === 'y3') {
            $a = rand(1, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 999999);
        } else {
            $a = rand(999, 9999999);
        }

        $qtn = "Round " . number_format($a) . " to the nearest ten, hundred and thousand.";
        $answr = "Ten: " . number_format(round($a, -1)) . ". Hundred: " . number_format(round($a, -2)) . ". Thousand: " . number_format(round($a, -3));
        return $this->make_array($qtn, $answr);
    }

    public

    function round_tenthousand_hundredthousand()
    {
        if ($_SESSION['year'] === 'y3') {
            $a = rand(1, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(99999, 999999);
        } else {
            $a = rand(99999, 9999999);
        }

        $qtn = "Round " . number_format($a) . " to the nearest ten thousand and hundred thousand.";
        $answr = "Ten thousand: " . number_format(round($a, -4)) . ". Hundred thousand: " . number_format(round($a, -5));
        return $this->make_array($qtn, $answr);
    }

    public

    function round_ten_and_hundred_thousand_tenthousand_hundredthousand()
    {
        if ($_SESSION['year'] === 'y3') {
            $a = rand(1, 999);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(999, 9999);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(99999, 999999);
        } else {
            $a = rand(99999, 9999999);
        }

        $qtn = "Round " . number_format($a) . " to the nearest ten, hundred, thousand, ten thousand and hundred thousand.";
        $answr = "Ten: " . number_format(round($a, -1)) . ". Hundred: " . number_format(round($a, -2)) . ". Thousand: " . number_format(round($a, -3)) . ". Ten thousand: " . number_format(round($a, -4)) . ". Hundred thousand: " . number_format(round($a, -5));
        return $this->make_array($qtn, $answr);
    }

    public

    function round_decimal_numbers_2dp()
    {
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(0.1, 0.9);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(9999, 99999) / 1000;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(9999, 99999) / 1000;
        } else {
            $a = rand(9999, 99999) / 1000;
        }

        $qtn = "Round " . $a . " to the nearest 2 decimal places.";
        $answr = round($a, +2);
        return $this->make_array($qtn, $answr);
    }

    public

    function round_decimal_numbers_3dp()
    {
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(0.1, 0.9);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 99);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(9999, 999999) / 100000;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(9999, 999999) / 100000;
        } else {
            $a = rand(9999, 999999) / 100000;
        }

        $qtn = "Round " . $a . " to the nearest 3 decimal places.";
        $answr = round($a, +3);
        return $this->make_array($qtn, $answr);
    }

    public

    function estimating_add()
    {
        $a = rand(1, 1000);
        $b = rand(1, 1000);
        $rand = rand(0, 1);
        if ($rand === 0) {
            $word = "ten";
        } else {
            $word = "hundred";
        }

        $qtn = "Estimate " . $a . " + " . $b . ". Give your answer to the nearest " . $word . ".";
        if ($rand === 0) {
            $answr = round($a, -1) + round($b, -1);
        } else {
            $answr = round($a, -2) + round($b, -2);
        }

        return $this->make_array($qtn, $answr);
    }

    public

    function estimating_subtract()
    {
        $a = rand(1, 1000);
        $b = rand(1, 1000);
        while ($b > $a) {
            $b = rand(1, 1000);
        }

        $rand = rand(0, 1);
        if ($rand === 0) {
            $word = "ten";
        } else {
            $word = "hundred";
        }

        $qtn = "Estimate " . $a . " - " . $b . ". Give your answer to the nearest " . $word . ".";
        if ($rand === 0) {
            $answr = round($a, -1) - round($b, -1);
        } else {
            $answr = round($a, -2) - round($b, -2);
        }

        return $this->make_array($qtn, $answr);
    }

    public

    function rand_rounding()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->estimating_subtract();
        } else {
            return $this->estimating_add();
        }
    }

    // Addition

    public

    function addition_TO_O()
    {
        $a = rand(1, 100);
        $b = rand(1, 9);
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function addition_TO_TO()
    {
        $a = rand(1, 99);
        $b = rand(1, 99);
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function addition_HTO_TO()
    {
        $a = rand(1, 899);
        $b = rand(1, 99);
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function HTO_HTO_addition()
    {
        $a = rand(1, 999);
        $b = rand(1, 999);
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9) * 10;
        } elseif ($_SESSION['year'] === 'y4') {
            while ($a + $b > 1000) {
                $a = rand(1, 999);
                $b = rand(1, 999);
            }
        } else {
            $a = rand(1, 999);
            $b = rand(1, 999);
        }

        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function missing_HTO_HTO_addition()
    {
        $a = rand(99, 999);
        $b = rand(99, 999);

        $aString = str_split($a);
        $bString = str_split($b);
        $selectA = rand(0, count($aString) - 1);
        $selectB = rand(0, count($bString) - 1);
        while ($selectB === $selectA) {
            $selectB = rand(0, count($bString) - 1);
        }

        $aString[$selectA] = "_";
        $bString[$selectB] = "_";
        $c = $a + $b;
        $qtn = implode($aString) . " + " . implode($bString) . " = "  . $c;

        $answr = $a . " + " . $b . " = " . $c;
        return $this->make_array($qtn, $answr);
    }

    function missing_HTO_TO_addition()
    {
        $a = rand(99, 999);
        $b = rand(9, 99);

        $aString = str_split($a);
        $bString = str_split($b);
        $selectA = rand(0, count($aString) - 1);
        $selectB = rand(0, count($bString) - 1);
        while ($selectB === $selectA) {
            $selectB = rand(0, count($bString) - 1);
        }

        $selector = '';
        $rand = rand(0, 1);
        if ($rand = 0) {
            $aString[$selectA] = "_";
        } else {
            $bString[$selectB] = "_";
        }

        $c = $a + $b;
        $qtn = implode($aString) . " + " . implode($bString) . " = "  . $c;

        $answr = $a . " + " . $b . " = " . $c;
        return $this->make_array($qtn, $answr);
    }

    public

    function bridge1000_addition_HTO_HTO()
    {
        $a = rand(1, 999);
        $b = rand(1, 999);
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9) * 10;
        } elseif ($_SESSION['year'] === 'y4') {
            while ($a + $b < 1000) {
                $a = rand(1, 999);
                $b = rand(1, 999);
            }
        } else {
            $a = rand(1, 999);
            $b = rand(1, 999);
        }

        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function addition_ThHTO_HTO()
    {
        $a = rand(1, 9999);
        $b = rand(1, 999);
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function addition_ThHTO_ThHTO()
    {
        $a = rand(1, 9999);
        $b = rand(1, 9999);
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function missing_ThHTO_ThHTO_addition()
    {
        $a = rand(999, 9999);
        $b = rand(999, 9999);

        $aString = str_split($a);
        $bString = str_split($b);
        $selectA = rand(0, count($aString) - 1);
        $selectB = rand(0, count($bString) - 1);
        $selectA2 = rand(0, count($aString) - 1);
        $selectB2 = rand(0, count($bString) - 1);
        while ($selectB === $selectA) {
            $selectB = rand(0, count($bString) - 1);
        }
        while ($selectA2 === $selectA || $selectA2 === $selectB) {
            $selectA2 = rand(0, count($bString) - 1);
        }
        while ($selectB2 === $selectA || $selectB2 === $selectB || $selectB2 === $selectA2) {
            $selectB2 = rand(0, count($bString) - 1);
        }

        $aString[$selectA] = "_ ";
        $bString[$selectB] = "_ ";
        $aString[$selectA2] = "_ ";
        $bString[$selectB2] = "_ ";

        $c = $a + $b;
        $qtn = implode($aString) . " + " . implode($bString) . " = "  . $c;

        $answr = $a . " + " . $b . " = " . $c;
        return $this->make_array($qtn, $answr);
    }

    function missing_TThThHTO_TthThHTO_addition()
    {
        $a = rand(9999, 99999);
        $b = rand(9999, 99999);

        $aString = str_split($a);
        $bString = str_split($b);
        $selectA = rand(0, count($aString) - 1);
        $selectB = rand(0, count($bString) - 1);
        $selectA2 = rand(0, count($aString) - 1);
        $selectB2 = rand(0, count($bString) - 1);
        while ($selectB === $selectA) {
            $selectB = rand(0, count($bString) - 1);
        }
        while ($selectA2 === $selectA || $selectA2 === $selectB) {
            $selectA2 = rand(0, count($bString) - 1);
        }
        while ($selectB2 === $selectA || $selectB2 === $selectB || $selectB2 === $selectA2) {
            $selectB2 = rand(0, count($bString) - 1);
        }

        $aString[$selectA] = "_ ";
        $bString[$selectB] = "_ ";
        $aString[$selectA2] = "_ ";
        $bString[$selectB2] = "_ ";

        $c = $a + $b;
        $qtn = implode($aString) . " + " . implode($bString) . " = "  . $c;

        $answr = $a . " + " . $b . " = " . $c;
        return $this->make_array($qtn, $answr);
    }

    function missing_TThThHTO_TthThHTO_subtraction()
    {
        $a = rand(9999, 99999);
        $b = rand(9999, $a);

        $aString = str_split($a);
        $bString = str_split($b);
        $selectA = rand(0, count($aString) - 1);
        $selectB = rand(0, count($bString) - 1);
        $selectA2 = rand(0, count($aString) - 1);
        $selectB2 = rand(0, count($bString) - 1);
        while ($selectB === $selectA) {
            $selectB = rand(0, count($bString) - 1);
        }
        while ($selectA2 === $selectA || $selectA2 === $selectB) {
            $selectA2 = rand(0, count($bString) - 1);
        }
        while ($selectB2 === $selectA || $selectB2 === $selectB || $selectB2 === $selectA2) {
            $selectB2 = rand(0, count($bString) - 1);
        }

        $aString[$selectA] = "_ ";
        $bString[$selectB] = "_ ";
        $aString[$selectA2] = "_ ";
        $bString[$selectB2] = "_ ";

        $c = $a - $b;
        $qtn = implode($aString) . " - " . implode($bString) . " = "  . $c;

        $answr = $a . " - " . $b . " = " . $c;
        return $this->make_array($qtn, $answr);
    }

    function missing_ThHTO_ThHTO_subtraction()
    {
        $a = rand(999, 9999);
        $b = rand(999, $a);

        $aString = str_split($a);
        $bString = str_split($b);
        $selectA = rand(0, count($aString) - 1);
        $selectB = rand(0, count($bString) - 1);
        $selectA2 = rand(0, count($aString) - 1);
        $selectB2 = rand(0, count($bString) - 1);
        while ($selectB === $selectA) {
            $selectB = rand(0, count($bString) - 1);
        }
        while ($selectA2 === $selectA || $selectA2 === $selectB) {
            $selectA2 = rand(0, count($bString) - 1);
        }
        while ($selectB2 === $selectA || $selectB2 === $selectB || $selectB2 === $selectA2) {
            $selectB2 = rand(0, count($bString) - 1);
        }

        $aString[$selectA] = "_ ";
        $bString[$selectB] = "_ ";
        $aString[$selectA2] = "_ ";
        $bString[$selectB2] = "_ ";

        $c = $a - $b;
        $qtn = implode($aString) . " - " . implode($bString) . " = "  . $c;

        $answr = $a . " - " . $b . " = " . $c;
        return $this->make_array($qtn, $answr);
    }

    function missing_HTO_TO_multiplication()
    {
        $a = rand(999, 999);
        $b = rand(9, 99);

        $aString = str_split($a);
        $bString = str_split($b);
        $selectA = rand(0, count($aString) - 1);
        $selectB = rand(0, count($bString) - 1);

        while ($selectB === $selectA) {
            $selectB = rand(0, count($bString) - 1);
        }

        $aString[$selectA] = "_ ";
        $bString[$selectB] = "_ ";

        $c = $a * $b;
        $qtn = implode($aString) . " x " . implode($bString) . " = "  . $c;

        $answr = $a . " x " . $b . " = " . $c;
        return $this->make_array($qtn, $answr);
    }

    function addition_TthThHTO_ThHTO()
    {
        $a = rand(1, 99999);
        $b = rand(1, 9999);
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function addition_TthThHTO_TthThHTO()
    {
        $a = rand(1, 99999);
        $b = rand(1, 99999);
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function rand_adding_beyond_four_digits()
    {
        $a = rand(0, 4);
        if ($a === 0) {
            return $this->addition_ThHTO_HTO();
        } elseif ($a === 1) {
            return $this->addition_ThHTO_ThHTO();
        } elseif ($a === 2) {
            return $this->addition_TthThHTO_ThHTO();
        } else {
            return $this->addition_TthThHTO_TthThHTO();
        }
    }

    // addition decimals

    public

    function addition_ot_ot()
    {
        $a = rand(1, 99) / 10;
        $b = rand(1, 99) / 10;
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function addition_oth_oth()
    {
        $a = rand(1, 999) / 100;
        $b = rand(1, 999) / 100;
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function addition_othth_othth()
    {
        $a = rand(99, 9999) / 1000;
        $b = rand(99, 9999) / 1000;
        $qtn = $a . " + " . $b . " =";
        $answr = $a + $b;
        return $this->make_array($qtn, $answr);
    }

    // Subtraction

    public

    function subtraction_TO_O()
    {
        $a = rand(9, 100);
        $b = rand(1, 9);
        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_TO_TO()
    {
        $a = rand(1, 99);
        $b = rand(1, $a);
        while ($a - $b < 0) {
            $b = rand(1, $a);
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_HTO_TO()
    {
        $a = rand(1, 999);
        $b = rand(1, 99);
        while ($a - $b < 0) {
            $b = rand(1, 99);
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_HTO_HTO()
    {
        $a = rand(1, 999);
        $b = rand(1, $a);
        while ($a - $b < 0) {
            $b = rand(1, $a);
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_ThHTO_HTO()
    {
        $a = rand(1, 9999);
        $b = rand(1, 999);
        while ($a - $b < 0) {
            $b = rand(1, 999);
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_ThHTO_ThHTO()
    {
        $a = rand(1, 9999);
        $b = rand(1, $a);
        while ($a - $b < 0) {
            $b = rand(1, $a);
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_TthThHTO_ThHTO()
    {
        $a = rand(1, 99999);
        $b = rand(1, 9999);
        while ($a - $b < 0) {
            $b = rand(1, 9999);
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_TthThHTO_TthThHTO()
    {
        $a = rand(1, 99999);
        $b = rand(1, $a);
        while ($a - $b < 0) {
            $b = rand(1, $a);
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_HthTthThHTO_TthThHTO()
    {
        $a = rand(1, 999999);
        $b = rand(1, $a);
        while ($a - $b < 0) {
            $b = rand(1, $a);
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function add_or_subtract_3_digit()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->subtraction_HTO_HTO();
        } else {
            return $this->HTO_HTO_addition();
        }
    }

    public

    function add_or_subtract_4_digit()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->subtraction_ThHTO_HTO();
        } else {
            return $this->addition_ThHTO_HTO();
        }
    }

    // subtraction decimal

    public

    function subtraction_ot_ot()
    {
        $a = rand(1, 99) / 10;
        $b = rand(1, 99) / 10;
        while ($a - $b < 0) {
            $a = rand(1, 99) / 10;
            $b = rand(1, 99) / 10;
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_ot_otth()
    {
        $a = rand(1, 99) / 10;
        $b = rand(99, 999) / 100;
        while ($a - $b < 0) {
            $a = rand(1, 99) / 10;
            $b = rand(99, 999) / 100;
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_oth_oth()
    {
        $a = rand(1, 999) / 100;
        $b = rand(1, 999) / 100;
        while ($a - $b < 0) {
            $a = rand(1, 999) / 100;
            $b = rand(1, 999) / 100;
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_oth_othth()
    {
        $a = rand(99, 999) / 100;
        $b = rand(99, 9999) / 1000;
        while ($a - $b < 0) {
            $a = rand(1, 999) / 100;
            $b = rand(1, 9999) / 1000;
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function subtraction_othth_othth()
    {
        $a = rand(999, 9999) / 1000;
        $b = rand(999, 9999) / 1000;
        while ($a - $b < 0) {
            $a = rand(999, 9999) / 1000;
            $b = rand(999, 9999) / 1000;
        }

        $qtn = $a . " - " . $b . " =";
        $answr = $a - $b;
        return $this->make_array($qtn, $answr);
    }

    // times tables

    public

    function two_five_ten()
    {
        $array = [2, 5, 10];
        $a = rand(1, 10);
        $selector = rand(0, 2);
        $b = $array[$selector];
        $qtn = $a . " x " . $b . " = ";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_two_five_ten()
    {
        $array = [2, 5, 10];
        $selector = rand(0, 2);
        $b = $array[$selector];
        $a = rand(1, 10) * $b;
        $qtn = $a . " &#247; " . $b . " = ";
        $answr = $a / $b;
        return $this->make_array($qtn, $answr);
    }

    // Identify multiples of 2, 5 , 10

    public

    function multiples_of_2_5_10()
    {
        $a = rand(1, 100);
        $answr;
        if ($a % 2 === 0 && $a % 5 === 0 && $a % 10 === 0) {
            $answr = "It is a multiple of 2, 5 and 10";
        } elseif ($a % 2 != 0 && $a % 5 === 0 && $a % 10 === 0) {
            $answr = "It is a multiple of 5 and 10";
        } elseif ($a % 2 === 0 && $a % 5 != 0 && $a % 10 === 0) {
            $answr = "It is a multiple of 2 and 10";
        } elseif ($a % 2 === 0 && $a % 5 === 0 && $a % 10 != 0) {
            $answr = "It is a multiple of 2 and 5";
        } elseif ($a % 2 != 0 && $a % 5 != 0 && $a % 10 === 0) {
            $answr = "It is a multiple of 10";
        } elseif ($a % 2 != 0 && $a % 5 === 0 && $a % 10 != 0) {
            $answr = "It is a multiple of 5";
        } elseif ($a % 2 === 0 && $a % 5 != 0 && $a % 10 != 0) {
            $answr = "It is a multiple of 2";
        } else {
            $answr = "Not a multiple.";
        }

        $qtn = "Is " . $a . " a multiple of 2, 5 or 10?";
        return $this->make_array($qtn, $answr);
    }

    public

    function three_x()
    {
        $multiplier = 3;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function four_x()
    {
        $multiplier = 4;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function five_x()
    {
        $multiplier = 5;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function six_x()
    {
        $multiplier = 6;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function seven_x()
    {
        $multiplier = 7;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function eight_x()
    {
        $multiplier = 8;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function nine_x()
    {
        $multiplier = 9;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function eleven_x()
    {
        $multiplier = 11;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function twelve_x()
    {
        $multiplier = 12;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function ten_x()
    {
        $multiplier = 10;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier . " x " . $a . " = ";
        $answr = $multiplier . " x " . $a . " = " . $multiplier * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function rand_3_4_8()
    {
        $a = rand(0, 2);
        if ($a === 0) {
            return $this->three_x();
        } elseif ($a === 1) {
            return $this->four_x();
        } else {
            return $this->eight_x();
        }
    }

    public

    function _3_number_multiply()
    {
        $a = rand(1, 12);
        $b = rand(1, 12);
        $c = rand(1, 12);
        $qtn = $a . " x " . $b . " x " . $c . " = ";
        $answr = $a * $b * $c;
        return $this->make_array($qtn, $answr);
    }

    public

    function all_rand()
    {
        $a = rand(0, 11);
        if ($a === 0) {
            return $this->three_x();
        } elseif ($a === 1) {
            return $this->four_x();
        } elseif ($a === 2) {
            return $this->five_x();
        } elseif ($a === 3) {
            return $this->six_x();
        } elseif ($a === 4) {
            return $this->seven_x();
        } elseif ($a === 5) {
            return $this->eight_x();
        } elseif ($a === 6) {
            return $this->nine_x();
        } elseif ($a === 7) {
            return $this->eleven_x();
        } elseif ($a === 8) {
            return $this->twelve_x();
        } else {
            return $this->ten_x();
        }
    }

    public

    function multiple()
    {
        if ($_SESSION['year'] === 'y4') {
            $a = rand(1, 10);
            $b = rand(1, 12);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 250);
            $b = rand(1, 12);
        } else {
            $a = rand(1, 1000);
            $b = rand(1, 12);
        }

        $c = $a * $b;
        if (rand(0, 1) === 0) {
            $c += rand(1, 20);
        }

        $qtn = "Is " . $c . " a multiple of " . $b . "?";
        $answr = "";
        if ($c % $b === 0) {
            $answr = $c . " is a multiple of " . $b . ".";
        } else {
            $answr = $c . " is NOT a multiple of " . $b . ".";
        }

        return $this->make_array($qtn, $answr);
    }

    public

    function factor()
    {
        if ($_SESSION['year'] === 'y4') {
            $a = rand(1, 10);
            $b = rand(2, 12);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 100);
            $b = rand(2, 12);
        } else {
            $a = rand(1, 1000);
            $b = rand(2, 12);
        }

        $c = $a * $b;
        if (rand(0, 1) === 0) {
            $c += rand(1, 20);
        } else {
            $c = $a * $b;
        }

        $qtn = "Is " . $b . " a factor of " . $c . "?";
        $answr = "";
        if ($a * $b === $c) {
            $answr = $b . " is a factor of " . $c . " (" . $a . " x " . $b . " = " . $c . ")";
        } else {
            $answr = $b . " is NOT a factor of " . $c . ".";
        }

        return $this->make_array($qtn, $answr);
    }

    public

    function rand_factor_multiple()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->factor();
        } else {
            return $this->multiple();
        }
    }

    public

    function factor_pairs()
    {
        $f = rand(5, 144);
        $pairs = array();
        $unique = array();
        for ($i = 1; $i < $f / 2; $i++) { // only need to check until half (the other half would repeat the values

            // if not divisable / already found

            if ($f % $i != 0 || isset($unique[$i]) || isset($unique[$f / $i])) {
                continue;
            }

            $pairs[] = array(
                $i,
                $f / $i
            );
            $unique[$i] = true;
        }

        $answr = "";
        $qtn = "List all the factor pairs of " . $f;
        $p = array();
        foreach ($pairs as $pair) {
            $answr .= sprintf('(%s, %s) ', $pair[0], $pair[1]);
        }

        return $this->make_array($qtn, $answr);

        // echo($answr);

    }

    public

    function commutativity()
    {
        $a = rand(1, 9);
        $b = rand(1, 9);
        $c = $a * $b;
        $d = rand(1, 3);
        $qtn;
        $answr;
        if ($d === 1) {
            $qtn = "Find the missing numbers: __ x " . $b . " = __ = " . $b . " x " . $a;
            $answr = $a . ", " . $c;
        } elseif ($d === 2) {
            $qtn = "Find the missing numbers: " . $a . " x __ = " . $c . " = " . $b . " x __ ";
            $answr = $b . ", " . $a;
        } elseif ($d === 3) {
            $qtn = "Find the missing numbers: " . $a . " x __ = __ = " . $b . " x " . $a;
            $answr = $b . ", " . $c;
        }

        return $this->make_array($qtn, $answr);
    }

    public

    function square_numbers()
    {
        $a = rand(1, 12);
        $qtn = "What is " . $a . "<sup>2</sup>?";
        $answr = $a . "<sup>2</sup> = " . $a * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function cubed_numbers()
    {
        $a = rand(1, 12);
        $qtn = "What is " . $a . "<sup>3</sup>?";
        $answr = $a . "<sup>3</sup> = " . $a * $a * $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function rand_cubed_squared()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->cubed_numbers();
        } else {
            return $this->square_numbers();
        }
    }

    public

    function prime_numbers()
    {
        $a;
        if ($_SESSION['year'] === 'y4') {
            $a = rand(1, 50);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 100);
        } else {
            $a = rand(1, 1000);
        }

        function is_prime($number)
        {

            // 1 is not prime

            if ($number == 1) {
                return false;
            }

            // 2 is the only even prime number

            if ($number == 2) {
                return true;
            }

            // square root algorithm speeds up testing of bigger prime numbers

            $x = sqrt($number);
            $x = floor($x);
            for ($i = 2; $i <= $x; ++$i) {
                if ($number % $i == 0) {
                    break;
                }
            }

            if ($x == $i - 1) {
                return true;
            } else {
                return false;
            }
        }

        $prime = is_prime($a);
        $answr;
        if ($prime === true) {
            $answr = $a . " is a prime number.";
        } else {
            $answr = $a . " is a composite number.";
        }

        $qtn = "Is " . $a . " a prime or composite number?";
        return $this->make_array($qtn, $answr);
    }

    public

    function common_factors()
    {
        $array = [2, 4, 6, 8, 10];
        $c = $array[rand(1, 4)];
        $a = floor(rand(1, 15) * $c);
        $b = floor(rand(1, 15) * $c);
        $answr = "";
        $number_of_factors = 0;
        $min = ($a < $b) ? $a : $b;
        for ($i = 1; $i <= $min / 2; $i++) {
            if (($a % $i == 0) && ($b % $i == 0)) {
                $answr .= $i . ", ";
                $number_of_factors++;
            }
        }

        trim($answr, -2);
        $qtn = "Identify the " . $number_of_factors . " common factors of " . $a . " and " . $b . ".";
        return $this->make_array($qtn, $answr);
    }

    // inverse

    public

    function inverse_three_x()
    {
        $multiplier = 3;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_four_x()
    {
        $multiplier = 4;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_five_x()
    {
        $multiplier = 5;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_six_x()
    {
        $multiplier = 6;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_seven_x()
    {
        $multiplier = 7;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_eight_x()
    {
        $multiplier = 8;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_nine_x()
    {
        $multiplier = 9;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_ten_x()
    {
        $multiplier = 10;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_eleven_x()
    {
        $multiplier = 11;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function inverse_twelve_x()
    {
        $multiplier = 12;
        $tables = rand(0, 11);
        $a = rand(0, 11);
        $qtn = $multiplier * $tables . " &#247; " . $multiplier . " = ";
        $answr = $multiplier * $tables . " &#247; " . $multiplier . " = " . $tables;
        return $this->make_array($qtn, $answr);
    }

    public

    function rand_inverse_y3()
    {
        $a = rand(0, 4);
        if ($a === 0) {
            return $this->inverse_three_x();
        } elseif ($a === 1) {
            return $this->inverse_four_x();
        } elseif ($a === 2) {
            return $this->inverse_five_x();
        } elseif ($a === 3) {
            return $this->inverse_eight_x();
        } elseif ($a === 4) {
            return $this->inverse_ten_x();
        }
    }

    public

    function rand_inverse()
    {
        $a = rand(0, 11);
        if ($a === 0) {
            return $this->inverse_three_x();
        } elseif ($a === 1) {
            return $this->inverse_four_x();
        } elseif ($a === 2) {
            return $this->inverse_five_x();
        } elseif ($a === 3) {
            return $this->inverse_six_x();
        } elseif ($a === 4) {
            return $this->inverse_seven_x();
        } elseif ($a === 5) {
            return $this->inverse_eight_x();
        } elseif ($a === 6) {
            return $this->inverse_nine_x();
        } elseif ($a === 7) {
            return $this->inverse_eleven_x();
        } elseif ($a === 8) {
            return $this->inverse_twelve_x();
        } else {
            return $this->inverse_ten_x();
        }
    }

    public

    function multiply_inverse_rand()
    {
        $a = rand(0, 1);
        if ($a === 0) {
            return $this->rand_inverse();
        } elseif ($a === 1) {
            return $this->all_rand();
        }
    }

    // missing number multiplication
    // multiply

    public

    function TO_O_multiply()
    {
        $a = rand(9, 100);
        $b = rand(1, 9);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function x_multiply_TO_TO()
    {
        $a = rand(10, 99);
        $b = rand(10, $a);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_HTO_O()
    {
        $a = rand(1, 999);
        $b = rand(1, 9);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_HTO_TO()
    {
        $a = rand(1, 999);
        $b = rand(1, 99);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_HTO_HTO()
    {
        $a = rand(1, 999);
        $b = rand(1, $a);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_ThHTO_HTO()
    {
        $a = rand(1, 9999);
        $b = rand(1, 999);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_ThHTO_O()
    {
        $a = rand(1, 9999);
        $b = rand(1, 9);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_ThHTO_TO()
    {
        $a = rand(1, 9999);
        $b = rand(10, 99);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_ThHTO_ThHTO()
    {
        $a = rand(1, 9999);
        $b = rand(1, $a);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_TthThHTO_ThHTO()
    {
        $a = rand(1, 99999);
        $b = rand(1, 9999);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_TthThHTO_TthThHTO()
    {
        $a = rand(1, 99999);
        $b = rand(1, $a);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function multiply_unittenth_x_unit()
    {
        $a = rand(1, 99) / 10;
        $b = rand(2, 9);
        $qtn = $a . " x " . $b . " =";
        $answr = $a * $b;
        return $this->make_array($qtn, $answr);
    }

    public

    function divide_TO_O_noremainder()
    {
        $a = rand(1, 9);
        $b = rand(1, 9);
        $c = $a * $b;
        $remainder = $a % $b;
        $qtn = $c . " &#247; " . $b . " =";
        $answr = $a;
        return $this->make_array($qtn, $answr);
    }

    public

    function TO_O_divide()
    {
        $a = rand(9, 100);
        $b = rand(1, 9);
        $remainder = $a % $b;
        $qtn = $a . " &#247; " . $b . " =";
        if ($remainder > 0) {
            $answr = round($a / $b) . " rem. " . $remainder;
        } else {
            $answr = $a / $b;
        }

        return $this->make_array($qtn, $answr);
    }

    public

    function divide_HTO_O()
    {
        $a = rand(100, 1000);
        $b = rand(1, 9);
        $remainder = $a % $b;
        $qtn = $a . " &#247; " . $b . " =";
        if ($remainder > 0) {
            $answr = round($a / $b) . " rem. " . $remainder;
        } else {
            $answr = $a / $b;
        }

        return $this->make_array($qtn, $answr);
    }

    public

    function divide_ThHTO_O()
    {
        $a = rand(1000, 10000);
        $b = rand(1, 9);
        $remainder = $a % $b;
        $qtn = $a . " &#247; " . $b . " = ";
        if ($remainder > 0) {
            $answr = round($a / $b) . " rem. " . $remainder;
        } else {
            $answr = $a / $b;
        }

        return $this->make_array($qtn, $answr);
    }

    public

    function multiplybyten()
    {
        $a = rand(1, 9);
        $b = 10;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 20);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(10, 100);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 1000);
            $b = 10;
        } else {
            $a = rand(500, 3000);
            $b = 10;
        }

        $qtn = $a . " x " . $b . " = ";
        $aswr = $a * $b;
        return $this->make_array($qtn, $aswr);
    } //year 1-6
    public

    function multiplybyhundred()
    {
        $a = rand(1, 99);
        $b = 100;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
            $b = 100;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 20);
            $b = 100;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 9);
            $b = 100;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = 100;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 1000);
            $b = 100;
        } else {
            $a = rand(500, 3000);
            $b = 100;
        }

        $qtn = $a . " x " . $b . " = ";
        $aswr = $a * $b;
        return $this->make_array($qtn, $aswr);
    }

    public

    function multiplybythousand()
    {
        $a = rand(1, 99);
        $b = 1000;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 19);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(10, 100);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(100, 1000);
            $b = 10;
        } else {
            $a = rand(500, 3000);
            $b = 10;
        }

        $qtn = $a . " x " . $b . " = ";
        $aswr = $a * $b;
        return $this->make_array($qtn, $aswr);
    }

    public

    function dividebyten()
    {
        $a = rand(1, 9);
        $b = 10;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 20);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(10, 100);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000);
        } else {
            $a = rand(500, 3000);
        }

        $qtn = $a . " &#247 " . $b . " = ";
        $aswr = $a / $b;
        return $this->make_array($qtn, $aswr);
    }

    public

    function dividebyhundred()
    {
        $a = rand(1, 9);
        $b = 100;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 20);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(10, 100);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000);
        } else {
            $a = rand(500, 3000);
        }

        $qtn = $a . " &#247 " . $b . " = ";
        $aswr = $a / $b;
        return $this->make_array($qtn, $aswr);
    }

    public

    function dividebythousand()
    {
        $a = rand(1, 9);
        $b = 1000;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 20);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(10, 100);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000);
        } else {
            $a = rand(500, 3000);
        }

        $qtn = $a . " &#247 " . $b . " = ";
        $aswr = $a / $b;
        return $this->make_array($qtn, $aswr);
    }

    public

    function decimalmultiplybyten()
    {
        $a = rand(1, 9);
        $b = 10;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 9) / 10;
        } else {
            $a = rand(500, 3000);
        }

        $qtn = $a . " x " . $b . " = ";
        $aswr = $a * $b;
        return $this->make_array($qtn, $aswr);
    } //year 1-6
    public

    function decimalmultiplybyones()
    {
        $a = rand(11, 99) / 10;
        $b = rand(2, 9);

        $qtn = $a . " x " . $b . " = ";
        $aswr = $a * $b;
        return $this->make_array($qtn, $aswr);
    } //year 1-6
    public

    function decimalmultiplybyhundred()
    {
        $a = rand(1, 99);
        $b = 100;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 9) / 10;
        } else {
            $a = rand(500, 3000);
        }

        $qtn = $a . " x " . $b . " = ";
        $aswr = $a * $b;
        return $this->make_array($qtn, $aswr);
    }

    public

    function decimalmultiplybythousand()
    {
        $a = rand(1, 99);
        $b = 1000;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 9) / 10;
        } else {
            $a = rand(500, 3000);
        }

        $qtn = $a . " x " . $b . " = ";
        $aswr = $a * $b;
        return $this->make_array($qtn, $aswr);
    }

    public

    function decimaldividebyten()
    {
        $a = rand(1, 9);
        $b = 10;
        if ($_SESSION['year'] === 'y1') {
            $a = rand(1, 9);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 9) / 10;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 9) / 10;
        } else {
            $a = rand(500, 3000);
        }

        $qtn = $a . " &#247 " . $b . " = ";
        $aswr = $a / $b;
        return $this->make_array($qtn, $aswr);
    }

    public

    function rand_multiply_divide_powers10()
    {
        $a = rand(0, 8);
        if ($a === 0) {
            return $this->multiplybyten();
        } elseif ($a === 1) {
            return $this->multiplybyhundred();
        } elseif ($a === 2) {
            return $this->multiplybythousand();
        } elseif ($a === 3) {
            return $this->dividebyten();
        } elseif ($a === 4) {
            return $this->dividebyhundred();
        } elseif ($a === 5) {
            return $this->dividebythousand();
        } elseif ($a === 6) {
            return $this->decimalmultiplybyten();
        } elseif ($a === 7) {
            return $this->decimalmultiplybythousand();
        } elseif ($a === 8) {
            return $this->decimaldividebyten();
        }
    }

    // Represent numbers

    public

    function in_words()
    {
        $words = new Resources();
        $number;
        if ($_SESSION['year'] === 'y1') {
            $number = rand(1, 20);
        } elseif ($_SESSION['year'] === 'y2') {
            $number = rand(1, 100);
        } elseif ($_SESSION['year'] === 'y3') {
            $number = rand(100, 1000);
        } elseif ($_SESSION['year'] === 'y4') {
            $number = rand(100, 1000);
        } elseif ($_SESSION['year'] === 'y5') {
            $number = rand(100, 1000000);
        } else {
            $number = rand(500, 10000000);
        }

        $a = number_format($number);
        $qtn = "Write " . $a . " in words.";
        $answr = $words->numbers_in_words($a);
        return $this->make_array($qtn, $answr);
    } //year 1
    public

    function number_lines_y2()
    {
        $firstnum = rand(1, 50);
        $endnum = $firstnum + 10;
        $number_to_place = rand($firstnum, $endnum);
        $increment = round(($endnum - $firstnum) / 10);
        if ($_SESSION['year'] === 'y1') {
            $firstnum = rand(1, 50);
            $endnum = $firstnum + 10;
            $number_to_place = rand($firstnum, $endnum);
            $increment = round(($endnum - $firstnum) / 10);
            $space = "---";
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000);
            $b = $a + 25;
        } else {
            $a = rand(1, 1000);
            $b = $a + 25;
        }

        if ($number_to_place === $firstnum) {
            $number_to_place -= 1;
        }

        $qtnstring = "Place " . $number_to_place . " on the number line. " . $firstnum;
        $answrstrg = $firstnum;
        for ($i = $firstnum; $i <= $endnum; $i += $increment) {
            if ($i === $number_to_place) {
                $qtnstring .= $space;
                $answrstrg .= $number_to_place;
            } elseif ($i < $endnum) {
                $qtnstring .= $space;
                $answrstrg .= $space;
            }
        }

        $qtnstring .= $endnum;
        $answrstrg .= $endnum;
        return $this->make_array($qtnstring, $answrstrg);
    }

    public

    function number_lines_y3()
    {
        $firstnum = rand(1, 50);
        $endnum = $firstnum + 10;
        $number_to_place = rand($firstnum, $endnum);
        $increment = round(($endnum - $firstnum) / 10);
        if ($_SESSION['year'] === 'y1') {
            $firstnum = rand(1, 50);
            $endnum = $firstnum + 10;
            $number_to_place = rand($firstnum, $endnum);
            $increment = round(($endnum - $firstnum) / 10);
            $space = "---";
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 100);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 500);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
            $b = $a + 25;
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000);
            $b = $a + 25;
        } else {
            $a = rand(1, 1000);
            $b = $a + 25;
        }

        if ($number_to_place === $firstnum) {
            $number_to_place -= 1;
        }

        $qtnstring = "Place " . $number_to_place . " on the number line. " . $firstnum;
        $answrstrg = $firstnum;
        for ($i = $firstnum; $i <= $endnum; $i += $increment) {
            if ($i === $number_to_place) {
                $qtnstring .= $space;
                $answrstrg .= $number_to_place;
            } elseif ($i < $endnum) {
                $qtnstring .= $space;
                $answrstrg .= $space;
            }
        }

        $qtnstring .= $endnum;
        $answrstrg .= $endnum;
        return $this->make_array($qtnstring, $answrstrg);
    }

    public

    function read_roman_numerals()
    {
        if ($_SESSION['year'] === 'y1') {
            $firstnum = rand(1, 5);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 10);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 20);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 100);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000);
        } else {
            $a = rand(1, 9999);
        }

        $numerals = $this->numberToRomanRepresentation($a);
        $qtnstrg = "What is " . $numerals . " in digits?";
        $answr = $a;
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function roman_numerals_exceeding()
    {
        if ($_SESSION['year'] === 'y1') {
            $firstnum = rand(1, 5);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 10);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 20);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 500);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(999, 2030);
        } else {
            $a = rand(1, 3000);
        }

        $numerals = $this->numberToRomanRepresentation($a);
        $qtnstrg = "What is " . $numerals . " in digits?";
        $answr = $a;
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function write_roman_numerals_exceeding()
    {
        if ($_SESSION['year'] === 'y1') {
            $firstnum = rand(1, 5);
        } elseif ($_SESSION['year'] === 'y2') {
            $a = rand(1, 10);
        } elseif ($_SESSION['year'] === 'y3') {
            $a = rand(1, 20);
        } elseif ($_SESSION['year'] === 'y4') {
            $a = rand(1, 100);
        } elseif ($_SESSION['year'] === 'y5') {
            $a = rand(1, 1000);
        } else {
            $a = rand(1, 3000);
        }

        $qtnstrg = "What is " . $a . " in Roman Numerals?";
        $answr = $this->numberToRomanRepresentation($a);
        return $this->make_array($qtnstrg, $answr);
    }

    public

    function numberToRomanRepresentation($number)
    {
        $map = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }

        return $returnValue;
    }

    //fractions

    public function orderFractionsSameDenoms()
    {
        $qtnstrg = "Sort these fractions, smallest first: ";
        $answr = "";

        $multiples = rand(3, 12);

        $fractionsToSort = array();

        for ($i = 0; $i < 6; $i++) {
            //$selector = rand(2, $size) - 1;
            $denom = $multiples;
            $numer = rand(1, $denom);
            $fractionString = $numer . "/" . $denom;
            $decimal = $this->convertToDecimal($fractionString);
            $fractionsToSort[$fractionString] = $decimal;
        }

        foreach ($fractionsToSort as $key => $value) {
            $qtnstrg = $qtnstrg . $key . " - ";
        }

        asort($fractionsToSort);

        foreach ($fractionsToSort as $key => $value) {
            $answr = $answr . $key . " - ";
        }

        $answr = trim($answr, '- ');
        $qtnstrg = trim($qtnstrg, '- ');

        return $this->make_array($qtnstrg, $answr);
    }

    public function orderFractionsDifferentDenoms()
    {

        $qtnstrg = "Sort these fractions, smallest first: ";
        $answr = "";

        do {
            $commonMultiple = rand(10, 50);
            $multiples = $this->findCommonMultiples($commonMultiple);
            $size = count($multiples);
        } while ($size < 4);

        $fractionsToSort = array();

        for ($i = 0; $i < 6; $i++) {
            $selector = rand(2, $size) - 1;
            $denom = $multiples[$selector];
            $numer = rand(1, $denom);
            $fractionString = $numer . "/" . $denom;
            $decimal = $this->convertToDecimal($fractionString);
            $fractionsToSort[$fractionString] = $decimal;
        }

        foreach ($fractionsToSort as $key => $value) {
            $qtnstrg = $qtnstrg . $key . " - ";
        }

        asort($fractionsToSort);

        foreach ($fractionsToSort as $key => $value) {
            $answr = $answr . $key . " - ";
        }

        $answr = trim($answr, '- ');
        $qtnstrg = trim($qtnstrg, '- ');

        return $this->make_array($qtnstrg, $answr);
    }

    public function fractionAsDecimalNumbers()
    {
        $denomArray = array(2, 4, 5, 10, 50, 100);
        $denom = $denomArray[rand(0, count($denomArray) - 1)];

        $numer = rand(1, $denom);

        $qtnstrg = "Write " . $numer . "/" . $denom . " as a decimal.";
        $answr = $numer / $denom;

        return $this->make_array($qtnstrg, $answr);
    }



    //fractions: convert fraction to decimal
    function convertToDecimal($fraction)
    {
        $numbers = explode("/", $fraction);
        return round($numbers[0] / $numbers[1], 6);
    }

    //fractions: find common multiples
    public function findCommonMultiples($number)
    {

        $multiples = [];

        for ($i = 1; $i < $number; $i++) {
            if ($number % $i === 0) {
                array_push($multiples, $i);
            }
        }
        return $multiples;
    }

    //percentages

    function percentage10percent10multiple()
    {
        $num = rand(1, 100) * 10;

        $qtn = "Find 10% of " . $num . ".";
        $aswr = $num / 10;
        return $this->make_array($qtn, $aswr);
    }

    function percentage10percentNumber()
    {
        $num = rand(1, 1000);

        $qtn = "Find 10% of " . $num . ".";
        $aswr = $num / 10;
        return $this->make_array($qtn, $aswr);
    }

    function percentage20percent10multiple()
    {
        $num = rand(1, 100) * 10;

        $qtn = "Find 20% of " . $num . ".";
        $aswr = $num / 10 * 2;
        return $this->make_array($qtn, $aswr);
    }

    function percentage20percentNumber()
    {
        $num = rand(1, 1000);

        $qtn = "Find 20% of " . $num . ".";
        $aswr = $num / 10 * 2;
        return $this->make_array($qtn, $aswr);
    }

    function percentage1percent10multiple()
    {
        $num = rand(1, 100) * 10;

        $qtn = "Find 1% of " . $num . ".";
        $aswr = $num / 100;
        return $this->make_array($qtn, $aswr);
    }

    function percentage1percentNumber()
    {
        $num = rand(1, 1000);

        $qtn = "Find 1% of " . $num . ".";
        $aswr = $num / 100;
        return $this->make_array($qtn, $aswr);
    }

    function percentNumber()
    {
        $num = rand(1, 1000);
        $percent = rand(1, 100);

        $qtn = "Find " . $percent . "% of " . $num . ".";
        $aswr = $num / 100 * $percent;
        return $this->make_array($qtn, $aswr);
    }

    function percent5Multiple10()
    {
        $num = rand(1, 100) * 10;

        $qtn = "Find 5% of " . $num . ".";
        $aswr = $num / 100 * 5;
        return $this->make_array($qtn, $aswr);
    }

    // Decimals

    public function decimalAsFraction()
    {
        $num = rand(1, 100) / 100;
        $denom = 100;

        $qtn = "Write " . $num . " as a fraction.";
        $aswr = ($num * 100) . "/" . $denom;
        return $this->make_array($qtn, $aswr);
    }

    public function decimalAsSimplestFraction()
    {
        $num = rand(1, 100);
        $decimal = $num / 100;
        $denom = 100;
        $factor = 1;

        for ($i = 1; $i <= $num; $i++) {
            if (($num % $i === 0) && ($denom % $i === 0)) {
                $factor = $i;
            }
        }

        $qtn = "Write " . $decimal . " as a fraction in its simplest form.";

        $num = $num / $factor;
        $denom = $denom / $factor;

        $aswr = $num . "/" . $denom;
        return $this->make_array($qtn, $aswr);
    }

    public function decimalAsPercent()
    {
        $decimal = rand(1, 100) / 100;

        $qtn = "Write " . $decimal . " as percent(%).";
        $aswr = $decimal * 100 . "%";
        return $this->make_array($qtn, $aswr);
    }

    public function fractionAsPercent()
    {
        $options = [2, 4, 5, 8, 10, 20, 50, 100];
        $denom = $options[rand(0, 7)];
        $numer = rand(1, $denom);
        $fractionString = $numer . "/" . $denom;
        $decimal = $this->convertToDecimal($fractionString);
        $qtn = "Write " . $fractionString . " as percent(%).";
        $aswr = $decimal * 100 . "%";



        return $this->make_array($qtn, $aswr);
    }




    // Convert questions and answers to array.

    public

    function make_array($ques, $ans)
    {
        return $array = array(
            "qtn" => $ques,
            "aswr" => $ans
        );
    }

    public

    function add_commas($number)
    {
        $length = sizeof($number);
    }

    public

    function none()
    {
        $qtn = "Coming soon";
        $aswr = "Coming soon";
        return $this->make_array($qtn, $aswr);
    }
}
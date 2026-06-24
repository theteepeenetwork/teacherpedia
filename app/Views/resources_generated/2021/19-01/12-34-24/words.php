<?php

class Words {
    //Number in words
function numbers_in_words($num = false)
{
    $num = str_replace(array(',', ''), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '' );
        } elseif ($tens >= 20) {
            $tens = (int)($tens / 10);
            $tens = ' and ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    $words = implode(' ',  $words);
    $words = preg_replace('/^\s\b(and)/', '', $words );
    $words = trim($words);
    $words = ucfirst($words);
    $words = $words . ".";
    return $words;
}

    function calcs_in_words() {
            $names = array( 
                "+" => "add",
                "-" => "subtract",
                "÷" => "divide",
                "x" => "times",
            );
            $random = rand(1, 8);
            return($names[$random]);
        }

    function fractions_in_words($select) {
            $fractions = array( 
                2 => "half",
                3 => "third",
                4 => "quarter",
                5 => "fifth",
                6 => "sixth",
                7 => "seventh",
                8 => "eight",
                9 => "ninth",
            );
            return($fractions[$select]);
        }

    function even_number_to() {
        $even_numbers = array();
        for($i = 0; $i < 100; $i++) {
            if($i % 2 == 0) {
                array_push($even_numbers, $i);
            }
        }
            $random = rand(0, 50);
            return($even_numbers[$random]);
        }

    function even_number_o() {
        $even_numbers = array();
        for($i = 0; $i <= 10; $i++) {
            if($i % 2 == 0) {
                array_push($even_numbers, $i);
            }
        }
            $random = rand(1, 5);
            return($even_numbers[$random]);
        }
    
    function girls_name() {
        $names = array( 
            "Annette",
            "Susan",
            "Mary",
            "Hannah",
            "Janet",
            "Sasha",
            "Menique",
            "Sandra",
            "Phillipa",
        );
        
        $random = rand(1, 8);
        return($names[$random]);
    }

    function boys_name() {
        $names = array( 
            "James",
            "Andrew",
            "Mark",
            "Ahmed",
            "Mohammad",
            "Steven",
            "Ashley",
            "Leo",
            "Theo",
        );
        $random = rand(1, 8);
        return($names[$random]);
    }

    function month() {

        $months = array(
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        );
        $random = rand(0, 11);
        return($months[$random]);
    }
    
    function trim_all( $str , $what = NULL , $with = ' ' )
    {
        if( $what === NULL )
        {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space

            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }
   
        return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
    }
    
    function str_replace_last( $search , $replace , $str ) {
        if( ( $pos = strrpos( $str , $search ) ) !== false ) {
            $search_length  = strlen( $search );
            $str    = substr_replace( $str , $replace , $pos , $search_length );
        }
    return $str;
    }
    
}
        


?>
<h1>Place value</h1>
<form class="needs-validation" id="questionsform" action="worksheets2/controller/generator.php" method="post" name="form">
<div class="form-group" style="margin: auto">
    <div style="width: 20%">
    	Worksheet Title: <input class="form-control" type="text" placeholder="Mental Starter" name="sheetName" />
    	<br />
    	Year Group: <ul class="list-group">
        <!--<li>
            <input class="list-group-item" id="year1table" name="year" type="radio" value="y1" onclick="y1()" />Year 1</li>
        <li>
            <input class="list-group-item" id="year2table" name="year" type="radio" value="y2" onclick="y2()" />Year 2</li>-->
        <li class="list-group-item">
            <input id="year3table" name="year" type="radio" value="y3" onclick="y3()" required />Year 3</li>
        <li class="list-group-item">
            <input id="year4table" name="year" type="radio" value="y4" onclick="y4()" />Year 4</li>
        <li class="list-group-item">
            <input id="year5table" name="year" type="radio" value="y5" onclick="y5()" />Year 5</li>
        <li class="list-group-item">
            <input id="year6table" name="year" type="radio" value="y6" onclick="y6()" />Year 6</li>
    	</ul>
	</div>
    <div id="year1" style="display:none">
    </div>
    <div id="year2" style="display:none">
    </div>
    <div id="year3" style="display:none">
        <table class="table table-bordered" border="1" width="" cellspacing="0" cellpadding="0" display="none">
            <tbody>
                <td colspan="9">You can select up to 25 questions.</td>
                <tr>
                    <td colspan="2" rowspan="2" width="238">
                        <p><strong> Assessment Focus </strong></p>
                    </td>
                    <td colspan="6" width="1236">
                        <p><strong> Year 3 Long Term Planning </strong></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="" width="298">
                        <p><strong> Emerging </strong></p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                    <td colspan="" width="298">
                        <p><strong> Developing </strong></p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                    <td colspan="" width="298">
                        <p><strong> Exceeding </strong></p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                </tr>
                <tr>
                    <td rowspan="24" width="118">
                        <p><strong> Number </strong></p>
                    </td>
                    <td rowspan="3" width="119">
                        <p><strong> Counting </strong></p>
                    </td>
                    <td width="250">Count in steps of 50 from 0</td>
                    <td>
                        <select name="id[count_in_fifties_zero][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">Count in steps of 4 from 0</td>
                    <td>
                        <select name="id[count_in_fours][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Count backwards in steps of 50</p>
                    </td>
                    <td>
                        <select name="id[count_in_fifties_multiple_5][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>Count in steps of 100 from 0</p>
                    </td>
                    <td>
                        <select name="id[count_in_5hundreds_zero][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Count in steps of 8 from 0</p>
                    </td>
                    <td>
                        <select name="id[count_in_eights][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Count backwards in steps of 100</p>
                    </td>
                    <td>
                        <select name="id[_count_back_in_hundreds][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>find 10 more or less than a given number</p>
                    </td>
                    <td>
                        <select name="id[rand_ten_more_less][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>find 100 more or less than a given number</p>
                    </td>
                    <td>
                        <select name="id[rand_hundred_more_less][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>find 10 or 100 more or less than a given number.</p>
                    </td>
                    <td>
                        <select name="id[rand_ten_hundred_more_less][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="119">
                        <p><strong> Comparing numbers </strong></p>
                    </td>
                    <td colspan="3" width="250">
                        <p>compare and order numbers up to 1000 in numerals</p>
                    </td>
                    <td>
                        <select name="id[_order_size_order][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>compare and order numbers up to 1000 in words</p>
                    </td>
                    <td>
                        <select name="id[size_order_words][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="119">
                        <p><strong> Identifying, representing &amp; estimating numbers </strong></p>
                    </td>
                    <td rowspan="2" width="250">
                        <p>identify and represent numbers using different methods by estimating postion on a number line</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[number_liney3][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>identify, represent and estimate numbers using different representations (including those related to measure)</p>
                    </td>
                    <td rowspan="2">In development</td>
                    <td width="250">
                        <p>identify, represent and estimate numbers using different representations (including those related to measure)</p>
                    </td>
                    <td>In development</td>
                </tr>
                <tr>
                    <td width="250">
                        <p>recognise multiples of 2, 5 and 10 up to 1000</p>
                    </td>
                    <td>
                        <select name="id[multiples_of_2_5_10][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="119">
                        <p><strong> Reading &amp; writing numbers </strong></p>
                    </td>
                    <td width="250">
                        <p>Read and write numbers up to 1000 in numerals</p>
                    </td>
                    <td>
                        <select name="id[words_write_in_numerals][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Read and write up to 1000 in words</p>
                    </td>
                    <td>
                        <select name="id[write_numerals_in_words][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Read and write numbers up to 1000 in numerals and words</p>
                    </td>
                    <td>
                        <select name="id[rand_nums_words][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="4" width="119">
                        <p><strong> Understanding place value </strong></p>
                    </td>
                    <td width="250">
                        <p>Recognise the value of each digit in 2-digit numbers (tens, ones/units)</p>
                    </td>
                    <td>
                        <select name="id[partition_number_2digit][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Partition and recognise the value of HTU</p>
                    </td>
                    <td>
                        <select name="id[partition_number_3digit][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Partition 3-digit numbers in more than one way (adding values)</p>
                    </td>
                    <td>
                        <select name="id[_add_partition_number][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>Double simple numbers beyond 20 (ending in zero e.g. 30, 40, 50)</p>
                    </td>
                    <td>
                        <select name="id[double_number_multiplesof_10][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Double numbers beyond 20, with units (e.g. 21, 33 etc) up to 50</p>
                    </td>
                    <td>
                        <select name="id[double_number_meeting][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>I am fluent in the order and place value of numbers to 1000</p>
                    </td>
                    <td>
                        <select name="id[rand_place_value_order][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="250">
                        <p>Halve simple numbers beyond 20 (answers ending in zero)</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[_halve_number_answerendingzero][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>Halve numbers beyond 20 (answers ending in 5 e.g. halve of 30 is 15)</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[halve_number_answermultiple5][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Double all numbers up to 100</p>
                    </td>
                    <td>
                        <select name="id[double_number_exceeding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>Halve all numbers up to 100</p>
                    </td>
                    <td>
                        <select name="id[halve_number_100][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="119">
                        <p><strong> Rounding </strong></p>
                    </td>
                    <td width="250">
                        <p>Round 2-digit numbers to the nearest 10</p>
                    </td>
                    <td>
                        <select name="id[ten_round_numbers][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Round 2 and 3-digit numbers to the nearest 10 or 100</p>
                    </td>
                    <td>
                        <select name="id[round_ten_and_hundred][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>round 2 and 3-digit numbers to the nearest 10 or 100 and give estimates for their sums and differences</p>
                    </td>
                    <td>
                        <select name="id[rand_rounding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="119">
                        <p><strong> *Written methods </strong></p>
                        <p><strong> (+ and -) </strong></p>
                    </td>
                    <td width="250">
                        <p>add TU and TU (bridging 100)</p>
                    </td>
                    <td>
                        <select name="id[addition_TO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>add HTU and TU (not bridging 1000)</p>
                    </td>
                    <td>
                        <select name="id[addition_HTO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>add HTU and HTU</p>
                    </td>
                    <td>
                        <select name="idHTO_HTO_addition[]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>subtract TU and TU</p>
                    </td>
                    <td>
                        <select name="id[subtraction_TO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>subtract TU from HTU (HTU - TU)</p>
                    </td>
                    <td>
                        <select name="id[subtraction_HTO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>subtract HTU from HTU</p>
                    </td>
                    <td>
                        <select name="id[subtraction_HTO_HTO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="6" width="119">
                        <p><strong> Multiplication and division </strong></p>
                    </td>
                    <td width="250">
                        <p>3x table.</p>
                    </td>
                    <td>
                        <select name="id[three_x][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>8 x table</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[eight_x][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>Mixed 3, 4 and 8 x table</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[rand_3_4_8][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td width="250">
                        <p>4 x table</p>
                    </td>
                    <td>
                        <select name="id[four_x][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="250">
                        <p>Ones x 10</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[ten_x][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Inverse 3 x table</p>
                    </td>
                    <td>
                        <select name="id[inverse_three_x][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>Inverse 8 x table</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[inverse_eight_x][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>Inverse 4 x table</p>
                    </td>
                    <td>
                        <select name="id[inverse_four_x][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="250">
                        <p>TO x 10</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[multiplybyten][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Ones x 100</p>
                    </td>
                    <td>
                        <select name="id[multiplybyhundred][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>Inverse 2, 3, 4, 5, 8, 10 x table</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[rand_inverse_y3][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>TO x 100</p>
                    </td>
                    <td>
                        <select name="id[multiplybyhundred][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="119">
                        <p><strong> Written methods (x and &divide;) </strong></p>
                    </td>
                    <td rowspan="2" width="250">
                        <p>TO x O</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[TO_O_multiply][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>TO &divide; O no remainder</p>
                    </td>
                    <td>
                        <select name="id[divide_TO_O_noremainder][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>TO &divide; O remainder</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[divide_TO_O][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>TO x TO</p>
                    </td>
                    <td>
                        <select name="id[multiply_TO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="year4" style="display:none">
        <table class="table table-bordered" border="1" width="982" cellspacing="0" cellpadding="0">
            <tbody>
                <td colspan="9">You can select up to 25 questions.</td>
                <tr>
                    <td colspan="2" rowspan="2" width="215">
                        <p>Assessment Focus</p>
                    </td>
                    <td colspan="6" width="767">
                        <p>Year 4 Long Term Planning</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="" width="257">
                        <p>Below</p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                    <td colspan="" width="246">
                        <p>Meeting</p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                    <td colspan="" width="265">
                        <p>Exceeding</p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                </tr>
                <tr>
                    <td rowspan="24" width="103">
                        <p>Number</p>
                    </td>
                    <td rowspan="4" width="113">
                        <p>Counting</p>
                    </td>
                    <td width="202">
                        <p>* I can count in multiples of 25, 1000 and 11</p>
                    </td>
                    <td>
                        <select name="id[-][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>* I can count in multiples of 7 and 9</p>
                    </td>
                    <td>
                        <select name="id[-][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="4" width="227">
                        <p>I can count in multiples of 6, 7, 9, 11, 12, 25 and 1000 <strong>(Children should know all tables by this point)</strong></p>
                    </td>
                    <td rowspan="4">
                        <select name="id[random_count_x][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="202">
                        <p>I can find 1000 more than any given number</p>
                    </td>
                    <td>
                        <select name="id[thousand_more][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can find 1000 less than any given number</p>
                    </td>
                    <td>
                        <select name="id[thousand_less][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" width="202">
                        <p>I can count backwards through to zero</p>
                    </td>
                    <td>
                        <select name="id[count_through_zero][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    
                </tr>
                <tr>
                    <td width="202">
                        <p>I can say how many more is needed to make 100 from a given number</p>
                    </td>
                    <td>
                        <select name="id[number_bond_100][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can say how many more is needed to make 1000 from a given number</p>
                    </td>
                    <td>
                        <select name="id[number_bond_1000][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="113">
                        <p>Comparing numbers</p>
                    </td>
                    <td width="202" colspan="3">
                        <p>I can order and compare numbers beyond 1000</p>
                    </td>
                    <td>
                        <select name="id[_order_size_order][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="227">
                        <p>I can compare numbers with the same number to decimal places up to 2 decimal places (copied from fractions)</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[decimals_size_order][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="202" colspan="3">
                        <p>I can use the symbols &lt; and &gt; to state inequalities</p>
                    </td>
                    <td>
                        <select name="id[more_or_less_than][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="113">
                        <p>Identifying, representing &amp; estimating numbers</p>
                    </td>
                    <td width="202" colspan="5">
                        <p>* I can identify, represent and estimate numbers using different representations; including measures</p>
                    </td>
                    <td>
                        <select name="id[-][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                <tr>
                    <td rowspan="2" width="113">
                        <p>Reading &amp; writing numbers</p>
                    </td>
                    <td width="202">
                        <p>I can read numbers in words and write as digits to 1000.</p>
                    </td>
                    <td>
                        <select name="id[words_write_in_numerals_below][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can read numbers in words and write in digits to at least 10,000 and vice versa.</p>
                    </td>
                    <td>
                        <select name="id[rand_nums_words][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="1" width="227">
                        <p>I can read, write, order and compare numbers to 100,000</p>
                    </td>
                    <td rowspan="1">
                        <select name="id[d_exceeding_rand_nums_words][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="202" colspan="3">
                        <p>I can read Roman numerals to 100 (I to C)</p>
                    </td>
                    <td>
                        <select name="id[read_roman_numerals][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can read Roman numerals to 500. </p>
                    </td>
                    <td>
                        <select name="id[roman_numerals_exceeding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="3" width="113">
                        <p>Understanding place value</p>
                    </td>
                    <td width="202">
                        <p>I can recognise the place value of each digit in 4-digit numbers (ThHTU)</p>
                    </td>
                    <td>
                        <select name="id[place_value_below][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can recognise the place value of each digit in 5-digit numbers</p>
                    </td>
                    <td>
                        <select name="id[place_value][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="227">
                        <p>I can recognise the place value of each digit in 6-digit numbers</p>
                    </td>
                    <td>
                        <select name="id[place_value_exceeding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="202">
                        <p>I can double numbers beyond 100 (e.g. 200, 300, 400 etc)</p>
                    </td>
                    <td>
                        <select name="id[double_number_multiplesof100][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can double numbers beyond 100 involving tens (e.g. 120, 140 etc)</p>
                    </td>
                    <td>
                        <select name="id[double_number_multiplesof_10][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="227">
                        <p>I can double numbers beyond 100 involving tens and units (e.g 115)</p>
                    </td>
                    <td>
                        <select name="id[_double_number_1000][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="202">
                        <p>I can halve numbers above 100 (e.g. 200, 300, 400 etc)</p>
                    </td>
                    <td>
                        <select name="id[halve_number_multipleof100][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can halve numbers above 100 (e.g. 120, 140 etc)</p>
                    </td>
                    <td>
                        <select name="id[_halve_number_answerendingzero][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="227">
                        <p>I can halve numbers above 100 involving tens that will end in 5 (e.g 110)</p>
                    </td>
                    <td>
                        <select name="id[halve_number_answermultiple5][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="113">
                        <p>Rounding</p>
                    </td>
                    <td width="202">
                        <p>I can round any number to the nearest 10</p>
                    </td>
                    <td>
                        <select name="id[ten_round_numbers][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can round any number to the nearest 100</p>
                    </td>
                    <td>
                        <select name="id[round_numbers_hundred][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="227">
                        <p>I can round any number to the nearest <strong>10, 100 or 1000</strong></p>
                    </td>
                    <td>
                        <select name="id[round_ten_and_hundred_thousand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="1" width="113">
                        <p>Addition and subtraction</p>
                    </td>
                    <td width="202" colspan="5">
                        <p>I can estimate and use inverse operations to check answers</p>
                    </td>
                    <td>
                        <select name="id[rand_rounding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="113" rowspan="2">
                        <p>*Written methods</p>
                        <p>(+ and -)</p>
                    </td>
                    <td width="202">
                        <p>I can add HTU and HTU</p>
                    </td>
                    <td>
                        <select name="id[HTO_HTO_addition][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td>
                    	<p>I can add HTU and HTU (bridging 1000)</p>
                    </td>
                    <td>
                        <select name="id[bridge1000_addition_HTO_HTO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="227" rowspan="2">
                        <p>I can add and subtract numbers up to 4-digits</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[add_or_subtract_4_digit][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<td>
                        <p>I can subtract TU from HTU (HTU � TU)</p>
                    </td>
                    <td>
                        <select name="id[subtraction_HTO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>I can subtract HTU from HTU</p>
                    </td>
                    <td>
                        <select name="id[subtraction_HTO_HTO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="4" width="113">
                        <p>Multiplication and division</p>
                    </td>
                    <td width="202" colspan="1">
                        <p>I can recall multiplication facts for all the multiplication tables up to 12 x 12.</p>
                    </td>
                    <td>
                        <select name="id[all_rand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="202">
                        <p>I can recall inverse multiplication facts for all the multiplication tables up to 12 x 12.</p>
                    </td>
                    <td>
                        <select name="id[rand_inverse][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="227">
                        <p>I can multiply up to 12x12 and recall inverse facts.</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[multiply_inverse_rand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="208" colspan="1">
                        <p>I can use factor pairs in mental calculations.</p>
                    </td>
                    <td>
                        <select name="id[factor_pairs][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208" colspan="1">
                        <p>I can multiply 3 numnbers.</p>
                    </td>
                    <td>
                        <select name="id[_3_number_multiply][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="202" colspan="5">
                        <p>I can recognise and use factor pairs and commutatively in mental calculations</p>
                    </td>
                    <td rowspan="2">
                        <select name="id[commutativity][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>

                </tr>
                <tr>
                    <td rowspan="4" width="113">
                        <p>*Written methods (x and �)</p>
                    </td>
                    <td width="202">
                        <p>TU x U</p>
                    </td>
                    <td>
                        <select name="id[x_TO_O_multiply][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="4" width="208">&nbsp;</td>
                    <td rowspan="4" width="38">&nbsp;</td>
                    <td rowspan="4" width="227">
                        <p>* I can calculate TU x TU</p>
                    </td>
                    <td rowspan="4">
                        <select name="id[x_multiply_TO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	
                 </tr>
                 <tr>
                    <td width="202">
                        <p>HTU x U</p>
                    </td>
                    <td>
                        <select name="id[multiply_HTO_O][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                 </tr>
                 <tr>
                    <td width="202">
                        <p>I can divide TU by U</p>
                    </td>
                    <td>
                        <select name="id[TO_O_divide][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <!--<tr>
                    <td width="113">
                        <p>Fractions (including decimals and %)</p>
                    </td>
                    <td width="202">
                        <p>* I can recognise and show, using diagrams, families of common equivalent fractions</p>
                        <p>* I can count up and down in hundredths; recognising that hundredths happen when dividing by 100 and dividing tenths by 10.</p>
                        <p>* I can find the effect of dividing a one or two digit number by 10 and 100, identifying the value of the digits in the answer as units, tenths and hundredths.</p>
                        <p>* I can recognise and write decimal equivalents to 1/2, 1/4 and 3/4.</p>
                        <p>* I can recognise and write decimal equivalents of any number of tenths</p>
                    </td>
                    <td>
                        <select name="id[-][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="208">
                        <p>* I can add and subtract fractions where the denominator is the same number.</p>
                        <p>* I can find the effect of dividing a one or two digit number by 10 and 100, identifying the value of the digits in the answer as units, tenths and hundredths.</p>
                        <p>*I can round decimals with one decimal place to the nearest whole number.</p>
                        <p>* I can recognise and write decimal equivalents of any number of hundredths</p>
                        <p>* I can solve problems involving increasingly harder fractions to find quantities where the answer is a whole number.</p>
                    </td>
                    <td>
                        <select name="id[-][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="227">
                        <p>* I can compare numbers with the same number to decimal places up to 2 decimal places</p>
                        <p>* I can solve simple measure and money problems involving fractions and decimals to two decimal places.</p>
                        <p>* I can recognise and write decimal equivalents of any number of tenths or hundredths</p>
                        <p>* I can solve problems involving increasingly harder fractions to find quantities where the answer is a whole number.</p>
                    </td>
                    <td>
                        <select name="id[-][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>-->
            </tbody>
        </table>
    </div>
    <div id="year5" style="">
        <table class="table table-bordered" border="1" width="1000" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td colspan="9">You can select up to 25 questions.</td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2" width="195">
                        <p>Assessment Focus</p>
                    </td>
                    <td colspan="6" width="768">
                        <p>Year 5 Long Term Planning</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="" width="298">
                        <p>Emerging</p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                    <td colspan="" width="298">
                        <p>Meeting</p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                    <td colspan="" width="298">
                        <p>Exceeding</p>
                    </td>
                    <td colspan="" width="20">
                        <p>No. of questions</p>
                    </td>
                </tr>
                <tr>
                    <td rowspan="40" width="88">
                        <p>Number</p>
                    </td>
                    <td rowspan="2" width="107">
                        <p>Counting</p>
                    </td>
                    <td width="250">
                        <p>count forwards and backwards with positive and negative whole numbers through zero</p>
                    </td>
                    <td width="38">
                        <select name="id[count_through_zero][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>count forwards/backwards in 1000s, 10,000s and 100,000s up to 1,000,000</p>
                    </td>
                    <td rowspan="2" width="47">
                        <select name="id[rand_thousand_tenthousand_hundredthousand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>count from any given number in whole number and decimal steps</p>
                    </td>
                    <td rowspan="2" width="47">
                        <select name="id[rand_count_whole_and_decimal][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>count forwards/backwards in 10s and 100s up to 1,000,000</p>
                    </td>
                    <td width="38">
                        <select name="id[rand_forwardsbackwards_10s_100s][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="107">
                        <p>Comparing numbers</p>
                    </td>
                    <td width="250">
                        <p>order numbers up to 500,000</p>
                    </td>
                    <td width="38">
                        <select name="id[below_size_order][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Confidently order numbers up to 1,000,000</p>
                    </td>
                    <td width="47">
                        <select name="id[_order_size_order][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Confidently order numbers beyond 1,000,000</p>
                    </td>
                    <td width="47">
                        <select name="id[exceeding_size_order][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="107">
                        <p>Reading &amp; writing numbers</p>
                    </td>
                    <td width="250">
                        <p>write numbers to 1,000,000 in words.</p>
                    </td>
                    <td width="38">
                        <select name="id[size_order_words][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p><strong>write numbers to 1,000,000 in words as digits.</strong></p>
                    </td>
                    <td width="47">
                        <select name="id[words_write_in_numerals][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p><strong>write numbers beyond 1,000,000 in words and digits.</strong></p>
                    </td>
                    <td width="47">
                        <select name="id[rand_write_exceeding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>read Roman numerals to 1000 (M)</p>
                    </td>
                    <td width="38">
                        <select name="id[read_roman_numerals][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>recognise years written in Roman numerals</p>
                    </td>
                    <td width="47">
                        <select name="id[roman_numerals_exceeding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>write Roman numerals to 1000 (M)</p>
                    </td>
                    <td width="47">
                        <select name="id[write_roman_numerals_exceeding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="4" width="107">
                        <p>Understanding place value</p>
                    </td>
                    <td width="250">
                        <p>identify the place value of any digit up to 500,000</p>
                    </td>
                    <td width="38">
                        <select name="id[place_value][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>identify the place value of any digit up to 1,000,000</p>
                    </td>
                    <td width="47">
                        <select name="id[place_value_exceeding][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>identify the place value of any digit up to 1,000,000 and add place values together.</p>
                    </td>
                    <td width="47">
                        <select name="id[_add_partition_number][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p><strong>confidently </strong>double/halve any number up to 3-digits</p>
                    </td>
                    <td width="38">
                        <select name="id[_rand_double_halve_wt_y5][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>double/halve any number up to 4-digits</p>
                    </td>
                    <td width="47">
                        <select name="id[double_halve_aare_y5_rand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>double/halve any number beyond 4-digits</p>
                    </td>
                    <td width="47">
                        <select name="id[double_halve_aboveare_y5_rand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>double numbers involving decimals to 1dp</p>
                    </td>
                    <td width="38">
                        <select name="id[double_number_1decimal][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250" colspan="3">
                        <p>double numbers involving decimals to 2dp</p>
                    </td>
                    <td width="47">
                        <select name="id[double_number_2decimal][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>have halve numbers that will end in decimals to 1dp (e.g. halve of 133 = 66.5)</p>
                    </td>
                    <td width="38">
                        <select name="id[halve_number_answerof1decimal][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250" colspan="3">
                        <p>Halve numbers that will end in decimals to 2dp (e.g. halve of 66.5 = 33.25)</p>
                    </td>
                    <td width="47">
                        <select name="id[halve_number_answerof2decimal][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="107">
                        <p>Rounding</p>
                    </td>
                    <td width="250">
                        <p>round any number up to 1,000,000 to the nearest 10, 100, 1000</p>
                    </td>
                    <td width="38">
                        <select name="id[round_ten_and_hundred_thousand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>round any number up to 1,000,000 to the nearest 10,000 and 100,000</p>
                    </td>
                    <td width="47">
                        <select name="id[round_tenthousand_hundredthousand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="2" width="250">
                        <p>round any number up to 1,000,000 to the nearest 10, 100, 1000, 10,000 and 100,000</p>
                    </td>
                    <td rowspan="2" width="47">
                        <select name="id[round_ten_and_hundred_thousand_tenthousand_hundredthousand][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>round decimals to 2dp</p>
                    </td>
                    <td width="38">
                        <select name="id[round_decimal_numbers_2dp][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>round decimals to 3dp</p>
                    </td>
                    <td width="47">
                        <select name="id[round_decimal_numbers_3dp][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="7" width="107">
                        <p>Written methods</p>
                        <p>(+ and -)</p>
                    </td>
                    <td width="250">
                        <p>add ThHTU and HTU</p>
                    </td>
                    <td width="38">
                        <select name="id[addition_ThHTO_HTO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>add ThHTU and ThHTU</p>
                    </td>
                    <td width="47">
                        <select name="id[addition_ThHTO_ThHTO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>add and subtract numbers with more than 4-digits</p>
                    </td>
                    <td width="47">
                        <select name="id[rand_adding_beyond_four_digits][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="6" width="250">
                        <p>subtract HTU and ThHTU</p>
                    </td>
                    <td rowspan="6" width="38">
                        <select name="id[subtraction_ThHTO_HTO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>subtract ThHTU from ThHTU</p>
                    </td>
                    <td width="47">
                        <select name="id[subtraction_ThHTO_ThHTO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>U. t h th + U. t h th</p>
                    </td>
                    <td width="47">
                        <select name="id[addition_othth_othth][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>U. t + U. t</p>
                    </td>
                    <td width="47">
                        <select name="id[addition_ot_ot][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>U. t h th - U. t h th</p>
                    </td>
                    <td width="47">
                        <select name="id[subtraction_othth_othth][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>U. t h + U. t h</p>
                    </td>
                    <td width="47">
                        <select name="id[addition_oth_oth][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="4" width="250">
                        <p>U. t h - U. t h th</p>
                    </td>
                    <td rowspan="4" width="47">
                        <select name="id[subtraction_oth_othth][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>U. t - U. t</p>
                    </td>
                    <td width="47">
                        <select name="id[subtraction_ot_ot][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>U. t h - U. t h</p>
                    </td>
                    <td width="47">
                        <select name="id[subtraction_oth_oth][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>U. t - U. t h</p>
                    </td>
                    <td width="47">
                        <select name="id[subtraction_ot_otth][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="3" width="107">
                        <p>Multiplication and division</p>
                    </td>
                    <td width="250">
                        <p>Identify multiples and factors.</p>
                    </td>
                    <td width="38">
                        <select name="id[rand_factor_multiple][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>find all factor pairs of a number</p>
                    </td>
                    <td width="47">
                        <select name="id[factor_pairs][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="3" width="250">
                        <p>recognise and use square numbers and cube numbers and the notations for these (<sup>2 </sup>and<sup>3</sup>).</p>
                    </td>
                    <td rowspan="3" width="47">
                        <select name="id[rand_cubed_squared][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" width="250">
                        <p>Know and use the vocabulary of prime numbers, prime factors and composite (non- prime) numbers</p>
                    </td>
                    <td rowspan="2" width="38">
                        <select name="id[prime_numbers][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Identify common factors of two numbers.</p>
                    </td>
                    <td width="47">
                        <select name="id[common_factors][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>multiply and divide whole numbers and decimals by 10, 100 and 1000.</p>
                    </td>
                    <td width="47">
                        <select name="id[rand_multiply_divide_powers10][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td rowspan="3" width="107">
                        <p>*Written methods (x and &divide;)</p>
                    </td>
                    <td width="250">
                        <p>calculate HTU x U</p>
                    </td>
                    <td width="38">
                        <select name="id[multiply_HTO_O][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>calculate ThHTU x U</p>
                    </td>
                    <td width="47">
                        <select name="id[multiply_ThHTO_O][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td rowspan="3" width="250">
                        <p>*calculate U.t x U</p>
                    </td>
                    <td rowspan="3" width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>calculate TU x TU</p>
                    </td>
                    <td width="38">
                        <select name="id[x_multiply_TO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>calculate ThHTU x TU</p>
                    </td>
                    <td width="47">
                        <select name="id[multiply_ThHTO_TO][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>calculate HTU &divide; U</p>
                    </td>
                    <td width="38">
                        <select name="id[divide_HTO_O][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>calculate ThHTU &divide; U</p>
                    </td>
                    <td width="47">
                        <select name="id[divide_ThHTO_O][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="107" rowspan="3">
                        <p>Fractions (including decimals and %)</p>
                    </td>
                    <td width="250">
                        <p>I can compare and order fractions whose denominators are all multiples of the same number.</p>
                    </td>
                    <td width="38">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="38">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="38">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="107" rowspan="2">
                        <p>Percent</p>
                    </td>
                    <td width="250">
                        <p>Find 10% of a number</p>
                    </td>
                    <td width="38">
                        <select name="id[percentage10percentNumber][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="38">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                    <td width="250">
                        <p>Coming soon</p>
                    </td>
                    <td width="47">
                        <select name="id[none][]">
                            <option disabled selected value>-</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="year6" style="display:none">
        <?php include "year6table.html"; ?>
    </div>
    <button type="submit">Submit</button>
</div>
</form>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
	(function() {
	  'use strict';
	  window.addEventListener('load', function() {
	  	valthisform();
	    // Fetch all the forms we want to apply custom Bootstrap validation styles to
	    var forms = document.getElementsByClassName('needs-validation');
	    // Loop over them and prevent submission
	    var validation = Array.prototype.filter.call(forms, function(form) {
	      form.addEventListener('submit', function(event) {
	        if (form.checkValidity() === false) {
	          event.preventDefault();
	          event.stopPropagation();
	        }
	        form.classList.add('was-validated');
	      }, false);
	    });
	  }, false);
	})();

    function y1() {
        hideall();
        document.getElementById("year1").style.display = "block";
        document.getElementById("year1table").checked = true;
    }

    function y2() {
        hideall();
        document.getElementById("year2").style.display = "block";
        document.getElementById("year2table").checked = true;
    }

    function y3() {

        hideall();
        document.getElementById("year3").style.display = "block";
        document.getElementById("year3table").checked = true;
    }

    function y4() {
        hideall();
        document.getElementById("year4").style.display = "block";
        document.getElementById("year4table").checked = true;
    }

    function y5() {
        hideall();
        document.getElementById("year5").style.display = "block";
        document.getElementById("year5table").checked = true;
    }

    function y6() {
        hideall();
        document.getElementById("year6").style.display = "block";
        document.getElementById("year6table").checked = true;
    }

    function hideall() {
        document.getElementById("year1").style.display = "none";
        document.getElementById("year2").style.display = "none";
        document.getElementById("year3").style.display = "none";
        document.getElementById("year4").style.display = "none";
        document.getElementById("year5").style.display = "none";
        document.getElementById("year6").style.display = "none";
        document.getElementById("questionsform").reset();
    }


</script>
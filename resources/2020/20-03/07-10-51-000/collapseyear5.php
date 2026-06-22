<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    .accordion,
    .innerAccordion {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        margin: 3px;
        padding: 18px;
        width: 100%;
        border: none;
        border-radius: 9px;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
    }

    .panel-inner-below {
        background-color: red;
    }

    .panel-inner-meeting {
        background-color: orange;
    }

    .panel-inner-above {
        background-color: green;
    }

    .active,
    .accordion:hover {
        background-color: #ccc;
    }

    .panel {
        padding: 0 18px;
        background-color: white;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.2s ease-out;
    }

    .card-header {
        padding: 0;
    }

    td {
        padding: 0 !important;
        vertical-align: none !important;
    }

    .table {
        margin-bottom: 0;
    }

    .card-header-emerging {
        background-color: #ffe6e6;
    }

    .card-header-developing {
        background-color: #fff2e6;
    }

    .card-header-secure {
        background-color: #e6ffe6;
    }

    select {
        vertical-align: 0 !important;
    }
    </style>
</head>


<body>

    <div id="accordion">


        <!-- counting Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                        aria-controls="collapseOne">
                        Counting
                    </div>
                </h5>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <!-- counting Accordion -->
                    <div id="counting-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="counting-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse show"
                                        data-target="#counting-collapseOne" aria-expanded="true"
                                        aria-controls="counting-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="counting-collapseOne" class="collapse show" aria-labelledby="counting-emerging"
                                data-parent="#counting-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>count forwards and backwards with positive and negative whole
                                                        numbers through zero</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="counting-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#counting-collapseTwo" aria-expanded="false"
                                        aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="counting-collapseTwo" class="collapse show" aria-labelledby="counting-developing"
                                data-parent="#counting-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td rowspan="2" width="250">
                                                    <p>count forwards/backwards in 1000s, 10,000s and 100,000s up to
                                                        1,000,000</p>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="counting-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show" aria-labelledby="counting-secure"
                                data-parent="#counting-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End counting Accordion -->
        <!-- comparing-numbers Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#comparing-numbers-collapseOne"
                        aria-expanded="true" aria-controls="comparing-numbers-collapseOne">
                        Comparing Numbers
                    </div>
                </h5>
            </div>

            <div id="comparing-numbers-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="comparing-numbers-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="comparing-numbers-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#comparing-numbers-collapseTwo" aria-expanded="false"
                                        aria-controls="comparing-numbers-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="comparing-numbers-collapseTwo" class="collapse show"
                                aria-labelledby="comparing-numbers-emerging" data-parent="#comparing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="comparing-numbers-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#comparing-numbers-collapseTwo" aria-expanded="false"
                                        aria-controls="comparing-numbers-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="comparing-numbers-collapseTwo" class="collapse show"
                                aria-labelledby="comparing-numbers-developing"
                                data-parent="#comparing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="comparing-numbers-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="comparing-numbers-secure" data-parent="#comparing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End comparing-numbers Accordion -->
        <!-- reading-and-writing-numbers Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse"
                        data-target="#reading-and-writing-numbers-collapseOne" aria-expanded="true"
                        aria-controls="reading-and-writing-numbers-collapseOne">
                        Reading & Writing Numbers
                    </div>
                </h5>
            </div>

            <div id="reading-and-writing-numbers-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="reading-and-writing-numbers-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="reading-and-writing-numbers-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#reading-and-writing-numbers-collapseTwo" aria-expanded="false"
                                        aria-controls="reading-and-writing-numbers-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="reading-and-writing-numbers-collapseTwo" class="collapse show"
                                aria-labelledby="reading-and-writing-numbers-emerging"
                                data-parent="#reading-and-writing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="reading-and-writing-numbers-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#reading-and-writing-numbers-collapseTwo" aria-expanded="false"
                                        aria-controls="reading-and-writing-numbers-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="reading-and-writing-numbers-collapseTwo" class="collapse show"
                                aria-labelledby="reading-and-writing-numbers-developing"
                                data-parent="#reading-and-writing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p><strong>write numbers to 1,000,000 in words as digits.</strong>
                                                    </p>
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
                                            </tr>
                                            <tr>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="reading-and-writing-numbers-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="reading-and-writing-numbers-secure"
                                data-parent="#reading-and-writing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p><strong>write numbers beyond 1,000,000 in words and
                                                            digits.</strong></p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End reading-and-writing-numbers Accordion -->
        <!-- understanding-place-value Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse"
                        data-target="#understanding-place-value-collapseOne" aria-expanded="true"
                        aria-controls="understanding-place-value-collapseOne">
                        Understanding Place Value
                    </div>
                </h5>
            </div>

            <div id="understanding-place-value-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="understanding-place-value-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="understanding-place-value-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#understanding-place-value-collapseTwo" aria-expanded="false"
                                        aria-controls="understanding-place-value-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="understanding-place-value-collapseTwo" class="collapse show"
                                aria-labelledby="understanding-place-value-emerging"
                                data-parent="#understanding-place-value-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                            <tr>
                                                <td width="250">
                                                    <p><strong>confidently </strong>double/halve any number up to
                                                        3-digits</p>
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
                                            </tr>
                                            <tr>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="understanding-place-value-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#understanding-place-value-collapseTwo" aria-expanded="false"
                                        aria-controls="understanding-place-value-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="understanding-place-value-collapseTwo" class="collapse show"
                                aria-labelledby="understanding-place-value-developing"
                                data-parent="#understanding-place-value-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                            <tr>
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
                                                    <p>halve numbers that will end in decimals to 1dp (e.g. halve
                                                        of 133 = 66.5)</p>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="understanding-place-value-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="understanding-place-value-secure"
                                data-parent="#understanding-place-value-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>identify the place value of any digit up to 1,000,000 and add
                                                        place values together.</p>
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
                                                <td width="250" colspan="3">
                                                    <p>Halve numbers that will end in decimals to 2dp (e.g. halve of
                                                        66.5 = 33.25)</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End understanding-place-value Accordion -->
        <!-- rounding Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#rounding-collapseOne"
                        aria-expanded="true" aria-controls="rounding-collapseOne">
                        Rounding
                    </div>
                </h5>
            </div>

            <div id="rounding-collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <div id="rounding-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="rounding-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#rounding-collapseTwo" aria-expanded="false"
                                        aria-controls="rounding-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="rounding-collapseTwo" class="collapse show" aria-labelledby="rounding-emerging"
                                data-parent="#rounding-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="rounding-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#rounding-collapseTwo" aria-expanded="false"
                                        aria-controls="rounding-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="rounding-collapseTwo" class="collapse show" aria-labelledby="rounding-developing"
                                data-parent="#rounding-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>round any number up to 1,000,000 to the nearest 10,000 and
                                                        100,000</p>
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
                                            </tr>
                                            <tr>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="rounding-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show" aria-labelledby="rounding-secure"
                                data-parent="#rounding-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td rowspan="2" width="250">
                                                    <p>round any number up to 1,000,000 to the nearest 10, 100, 1000,
                                                        10,000 and 100,000</p>
                                                </td>
                                                <td rowspan="2" width="47">
                                                    <select
                                                        name="id[round_ten_and_hundred_thousand_tenthousand_hundredthousand][]">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End rounding Accordion -->
        <!-- addition-subtraction-written-methods Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse"
                        data-target="#addition-subtraction-written-methods-collapseOne" aria-expanded="true"
                        aria-controls="addition-subtraction-written-methods-collapseOne">
                        Written methods (+ and -)
                    </div>
                </h5>
            </div>

            <div id="addition-subtraction-written-methods-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="addition-subtraction-written-methods-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging"
                                id="addition-subtraction-written-methods-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#addition-subtraction-written-methods-collapseTwo"
                                        aria-expanded="false"
                                        aria-controls="addition-subtraction-written-methods-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="addition-subtraction-written-methods-collapseTwo" class="collapse show"
                                aria-labelledby="addition-subtraction-written-methods-emerging"
                                data-parent="#addition-subtraction-written-methods-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing"
                                id="addition-subtraction-written-methods-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#addition-subtraction-written-methods-collapseTwo"
                                        aria-expanded="false"
                                        aria-controls="addition-subtraction-written-methods-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="addition-subtraction-written-methods-collapseTwo" class="collapse show"
                                aria-labelledby="addition-subtraction-written-methods-developing"
                                data-parent="#addition-subtraction-written-methods-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                            <tr>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure"
                                id="addition-subtraction-written-methods-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="addition-subtraction-written-methods-secure"
                                data-parent="#addition-subtraction-written-methods-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End addition-subtraction-written-methods Accordion -->
        <!-- multiplication-and-division Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse"
                        data-target="#multiplication-and-division-collapseOne" aria-expanded="true"
                        aria-controls="multiplication-and-division-collapseOne">
                        Multiplication and division
                    </div>
                </h5>
            </div>

            <div id="multiplication-and-division-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="multiplication-and-division-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="multiplication-and-division-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#multiplication-and-division-collapseTwo" aria-expanded="false"
                                        aria-controls="multiplication-and-division-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="multiplication-and-division-collapseTwo" class="collapse show"
                                aria-labelledby="multiplication-and-division-emerging"
                                data-parent="#multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                            <tr>
                                                <td rowspan="2" width="250">
                                                    <p>Know and use the vocabulary of prime numbers, prime factors and
                                                        composite (non- prime) numbers</p>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="multiplication-and-division-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#multiplication-and-division-collapseTwo" aria-expanded="false"
                                        aria-controls="multiplication-and-division-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="multiplication-and-division-collapseTwo" class="collapse show"
                                aria-labelledby="multiplication-and-division-developing"
                                data-parent="#multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                            <tr>
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
                                                    <p>multiply and divide whole numbers and decimals by 10, 100 and
                                                        1000.</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="multiplication-and-division-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="multiplication-and-division-secure"
                                data-parent="#multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td rowspan="3" width="250">
                                                    <p>recognise and use square numbers and cube numbers and the
                                                        notations for these (<sup>2 </sup>and<sup>3</sup>).</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End multiplication-and-division Accordion -->
        <!-- written-multiplication-and-division Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse"
                        data-target="#written-multiplication-and-division-collapseOne" aria-expanded="true"
                        aria-controls="written-multiplication-and-division-collapseOne">
                        Written methods (x and &divide;)
                    </div>
                </h5>
            </div>

            <div id="written-multiplication-and-division-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="written-multiplication-and-division-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging"
                                id="written-multiplication-and-division-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#written-multiplication-and-division-collapseTwo"
                                        aria-expanded="false"
                                        aria-controls="written-multiplication-and-division-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="written-multiplication-and-division-collapseTwo" class="collapse show"
                                aria-labelledby="written-multiplication-and-division-emerging"
                                data-parent="#written-multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>Calculate HTU x U</p>
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
                                            </tr>
                                            <tr>
                                                <td width="250">
                                                    <p>Calculate TU x TU</p>
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
                                            </tr>
                                            <tr>
                                                <td width="250">
                                                    <p>Calculate HTU &divide; U</p>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing"
                                id="written-multiplication-and-division-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#written-multiplication-and-division-collapseTwo"
                                        aria-expanded="false"
                                        aria-controls="written-multiplication-and-division-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="written-multiplication-and-division-collapseTwo" class="collapse show"
                                aria-labelledby="written-multiplication-and-division-developing"
                                data-parent="#written-multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>Calculate ThHTU x U</p>
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
                                            </tr>
                                            <tr>
                                                <td width="250">
                                                    <p>Calculate ThHTU x TU</p>
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
                                                    <p>Calculate ThHTU &divide; U</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="written-multiplication-and-division-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="written-multiplication-and-division-secure"
                                data-parent="#written-multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td rowspan="3" width="250">
                                                    <p>Calculate U.t x U</p>
                                                </td>
                                                <td rowspan="3" width="47">
                                                    <select name="id[decimalmultiplybyones][]">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End written-multiplication-and-division Accordion -->
        <!-- fractions Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#fractions-collapseOne"
                        aria-expanded="true" aria-controls="fractions-collapseOne">
                        Fractions including decimals and %
                    </div>
                </h5>
            </div>

            <div id="fractions-collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <div id="fractions-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="fractions-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#fractions-collapseTwo" aria-expanded="false"
                                        aria-controls="fractions-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="fractions-collapseTwo" class="collapse show" aria-labelledby="fractions-emerging"
                                data-parent="#fractions-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>I can compare and order fractions whose denominators are all
                                                        multiples of the same number.</p>
                                                </td>
                                                <td width="38">
                                                    <select name="id[orderFractionsSameDenoms][]">
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
                                                    <p>I can read and write fractions as decimal numbers.</p>
                                                </td>
                                                <td width="38">
                                                    <select name="id[fractionAsDecimalNumbers][]">
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
                                                    <p>I can write decimals as fractions over 100</p>
                                                </td>
                                                <td width="38">
                                                    <select name="id[decimalAsFraction][]">
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
                                                    <p>I can write decimals as percent.</p>
                                                </td>
                                                <td width="38">
                                                    <select name="id[decimalAsPercent][]">
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
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="fractions-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#fractions-collapseTwo" aria-expanded="false"
                                        aria-controls="fractions-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="fractions-collapseTwo" class="collapse show" aria-labelledby="fractions-developing"
                                data-parent="#fractions-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>I can compare and order fractions whose denominators are all
                                                        different.</p>
                                                </td>
                                                <td width="47">
                                                    <select name="id[orderFractionsDifferentDenoms][]">
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
                                                    <p>I can read and write decimal numbers as equivalent fractions in
                                                        their simplest form.</p>
                                                </td>
                                                <td width="47">
                                                    <select name="id[decimalAsSimplestFraction][]">
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
                                                    <p>I can write fractions as percent.*</p>
                                                </td>
                                                <td width="47">
                                                    <select name="id[fractionAsPercent][]">
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
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="fractions-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show" aria-labelledby="fractions-secure"
                                data-parent="#fractions-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                Coming Soon
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End fractions Accordion -->
        <!-- percentages Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#percentages-collapseOne"
                        aria-expanded="true" aria-controls="percentages-collapseOne">
                        Percentages
                    </div>
                </h5>
            </div>

            <div id="percentages-collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <div id="percentages-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="percentages-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#percentages-collapseTwo" aria-expanded="false"
                                        aria-controls="percentages-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="percentages-collapseTwo" class="collapse show"
                                aria-labelledby="percentages-emerging" data-parent="#percentages-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>Find 10% of a multiple of 10</p>
                                                </td>
                                                <td width="38">
                                                    <select name="id[percentage10percent10multiple][]">
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
                                                    <p>Find 20% of a multiple of 10</p>
                                                </td>
                                                <td width="38">
                                                    <select name="id[percentage20percent10multiple][]">
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
                                                    <p>Find 1% of a multple of 10</p>
                                                </td>
                                                <td width="38">
                                                    <select name="id[percentage1percent10multiple][]">
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
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="percentages-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#percentages-collapseTwo" aria-expanded="false"
                                        aria-controls="percentages-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="percentages-collapseTwo" class="collapse show"
                                aria-labelledby="percentages-developing" data-parent="#percentages-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>

                                            <tr>
                                                <td width="250">
                                                    <p>Find 10% of any number</p>
                                                </td>
                                                <td width="47">
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
                                            </tr>
                                            <tr>
                                                <td width="250">
                                                    <p>Frind 20% of any number</p>
                                                </td>
                                                <td width="47">
                                                    <select name="id[percentage20percentNumber][]">
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
                                                <td width="47">
                                                    <select name="id[percentage1percentNumber][]">
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
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="percentages-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show" aria-labelledby="percentages-secure"
                                data-parent="#percentages-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="250">
                                                    <p>Find 5% of a multiple of 10</p>
                                                </td>
                                                <td width="47">
                                                    <select name="id[percent5Multiple10][]">
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
                                                    <p>Find any % of a number</p>
                                                </td>
                                                <td width="47">
                                                    <select name="id[percentNumber][]">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End percentages Accordion -->
        <!-- mastery Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#mastery-collapseOne"
                        aria-expanded="true" aria-controls="mastery-collapseOne">
                        Mastery
                    </div>
                </h5>
            </div>

            <div id="mastery-collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <div id="mastery-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="mastery-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#mastery-collapseTwo" aria-expanded="false"
                                        aria-controls="mastery-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="mastery-collapseTwo" class="collapse show" aria-labelledby="mastery-emerging"
                                data-parent="#mastery-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Identify the missing numbers in an HTO+HTO addition calculation.
                                                </td>
                                                <td width="47">
                                                    <select name="id[missing_HTO_HTO_addition][]">
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
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="mastery-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#mastery-collapseTwo" aria-expanded="false"
                                        aria-controls="mastery-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="mastery-collapseTwo" class="collapse show" aria-labelledby="mastery-developing"
                                data-parent="#mastery-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Identify the missing numbers in an ThHTO+ThHTO addition calculation.
                                                </td>
                                                <td width="47">
                                                    <select name="id[missing_ThHTO_ThHTO_addition][]">
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
                                                <td width="47">
                                                    Identify the missing numbers in an ThHTO-ThHTO subtraction
                                                    calculation.
                                                </td>
                                                <td width="47">
                                                    <select name="id[missing_ThHTO_ThHTO_subtraction][]">
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
                                                <td>Identify the missing numbers in an TthThHTO-ThThHTO subtraction
                                                    calculation.</td>
                                                <td width="47">
                                                    <select name="id[missing_TThThHTO_TthThHTO_subtraction][]">
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
                                                <td>Identify the missing numbers in an HTO x TO multiplication
                                                    calculation.</td>
                                                <td>
                                                    <select name="id[missing_HTO_TO_multiplication][]">
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
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="mastery-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show" aria-labelledby="mastery-secure"
                                data-parent="#mastery-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Identify the missing numbers in an TthThHTO+ThThHTO subtraction
                                                    calculation.</td>
                                                <td width="47">
                                                    <select name="id[missing_TThThHTO_TthThHTO_addition][]">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End mastery Accordion -->






    </div>

</body>

</html>
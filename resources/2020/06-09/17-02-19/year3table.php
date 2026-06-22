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
                    <!-- Counting Accordion -->
                    <div id="counting-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="counting-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse" data-target="#counting-collapseOne"
                                        aria-expanded="true" aria-controls="counting-collapseOne">
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
                                                <td scope="row">Count in steps of 50 from 0</td>
                                                <td><select name="id[count_in_fifties_zero][]">
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
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Count in steps of 100 from 0
                                                </td>
                                                <td>
                                                    <select name="id[count_in_hundreds_zero][]">
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
                                        <tr>
                                            <td>Count in steps of 4 from 0</td>
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
                                        </tr>

                                        <tr>
                                            <td>
                                                Count in steps of 8 from 0
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
                                        </tr>
                                        <tr>

                                            <td>
                                                find 100 more or less than a given number
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
                                        </tr>

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
                                        <tr>
                                            <td>
                                                Count backwards in steps of 50
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
                                            <td>
                                                Count backwards in steps of 100
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
                                            <td>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End Counting Accordion -->



                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <div class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="false" aria-controls="collapseTwo">
                        Comparing Numbers
                    </div>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    <!-- Counting Accordion -->
                    <div id="Comparing-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="Comparing-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse"
                                        data-target="#Comparing-collapseOne" aria-expanded="true"
                                        aria-controls="Comparing-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="Comparing-collapseOne" class="collapse show" aria-labelledby="Comparing-emerging"
                                data-parent="#Comparing-accordion">
                                <div class="card-body">
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="Comparing-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#Comparing-collapseTwo" aria-expanded="false"
                                        aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="Comparing-collapseTwo" class="collapse show" aria-labelledby="Comparing-developing"
                                data-parent="#Comparing-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td colspan="3">
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="Comparing-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#Comparing-collapseThree" aria-expanded="false"
                                        aria-controls="Comparing-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="Comparing-collapseThree" class="collapse show" aria-labelledby="Comparing-secure"
                                data-parent="#Comparing-accordion">
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <td>
                                                <p>Compare and order numbers up to 1000 in words</p>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End Counting Accordion -->



                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                    <div class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"
                        aria-expanded="false" aria-controls="collapseThree">
                        Identifying and representing numbers
                    </div>
                </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    <!-- Counting Accordion -->
                    <div id="identifyingrepresenting-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="identifyingrepresenting-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse"
                                        data-target="#identifyingrepresenting-collapseOne" aria-expanded="true"
                                        aria-controls="identifyingrepresenting-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="identifyingrepresenting-collapseOne" class="collapse show"
                                aria-labelledby="identifyingrepresenting-emerging"
                                data-parent="#identifyingrepresenting-accordion">
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <td>
                                                <p>identify and represent numbers using different methods by estimating
                                                    postion on a number line</p>
                                            </td>
                                            <td>
                                                <select disabled name="id[number_liney3][]">
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
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="identifyingrepresenting-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#identifyingrepresenting-collapseTwo" aria-expanded="false"
                                        aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="identifyingrepresenting-collapseTwo" class="collapse show"
                                aria-labelledby="identifyingrepresenting-developing"
                                data-parent="#identifyingrepresenting-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>identify, represent and estimate numbers using different
                                                    representations
                                                    (including those related to measure)</p>
                                            </td>
                                            <td>In development</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="identifyingrepresenting-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#identifyingrepresenting-collapseThree" aria-expanded="false"
                                        aria-controls="identifyingrepresenting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="identifyingrepresenting-collapseThree" class="collapse show"
                                aria-labelledby="identifyingrepresenting-secure"
                                data-parent="#identifyingrepresenting-accordion">
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <td>
                                                <p>identify, represent and estimate numbers using different
                                                    representations
                                                    (including those related to measure)</p>
                                            </td>
                                            <td>In development</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>



                    <!-- End Counting Accordion -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading-Four">
                <h5 class="mb-0">
                    <div class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse-Four"
                        aria-expanded="false" aria-controls="collapse-Four">
                        Reading & writing number
                    </div>
                </h5>
            </div>
            <div id="collapse-Four" class="collapse" aria-labelledby="heading-Four" data-parent="#accordion">
                <div class="card-body">

                    <!-- reading-and-writing-numbers- Accordion -->

                    <div id="reading-and-writing-numbers-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="reading-and-writing-numbers-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse"
                                        data-target="#reading-and-writing-numbers-collapseOne" aria-expanded="true"
                                        aria-controls="reading-and-writing-numbers-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="reading-and-writing-numbers-collapseOne" class="collapse show"
                                aria-labelledby="reading-and-writing-numbers-emerging"
                                data-parent="#reading-and-writing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="reading-and-writing-numbers-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#reading-and-writing-numbers-collapseTwo" aria-expanded="false"
                                        aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="reading-and-writing-numbers-collapseTwo" class="collapse show"
                                aria-labelledby="reading-and-writing-numbers-developing"
                                data-parent="#reading-and-writing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="reading-and-writing-numbers-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#reading-and-writing-numbers-collapseThree" aria-expanded="false"
                                        aria-controls="reading-and-writing-numbers-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="reading-and-writing-numbers-collapseThree" class="collapse show"
                                aria-labelledby="reading-and-writing-numbers-secure"
                                data-parent="#reading-and-writing-numbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End reading-and-writing-numbers- Accordion -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading-understanding-place-value">
                <h5 class="mb-0">
                    <div class="btn btn-link collapsed" data-toggle="collapse"
                        data-target="#collapse-understanding-place-value" aria-expanded="false"
                        aria-controls="collapse-understanding-place-value">
                        Understanding place value
                    </div>
                </h5>
            </div>
            <div id="collapse-understanding-place-value" class="collapse"
                aria-labelledby="heading-understanding-place-value" data-parent="#accordion">
                <div class="card-body">

                    <!-- understanding-place-value Accordion -->

                    <div id="understanding-place-value-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="understanding-place-value-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse"
                                        data-target="#understanding-place-value-collapseOne" aria-expanded="true"
                                        aria-controls="understanding-place-value-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="understanding-place-value-collapseOne" class="collapse show"
                                aria-labelledby="understanding-place-value-emerging"
                                data-parent="#understanding-place-value-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>Recognise the value of each digit in 2-digit numbers (tens,
                                                    ones/units)</p>
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
                                        </tr>
                                        <tr>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Halve simple numbers beyond 20 (answers ending in zero)</p>
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="understanding-place-value-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#understanding-place-value-collapseTwo" aria-expanded="false"
                                        aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="understanding-place-value-collapseTwo" class="collapse show"
                                aria-labelledby="understanding-place-value-developing"
                                data-parent="#understanding-place-value-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Halve numbers beyond 20 (answers ending in 5 e.g. halve of 30 is 15)
                                                </p>
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
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="understanding-place-value-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#understanding-place-value-collapseThree" aria-expanded="false"
                                        aria-controls="understanding-place-value-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="understanding-place-value-collapseThree" class="collapse show"
                                aria-labelledby="understanding-place-value-secure"
                                data-parent="#understanding-place-value-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>Partition 3-digit numbers in more than one way (adding values)</p>
                                            </td>
                                            <td>
                                                <select name="id[parition_more_than_oneway][]">
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
                                            <td>
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
                                            <td>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End understanding-place-value Accordion -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading-rounding">
                <h5 class="mb-0">
                    <div class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse-rounding"
                        aria-expanded="false" aria-controls="collapse-rounding">
                        Rounding
                    </div>
                </h5>
            </div>
            <div id="collapse-rounding" class="collapse" aria-labelledby="heading-rounding" data-parent="#accordion">
                <div class="card-body">

                    <!-- rounding Accordion -->

                    <div id="rounding-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="rounding-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse show"
                                        data-target="#rounding-collapseOne" aria-expanded="true"
                                        aria-controls="rounding-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="rounding-collapseOne" class="collapse show" aria-labelledby="rounding-emerging"
                                data-parent="#rounding-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="rounding-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#rounding-collapseTwo" aria-expanded="false"
                                        aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="rounding-collapseTwo" class="collapse show" aria-labelledby="rounding-developing"
                                data-parent="#rounding-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="rounding-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#rounding-collapseThree" aria-expanded="false"
                                        aria-controls="rounding-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="rounding-collapseThree" class="collapse show" aria-labelledby="rounding-secure"
                                data-parent="#rounding-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>round 2 and 3-digit numbers to the nearest 10 or 100 and give
                                                    estimates for their sums and differences</p>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End rounding Accordion -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading-addition-subtraction-written-methods">
                <h5 class="mb-0">
                    <div class="btn btn-link collapsed" data-toggle="collapse"
                        data-target="#collapse-addition-subtraction-written-methods" aria-expanded="false"
                        aria-controls="collapse-addition-subtraction-written-methods">
                        Written methods - addition and subtraction
                    </div>
                </h5>
            </div>
            <div id="collapse-addition-subtraction-written-methods" class="collapse"
                aria-labelledby="heading-addition-subtraction-written-methods" data-parent="#accordion">
                <div class="card-body">

                    <!-- addition-subtraction-written-methods Accordion -->

                    <div id="addition-subtraction-written-methods-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging"
                                id="addition-subtraction-written-methods-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse"
                                        data-target="#addition-subtraction-written-methods-collapseOne"
                                        aria-expanded="true"
                                        aria-controls="addition-subtraction-written-methods-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="addition-subtraction-written-methods-collapseOne" class="collapse show"
                                aria-labelledby="addition-subtraction-written-methods-emerging"
                                data-parent="#addition-subtraction-written-methods-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
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
                                        </tr>
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
                                        aria-expanded="false" aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="addition-subtraction-written-methods-collapseTwo" class="collapse show"
                                aria-labelledby="addition-subtraction-written-methods-developing"
                                data-parent="#addition-subtraction-written-methods-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure"
                                id="addition-subtraction-written-methods-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#addition-subtraction-written-methods-collapseThree"
                                        aria-expanded="false"
                                        aria-controls="addition-subtraction-written-methods-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="addition-subtraction-written-methods-collapseThree" class="collapse show"
                                aria-labelledby="addition-subtraction-written-methods-secure"
                                data-parent="#addition-subtraction-written-methods-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>add HTO and HTO</p>
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
                                        </tr>

                                        <tr>
                                            <td>
                                                <p>add ThHTO and HTO</p>
                                            </td>
                                            <td>
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

                                            <td>
                                                <p>Missing number HTO + TU</p>
                                            </td>
                                            <td>
                                                <select name="id[missing_HTO_TO_addition][]">
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End addition-subtraction-written-methods Accordion -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading-multiplication-and-division">
                <h5 class="mb-0">
                    <div class="btn btn-link collapsed" data-toggle="collapse"
                        data-target="#collapse-multiplication-and-division" aria-expanded="false"
                        aria-controls="collapse-multiplication-and-division">
                        Multiplication and division
                    </div>
                </h5>
            </div>
            <div id="collapse-multiplication-and-division" class="collapse"
                aria-labelledby="heading-multiplication-and-division" data-parent="#accordion">
                <div class="card-body">

                    <!-- multiplication-and-division Accordion -->

                    <div id="multiplication-and-division-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="multiplication-and-division-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse"
                                        data-target="#multiplication-and-division-collapseOne" aria-expanded="true"
                                        aria-controls="multiplication-and-division-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="multiplication-and-division-collapseOne" class="collapse show"
                                aria-labelledby="multiplication-and-division-emerging"
                                data-parent="#multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
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
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
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
                                            <td>
                                                <p>Ones x 10</p>
                                            </td>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>TO x 10</p>
                                            </td>
                                            <td>
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="multiplication-and-division-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#multiplication-and-division-collapseTwo" aria-expanded="false"
                                        aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="multiplication-and-division-collapseTwo" class="collapse show"
                                aria-labelledby="multiplication-and-division-developing"
                                data-parent="#multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>8 x table</p>
                                            </td>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
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
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td>
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
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="multiplication-and-division-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#multiplication-and-division-collapseThree" aria-expanded="false"
                                        aria-controls="multiplication-and-division-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="multiplication-and-division-collapseThree" class="collapse show"
                                aria-labelledby="multiplication-and-division-secure"
                                data-parent="#multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>Mixed 3, 4 and 8 x table</p>
                                            </td>
                                            <td>
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
                                            <td>
                                                <p>Inverse 8 x table</p>
                                            </td>
                                            <td>
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
                                            <td>
                                                <p>Inverse 2, 3, 4, 5, 8, 10 x table</p>
                                            </td>
                                            <td>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End multiplication-and-division Accordion -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading-written-multiplication-and-division">
                <h5 class="mb-0">
                    <div class="btn btn-link collapsed" data-toggle="collapse"
                        data-target="#collapse-written-multiplication-and-division" aria-expanded="false"
                        aria-controls="collapse-written-multiplication-and-division">
                        Written methods - multiplication and division
                    </div>
                </h5>
            </div>
            <div id="collapse-written-multiplication-and-division" class="collapse"
                aria-labelledby="heading-written-multiplication-and-division" data-parent="#accordion">
                <div class="card-body">

                    <!-- written-multiplication-and-division Accordion -->

                    <div id="written-multiplication-and-division-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging"
                                id="written-multiplication-and-division-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link" data-toggle="collapse"
                                        data-target="#written-multiplication-and-division-collapseOne"
                                        aria-expanded="true"
                                        aria-controls="written-multiplication-and-division-collapseOne">
                                        Emerging
                                    </div>
                                </h5>
                            </div>

                            <div id="written-multiplication-and-division-collapseOne" class="collapse show"
                                aria-labelledby="written-multiplication-and-division-emerging"
                                data-parent="#written-multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>TO x O</p>
                                            </td>
                                            <td>
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
                                        </tr>
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
                                        aria-expanded="false" aria-controls="counting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="written-multiplication-and-division-collapseTwo" class="collapse show"
                                aria-labelledby="written-multiplication-and-division-developing"
                                data-parent="#written-multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>TO x TO</p>
                                            </td>
                                            <td>
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
                                            <td>
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
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="written-multiplication-and-division-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#written-multiplication-and-division-collapseThree"
                                        aria-expanded="false"
                                        aria-controls="written-multiplication-and-division-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="written-multiplication-and-division-collapseThree" class="collapse show"
                                aria-labelledby="written-multiplication-and-division-secure"
                                data-parent="#written-multiplication-and-division-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <p>TO &divide; O remainder</p>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End written-multiplication-and-division Accordion -->
                </div>
            </div>
        </div>


    </div>

</body>

</html>
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
                    <div class="btn btn-link" data-toggle="collapse" data-target="#counting-collapseOne"
                        aria-expanded="true" aria-controls="counting-collapseOne">
                        Counting
                    </div>
                </h5>
            </div>

            <div id="counting-collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <div id="counting-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="counting-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#counting-collapseTwo" aria-expanded="false"
                                        aria-controls="counting-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="counting-collapseTwo" class="collapse show" aria-labelledby="counting-emerging"
                                data-parent="#counting-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Count in multiples of 25, 1000 and 11</p>
                                                </td>
                                                <td>
                                                    <select name="id[random_25_50_11][]">
                                                        <option disabled selected value>-</option>
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
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
                                                    <p>Find 1000 more than any given number</p>
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
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Count backwards through to zero</p>
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
                                                <td>
                                                    <p>Say how many more is needed to make 100 from a given number
                                                    </p>
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
                                                <td>
                                                    <p>Count in multiples of 7 and 9</p>
                                                </td>
                                                <td>
                                                    <select name="id[random_7_9][]">
                                                        <option disabled selected value>-</option>
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
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
                                                    <p>Find 1000 less than any given number</p>
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
                                                <td>
                                                    <p>Say how many more is needed to make 1000 from a given
                                                        number</p>
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
                                                <td>
                                                    <p>Count in multiples of 6, 7, 9, 11, 12, 25 and 1000

                                                    </p>
                                                </td>
                                                <td>
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
        <!-- comparingNumbers Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#comparingNumbers-collapseOne"
                        aria-expanded="true" aria-controls="comparingNumbers-collapseOne">
                        Comparing Numbers
                    </div>
                </h5>
            </div>

            <div id="comparingNumbers-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="comparingNumbers-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="comparingNumbers-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#comparingNumbers-collapseTwo" aria-expanded="false"
                                        aria-controls="comparingNumbers-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="comparingNumbers-collapseTwo" class="collapse show"
                                aria-labelledby="comparingNumbers-emerging" data-parent="#comparingNumbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Order and compare numbers beyond 1000</p>
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
                                            <tr>
                                                <td>
                                                    <p>Use the symbols &lt; and &gt; to state inequalities</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="comparingNumbers-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#comparingNumbers-collapseTwo" aria-expanded="false"
                                        aria-controls="comparingNumbers-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="comparingNumbers-collapseTwo" class="collapse show"
                                aria-labelledby="comparingNumbers-developing" data-parent="#comparingNumbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="comparingNumbers-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="comparingNumbers-secure" data-parent="#comparingNumbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Cmpare numbers with the same number to decimal places up
                                                        to 2 decimal places</p>
                                                </td>
                                                <td>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End comparingNumbers Accordion -->
        <!-- identifyingandRepresenting Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse"
                        data-target="#identifyingandRepresenting-collapseOne" aria-expanded="true"
                        aria-controls="identifyingandRepresenting-collapseOne">
                        Identifying, representing and using numbers
                    </div>
                </h5>
            </div>

            <div id="identifyingandRepresenting-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="identifyingandRepresenting-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="identifyingandRepresenting-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#identifyingandRepresenting-collapseTwo" aria-expanded="false"
                                        aria-controls="identifyingandRepresenting-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="identifyingandRepresenting-collapseTwo" class="collapse show"
                                aria-labelledby="identifyingandRepresenting-emerging"
                                data-parent="#identifyingandRepresenting-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Identify, represent and estimate numbers using different
                                                        representations; including measures</p>
                                                </td>
                                                <td>
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
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="identifyingandRepresenting-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#identifyingandRepresenting-collapseTwo" aria-expanded="false"
                                        aria-controls="identifyingandRepresenting-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="identifyingandRepresenting-collapseTwo" class="collapse show"
                                aria-labelledby="identifyingandRepresenting-developing"
                                data-parent="#identifyingandRepresenting-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="identifyingandRepresenting-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="identifyingandRepresenting-secure"
                                data-parent="#identifyingandRepresenting-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>

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
        <!-- End identifyingandRepresenting Accordion -->
        <!-- readingWritingNumbers Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#readingWritingNumbers-collapseOne"
                        aria-expanded="true" aria-controls="readingWritingNumbers-collapseOne">
                        Reading and writing numbers
                    </div>
                </h5>
            </div>

            <div id="readingWritingNumbers-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="readingWritingNumbers-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="readingWritingNumbers-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#readingWritingNumbers-collapseTwo" aria-expanded="false"
                                        aria-controls="readingWritingNumbers-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="readingWritingNumbers-collapseTwo" class="collapse show"
                                aria-labelledby="readingWritingNumbers-emerging"
                                data-parent="#readingWritingNumbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Read numbers in words and write as digits to 1000.</p>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="readingWritingNumbers-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#readingWritingNumbers-collapseTwo" aria-expanded="false"
                                        aria-controls="readingWritingNumbers-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="readingWritingNumbers-collapseTwo" class="collapse show"
                                aria-labelledby="readingWritingNumbers-developing"
                                data-parent="#readingWritingNumbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Read numbers in words and write in digits to at least
                                                        10,000 and vice versa.</p>
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
                                                <td>
                                                    <p>Read Roman numerals to 100 (I to C)</p>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="readingWritingNumbers-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="readingWritingNumbers-secure"
                                data-parent="#readingWritingNumbers-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Read, write, order and compare numbers to 100,000</p>
                                                </td>
                                                <td>
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
                                                <td>
                                                    <p>Read Roman numerals to 500. </p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End readingWritingNumbers Accordion -->
        <!-- understandingPlaceValue Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#understandingPlaceValue-collapseOne"
                        aria-expanded="true" aria-controls="understandingPlaceValue-collapseOne">
                        Understanding Place Value
                    </div>
                </h5>
            </div>

            <div id="understandingPlaceValue-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="understandingPlaceValue-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="understandingPlaceValue-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#understandingPlaceValue-collapseTwo" aria-expanded="false"
                                        aria-controls="understandingPlaceValue-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="understandingPlaceValue-collapseTwo" class="collapse show"
                                aria-labelledby="understandingPlaceValue-emerging"
                                data-parent="#understandingPlaceValue-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Recognise the place value of each digit in 4-digit numbers
                                                        (ThHTU)</p>
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
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Double numbers beyond 100 (e.g. 200, 300, 400 etc)</p>
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
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Halve numbers above 100 (e.g. 200, 300, 400 etc)</p>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="understandingPlaceValue-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#understandingPlaceValue-collapseTwo" aria-expanded="false"
                                        aria-controls="understandingPlaceValue-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="understandingPlaceValue-collapseTwo" class="collapse show"
                                aria-labelledby="understandingPlaceValue-developing"
                                data-parent="#understandingPlaceValue-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Recognise the place value of each digit in 5-digit numbers
                                                    </p>
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
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Double numbers beyond 100 involving tens (e.g. 120, 140
                                                        etc)</p>
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
                                                    <p>Halve numbers above 100 (e.g. 120, 140 etc)</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="understandingPlaceValue-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="understandingPlaceValue-secure"
                                data-parent="#understandingPlaceValue-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Recognise the place value of each digit in 6-digit numbers
                                                    </p>
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
                                                <td>
                                                    <p>Double numbers beyond 100 involving tens and units (e.g
                                                        115)</p>
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
                                                <td>
                                                    <p>Halve numbers above 100 involving tens that will end in 5
                                                        (e.g 110)</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End understandingPlaceValue Accordion -->
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
                                                <td>
                                                    <p>Round any number to the nearest 10</p>
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
                                                <td>
                                                    <p>Round any number to the nearest 100</p>
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
                                                <td>
                                                    <p>Round any number to the nearest <strong>10, 100 or
                                                            1000</strong></p>
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
        <!-- additionAndSubtraction Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#additionAndSubtraction-collapseOne"
                        aria-expanded="true" aria-controls="additionAndSubtraction-collapseOne">
                        Addition and Subtraction
                    </div>
                </h5>
            </div>

            <div id="additionAndSubtraction-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="additionAndSubtraction-accordion">

                        <!--
                        <div class="card">
                            <div class="card-header card-header-emerging" id="additionAndSubtraction-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#additionAndSubtraction-collapseTwo" aria-expanded="false"
                                        aria-controls="additionAndSubtraction-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="additionAndSubtraction-collapseTwo" class="collapse show"
                                aria-labelledby="additionAndSubtraction-emerging"
                                data-parent="#additionAndSubtraction-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
-->

                        <div class="card">
                            <div class="card-header card-header-developing" id="additionAndSubtraction-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#additionAndSubtraction-collapseTwo" aria-expanded="false"
                                        aria-controls="additionAndSubtraction-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="additionAndSubtraction-collapseTwo" class="collapse show"
                                aria-labelledby="additionAndSubtraction-developing"
                                data-parent="#additionAndSubtraction-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Estimate and use inverse operations to check answers</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="card">
                            <div class="card-header card-header-secure" id="additionAndSubtraction-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="additionAndSubtraction-secure"
                                data-parent="#additionAndSubtraction-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
-->
                    </div>
                </div>
            </div>
        </div>
        <!-- End additionAndSubtraction Accordion -->
        <!-- writtenAdditionAndSubtraction Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse"
                        data-target="#writtenAdditionAndSubtraction-collapseOne" aria-expanded="true"
                        aria-controls="writtenAdditionAndSubtraction-collapseOne">
                        Written Addition and Subtraction
                    </div>
                </h5>
            </div>

            <div id="writtenAdditionAndSubtraction-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="writtenAdditionAndSubtraction-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="writtenAdditionAndSubtraction-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#writtenAdditionAndSubtraction-collapseTwo" aria-expanded="false"
                                        aria-controls="writtenAdditionAndSubtraction-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="writtenAdditionAndSubtraction-collapseTwo" class="collapse show"
                                aria-labelledby="writtenAdditionAndSubtraction-emerging"
                                data-parent="#writtenAdditionAndSubtraction-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Add HTU and HTU</p>
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
                                                    <p>Subtract TU from HTU (HTU - TU)</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing"
                                id="writtenAdditionAndSubtraction-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#writtenAdditionAndSubtraction-collapseTwo" aria-expanded="false"
                                        aria-controls="writtenAdditionAndSubtraction-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="writtenAdditionAndSubtraction-collapseTwo" class="collapse show"
                                aria-labelledby="writtenAdditionAndSubtraction-developing"
                                data-parent="#writtenAdditionAndSubtraction-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Add HTU and HTU (bridging 1000)</p>
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
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Subtract HTU from HTU</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="writtenAdditionAndSubtraction-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="writtenAdditionAndSubtraction-secure"
                                data-parent="#writtenAdditionAndSubtraction-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Add and subtract numbers up to 4-digits</p>
                                                </td>
                                                <td>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End writtenAdditionAndSubtraction Accordion -->
        <!-- multiplicationAndDivision Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse"
                        data-target="#multiplicationAndDivision-collapseOne" aria-expanded="true"
                        aria-controls="multiplicationAndDivision-collapseOne">
                        Multiplication and Division
                    </div>
                </h5>
            </div>

            <div id="multiplicationAndDivision-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="multiplicationAndDivision-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="multiplicationAndDivision-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#multiplicationAndDivision-collapseTwo" aria-expanded="false"
                                        aria-controls="multiplicationAndDivision-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="multiplicationAndDivision-collapseTwo" class="collapse show"
                                aria-labelledby="multiplicationAndDivision-emerging"
                                data-parent="#multiplicationAndDivision-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Recall multiplication facts for all the multiplication
                                                        tables up to 12 x 12.</p>
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
                                            <tr>
                                                <td>
                                                    <p>Use factor pairs in mental calculations.</p>
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
                                            </tr>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="multiplicationAndDivision-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#multiplicationAndDivision-collapseTwo" aria-expanded="false"
                                        aria-controls="multiplicationAndDivision-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="multiplicationAndDivision-collapseTwo" class="collapse show"
                                aria-labelledby="multiplicationAndDivision-developing"
                                data-parent="#multiplicationAndDivision-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Recall inverse multiplication facts for all the
                                                        multiplication tables up to 12 x 12.</p>
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
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Mltiply 3 numbers.</p>
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
                                                <td>
                                                    <p>Recognise and use factor pairs and commutatively in mental
                                                        calculations</p>
                                                </td>
                                                <td>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="multiplicationAndDivision-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="multiplicationAndDivision-secure"
                                data-parent="#multiplicationAndDivision-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Multiply up to 12x12 and recall inverse facts.</p>
                                                </td>
                                                <td>
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

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End multiplicationAndDivision Accordion -->
        <!-- writtenMultiplyandDivide Accordion -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <div class="btn btn-link" data-toggle="collapse" data-target="#writtenMultiplyandDivide-collapseOne"
                        aria-expanded="true" aria-controls="writtenMultiplyandDivide-collapseOne">
                        Written Multiplication and Division
                    </div>
                </h5>
            </div>

            <div id="writtenMultiplyandDivide-collapseOne" class="collapse" aria-labelledby="headingOne"
                data-parent="#accordion">
                <div class="card-body">
                    <div id="writtenMultiplyandDivide-accordion">


                        <div class="card">
                            <div class="card-header card-header-emerging" id="writtenMultiplyandDivide-emerging">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#writtenMultiplyandDivide-collapseTwo" aria-expanded="false"
                                        aria-controls="writtenMultiplyandDivide-collapseTwo">
                                        Emerging
                                    </div>
                                </h5>
                            </div>
                            <div id="writtenMultiplyandDivide-collapseTwo" class="collapse show"
                                aria-labelledby="writtenMultiplyandDivide-emerging"
                                data-parent="#writtenMultiplyandDivide-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>TU x U</p>
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
                                            <tr>
                                                <td>
                                                    <p>TU &divide U</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-developing" id="writtenMultiplyandDivide-developing">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#writtenMultiplyandDivide-collapseTwo" aria-expanded="false"
                                        aria-controls="writtenMultiplyandDivide-collapseTwo">
                                        Developing
                                    </div>
                                </h5>
                            </div>
                            <div id="writtenMultiplyandDivide-collapseTwo" class="collapse show"
                                aria-labelledby="writtenMultiplyandDivide-developing"
                                data-parent="#writtenMultiplyandDivide-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>TU x TU</p>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-secure" id="writtenMultiplyandDivide-secure">
                                <h5 class="mb-0">
                                    <div class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#couting-collapseThree" aria-expanded="false"
                                        aria-controls="couting-collapseThree">
                                        Secure
                                    </div>
                                </h5>
                            </div>
                            <div id="couting-collapseThree" class="collapse show"
                                aria-labelledby="writtenMultiplyandDivide-secure"
                                data-parent="#writtenMultiplyandDivide-accordion">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End writtenMultiplyandDivide Accordion -->


    </div>

</body>

</html>
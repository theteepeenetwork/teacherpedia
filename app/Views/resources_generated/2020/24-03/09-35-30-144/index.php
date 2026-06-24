<head>
  <style>
    #toHide {
      display: none;
    }
  </style>
</head>

<form class="needs-validation" id="questionsform" action="<?php echo $action ?>" method="post" name="form">
  <fieldset>
    <legend>Digits for top number</legend>
    <div class="form-group">
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="defaultInline2" name="topNumber" value="2" checked>
        <label class="custom-control-label" for="defaultInline2">2</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="defaultInline3" name="topNumber" value="3">
        <label class="custom-control-label" for="defaultInline3">3</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="defaultInline4" name="topNumber" value="4">
        <label class="custom-control-label" for="defaultInline4">4</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="defaultInline5" name="topNumber" value="5">
        <label class="custom-control-label" for="defaultInline5">5</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="defaultInline6" name="topNumber" value="6">
        <label class="custom-control-label" for="defaultInline6">6</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="defaultInline7" name="topNumber" value="7">
        <label class="custom-control-label" for="defaultInline7">7</label>
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend>Digits for bottom number</legend>
    <div class="form-group">
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="bottomNumber1" name="bottomNumber" value="1" checked>
        <label class="custom-control-label" for="bottomNumber1">1</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="bottomNumber2" name="bottomNumber" value="2">
        <label class="custom-control-label" for="bottomNumber2">2</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="bottomNumber3" name="bottomNumber" value="3">
        <label class="custom-control-label" for="bottomNumber3">3</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="bottomNumber4" name="bottomNumber" value="4">
        <label class="custom-control-label" for="bottomNumber4">4</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="bottomNumber5" name="bottomNumber" value="5">
        <label class="custom-control-label" for="bottomNumber5">5</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="bottomNumber6" name="bottomNumber" value="6">
        <label class="custom-control-label" for="bottomNumber6">6</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="bottomNumber6" name="bottomNumber" value="7">
        <label class="custom-control-label" for="bottomNumber6">7</label>
      </div>
    </div>
  </fieldset>
  <input id="toHide" type="radio" name="calculation" value="addition" checked="checked"><br>

  <button class="btn button" type="submit">Submit</button>
  </div>
  <br />

</form>
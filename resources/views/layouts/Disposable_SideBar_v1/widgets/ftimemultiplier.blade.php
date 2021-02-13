<div class="card mb-2">
  <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-equals float-right"></i>Flight Time Multiplier</h5></div>
  <div class="card-body p-1">
    <form class="form-group" id="ftmultip">
      <div class="row row-cols-2">
        <div class="col">
          <label for="fh">Flight Hours</label>
          <input id="fh" type="text" class="form-control mb-1" maxlength="2"/>
        </div>
        <div class="col">
          <label for="fm">Flight Mins</label>
          <input id="fm" type="text" class="form-control mb-1" maxlength="2"/>
        </div>
        <div class="col">
          <label for="fmt">Multiplier</label>
          <input id="fmt" type="text" class="form-control" maxlength="5"/>
        </div>
        <div class="col">
          <label for="fr">Result</label>
          <input id="fr" type="text" class="form-control" maxlength="8" disabled/>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer p-1 text-right">
    <input type="button" onclick="TimeMultiplier()" class="btn btn-sm btn-primary" value="Calculate">
  </div>
</div>
{{-- Flight Time Multiplier/Calculator Script --}}
<script type="text/javascript">
    function TimeMultiplier() {
        // Get user flight time by hours and minutes
        var h = document.getElementById("fh").value;
        var m = document.getElementById("fm").value;
        // Get the multiplier factor
        var factor = document.getElementById("fmt").value;
        // Do the convertion and calculation
        var seconds = (h * 60 * 60) + (m * 60);
        var newSeconds= factor * seconds;
        var date = new Date(newSeconds * 1000);
        var hh = date.getUTCHours();
        var mm = date.getUTCMinutes();
        // Make the result looks good
        if (hh < 10) {hh = "0"+hh;}
        if (mm < 10) {mm = "0"+mm;}
        var result = hh+":"+mm;
        // Display the result
        document.getElementById("fr").value = result;
    }
</script>

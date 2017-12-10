$( document ).ready(function() {

  // People multi-select
  $('select.multi').select2();
  
  // Date picker
  $("#date").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"}).datepicker({
    autoclose: true,
    format: 'dd/mm/yyyy',
    weekStart: 1,
    startDate: new Date(),
  });;
  
  $("#time").inputmask("hh:mm", {"placeholder": "hh:mm"});
  
  // Auto-fill time from event Type
  $('#type').on('change', function() {
    $('#time').val($(this).find(":selected").attr('data-time'));
  });
  
  // Auto-fill location from event Type
  $('#type').on('change', function() {
    $("#location").val($(this).find(":selected").attr('data-location'));
  });
  
  
  // Series add
  $("#createSeriesButton").addClass("btn btn-link").removeAttr("href").attr('data-toggle', 'modal').attr('data-target', '#createSeries');

  var createSeries = function(){

    var name = $('#seriesName').val();
    var description = $('#seriesDescription').val();

      if(name == ''){

        $('#seriesName').css( "border", "3px solid red" );
        $('#seriesName').after('<p>Series name is required and cannot be left blank</p>');

      } else if(description == ''){

        $('#seriesDescription').css( "border", "3px solid red" );
        $('#seriesDescription').after('<p>Series description is required and cannot be left blank</p>');

      } else {
        
        var postObject = new Object();
        postObject.name = name;
        postObject.description = description;
        
        var postData = JSON.stringify(postObject);
        
        $.ajax({
          type: 'POST',
          contentType: 'application/json',
          url: "../api/v1/series",
          dataType: "json",
          data: postData,
          success: function(data, textStatus, jqXHR){
              $("#eventGroup")[0].options.add( new Option(data.data.name,data.data.id) );
              $("#eventGroup").val(data.data.id);
              $('#seriesName').val("");
              $('#seriesDescription').val("");
              $('#createSeries').modal('toggle');
          },
          error: function(jqXHR, textStatus, errorThrown){
              alert('createSeries error: ' + textStatus);
          }
        });
      }
    return false;
  }

  $('#createSeriesSubmitButton').click(createSeries);
  $('#seriesDescription').keyup(function(event){
      if(event.keyCode == 13){
          createSeries();
      }
  });

});
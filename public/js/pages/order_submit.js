var handleAdd = function() {
  //debugger;
  var form = $('#manualOrderForm');
  var errorAlert = $('.alert-danger', form);
  var successAlert = $('.alert-success', form);
  var base_url = window.location.origin;
  console.log(base_url);
  form.validate({
      // Specify validation rules
      onkeyup: false,
      rules: {
        payment_method: {
              required: true,
              maxlength: 50
          }
      },
      // Specify validation error messages
      messages: {
        payment_method: {
              maxlength: "Company name should be less than 50 characters"
          }
      },

      invalidHandler: function (event, validator) { //display error alert on form submit
          successAlert.hide();
          errorAlert.show();
      },
      errorPlacement: function (error, element) { // render error placement for each input type
          // var icon = $(element).parent('.input-icon').children('i');
          var mainParent = $(element).closest('.form-group');
          var icon = mainParent.find('.input-icon').children('i');
          icon.removeClass('fa-check').addClass("fa-warning");
          icon.attr("data-original-title", error.text()).tooltip({placement:"auto top"});//({'container': 'body'});
      },
      highlight: function (element) { // hightlight error inputs
          $(element).closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group
      },
      unhighlight: function (element) {
          var icon = $(element).parent('.input-icon').children('i');
          if ($(element).hasClass('ifIsEmpty') && $(element).val() == '') {
              $(element).closest('.form-group').removeClass("has-success").removeClass('has-error');
              icon.removeClass("fa-warning").removeClass("fa-check");
          }
      },
      success: function (label, element) {
          var mainParent = $(element).closest('.form-group');
          var icon = mainParent.find('.input-icon').children('i');
          // var icon = $(element).parent('.input-icon').children('i');
          $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
          icon.removeClass("fa-warning").addClass("fa-check");
      },
      submitHandler: function(form) {
          debugger;
          var $obj = $(form);
          var formData = new FormData(form);
          //var formData = form.serialize();
         // var formData = $('#company_form').serialize();
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type:"POST",
              url: form.action,
              processData:false,
              contentType:false,
              //data:form.serialize(),//only input
              data: formData,
              //dataType	  : 'json',
              beforeSend: function () {
                  debugger;
                  $obj.find('button[type="submit"] i').attr('class', 'fa fa-circle-o-notch fa-spin fa-sm fa-fw');
                  $obj.find('button[type="submit"]').attr('disabled', true);
              },
              success: function(response){
                  debugger;
                  console.log(response);
              },
              error: function (data, status, e) {
                  //toastr.error(e, "error storing company data");
                  $obj.find('button[type="submit"] i').attr('class', '');
                  $obj.find('button[type="submit"]').attr('disabled', false);
                  return false;
              },
              complete: function() {
                  $obj.find('button[type="submit"] i').attr('class', '');
                  $obj.find('button[type="submit"]').attr('disabled', false);
              },
          });
      }
  });	//form.validate
};	// handleAdd

$(document).ready(function () {
  handleAdd();
});
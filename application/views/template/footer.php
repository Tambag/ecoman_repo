    <div style="overflow: hidden;display: block;padding-top: 50px;clear: both;">&nbsp;</div>

    <script src="<?php echo asset_url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo asset_url('js/application.js'); ?>"></script>

    <?php if($this->uri->segment(1)!="isscoping" and $this->uri->segment(1)!="isscopingauto"): ?>

    <script src="<?php echo asset_url('js/flatui-fileinput.js'); ?>"></script>
      <script src="<?php echo asset_url('js/jquery-ui-1.10.3.custom.min.js'); ?>"></script>
      <script src="<?php echo asset_url('js/jquery.ui.touch-punch.min.js'); ?>"></script>
      <script src="<?php echo asset_url('js/jquery-ui-1.10.4.custom.js'); ?>"></script>

    <script src="<?php echo asset_url('js/bootstrap-select.js'); ?>"></script>
    <script src="<?php echo asset_url('js/bootstrap-switch.js'); ?>"></script>
    <script src="<?php echo asset_url('js/flatui-checkbox.js'); ?>"></script>
    <script src="<?php echo asset_url('js/flatui-radio.js'); ?>"></script>
    <script src="<?php echo asset_url('js/jquery.tagsinput.js'); ?>"></script>
    <script src="<?php echo asset_url('js/jquery.placeholder.js'); ?>"></script>
    <script src="<?php echo asset_url('js/holder.js'); ?>"></script>
    <script src="<?php echo asset_url('js/jquery.autocomplete.multiselect.js'); ?>"></script>

    <script type="text/javascript">
        var marker;
        var lat,lon;

        $('#myModal').on('shown.bs.modal', function (e) {
            google.maps.event.trigger(map, 'resize'); // modal acildiktan sonra haritanın resize edilmesi gerekiyor.

            map.setZoom(15);
            if(!marker)
                map.setCenter(new google.maps.LatLng(39.9738971871888, 32.745467126369476));
            else
                map.setCenter(marker.getPosition());

            google.maps.event.addListener(map, 'click', function(event) {
                $("#latId").val("Lat:" + event.latLng.lat()); $("#longId").val("Long:" + event.latLng.lng());
                $("#lat").val(event.latLng.lat()); $("#long").val(event.latLng.lng());
                placeMarker(event.latLng);
            });

        });



        function placeMarker(location) {
          if ( marker ) {
            marker.setPosition(location);
          } else {
            marker = new google.maps.Marker({
              position: location,
              map: map
            });
          }
        }

    </script>

    <!-- AutoComplete trigger JS Code -->
    <script type="text/javascript">
    $(function(){
      $("#companySearch").autocomplete({
        source: "companySearch", // path to the company_search method
        multiselect: true
      });
    });
    </script>

    <script type="text/javascript">
      $(document).ready(function () {
        $('#assignCompany').change(function () {
          var company = $(this).val();
          $.ajax({
            url: "<?php echo base_url('contactperson');?>",
            async: false,
            type: "POST",
            data: "company_id="+company,
            dataType: "json",
            success: function(data) {
              $('#assignContactPerson option').remove();

              for (var k = 0; k < data.length; k++) {
                for (var i = 0; i < data[k].length; i++) {
                  var opt =data[k][i]['id'];
                  if($("#assignContactPerson option[value='"+ opt +"']").length == 0)
                  {
                    $("#assignContactPerson").append(new Option(data[k][i]['name']+' '+data[k][i]['surname']+' - '+data[k][i]['cmpny_name'],data[k][i]['id']));
                  }
                }
              }
            }
          })
        });
      });
    </script>

    <script type="text/javascript">
      $(document).ready(function () {
        $('#equipment').bind('change',function () {
          var equipmentID = $(this).val();
          $.ajax({
            url: "<?php echo base_url('get_equipment_type');?>",
            async: false,
            type: "POST",
            data: "equipment_id="+equipmentID,
            dataType: "json",
            success: function(data) {
              $('#equipmentTypeName option').remove();
              $('#equipmentAttributeName option').remove();
              for(var i = 0 ; i < data.length ; i++){
                $("#equipmentTypeName").append(new Option(data[i]['name'],data[i]['id']));
              }
            }
          })
        });
        $('#equipment').trigger('change');
      });
    </script>

    <script type="text/javascript">
      $(document).ready(function () {
        $('#equipmentTypeName').bind('change',function () {
          var equipmentTypeID = $(this).val();
          $.ajax({
            url: "<?php echo base_url('get_equipment_attribute');?>",
            async: false,
            type: "POST",
            data: "equipment_type_id="+equipmentTypeID,
            dataType: "json",
            success: function(data) {
              $('#equipmentAttributeName option').remove();
              for(var i = 0 ; i < data.length ; i++){
                $("#equipmentAttributeName").append(new Option(data[i]['attribute_name'],data[i]['id']));
              }
            }
          })
        });
        $('#equipmentTypeName').trigger('change');
      });
    </script>
    <?php endif ?>

    <?php /* 
    <script type="text/javascript">
      $(document).ready(function () {
        $('#process').bind('change',function () {
          $("button[id*=subprocess]").parent().remove();
          $("[id*=subprocess]").remove();
          $('#lastprocess').val($(this).val());
          var stage=1;
          get_sub_process($(this).val(),stage);
        });
      });


      function get_sub_process(id,stage){
        var processID = id;

        $.ajax({
          url: "<?php echo base_url('get_sub_process');?>",
          async: false,
          type: "POST",
          data: "processID="+processID,
          dataType: "json",
          success: function(data) {
          if(data.length > 0){
            var pro_id=stage+'subprocess'+id;
            var select=document.createElement("select");
            select.id= pro_id;
            $('#processList').append(select);

            $("#"+pro_id).addClass('select-block')
            $("select").selectpicker({style: 'btn btn-default', menuStyle: 'dropdown-inverse'});
            $("select").selectpicker('refresh');
            $("#"+pro_id).append(new Option('Please select subprocess'));
            for(var i = 0 ; i < data.length ; i++){
              $("#"+pro_id).append(new Option(data[i]['name'],data[i]['id']));
            }
            $("#"+pro_id).bind('change',function () {

              var my_id = $(this).attr('id').slice(0,1);
              for (var i = parseInt(my_id)+1 ; i < 300 ; i++) {
                if($("[id*="+i+"subprocess]").length != 0){
                  $("[id*="+i+"subprocess]").remove();
                  $("#processList div:last-child").remove();
                }
               
              }
              stage += 1;
              get_sub_process($(this).val(),stage);
              $('#lastprocess').val($(this).val());
            });

          }

          }
        })

      }
    </script>
    */ ?>

    </body>
</html>

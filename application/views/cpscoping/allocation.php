<script type="text/javascript">
var pathname = window.location.pathname;
var prj_id = pathname.split("/")[3];
var cmpny_id = pathname.split("/")[4];
$(document).ready(function() {
    $("#prcss_name").change(function() { 
    	var prcss_id = $( "#prcss_name").val();
        $('#flow_name').children().remove();
        $('#flow_type_name').children().remove();
        $.ajax({ 
            type: "POST",
            dataType:'json',
            url: '<?php echo base_url('cp_allocation_array');?>/'+cmpny_id, 
            success: function(data)
            {
            	$('#flow_name').append('<option value="0">Nothing Selected</option>');
           		for(var k = 0 ; k < data.length ; k++){
           			if(data[k].company_process_id == prcss_id){
                    	$('#flow_name').append('<option value="'+data[k].flow_id+'">'+data[k].flowname+'</option>');
                    }
                }
                $('#flow_type_name').append('<option value="0">Nothing Selected</option>');
           		for(var k = 0 ; k < data.length ; k++){
           			if(data[k].company_process_id == prcss_id){
                    	$('#flow_type_name').append('<option value="'+data[k].flow_type_id+'">'+data[k].flow_type_name+'</option>');
                    }
                }
            }
        });
    });
});
</script>
<?php if(validation_errors() != NULL ): ?>
    <div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<div>Form couldn't be saved. Please fix the errors.</div>
      	<div class="popover-content">
      		<?php echo validation_errors(); ?>
      	</div>
    </div>
<?php endif ?>
<?php echo form_open_multipart('cpscoping/'.$project_id.'/'.$company_id.'/allocation'); ?>
	<div>
		<div class="col-md-3">
			<div><span class="badge">1</span> Please select a process then a flow to allocate</div>
			<hr>
			<div class="form-group row">
				<label for="prcss_name" class="control-label col-md-12">Select Process</label>
				<div class="col-md-12">
					<select name="prcss_name" id="prcss_name" class="btn-group select select-block">
						<option value="">Nothing Selected</option>
						<?php $kontrol = array(); $index = 0;?>
						<?php for($i = 0 ; $i < sizeof($prcss_info) ; $i++): ?>
							<option value="<?php echo $prcss_info[$i]['company_process_id']; ?>"><?php echo $prcss_info[$i]['prcessname']; ?></option>
						<?php endfor ?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label for="flow_name" class="control-label col-md-12">Select Flow</label>
				<div class="col-md-12">
					<select name="flow_name" id="flow_name" class="btn-group select select-block">
						<option value="">Nothing Selected</option>
					</select>
				</div>
			</div>

			<div class="form-group clearfix row">
				<label for="flow_type_name" class="control-label col-md-12">Select Flow Type</label>
				<div class="col-md-12">
					<select name="flow_type_name" id="flow_type_name" class="btn-group select select-block">
						<option value="">Nothing Selected</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div><span class="badge">2</span> Please fill all the boxes.</div>
			<hr>
			<div class="form-group clearfix row">
				<label class="control-label col-md-3">Amount</label>
				<label class="control-label col-md-3">Amount Unit</label>
				<label class="control-label col-md-3">Allocation (%)</label>
				<label class="control-label col-md-3">Accuracy Rate (%)</label>
				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('amount'); ?>" id="amount" placeholder="Number" name="amount">
				</div>
				<div class="col-md-3">
					<select name="unit_amount" id="unit_amount" class="btn-group select select-block">
						<option value="">Nothing Selected</option>
						<?php foreach ($unit_list as $u): ?>
							<option value="<?php echo $u['name']; ?>" <?php echo set_select('unit_amount', $u['name']); ?>><?php echo $u['name']; ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('allocation_amount'); ?>" id="allocation_amount" placeholder="Percentage" name="allocation_amount">
				</div>

				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('error_amount'); ?>" id="error_amount" placeholder="Percentage" name="error_amount">
				</div>
			</div>
			<hr>
			<div class="form-group clearfix row">
				<label class="control-label col-md-3">Cost</label>
				<label class="control-label col-md-3">Cost Unit</label>
				<label class="control-label col-md-3">Allocation (%)</label>
				<label class="control-label col-md-3">Accuracy Rate (%)</label>
				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('cost'); ?>" id="cost" placeholder="Number" name="cost">
				</div>
				<div class="col-md-3">
					<select name="unit_cost" id="unit_cost" class="btn-group select select-block">
						<option value="">Nothing Selected</option>
						<option value="Euro" <?php echo set_select('unit_cost', 'Euro'); ?>>Euro</option>
						<option value="Dolar" <?php echo set_select('unit_cost', 'Dolar'); ?>>Dolar</option>
						<option value="TL" <?php echo set_select('unit_cost', 'TL'); ?>>TL</option>
					</select>
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('allocation_cost'); ?>" id="allocation_cost" placeholder="Percentage" name="allocation_cost">
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('error_cost'); ?>" id="error_cost" placeholder="Percentage" name="error_cost">
				</div>
			</div>
			<hr>
			<div class="form-group clearfix row">
				<label class="control-label col-md-3">Environmental Impact</label>
				<label class="control-label col-md-3">EP</label>
				<label class="control-label col-md-3">Allocation (%)</label>
				<label class="control-label col-md-3">Accuracy Rate (%)</label>
				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('env_impact'); ?>" id="env_impact" placeholder="Number" name="env_impact">
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" id="unit_env_impact" value="EP" name="unit_env_impact" readonly>
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('allocation_env_impact'); ?>" id="allocation_env_impact" placeholder="Percentage" name="allocation_env_impact">
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" value="<?php echo set_value('error_ep'); ?>" id="error_ep" placeholder="Percentage" name="error_ep">
				</div>
			</div>
			<hr>
			<div class="form-group clearfix row">
				<label class="control-label col-md-6">Reference</label>
				<label class="control-label col-md-6">Unit</label>
				<div class="col-md-6">
					<input type="text" class="form-control" value="<?php echo set_value('reference'); ?>" id="reference" placeholder="Number" name="reference">
				</div>
				<div class="col-md-6">
					<select name="unit_reference" id="unit_reference" class="btn-group select select-block">
						<option value="">Nothing Selected</option>
						<?php foreach ($unit_list as $u): ?>
							<option value="<?php echo $u['name']; ?>" <?php echo set_select('unit_reference', $u['name']); ?>><?php echo $u['name']; ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<hr>
			<div class="form-group clearfix row">
				<label class="control-label col-md-6">KPI</label>
				<label class="control-label col-md-6">KPI Unit</label>
				<div class="col-md-6">
					<input type="text" class="form-control" id="kpi" placeholder="" name="kpi" readonly>
				</div>
				<div class="col-md-6">
					<input type="text" class="form-control" id="unit_kpi" placeholder="" name="unit_kpi" readonly>
				</div>
				
			</div>
			<div><button type="submit" class="btn btn-info"><i class="fa fa-floppy-o"></i> Save Allocation Data</button></div>
		</div>	
	</div>
</form>
<script type="text/javascript">
	$("#amount").change(hesapla);
	$("#reference").change(hesapla);
	function hesapla() { 
		$("#kpi").val(Number(($("#amount").val()/$("#reference").val()).toFixed(5)));
	}
	$("#unit_amount").change(unit_hesapla);
	$("#unit_reference").change(unit_hesapla);
	function unit_hesapla(){
		$("#unit_kpi").val($("#unit_amount option:selected").text()+"/"+$("#unit_reference option:selected").text());
	}
</script>
<script type="text/javascript">	$( document ).ready(unit_hesapla); $( document ).ready(hesapla);</script>
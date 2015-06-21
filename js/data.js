
function loadZone(){
	$('#zone option').last().attr("selected",true);
	var field = $('#zone').find('option:last').val();
	$.ajax(
		{url: 'chart_server.php?field='+field+'&fieldName1=zone&fieldName2=greenhouse_id',
			success: function(output) {
				var message = $('#greenhouse_id').find("option").eq(0).html();
				// alert(message);
				message = "<option>" + message + "</option>";
				$('#greenhouse_id').empty();
				output = message + output;
			$('#greenhouse_id').html(output);
		}
	});
}
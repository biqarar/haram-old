route(/score/, function(){
	$("#plan_id", this).combobox();
});

route(/score\/classes\/status=apilist\/classesid=\d+\/scoretypeid=\d+/, function(){
	//--------------- add retest attr for insert retstt in log
	$(".score-retest",this).click(function(){
		if($("input[classificationid="+$(this).attr('classificationid')+"]").attr("retest")){
			$("input[classificationid="+$(this).attr('classificationid')+"]").removeAttr("retest");
		}else{
			$("input[classificationid="+$(this).attr('classificationid')+"]").attr("retest", "retest");
		}
	});

	_warn = '<span class="status-warn" style="background: #FFECB3;	border: 1px solid #FFD54F;	padding: 3px 10px;	margin-right: 5px;	font-size: 12px;	display: inline-block;">تاریخ ثبت نشده است.</span>';
	_fatal = '<span class="status-fatal" style="background: #FFCDD2;	border: 1px solid #E57373;	padding: 3px 10px;	margin-right: 5px;	font-size: 12px;	display: inline-block;">تاریخ ثبت نشده است.</span>';
	_true = '<span class="icolikes" style="margin-right: 5px;padding: 0;display: inline-block;"></span>';

	$('.score-mark', this).change(function(){
		$(this).attr('disabled', 'disabled');
		classificationid = $(this).attr("classificationid")
		scoretypeid = $(this).attr("scoretypeid");
		value = $(this).val();
		if(value == "" ) {
			xhr_error("امتیاز را وارد کنید");
			$(this).removeAttr('disabled');
			return;
		}
		_self = $(this);
		if($(this).attr("retest")){
			url = "score/api/classificationid=" + classificationid + "/scoretypeid=" + scoretypeid + "/value=" + value + "/retest=true";
		}else{
			url = "score/api/classificationid=" + classificationid + "/scoretypeid=" + scoretypeid + "/value=" + value + "/retest=false";
		}
		$(_self).next('span').remove();
		$(_self).attr('disabled', 'disabled');
		$.ajax({
			type: "POST",
			url : url,
			success : function(data){
				$(_self).removeAttr('disabled');
				if(data.fatal){
					$(_fatal).html(data.fatal[0]).insertAfter(_self);
					// xhr_error(data.fatal[0]);
				}else if(data.warn){
					$(_warn).html(data.warn[0]).insertAfter(_self);
					// xhr_warn(data.warn[0]);
				}else{
					$(_true).insertAfter(_self);
					// xhr_true(data.true[0]);
				}
			}
		});
		$(this).removeAttr('disabled');

		return false;
	});
});
function insert_(a) {
	x = $("#calculation").html()
	$("#calculation").html(x + '=' + $(a).html() + '=');


	// x = $("#calculation").val()
	// $($(a).html()).appendTo('#calculation');
	// function insertMetachars(sStartTag, sEndTag) {
	// 	var bDouble = arguments.length > 1, oMsgInput = document.myForm.myTxtArea, nSelStart = oMsgInput.selectionStart, nSelEnd = oMsgInput.selectionEnd, sOldText = oMsgInput.value;
	// 	oMsgInput.value = sOldText.substring(0, nSelStart) + (bDouble ? sStartTag + sOldText.substring(nSelStart, nSelEnd) + sEndTag : sStartTag) + sOldText.substring(nSelEnd);
	// 	oMsgInput.setSelectionRange(bDouble || nSelStart === nSelEnd ? nSelStart + sStartTag.length : nSelStart, (bDouble ? nSelEnd : nSelStart) + sStartTag.length);
	// 	oMsgInput.focus();
	// }

}
route(/score\/calculation\/status\=(add|edit)/, function(){

	$("#plan_id").combobox( "destroy" );
	$("#plan_id", this).combobox({
		change : function(op){
			item = op.item.option.value
			$.ajax({
				type: "POST",
				url : "score/type/api/id=" + item,
				success : function(data){
					$("#list").html('');
					$("#list").val("");

				for(a in data.msg) {
					 if(data.msg[a]['title']){
							$("<a style='cursor: pointer' onclick='insert_(this);'>" + data.msg[a]['title'] + "</a><br>").appendTo("#list");
					 	}

					}
				}
			});
		}
	});


});
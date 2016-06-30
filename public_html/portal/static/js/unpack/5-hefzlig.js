route(/portal\/hefzlig/, function(){


	var _self = this;
	$( "#lig_id", this).combobox();
	$( "#hefz_team_id_1", this).combobox();
	$( "#hefz_team_id_2", this).combobox();
	$( "#hefz_group_id", this).combobox();
	$( "#type", this).combobox();
	$( "#hefz_group", this).combobox().next("span").css("display", "none");
	$( "label[for=hefz_group]").css("display", "none");
	$("#teachername", this).sautocomplate();


	$(".delete-hefz-teams-users").click(function(){
		teamuserid = $(this).attr("teamuserid");
		_self = $(this);
		$.ajax({
		type: "POST",
		url : "hefzlig/teams/status=apidelete/teamuserid=" + teamuserid ,
		success : function(data){
			
			xhr_result(data);
			$(_self).parents('tr').fadeOut();
		}
		});	
	});
	
});
function none_display_hefz_group(_self){
	$( "#hefz_group", _self).combobox().next("span").css("display", "none");
	$( "label[for=hefz_group]").css("display", "none");


}
function inline_block_display_hefz_group(_self){
	$( "#hefz_group", _self).css("display","none").next("span").css("display", "inline-block");
	$( "label[for=hefz_group]").css("display", "inline-block").html("گروه");
}



route(/portal\/hefzlig\/race/, function(){
	_self = $(this);
	$("#lig_id").combobox( "destroy" );
	$("#lig_id", this).combobox({
		change : function(op){
			item = op.item.option.value
			// race_type = $("#type option:selected").val(); 
			$.ajax({
				type: "POST",
				url : "hefzlig/ligsapi/id=" + item,
				success : function(data){
					// if(race_type == "دوره ای"){
						// inline_block_display_hefz_group(_self);
					// }else{
						none_display_hefz_group(_self);
						$( "#hefz_team_id_1", _self).combobox("destroy");
						$( "#hefz_team_id_2", _self).combobox("destroy");

						$("#hefz_team_id_1 option").each(function(){
							$(this).remove();
						});
						$("#hefz_team_id_2 option").each(function(){
							$(this).remove();
						});

						$("<option value='' disabled='disabled' selected='selected'>لطفا یکی از گزینه ها را انتخاب کنید</option>").appendTo($("#hefz_team_id_1"));
						$("<option value='' disabled='disabled' selected='selected'>لطفا یکی از گزینه ها را انتخاب کنید</option>").appendTo($("#hefz_team_id_2"));

						for(a in data['msg']['teams']) {
							// console.log(a);
							$("<option type='text' value='"+data['msg']['teams'][a]['id']+"' id='"+data['msg']['teams'][a]['id']+"' placeholder='"+data['msg']['teams'][a]['name']+"'>"+data['msg']['teams'][a]['name']+"</option>").appendTo($("#hefz_team_id_1"));
							$("<option type='text' value='"+data['msg']['teams'][a]['id']+"' id='"+data['msg']['teams'][a]['id']+"' placeholder='"+data['msg']['teams'][a]['name']+"'>"+data['msg']['teams'][a]['name']+"</option>").appendTo($("#hefz_team_id_2"));
						}

						$( "#hefz_team_id_1", _self).combobox();
						$( "#hefz_team_id_2", _self).combobox();
					// }
					console.log(data);
					// console.log(race_type);
				}
			});
		}
	});

});

route(/portal\/hefzlig\/race\/status\=delete/, function(){

	var _self = this;


	$(".race-delete").click(function(){
		raceid = $(this).attr("raceid");
		_self = $(this);
		$.ajax({
		type: "POST",
		url : "hefzlig/race/status=setdelete/id=" + raceid ,
		success : function(data){		
			xhr_result(data);
		}
		});	
	});
	
});
function hefz_result(raceid,_self){
	$.ajax({
		type: "POST",
		url : 'hefzlig/racing/status=resultapi/raceid=' + raceid,
		success : function(data){
			// console.log(data);
			for(a in data['msg']['result']){
				// console.log($("#race-result-value-" +a).html())
				$("#race-result-value-" +a).html(persian_nu(data['msg']['result'][a]['value']));
				$("#race-result-result-" +a).html(persian_nu(data['msg']['result'][a]['result']));
				$("#race-result-rate-" +a).html(persian_nu(data['msg']['result'][a]['rate']));
				$("#race-result-manfi-" +a).html(persian_nu(data['msg']['result'][a]['manfi']));	
			}
		}
	});
}

route(/hefzlig\/race\/status\=racing/, function(){

	$(".hefz-race-presence").click(function(){
		_self = $(this);
		raceid = $(this).attr("raceid");
		teamid = $(this).attr("teamid")
		teamusersid = $(this).attr("teamusersid")
		if($(this).prop('checked')){
			checked = 'true';
			$(_self).parents("tr").removeClass("rt-disable");
		}else{
			checked = 'false';
			$(_self).parents("tr").addClass("rt-disable");
		}
		
		$.ajax({
			type: "POST",
			url : "hefzlig/race/status=setpresence/raceid=" + raceid +  
					"/teamid=" + teamid + "/teamusersid=" + teamusersid
					+ "/checked=" + checked,
			success : function(data){
				xhr_result(data);

				console.log(data);
			}
		});	
		
	});

	$(".race-manfi").click(function(){
		_self = $(this);
		teamid = $(this).attr("teamid");
		raceid = $(this).attr("raceid")
		type = $(this).attr("type");
		$.ajax({
			type: "POST",
			url : "hefzlig/race/status=raceapi/teamid=" + teamid + "/raceid=" + raceid +  "/type=" + type,
			success : function(data){
				$(_self).removeAttr('disabled');
				if(data.fatal){
					xhr_result(data);
				}else if(data.warn){
					xhr_result(data);
				}else{
					// $(_true).insertAfter(_self);
					hefz_result(raceid,$(_self));
				}
			}
		});	
		
	});
	$('.race-mark', this).change(function(){
		$(this).attr('disabled', 'disabled');
		_self = $(this);

		teamuserid = $(this).attr("teamusersid");
		raceid = $(this).attr("raceid")
		value = $(this).val();
		type = $(this).attr("name");
		if(value == "" ) {
			$(this).removeAttr('disabled');
			// xhr_error("امتیاز را وارد کنید");
			console.error("امتیاز وارد نشده است");
			return;
		}
		
		_true = '<span class="icolikes" style="margin-right: 5px;padding: 0;display: inline-block;"></span>';
		$(_self).next('span').remove();

		url = "hefzlig/race/status=raceapi/teamuserid=" + teamuserid + "/raceid=" + raceid + "/value=" + value + "/type=" + type;
		
		$.ajax({
			type: "POST",
			url : url,
			success : function(data){
				$(_self).removeAttr('disabled');
				if(data.fatal){
					xhr_result(data);
				}else if(data.warn){
					xhr_result(data);
				}else{
					$(_true).insertAfter(_self);

					hefz_result(raceid,$(_self));
				}
			}
		});		
	});

});
route(/users\/status\=api/, function(){

	$(".add-hefz-teams-users",this).click(function(){
		teamid = $(this).attr("hefzteamsid");
		usersid = $(this).attr("usersid");
		$.ajax({
		type: "POST",
		url : "hefzlig/teams/status=apiadd/usersid=" + usersid + "/teamid=" + teamid,
		success : function(data){
			
			xhr_result(data);
		}
	});	
	})	

	
});



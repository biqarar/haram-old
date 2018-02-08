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

route(/portal\/hefzlig\/teams/, function(){
	_self = $(this);
	$("#lig_id").combobox( "destroy" );
	$("#lig_id", this).combobox({
		change : function(op){
			item = op.item.option.value
			
			$.ajax({
				type: "POST",
				url : "hefzlig/groupapi/ligid=" + item,
				success : function(data){
				console.log(data);
					$( "#hefz_group_id", _self).combobox("destroy");
			
					$("#hefz_group_id option").each(function(){
						$(this).remove();
					});
					
					$("<option value='' disabled='disabled' selected='selected'>لطفا یکی از گزینه ها را انتخاب کنید</option>").appendTo($("#hefz_group_id"));

					for(a in data['msg']['groupname']) {
						console.log(a);
						if(data['msg']['groupname'][a]['id']){

							$("<option type='text' value='"+data['msg']['groupname'][a]['id']+"' id='"+data['msg']['groupname'][a]['id']+"' placeholder='"+data['msg']['groupname'][a]['name']+"'>"+data['msg']['groupname'][a]['name']+"</option>").appendTo($("#hefz_group_id"));
						}			
					}

					$( "#hefz_group_id", _self).combobox();
				}
			});
		}
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
							console.log(a);
							if(data['msg']['teams'][a]['id']){
								$("<option type='text' value='"+data['msg']['teams'][a]['id']+"' id='"+data['msg']['teams'][a]['id']+"' placeholder='"+data['msg']['teams'][a]['name']+"'>"+data['msg']['teams'][a]['name']+"</option>").appendTo($("#hefz_team_id_1"));
								$("<option type='text' value='"+data['msg']['teams'][a]['id']+"' id='"+data['msg']['teams'][a]['id']+"' placeholder='"+data['msg']['teams'][a]['name']+"'>"+data['msg']['teams'][a]['name']+"</option>").appendTo($("#hefz_team_id_2"));
								
							}
						}

						$( "#hefz_team_id_1", _self).combobox();
						$( "#hefz_team_id_2", _self).combobox();
					// }	
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
			
			for(a in data['msg']['result']){
				
				$("#race-result-value-" +a).html(persian_nu(data['msg']['result'][a]['value']));
				$("#race-result-result-" +a).html(persian_nu(data['msg']['result'][a]['result']));
				$("#race-result-rate-" +a).html(persian_nu(data['msg']['result'][a]['rate']));
				$("#race-result-manfi-" +a).html(persian_nu(data['msg']['result'][a]['manfi']));	
			}
		}
	});
}

route(/hefzlig\/race\/status\=racing/, function(){

	// save sort teams
	xbody = $(this);
	$("#save-sort").click(function(){
		sort = new Array;
		i = 0;
		$("input[type=checkbox]",xbody).each(function(){
			raceid =  $(this).attr("raceid");
			teamid =  $(this).attr("teamid");
			// sort[teamid] = new Array;
			teamusersid = $(this).attr("teamusersid");
			sort[i]=[raceid,teamid,teamusersid];
			i++;
		});
			
			console.log(sort);

			$.ajax({
				type: "POST",
				url : "hefzlig/race/status=setsettings/raceid=" + raceid + "/type=sort",
				data: {'sort':sort},
				success : function(data){
					xhr_result(data);
				}
			});	
		// console.log(xbody);
	});




	$(".settings span").each(function(){
		if($(this).attr("id") == "save-sort") return;
		$(this).click(function(){
			type = $(this).attr("id");
			if(type == "telavat1"){
				$("#telavat2").removeClass("red");
				$("#telavat1").addClass("red");
			}else{
				$("#telavat1").removeClass("red");
				$("#telavat2").addClass("red");
			}
			raceid = $(this).attr("raceid");
			$.ajax({
				type: "POST",
				url : "hefzlig/race/status=setsettings/raceid=" + raceid + "/type=" + type,
				success : function(data){
					xhr_result(data);
				}
			});	
		});
	});

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
				hefz_result(raceid);
			}
		});	
		
	});

	$(".race-manfi").click(function(){
		_self = $(this);
		teamid = $(this).attr("teamid");
		raceid = $(this).attr("raceid")
		type = $(this).attr("type");
		// console.log(type);
		// xval = $("#race-result-manfi-" +teamid).html();
		// if(type == "up"){
		// 	$("#race-result-manfi-" +teamid).html(xval + 1);
		// }else{
			
		// 	$("#race-result-manfi-" +teamid).html(xval - 1);

		// }

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



route(/absence\/status=classeslist\/classesid=\d+/, function(){
	$(".absence-date-main")[0].callBackDate =  function(){
		date = convert_date($(this).val());
	}

	$("a.insertAbsenceApi", this).click(function(){
		
		if(typeof date == "undefined") {
			xhr_error("تاریخ انتخاب نشده است");
			return false;
		}


		classification = $(this).attr("classification");
		classesid = $(this).attr("classesid");
		type = $(".absence-type", $(this).parents("tr:eq(0)")).val();
		
		_self = $(this);
		
		if($(_self).hasClass("insertAbsenceApi")){
			$.ajax({
				type: "POST",
				url : "absence/api/classification=" + classification + "/date=" + date + "/type=" + type,
				success : function(data){
					

					if(data.fatal){
						xhr_error(data.fatal[0]);
						$(_self).removeClass("icodadd insertAbsenceApi").addClass("icoredclose deleteAbsenceApi");
						
						// $(_self).removeClass("icodadd insertAbsenceApi").addClass("icoredclose deleteAbsenceApi");
					}else if(data.warn){
						xhr_warn(data.warn[0]);

					}else{
						$(_self).removeClass("icodadd insertAbsenceApi").addClass("icoredclose deleteAbsenceApi");
						xhr_true(data.true[0]);
					}
				}
			});		

		}else if($(_self).hasClass("deleteAbsenceApi")){
			$.ajax({
				type: "POST",
				url : "absence/apidelete/classification=" + classification + "/date=" + date,
				success : function(data){
					if(data.fatal){
						xhr_error(data.fatal[0]);
						$(_self).removeClass("icoredclose deleteAbsenceApi").addClass("icodadd insertAbsenceApi");

					}else if(data.warn){
						xhr_warn(data.warn[0]);

					}else{
						$(_self).removeClass("icoredclose deleteAbsenceApi").addClass("icodadd insertAbsenceApi");
						xhr_true(data.true[0]);
					}
				}
			});	
		}

		return false;
	});



	$("a.deleteAbsenceApi", this).click(function(){
		
		if(typeof date == "undefined") {
			xhr_error("تاریخ انتخاب نشده است");
			return false;
		}
		
		classification = $(this).attr("classification");
		classesid = $(this).attr("classesid");
		_self = $(this);

		$.ajax({
			type: "POST",
			url : "absence/apidelete/classification=" + classification + "/date=" + date,
			success : function(data){
				if(data.fatal){
					xhr_error(data.fatal[0]);
					// $(_self).removeClass("icodadd deleteAbsenceApi").addClass("icoredclose insertAbsenceApi");
				}else if(data.warn){
					xhr_warn(data.warn[0]);

				}else{
					$(_self).removeClass("icodadd deleteAbsenceApi").addClass("icoredclose insertAbsenceApi");
					xhr_true(data.true[0]);
				}
			}
		});		
		return false;
	});

	function convert_date(date) {
		date = date.split("-");
		var m,d,y;
		y = date[0];
		m = date[1];
		d = date[2];
		if(y.length == 4) y = y;
		if(m < 10) {
			m = "0" + m;
		}
		if(d < 10) {
			d = "0" + d;
		}
	
		return y + "" + m + "" + d;
	}
});	

route(/absence\/classes\/classesid=\d+/, function(){

	function l(a) {console.log(a);}

	$(".absence-date-main")[0].callBackDate =  function(){
		date = convert_date($(this).val());
		l(date);
	}
	
	$("a").click(function(){
	
		alert("ddddddddddddd");
		return false;
	});

	$("a.insertAbsenceApi").click(function(){
		l("function");
		alert("fsdfs");
		
		return false;
	});


	function convert_date(date) {
		date = date.split("-");
		if(date[0].length == 4) y = date[0];
		if(date[1].length != 2) {
			m = "0" + date[1];
		}
		if(date[2].length != 2) {
			d = "0" + date[2];
		}
		return y + "" + m + "" + d;
	}
});	

route(/portal\/classes\/status=(add|edit)/, function(){
	var _self = this;
	$( ".select-plan", this).combobox();
	$( ".select-teacher", this).combobox();
	$( ".select-place", this).combobox();
	
});
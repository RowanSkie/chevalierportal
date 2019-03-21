function getUserList(){
	
	var c = getParam("subscription");
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getuserlist.php?subscription="+c+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		var users = data.data;	
		var dropdown = $('#ddUser');
		// Clear drop down list
		$(dropdown).empty();
		var $option = $("<option />");
		$option.attr("value", "").text("Select a User");
		$(dropdown).append($option);
		// Add option to drop down list
		$(dropdown).append($option);
		jQuery.each( users, function( i, user ) {
			// Create option
			var $option = $("<option />");
			// Add value and text to option
			$option.attr("value", user.name).text(user.type + " - " + user.name);
			// Add option to drop down list
			$(dropdown).append($option);

		});		
		document.body.style.cursor  = 'default';	
	});
	document.body.style.cursor  = 'default';	
}

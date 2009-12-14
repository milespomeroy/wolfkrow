$(function(){
	$(".stars").children().not(":radio").hide();
	$("#host").stars({
		oneVoteOnly: true,
		callback: function(ui, type, value)
		{
			// loader
			$("#host").hide();
			$("#loader-host").show();
			
			$.post("/meal/rate", 
					{newrate: value, 
						order_id: $("#order_id-host").val(), 
						vendor_id: $("#vendor_id-host").val()}, 
				function()
				{
					// loader
					$("#loader-host").hide();
					$("#host").show();
				}
			);
		}
	});
	
	$("#waiter").stars({
		oneVoteOnly: true,
		callback: function(ui, type, value)
		{
			// loader
			$("#waiter").hide();
			$("#loader-waiter").show();
			
			$.post("/meal/rate", 
					{newrate: value, 
						order_id: $("#order_id-waiter").val(), 
						vendor_id: $("#vendor_id-waiter").val()}, 
				function()
				{
					// loader
					$("#loader-waiter").hide();
					$("#waiter").show();
				}
			);
		}
	});
	
	$("#cook").stars({
		oneVoteOnly: true,
		callback: function(ui, type, value)
		{
			// loader
			$("#cook").hide();
			$("#loader-cook").show();
			
			$.post("/meal/rate", 
					{newrate: value, 
						order_id: $("#order_id-cook").val(), 
						vendor_id: $("#vendor_id-cook").val()}, 
				function()
				{
					// loader
					$("#loader-cook").hide();
					$("#cook").show();
				}
			);
		}
	});
	
	$("#busboy").stars({
		oneVoteOnly: true,
		callback: function(ui, type, value)
		{
			// loader
			$("#busboy").hide();
			$("#loader-busboy").show();
			
			$.post("/meal/rate", 
					{newrate: value, 
						order_id: $("#order_id-busboy").val(), 
						vendor_id: $("#vendor_id-busboy").val()}, 
				function()
				{
					// loader
					$("#loader-busboy").hide();
					$("#busboy").show();
				}
			);
		}
	});
});
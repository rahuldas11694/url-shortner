$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$("#js-generate-short-url").click(function(e){

	e.preventDefault();
	
	var original_url = $("#original-url").val();
	if(original_url == ""){
		alert("Please enter url");
	}

	$.ajax({
       url : '/generate-short-url', //PHP file to execute
       type : 'POST', //method used POST or GET
       data : { og_url: original_url}, // Parameters passed to the PHP file
       success : function(resp){ // Has to be there !
       	// resp = JSON.parse(resp);

       	if(resp.code == 1){
       		var data = resp.data;
       		var $short_url = "Short URL: <a href="+data.short_url+">" + data.short_url + "</a>";
       		$("#js-generated-short-url").append($short_url);
       		// $("#js-generated-short-url").text("Short URL: "+ data.short_url);

       	}else{
       		alert("Something went wring while generating short URL.. Please try again.");
       	}          
       },

       error : function(result, statut, error){ // Handle errors

       }

    });

});


$(".js-upgrade-plan a.upgrade").click(function(){
    
    var plan_id = $(this).data('planid');

    $.ajax({
       url : '/plan/upgrade', //PHP file to execute
       type : 'POST', //method used POST or GET
       data : { plan_id: plan_id }, // Parameters passed to the PHP file
       success : function(resp){ // Has to be there !
        // resp = JSON.parse(resp);

        if(resp.code == 1){
            var data = resp.data;
            alert(data.msg);

        }else{
            alert("Something went wring while generating short URL.. Please try again.");
        }          
       },

       error : function(result, statut, error){ // Handle errors

       }
    });


});
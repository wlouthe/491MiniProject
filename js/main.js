$(document).ready(function(){

    $( "#signup" ).click(function(){
		$("#myhr").show("slow",function(){});
        $( "#signuppage" ).show("slow", function(){});
        //event.preventDefault();
        
    });
    
    $( "#create" ).click(function(){
        var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
        if(pattern.test($("#sp1info").val()))
        {
            $.ajax({
                type: "POST",
                url: "http://web.njit.edu/~ll37/491MiniProject/mailer.php",
                async: false,
                data: {
                    "email": $("#sp1info").val(),
                },
                dataType: "xml",
                success: function(email,code){
                    console.log("success");
					$( "#sp1" ).hide();
					$( "#sp2" ).show("slow", function(){});
                },
                error: function(baba, gaga) {
                    alert("Error occured: " + gaga);
                }
             });
            
        }

        
        event.preventDefault();
        
    });

});
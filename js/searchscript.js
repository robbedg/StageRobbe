$(document).ready(function(){

    var url = window.location.protocol + "//" + 
        window.location.host +
        window.location.pathname +
        "/search";

    $(".search").keyup(function(){
        if($(".search").val().length>=3){

        var data = {search: $(".search").val(), type: $(".type").val()};

        $.ajax({
            dataType: "json",
            type: "post",
            url: url,
            cache: false,               
            data: data,
            success: function(response){
                $('#finalResult').html("");
                var obj = response;
                if(obj.length>0){
                    try{
                        var items=[];   
                        $.each(obj, function(i,val){                                            
                            items.push($('<li/>').text(val.name));
                        }); 
                        $('#finalResult').append.apply($('#finalResult'), items);
                    }catch(e) {     
                        alert('Exception while request..');
                    }       
                }else{
                    //nothing 
                }       

            },
            error: function(e){
                console.log(e);                     
            }
        });
        }else {
            $('#finalResult').empty(); //empty
        }
    return false;
    });
});
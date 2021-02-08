$(document).ready(function(){

    $(".like-btn").on("click", function(){
        var post_id= $(this).data("id");
        $clicked_btn=$(this);
        
        if($clicked_btn.hasClass("fa-thumbs-o-up")){
            action="like";
            $clicked_btn.removeClass("fa-thumbs-o-up");
            $clicked_btn.addClass("fa-thumbs-up");
         
            
        }else if($clicked_btn.hasClass("fa-thumbs-up")){
            action="unlike";
            $clicked_btn.addClass("fa-thumbs-o-up");
            $clicked_btn.removeClass("fa-thumbs-up");
        
        }

        $.ajax({
            url:"../Pages/user-feeds.php",
            type: "POST",
            data: {
                "action":action,
                "post_Id":post_id
            },
            succes:function(data){
                res= JSON.parse(data);
     
                if(action == "like"){
                    $clicked_btn.removeClass("fa-thumbs-o-up");
                    $clicked_btn.addClass("fa-thumbs-up");
                
                }else if(action == "unlike"){
                    $clicked_btn.removeClass("fa-thumbs-up");
                    $clicked_btn.addClass("fa-thumbs-o-up");
                   
                }
               
            }
        })

    });
  
})

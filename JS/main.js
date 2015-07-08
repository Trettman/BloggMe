$(document).ready(function(){
   $("#addFriend").click(function(){
      var data = $("#form-friend").serialize();

      $.ajax({
         data: data,
         type: "post",
         url: "add_friend.php",
         succes: function(){
            alert("Succes");
         },
         error: function(){
            alert("Error");
         }
      });
   });
   $("#removeFriend").click(function(){
      var data = $("#form-friend").serialize();

      $.ajax({
         data: data,
         type: "post",
         url: "remove_friend.php",
         succes: function(){
            alert("Succes");
         },
         error: function(){
            alert("Error");
         }
      });
   });
});
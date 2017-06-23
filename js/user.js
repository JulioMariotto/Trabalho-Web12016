$(function(){

  $("html").css("height", "100%");
  $(".row").css({
  		"width":"100%",
  		"padding-top":"109px",
  		"padding-bottom":"109px",
  		"margin":"0px",
  		"min-height":"100%",
  		"position":"relative"
});
  $("#back").css({
    "padding-left":"5px",
    "color":"black",
    "text-decoration" : "underline"
  });
  $("#ft").css({
  		"position":"absolute",
  		"bottom":"0",
  		"width":"100%",
  		"padding":"30px",
  		"background-color":"#222222",
  		"text-align":"center",
  		"color":"lightgrey"
});
  $(".new-panel").css({
      "height":"250px",
      "z-index":"1",
      "overflow":"auto"
});
  
  $(".panel panel-default").css("width", "500px");
});

$(document).ready(function(){
    $("#upft").click(function(){
        $("#Mod").modal();
    });
});

function delef(id)
{
  var v = confirm("Você tem certeza que deseja remover essa Foto?");

  if(v)
  {
    if (window.XMLHttpRequest) 
    {
      xmlhttp = new XMLHttpRequest();
    }
    xmlhttp.onreadystatechange = function() 
    {
      if (this.readyState == 4 && this.status == 200) 
      {
        document.getElementById("image").src = "pic/user-default.png";
        document.getElementById("image2").src = "pic/user-default.png";
      }
    }
    xmlhttp.open("GET","ajax/delpic.php?id=" + id, true);
    xmlhttp.send();
  }
}
$(function(){

  $(".input-group-btn").css("padding", "0px");
  $("html").css("height", "100%");
  $(".row").css({
  		"width":"100%",
  		"padding-top":"90px",
  		"padding-bottom":"90px",
  		"margin":"0px",
  		"min-height":"100%",
  		"position":"relative"
});
  $("#lgpg1").css({
  	"text-align":"center",
  	"padding-top":"220px"
});
  $("#sel").css({
  	"background-color":"white",
	  "color":"black"
});
  $(".list-group-item").css("font-size","18px");
  $(".list-group > a").css({
    "color":"black",
    "text-decoration":"none"
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
  $(".pull").css("overflow", "auto");
  $(".new-panel").css({
      "height":"250px",
      "z-index":"1",
      "overflow":"auto"
});
  $("#back").css({
    "padding-left":"5px",
    "color":"black",
    "text-decoration" : "underline"
  });
  $(".btn-confirm").on("click",function(){
        return confirm("Você confirma o envio da Lista de Exercicios?");
      });
  $("#hdn").hide();
  $("#lbl").hide();
  $(".vmr").css("color", "red");
  })

function showURL() 
{
  $("#hdn").show();
}

function showAss(str) 
{
  if (str=="") 
  {
    document.getElementById("txt").innerHTML="";
    return;
  } 
  if (window.XMLHttpRequest) 
  {
    xmlhttp = new XMLHttpRequest();
  } 
  else 
  {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() 
  {
    if (this.readyState == 4 && this.status == 200) 
    {
      document.getElementById("txt").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","ajax/listex.php?id="+str,true);
  xmlhttp.send();
}

function verf(str)
{
  var cmp = $("#pssw").val();

  if(str != cmp)
  {
    $("#cnf").attr("class", "alert alert-danger");
    $("#dng").attr("class", "form-group has-error has-feedback");
    $("#lbl").show();
  }
  else
  {
    $("#cnf").attr("class", "");
    $("#dng").attr("class", "form-group");
    $("#lbl").hide();
  }
}

function daLike(str) 
{ 
  if (window.XMLHttpRequest) 
  {
    xmlhttp = new XMLHttpRequest();
  } 
  else 
  {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() 
  {
    if (this.readyState == 4 && this.status == 200) 
    {
      document.getElementById("lk").innerHTML = this.responseText;
    }
  }
  xmlhttp.open("GET","ajax/rating.php?id="+ str + "&acao=like",true);
  xmlhttp.send();
}

function daDeslike(str) 
{ 
  if (window.XMLHttpRequest) 
  {
    xmlhttp = new XMLHttpRequest();
  } 
  else 
  {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() 
  {
    if (this.readyState == 4 && this.status == 200) 
    {
      document.getElementById("dlk").innerHTML = this.responseText;
    }
  }
  xmlhttp.open("GET","ajax/rating.php?id="+ str + "&acao=deslike",true);
  xmlhttp.send();
}
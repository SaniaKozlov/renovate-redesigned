$(document).ready(function(){
  		var $navbar = $("#main-navbar");
  		$(window).scroll(function(){
  			var headerHeight = $("div.header-background").height();
  			if ( $(this).scrollTop() > headerHeight ){
  		    	$navbar.removeClass("default").addClass("navbar-fixed-top");
  			} else if($(this).scrollTop() <= headerHeight && $navbar.hasClass("navbar-fixed-top")) {
  		        $navbar.removeClass("navbar-fixed-top");
  			}
  		});
  		
  		var monthes = ["січня","лютого","березеня","квітня","травня","червня","липня","серпня","вересня","жовтеня","листопада","грудня"];
  		var days = ["Неділя","Понеділок","Вівторок","Середа","Четвер","П’ятниця","Субота"];
  		
  		var date = new Date();
  		var year = date.getFullYear();
  		var month = monthes[date.getMonth()];
  		var day = date.getDate();
  		var dayOfWeek = days[date.getDay()];
  			
  		var output = dayOfWeek + ", " + day + " " + month + " " + year + " року";
  		
  		$("#date").text(output);
  	});
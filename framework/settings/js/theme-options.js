jQuery(document).ready(function($){

	$(".tab_block").hide();
	$(".tabs ul li:first").addClass("active").show();	
	$(".tab_block:first").show();
	
	$(".tabs ul li").click(function() {
		$(".tabs ul li").removeClass("active");
		$(this).addClass("active");
		$(".tab_block").hide();
		var activeTab = $(this).find("a").attr("href");
		$(activeTab).fadeIn();
		return false;
	});
											
});

jQuery(document).ready(function ($) {
	setTimeout(function () {
		$(".fade").fadeOut("slow", function () {
			$(".fade").remove();
		});
	}, 2000);
});
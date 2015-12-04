VK.init({apiId: 4845644, onlyWidgets: true});
vkHandler = null;
function initVK(){
	clearTimeout(vkHandler);
	vkHandler = setTimeout(function(){
		var elements = $('.vk').each(function(i, v){
			var options = {
					type: "button"
			}
						
			var href = $(v).attr('content-href');
			if (href != undefined) {
				options.pageUrl = href;
			}
			
			var title = $(v).attr('content-title');
			if (title != undefined) {
				options.pageTitle = title;
			}
			
			VK.Widgets.Like($(v).attr('id'), options);
		});
	}, 1000);
}

$(document).ready(function() {
	initVK();
});

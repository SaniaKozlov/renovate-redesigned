$('.partners').slick({
	infinite: true,
	slidesToShow: 1,
	slidesToScroll: 1,
	variableWidth: true,
	centerMode: true,
	autoplay: true,
	autoPlaySpeed: 2000,
	onSetPosition: function(){
		var maxWidth = 0;
		var itemsCount = 0;
		_.each($('.partners').find('.slick-slide'), function(div){
			if(!$(div).hasClass('slick-cloned')){
				maxWidth+=$(div).find('img').width();
				itemsCount+=1;
			}
		});
		$('.partners').css('max-width',maxWidth+'px');
		$('.partners').removeClass('transparent');
		var oldCenterMode = this.options.centerMode;
		this.options.centerMode = (itemsCount == 1) ? false : true;
		if (oldCenterMode != this.options.centerMode) this.refresh();
	}
});
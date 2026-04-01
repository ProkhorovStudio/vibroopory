$(document).on('click', '#headerfixed .buy.btn', function(){
	if ($('.catalog_detail .buy_block .offer_buy_block .to-cart').length) {
		if ($('.catalog_detail .buy_block .offer_buy_block .to-cart').is(':visible')) {
			$('.catalog_detail .buy_block .offer_buy_block .to-cart').trigger('click');
		} else {
			location.href = arNextOptions['PAGES']['BASKET_PAGE_URL'];
		}
	} else if ($('.catalog_detail .buy_block .offer_buy_block .btn').length) {
		$('.catalog_detail .buy_block .offer_buy_block .btn').trigger('click')
	}
})

$(document).on('click', '#headerfixed .bx_catalog_item_scu', function(){
	var offset = 0;
	offset = $('.catalog_detail .sku_props .bx_catalog_item_scu').offset().top;
		
	$('body, html').animate({scrollTop: offset-150}, 500);
})

$(document).on('click', ".stores-title .stores-title__list", function(){
	var _this = $(this);
	_this.siblings().removeClass('stores-title--active');
	_this.addClass('stores-title--active');

	$('.stores_block_wrap .stores-amount-list').hide(100).removeClass('stores-amount-list--active');
	$('.stores_block_wrap .stores-amount-list:eq('+_this.index()+')').show(100, function(){
		if(_this.hasClass('stores-title--map'))
		{
			if(typeof map !== 'undefined')
			{
				map.container.fitToViewport();
				if(typeof clusterer !== 'undefined' && !$(this).find('.detail_items').is(':visible'))
				{
					map.setBounds(clusterer.getBounds(), {
						zoomMargin: 40,
						// checkZoomRange: true
					});
				}
			}
		}
	}).addClass('stores-amount-list--active');

})


$(document).on('change', 'input[name="quantity"]', function(){
	let $this = $(this),
		qMax = $this.closest('.counter_block').find('.plus').data('max');

	if ($this.val() >= qMax) {
		let text = "Вы добавили в корзину максимально доступное на сегодня количество " + "<b>"+qMax+" шт</b>" +". Пожалуйста, свяжитесь с нами любым удобным способом и мы подберем для вас товар аналогичных технических характеристик и качества.";
		if (!$('.notify-max-quantity').length) {
			$('body').append('<div class="notify-max-quantity jqmWindow popup"><a href="#" class="close jqmClose"><i></i></a><div class="notify-body">'+text+'</div></div>');
		} else {
			$('.notify-body').html(text);
		}

		$('.notify-max-quantity').jqm().jqmShow();
	}
});
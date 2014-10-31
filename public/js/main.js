$(function ()
{
	$('.toggle').click(function (e)
	{
		e.preventDefault();
		$(this).parent().next().slideToggle();
	});

	var timeout;
	$('[data-call],[data-source]').each(function ()
	{
		var $this = $(this);
		$this.html('<div class="text-center"><i class="fa fa-5x fa-circle-o-notch fa-spin"></i></div>');
		var method;
		var url;
		if (method = $this.data('call'))
		{
			url = '/call/';
		} else
		{
			method = $this.data('source');
			url = '/source/'
		}
		$.get(url + method, function (data)
		{
			$this.text(data);
			if (timeout) clearTimeout(timeout);
			timeout = setTimeout(function () {
				hljs.initHighlighting.called = false;
				hljs.initHighlighting();
			}, 500);
		});
	});

	var selectCurrentLink = function ()
	{
		var currentPage = window.location.href;

		$('.nav:first li.active').removeClass('active');
		var currentPageLink = $('.nav a[href="' + currentPage + '"]');
		currentPageLink.closest('li').addClass('active');
	};

	$('.nav:first li').click(function ()
	{
		setTimeout(selectCurrentLink, 100);
	});
	selectCurrentLink();
});

$(function ()
{
	$('.toggle').click(function (e)
	{
		e.preventDefault();
		var $this = $(this);
		$this.parent().next().slideToggle();
	});

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
		});
	});
});
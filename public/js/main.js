$(function ()
{
	$('.toggle').click(function (e)
	{
		e.preventDefault();
		var $this = $(this);
		$this.parent().next().slideToggle();
	});
});
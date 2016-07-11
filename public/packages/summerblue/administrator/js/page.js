(function($)
{
	var $menu, $mobileMenu, $menuButton, $filterButton, $content;

	//dom ready
	$(function()
	{
		$menu = $('ul#menu, ul#lang_menu');
		$mobileMenu = $('#mobile_menu_wrapper');
		$menuButton = $('a#menu_button');
		$filterButton = $('a#filter_button');
		$filters = $('#sidebar');
		$content = $('#content');

		//set the menu hover and hoverout states
		$menu.find('li.menu').each(function()
		{
			var $this = $(this),
				$submenu = $this.children('ul');

			//bind events for the top-level menu item
			$this.bind({
				mouseenter: function()
				{
					clearTimeout($this.data('timer'));
					$this.addClass('current');
				},
				mouseleave: function()
				{
					$this.data('timer', setTimeout(function()
					{
						$submenu.fadeOut(150);
						$this.removeClass('current');
					}, 150));
				}
			});

			//make the submenu slide down on hover
			$this.hover(function()
			{
				//if this is a sub-submenu, slide it right instead of down
				if ($this.parent().closest('li.menu').length)
				{
					$this.addClass('current');
					$submenu.stop(true, true).show('slide', { direction: 'left' }, 200);
				}
				else
					$submenu.stop(true, true).slideDown(200);
			});
		});

		toggleMenu = function(toggle)
		{
			$menuButton.toggleClass('current', toggle);

			if (toggle)
				$mobileMenu.stop(true, true).show('slide', { direction: 'left' }, 100);
			else
				$mobileMenu.stop(true, true).hide('slide', { direction: 'left' }, 100);
		}

		toggleFilter = function(toggle)
		{
			$filterButton.toggleClass('current', toggle);
			$filters.toggleClass('shown', toggle);
			$content.toggleClass('hidden', toggle);

			admin.resizePage();
		}

		//clicking the menu button hides/shows the mobile menu
		$menuButton.click(function(e)
		{
			e.preventDefault();

			toggleMenu(!$menuButton.hasClass('current'));
		});

		//clicking the filter button hides/shows the filter
		$filterButton.click(function(e)
		{
			e.preventDefault();

			toggleFilter(!$filterButton.hasClass('current'));
		});

		//hide the menu on document click outside
		$(document).click(function(e)
		{
			var inMenuButton = $menuButton.is(e.target) || $menuButton.has(e.target).length !== 0,
				inMenu = $mobileMenu.is(e.target) || $mobileMenu.has(e.target).length !== 0,
				inFilterButton = $filterButton.is(e.target) || $filterButton.has(e.target).length !== 0,
				inFilters = $filters.is(e.target) || $filters.has(e.target).length !== 0;

			if ($menuButton.hasClass('current') && !inMenu && !inMenuButton)
				toggleMenu(false);

			if ($filterButton.hasClass('current') && !inFilters && !inFilterButton)
				toggleFilter(false);
		});

		//clicking menu items in the mobile menu hides/shows that submenu
		$mobileMenu.on('click', 'li.menu > span', function()
		{
			$(this).siblings('ul').toggle();
		});

		//set up the customscroll plugin for the mobile menu
		$mobileMenu.customscroll();


		//disable body scroll when scroll a scrollable content
		$('.scrollable').on('DOMMouseScroll mousewheel', function(ev) {
		    var $this = $(this),
		        scrollTop = this.scrollTop,
		        scrollHeight = this.scrollHeight,
		        height = $this.height(),
		        delta = (ev.type == 'DOMMouseScroll' ?
		            ev.originalEvent.detail * -40 :
		            ev.originalEvent.wheelDelta),
		        up = delta > 0;

		    var prevent = function() {
		        ev.stopPropagation();
		        ev.preventDefault();
		        ev.returnValue = false;
		        return false;
		    };

		    if (!up && -delta > scrollHeight - height - scrollTop) {
		        // Scrolling down, but this will take us past the bottom.
		        $this.scrollTop(scrollHeight);
		        return prevent();
		    } else if (up && delta > scrollTop) {
		        // Scrolling up, but this will take us past the top.
		        $this.scrollTop(0);
		        return prevent();
		    }
		});

		// filter btn
		$('#filter-btn-success').on('click', function() {
			var visible = $('#sidebar').is(':visible');

			if (visible) {
				$('.item_edit_container').fadeIn();
				$('#sidebar').fadeOut();
			} else {
				$('.item_edit_container').fadeOut();
				$('#sidebar').fadeIn();
			}
		});
	});
})(jQuery);

//fixes the issue with media queries not firing when the user resizes the browser in another tab
(function() {
	var hidden = "hidden";

	// Standards:
	if (hidden in document)
		document.addEventListener("visibilitychange", onchange);
	else if ((hidden = "mozHidden") in document)
		document.addEventListener("mozvisibilitychange", onchange);
	else if ((hidden = "webkitHidden") in document)
		document.addEventListener("webkitvisibilitychange", onchange);
	else if ((hidden = "msHidden") in document)
		document.addEventListener("msvisibilitychange", onchange);
	// IE 9 and lower:
	else if ('onfocusin' in document)
		document.onfocusin = document.onfocusout = onchange;
	// All others:
	else
		window.onpageshow = window.onpagehide
			= window.onfocus = window.onblur = onchange;

	function onchange (evt) {
		var v = 'sg-tab-bust-visible', h = 'sg-tab-bust-hidden',
			evtMap = {
				focus:v, focusin:v, pageshow:v, blur:h, focusout:h, pagehide:h
			};

		evt = evt || window.event;
		if (evt.type in evtMap)
			document.body.className = evtMap[evt.type];
		else
			document.body.className = this[hidden] ? "sg-tab-bust-hidden" : "sg-tab-bust-visible";

		//clear out the body's class
		document.body.className = '';
	}
})();

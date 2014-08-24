/**
* hoverIntent r5 // 2007.03.27 // jQuery 1.1.2+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne <brian@cherne.net>
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY;};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev]);}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob);},cfg.interval);}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev]);};var handleHover=function(e){var p=(e.type=="mouseover"?e.fromElement:e.toElement)||e.relatedTarget;while(p&&p!=this){try{p=p.parentNode;}catch(e){p=this;}}if(p==this){return false;}var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);}if(e.type=="mouseover"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob);},cfg.interval);}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob);},cfg.timeout);}}};return this.mouseover(handleHover).mouseout(handleHover);};})(jQuery);

/*
 * DC Vertical Mega Menu - jQuery vertical mega menu
 * Copyright (c) 2011 Design Chemical
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 */
(function($){

	//define the new for the plugin ans how to call it	
	$.fn.dcVerticalMegaMenu = function(options){
		//set default options  
		var defaults = {
			classParent: 'dc-mega',
			arrow: true,
			classArrow: 'dc-mega-icon',
			classContainer: 'sub-container',
			classSubMenu: 'sub',
			classMega: 'mega',
			classSubParent: 'mega-hdr',
			classSubLink: 'mega-hdr',
			classRow: 'row',
			rowItems: 3,
			speed: 'fast',
			effect: 'show',
			direction: 'right'
		};

		//call in the default otions
		var options = $.extend(defaults, options);
		var $dcVerticalMegaMenuObj = this;

		//act upon the element that is passed into the design    
		return $dcVerticalMegaMenuObj.each(function(options){

			$mega = $(this);
			if(defaults.direction == 'left'){
				$mega.addClass('left');
			} else {
				$mega.addClass('right');
			}
			// Get Menu Width
			var megaWidth = $mega.width();
			
			// Set up menu
			$('> li',$mega).each(function(){
				
				var $parent = $(this);
				var $megaSub = $('> ul',$parent);
	
				if($megaSub.length > 0){
					
				$('> a',$parent).addClass(defaults.classParent).append('<span class="'+defaults.classArrow+'"></span>');
					$megaSub.addClass(defaults.classSubMenu).wrap('<div class="'+defaults.classContainer+'" />');
					var $container = $('.'+defaults.classContainer,$parent);
					
					if($('ul',$megaSub).length > 0){
						
						$parent.addClass(defaults.classParent+'-li');
						$container.addClass(defaults.classMega);
						
						// Set sub headers
						$('> li',$megaSub).each(function(){
							$(this).addClass('mega-unit');
							if($('> ul',this).length){
								$(this).addClass(defaults.classSubParent);
								$('> a',this).addClass(defaults.classSubParent+'-a');
							} else {
								$(this).addClass(defaults.classSubLink);
								$('> a',this).addClass(defaults.classSubLink+'-a');
							}
						});
						
						// Create Rows
						var hdrs = $('.mega-unit',$parent);
						rowSize = parseInt(defaults.rowItems);
						for(var i = 0; i < hdrs.length; i+=rowSize){
							hdrs.slice(i, i+rowSize).wrapAll('<div class="'+defaults.classRow+'" />');
						}

						// Get mega dimensions
						var itemWidth = $('.mega-unit',$megaSub).outerWidth(true);
						var rowItems = $('.row:eq(0) .mega-unit',$megaSub).length;
						var innerItemWidth = itemWidth * rowItems;
						var totalItemWidth = innerItemWidth + containerPad;

						// Set mega header height
						$('.row',this).each(function(){
							$('.mega-unit:last',this).addClass('last');
							var maxValue = undefined;
							$('.mega-unit > a',this).each(function(){
								var val = parseInt($(this).height());
								if (maxValue === undefined || maxValue < val){
									maxValue = val;
								}
							});
							$('.mega-unit > a',this).css('height',maxValue+'px');
							$(this).css('width',innerItemWidth+'px');
						});
						var subWidth = $megaSub.outerWidth(true);
						var totalWidth = $container.outerWidth(true);
						var containerPad = totalWidth - subWidth;
						// Calculate Row Height
						$('.row',$megaSub).each(function(){
							var rowHeight = $(this).height();
							$(this).parent('.row').css('height',rowHeight+'px');
						});
						$('.row:last',$megaSub).addClass('last');
						$('.row:first',$megaSub).addClass('first');
					} else {
						$container.addClass('non-'+defaults.classMega);
					}
				} 
			
				var $container = $('.'+defaults.classContainer,$parent);
				// Get flyout height
				var subHeight = $container.height();
				var itemHeight = $parent.outerHeight(true);
				// Set position to top of parent
				$container.css({
					height: subHeight+'px',
					marginTop: -itemHeight+'px',
					zIndex: '1000',
					width: subWidth+'px'
				}).hide();
			});

			// HoverIntent Configuration
			var config = {
				sensitivity: 2, // number = sensitivity threshold (must be 1 or higher)
				interval: 100, // number = milliseconds for onMouseOver polling interval
				over: megaOver, // function = onMouseOver callback (REQUIRED)
				timeout: 0, // number = milliseconds delay before onMouseOut
				out: megaOut // function = onMouseOut callback (REQUIRED)
			};
			
			$('li',$dcVerticalMegaMenuObj).hoverIntent(config);
				
			function megaOver(){
				$(this).addClass('mega-hover');
				var $link = $('> a',this);
				var $subNav = $('.sub',this);
				var $container = $('.sub-container',this);
				var width = $container.width();
				var outerHeight = $container.outerHeight(true);
				var height = $container.height();
				var itemHeight = $(this).outerHeight(true);
				var offset = $link.offset();
				var scrollTop = $(window).scrollTop();
				offset = offset.top - scrollTop
				var bodyHeight = $(window).height();
				var maxHeight = bodyHeight - offset;
				var xsHeight = maxHeight - outerHeight - itemHeight;

				if(xsHeight < 0){
					var containerMargin = xsHeight - itemHeight;
					$container.css({marginTop: containerMargin+'px'});
				}
				
				var containerPosition = {right: megaWidth};
				if(defaults.direction == 'right'){
					containerPosition = {left: megaWidth};
				}
				
				if(defaults.effect == 'fade'){
					$container.css(containerPosition).fadeIn(defaults.speed);
				}
				if(defaults.effect == 'show'){
					$container.css(containerPosition).show();
				}
				if(defaults.effect == 'slide'){
					$container.css({
						width: 0,
						height: 0,
						opacity: 0});
					
					if(defaults.direction == 'right'){
				
						$container.show().css({
							left: megaWidth
						});
					} else {
					
						$container.show().css({
							right: megaWidth
						});
					}
					$container.animate({
							width: width,
							height: height,
							opacity: 1
						}, defaults.speed);
				}
			}
			
			function megaOut(){
				$(this).removeClass('mega-hover');
				var $container = $('.sub-container',this);
				$container.hide();
			}
		});
	};
})(jQuery);

/*
 * DC Mega Menu - jQuery mega menu
 * Copyright (c) 2011 Design Chemical
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
*/
(function($){

	//define the defaults for the plugin and how to call it	
	$.fn.dcMegaMenu = function(options){
		//set default options  
		var defaults = {
			classParent: 'dc-mega',
			classContainer: 'sub-container',
			classSubParent: 'mega-hdr',
			classSubLink: 'mega-hdr',
			classWidget: 'dc-extra',
			rowItems: 3,
			speed: 'fast',
			effect: 'fade',
			event: 'hover',
			fullWidth: false,

			onLoad : function(){},
                        beforeOpen : function(){},
			beforeClose: function(){}
		};

		//call in the default otions
		var options = $.extend(defaults, options);
		var $dcMegaMenuObj = this;

		//act upon the element that is passed into the design    
		return $dcMegaMenuObj.each(function(options){

			var clSubParent = defaults.classSubParent;
			var clSubLink = defaults.classSubLink;
			var clParent = defaults.classParent;
			var clContainer = defaults.classContainer;
			var clWidget = defaults.classWidget;
			
			megaSetup();
			
			function megaOver(){
				var subNav = $('.sub',this);
				$(this).addClass('mega-hover');
				if(defaults.effect == 'fade'){
					$(subNav).fadeIn(defaults.speed);
				}
				if(defaults.effect == 'slide'){
					$(subNav).show(defaults.speed);
				}
				// beforeOpen callback;
				defaults.beforeOpen.call(this);
			}

			function megaOut(){
				var subNav = $('.sub',this);
				$(this).removeClass('mega-hover');
				$(subNav).hide();
				// beforeClose callback;
				defaults.beforeClose.call(this);
			}

			function megaReset(){
				$('li',$dcMegaMenuObj).removeClass('mega-hover');
				$('.sub',$dcMegaMenuObj).hide();
			}

			function megaSetup(){
				$arrow = '<span class="dc-mega-icon"></span>';
				var clParentLi = clParent+'-li';
				var menuWidth = $dcMegaMenuObj.outerWidth();
				$('> li',$dcMegaMenuObj).each(function(){
					//Set Width of sub
					var $mainSub = $('> ul',this);
					var $primaryLink = $('> a',this);
					if($mainSub.length){
						$primaryLink.addClass(clParent).append($arrow);
						$mainSub.addClass('sub').wrap('<div class="'+clContainer+'" />');
						
						var pos = $(this).position();
						pl = pos.left;
							
						if($('ul',$mainSub).length){
							$(this).addClass(clParentLi);
							$('.'+clContainer,this).addClass('mega');
							$('> li',$mainSub).each(function(){
								if(!$(this).hasClass(clWidget)){
									$(this).addClass('mega-unit');
									if($('> ul',this).length){
										$(this).addClass(clSubParent);
										$('> a',this).addClass(clSubParent+'-a');
									} else {
										$(this).addClass(clSubLink);
										$('> a',this).addClass(clSubLink+'-a');
									}
								}
							});

							// Create Rows
							var hdrs = $('.mega-unit',this);
							rowSize = parseInt(defaults.rowItems);
							for(var i = 0; i < hdrs.length; i+=rowSize){
								hdrs.slice(i, i+rowSize).wrapAll('<div class="row" />');
							}

							// Get Sub Dimensions & Set Row Height
							$mainSub.show();
							
							// Get Position of Parent Item
							var pw = $(this).width();
							var pr = pl + pw;
							
							// Check available right margin
							var mr = menuWidth - pr;
							
							// // Calc Width of Sub Menu
							var subw = $mainSub.outerWidth();
							var totw = $mainSub.parent('.'+clContainer).outerWidth();
							var cpad = totw - subw;
							
							if(defaults.fullWidth == true){
								var fw = menuWidth - cpad;
								$mainSub.parent('.'+clContainer).css({width: fw+'px'});
								$dcMegaMenuObj.addClass('full-width');
							}
							var iw = $('.mega-unit',$mainSub).outerWidth(true);
							var rowItems = $('.row:eq(0) .mega-unit',$mainSub).length;
							var inneriw = iw * rowItems;
							var totiw = inneriw + cpad;
							
							// Set mega header height
							$('.row',this).each(function(){
								$('.mega-unit:last',this).addClass('last');
								var maxValue = undefined;
								$('.mega-unit > a',this).each(function(){
									var val = parseInt($(this).height());
									if (maxValue === undefined || maxValue < val){
										maxValue = val;
									}
								});
								$('.mega-unit > a',this).css('height',maxValue+'px');
								$(this).css('width',inneriw+'px');
							});
							
							// Calc Required Left Margin incl additional required for right align
							
							if(defaults.fullWidth == true){
								params = {left: 0};
							} else {
								
								var ml = mr < ml ? ml + ml - mr : (totiw - pw)/2;
								var subLeft = pl - ml;

								// If Left Position Is Negative Set To Left Margin
								var params = {left: pl+'px', marginLeft: -ml+'px'};
								
								if(subLeft < 0){
									params = {left: 0};
								}else if(mr < ml){
									params = {right: 0};
								}
							}
							$('.'+clContainer,this).css(params);
							
							// Calculate Row Height
							$('.row',$mainSub).each(function(){
								var rh = $(this).height();
								$('.mega-unit',this).css({height: rh+'px'});
								$(this).parent('.row').css({height: rh+'px'});
							});
							$mainSub.hide();
					
						} else {
							$('.'+clContainer,this).addClass('non-mega').css('left',pl+'px');
						}
					}
				});

				// Set position of mega dropdown to bottom of main menu
				var menuHeight = $('> li > a',$dcMegaMenuObj).outerHeight(true);
				$('.'+clContainer,$dcMegaMenuObj).css({top: menuHeight+'px'}).css('z-index','1000');
				
				if(defaults.event == 'hover'){
					// HoverIntent Configuration
					var config = {
						sensitivity: 2,
						interval: 100,
						over: megaOver,
						timeout: 400,
						out: megaOut
					};
					$('li',$dcMegaMenuObj).hoverIntent(config);
				}
				
				if(defaults.event == 'click'){
				
					$('body').mouseup(function(e){
						if(!$(e.target).parents('.mega-hover').length){
							megaReset();
						}
					});


				}
				
				// onLoad callback;
				defaults.onLoad.call(this);
			}
		});
	};
})(jQuery);

$(document).ready(function($){
        $('#mega-menu').dcMegaMenu({
		speed: 'fast',
		effect: 'slide'
        });

	$('#mega-1').dcVerticalMegaMenu({
		speed: 'fast',
		effect: 'slide',
		direction: 'right'
	});
});




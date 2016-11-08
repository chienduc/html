/////////////tabs /////////////
var ShowHideTab = new Class({
	initialize: function(elm, options){
		var container = $(elm);
		if(!elm){
			return false;
		}
		
		var items = container.getElements('ul.tabs li'),
			contents = container.getElements('.tableContent'),
			active = 0;
			
		for(var i = 0; i < items.length; i++){
			if(items[i].hasClass('current')){
				active = i;
				break;
			}
		}
			
		items.each(function(item, index){
			item.addEvent('click', function(e){
				if(e){
					e.stop();
				}
				
				if(active != index){
					items[active].removeClass('current');
					contents[active].addClass('hidden');	
					
					active = index;
					
					items[active].addClass('current');
					contents[active].removeClass('hidden');	
				}			
			});
		});	
	}	
});

/**************/
var SMLayerFix = new Class({	
	Implements: [Options, Events],	
	options: {		
		zIndex: 19999,
		opacity: 0.4,
		paddingTop: 0,
		closeButtonClass: 'close',
		timeOut: false
	},	
	initialize: function(selector, elmCoord, options){		
		this.setOptions(options);
		this.setup($(selector), elmCoord);
	},	
	setup: function(selector, elmCoord){
		if(!selector) return;
		var that = this;
		var overlay = $('overlay');
		if(!overlay){
			overlay = new Element('div', {
				'styles':{
					'display': 'block',
					'visibility': 'visible',
					'position': 'absolute',
					'top': 0,
					'left': 0,
					'width': window.getScrollSize().x,
					'height': window.getScrollSize().y,
					'zIndex': that.options.zIndex,
					'backgroundColor': '#000',
					'opacity': 0
				}
			}).inject(document.body);	
		}
		overlay.store('fx', new Fx.Tween(overlay, {
			property: 'opacity',
			duration: (Browser.Engine.trident? 200 : 350)
		}));
		selector.inject(overlay, 'after');
		
		var showCoord = elmCoord.getCoordinates();
		var layerSize = selector.getSize();
		var winSize = window.getSize();
		var top = (window.getHeight() - selector.getCoordinates().height)/2
		var left = showCoord.left;
		var left = (window.getWidth() - selector.getCoordinates().width)/2
		selector.setStyles({
			'opacity': 0,
			'position': 'absolute',
			'top': Math.max(0, Math.max(top, window.getScrollTop())),
			'left': left,
			'zIndex': that.options.zIndex + 1
		});
		selector.store('fx', new Fx.Tween(selector, {
			property: 'opacity',
			duration: 350			
		}));
		that.fireEvent('show', selector);
		
		if(Browser.Engine.trident){
			selector.retrieve('fx').set(1);
		}else{
			selector.retrieve('fx').start(1);
		}
		overlay.retrieve('fx').start(that.options.opacity);

		var closeBtn = selector.getElement('.' + that.options.closeButtonClass);
		if(closeBtn){
			closeBtn.removeEvents('click').addEvent('click', function(e){
				that.fireEvent('hide', selector);
				if(e) e.stop();
				
				if(Browser.Engine.trident){
					selector.retrieve('fx').set(0);
					selector.setStyle('top', -5000);
				}else{
					selector.retrieve('fx').start(0).chain(function(){
						selector.setStyle('top', -5000);
					});
				}
					
					overlay.retrieve('fx').start(0).chain(function(){
					overlay.destroy();
				});
			});
			
			if(that.options.timeOut){
				setTimeout(function(){
					closeBtn.fireEvent('click');
				}, that.options.timeOut);
			}
		}
	}
});


function initPopupBill(){
var relativeLink = '';
var permanentLink = '';
if($('valueRelative')) relativeLink = $('valueRelative').value;
if($('valuePermanent')) permanentLink = $('valuePermanent').value;
var listIcons = $$('span.check');
 var popups = $(document.body).getChildren('.popup2');
 var html = '';
  listIcons.each(function(icon, idx){
   icon.removeEvents('click').addEvent('click', function(e){
    if(e){
     e.stop();
    }
    if($('valueRelative')) $('valueRelative').value  =  relativeLink + icon.getElement('a').getProperty('rel') + html;
	if($('valuePermanent')) $('valuePermanent').value  = permanentLink + icon.getElement('a').getProperty('rel') + html;
    new SMLayerFix(popups[0], this);
    
   });
  });
}

//////////////////
window.addEvent('domready', function(){	
	initPopupBill();
	//new ShowHideTab('tabContent');
});
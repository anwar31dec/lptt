/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
 	
    jQuery('body').on('click','.azuraAddElementPage', function(event) {
        event.preventDefault();
        SqueezeBox.initialize({});
        SqueezeBox.open('index.php?option=com_azurapagebuilder&view=elements&topage=1&tmpl=component', {
            handler: 'iframe',
            size: {x: 890, y: 390}
        });
    });
    jQuery('body').on('click','.azuraAddElement', function(event) {
		event.preventDefault();
		jQuery('.current-adding-element').removeClass('current-adding-element');
		jQuery(this).parent().parent().addClass('current-adding-element');
		SqueezeBox.initialize({});
		SqueezeBox.open('index.php?option=com_azurapagebuilder&view=elements&topage=0&tmpl=component', {
			handler: 'iframe',
			size: {x: 890, y: 390}
		});
	});
	function azuraAddElement(data,topage){
        if(topage == '1'){
            jQuery('.azura-elements-page').append(decodeURIComponent(data));
        }else{
            jQuery('.current-adding-element').children().children('.elementchildren').append(decodeURIComponent(data));
            jQuery('.current-adding-element').removeClass('current-adding-element');
        }
		
		SqueezeBox.close();
	}

	function addSortable(){
		jQuery( ".azura-sortable" ).sortable({
			connectWith:".azura-sortable",
			appendTo: "body",
			placeholder: 'placeholder',
		    revert: true,
		    receive: function(event, ui) {
	      
	        },
	        start: function( event, ui ) {
	        	
	        },
	        stop: function( event, ui ) {
	     		addSortable();
	        }
		});
	}

  jQuery(function() {

	addSortable();

   	jQuery('body').on('click','.azura-element-tools-remove',function(event){
   		event.stopPropagation();
    	event.preventDefault();

    	var el = jQuery(this).parent().parent().parent();
    	el.remove();
   	});

    jQuery('body').on('click', '.masterAddSlider', function(event){
        event.stopPropagation();
        event.preventDefault();

        var itemHtml = '<div style="display: block; " class="azp_col-md-3 azura-element-block" data-typename="AzuraMasterSliderItem">';
            itemHtml += '<div class="width100 azura-element azura-element-type-masterslider azura-element-type-azuramasterslideritem" data-typename="AzuraMasterSliderItem">';

                itemHtml += '<span class="azura-element-title"><i class="fa fa-sliders"></i>  Slide</span>';

                itemHtml += '<div class="azura-element-tools">';
                    itemHtml += '<i class="fa fa-eye azura-element-tools-showhide"></i>';
                    itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
                    itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
                    itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
                itemHtml += '</div>';

            itemHtml += '</div>';

            itemHtml += '<div class="azura-element-type-azuramasterslideritem-container">';
                itemHtml +='<div class="azura-sortable elementchildren clearfix">';
                itemHtml +='</div>';
            itemHtml += '</div>';

            itemHtml +='<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"   title="Add element to slide" style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
            itemHtml += '<!-- /.azura-element-type-azuramasterslideritem-container -->';
            itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraMasterSliderItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
        itemHtml += '</div>';
        jQuery(this).closest('.azura-sortable').append(itemHtml);
    });

    jQuery('body').on('click', '.flexAddSlider', function(event){
        event.stopPropagation();
        event.preventDefault();

        var itemHtml = '<div style="display: block; " class="azp_col-md-6 azura-element-block" data-typename="AzuraFlexSliderItem">';
            itemHtml += '<div class="width100 azura-element azura-element-type-flexslider azura-element-type-azuraflexslideritem" data-typename="AzuraFlexSliderItem">';

                itemHtml += '<span class="azura-element-title"><i class="fa fa-sliders"></i>  Slide</span>';

                itemHtml += '<div class="azura-element-tools">';
                    itemHtml += '<i class="fa fa-eye azura-element-tools-showhide"></i>';
                    itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
                    itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
                    itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
                itemHtml += '</div>';

            itemHtml += '</div>';

            itemHtml += '<div class="azura-element-type-azurabxslideritem-container">';
                itemHtml +='<div class="azura-sortable elementchildren clearfix">';
                itemHtml +='</div>';
            itemHtml += '</div>';

            itemHtml +='<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"   title="Add element to slide" style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
            itemHtml += '<!-- /.azura-element-type-azuracarouselslideritem-container -->';
            itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraFlexSliderItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
        itemHtml += '</div>';
        jQuery(this).closest('.azura-sortable').append(itemHtml);
    });

    jQuery('body').on('click', '.bxAddSlider', function(event){
        event.stopPropagation();
        event.preventDefault();

        var itemHtml = '<div style="display: block; " class="azp_col-md-6 azura-element-block" data-typename="AzuraBxSliderItem">';
    itemHtml += '<div class="width100 azura-element azura-element-type-column azura-element-type-azurabxslideritem" data-typename="AzuraBxSliderItem">';

            itemHtml += '<span class="azura-element-title"><i class="fa fa-sliders"></i>  Slide</span>';

        itemHtml += '<div class="azura-element-tools">';
            itemHtml += '<i class="fa fa-eye azura-element-tools-showhide"></i>';
            itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
            itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
            itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
        itemHtml += '</div>';

    itemHtml += '</div>';

    itemHtml += '<div class="azura-element-type-azurabxslideritem-container">';
        itemHtml +='<div class="azura-sortable elementchildren clearfix">';
        itemHtml +='</div>';
    itemHtml += '</div>';

    itemHtml +='<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"   title="Add element to slide" style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
    itemHtml += '<!-- /.azura-element-type-azuracarouselslideritem-container -->';
    itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraBxSliderItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
itemHtml += '</div>';
        jQuery(this).closest('.azura-sortable').append(itemHtml);
    });

    jQuery('body').on('click', '.bscarouselAddSlider', function(event){
        event.stopPropagation();
        event.preventDefault();

        var itemHtml = '<div style="display: block; " class="azp_col-md-4 azura-element-block" data-typename="AzuraBsCarouselItem">';
    itemHtml += '<div class="width100 azura-element azura-element-type-column azura-element-type-azurabscarouselitem" data-typename="AzuraBsCarouselItem">';

            itemHtml += '<span class="azura-element-title"><i class="fa fa-sliders"></i>  Slide</span>';

        itemHtml += '<div class="azura-element-tools">';
            itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
            itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
            itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
        itemHtml += '</div>';

    itemHtml += '</div>';

    itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraBsCarouselItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
itemHtml += '</div>';
        jQuery(this).closest('.azura-sortable').append(itemHtml);
    });

	jQuery('body').on('click', '.carouselAddSlider', function(event){
		event.stopPropagation();
		event.preventDefault();

		var itemHtml = '<div style="display: block; " class="azp_col-md-6 azura-element-block" data-typename="AzuraCarouselSliderItem">';
	itemHtml += '<div class="width100 azura-element azura-element-type-column azura-element-type-azuracarouselslideritem" data-typename="AzuraCarouselSliderItem">';

			itemHtml += '<span class="azura-element-title"><i class="fa fa-sliders"></i>  Slide</span>';

		itemHtml += '<div class="azura-element-tools">';
			itemHtml += '<i class="fa fa-eye azura-element-tools-showhide"></i>';
			itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
			itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
			itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
		itemHtml += '</div>';

	itemHtml += '</div>';

	itemHtml += '<div class="azura-element-type-azuracarouselslideritem-container">';
		itemHtml +='<div class="azura-sortable elementchildren clearfix">';
        itemHtml +='</div>';
	itemHtml += '</div>';

    itemHtml +='<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"   title="Add element to slide" style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
	itemHtml += '<!-- /.azura-element-type-azuracarouselslideritem-container -->';
	itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraCarouselSliderItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
itemHtml += '</div>';
		jQuery(this).closest('.azura-sortable').append(itemHtml);
	});

	jQuery('body').on('click', '.servicesAddSlider', function(event){
		event.stopPropagation();
		event.preventDefault();
		var itemHtml = '<div style="display: block;" class="azp_col-md-4 azura-element-block" data-typename="AzuraServicesSliderItem">';
			itemHtml += '<div class="span12 azura-element azura-element-type-column azura-element-type-azuraservicesslideritem" data-typename="AzuraServicesSliderItem">';
					itemHtml += '<span class="azura-element-title"><i class="fa fa-cogs"></i>  Service</span>';
				itemHtml += '<div class="azura-element-tools">';
					itemHtml += '<i class="fa fa-eye azura-element-tools-showhide"></i>';
					itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
					itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
					itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
				itemHtml += '</div>';

			itemHtml += '</div>';

			itemHtml += '<div class="azura-element-type-azuraservicesslideritem-container">';
				itemHtml +='<div class="azura-sortable elementchildren clearfix">';
                itemHtml +='</div>';
			itemHtml += '</div>';

            itemHtml +='<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"   title="Add element to service" style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
			itemHtml += '<!-- /.azura-element-type-azuraservicesslideritem-container -->';
			itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraServicesSliderItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
		itemHtml += '</div>';

		jQuery(this).closest('.azura-sortable').append(itemHtml);
	});

	jQuery('body').on('click', '.teamAddMember', function(event){
		event.stopPropagation();
		event.preventDefault();

		var itemHtml = '<div style="display: block;" class="azp_col-md-4 azura-element-block ui-draggable ui-draggable-handle" data-typename="AzuraTeamMember">';
	itemHtml += '<div class="width100 azura-element azura-element-type-column azura-element-type-azuracarouselslideritem" data-typename="AzuraTeamMember">';

			itemHtml += '<span class="azura-element-title"><i class="fa fa-users"></i>  Member</span>';

		itemHtml += '<div class="azura-element-tools">';
			itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
			itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
			itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
		itemHtml += '</div>';

	itemHtml += '</div>';
	itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraTeamMember","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
itemHtml += '</div>';
		jQuery(this).closest('.azura-sortable').append(itemHtml);
	});

    jQuery('body').on('click', '.superAddSlider', function(event){
        event.stopPropagation();
        event.preventDefault();

        var itemHtml = '<div style="display: block;" class="azp_col-md-4 azura-element-block" data-typename="AzuraSuperSlidesItem">';
                itemHtml += '<div class="width100 azura-element azura-element-type-column azura-element-type-azurasuperslidesitem" data-typename="AzuraSuperSlidesItem">';

                        itemHtml += '<span class="azura-element-title"><i class="fa fa-sliders"></i>  Slide</span>';

                    itemHtml += '<div class="azura-element-tools">';
                        itemHtml += '<i class="fa fa-eye azura-element-tools-showhide"></i>';
                        itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
                        itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
                        itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
                    itemHtml += '</div>';

                itemHtml += '</div>';

                itemHtml += '<div class="azura-element-type-azurasuperslidesitem-container">';
                    itemHtml +='<div class="azura-sortable elementchildren clearfix">';
                    itemHtml +='</div>';
                itemHtml += '</div>';
                itemHtml +='<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"   title="Add element to slide" style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
                itemHtml += '<!-- /.azura-element-type-azurasuperslidesitem-container -->';
                itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraSuperSlidesItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
            itemHtml += '</div>';
        jQuery(this).closest('.azura-sortable').append(itemHtml);
    });
    jQuery('body').on('click', '.accordionAddItem', function(event){
        event.stopPropagation();
        event.preventDefault();


        var itemHtml = '<div style="display: block;" class="width100 azura-element-block" data-typename="AzuraAccordionItem">';
    itemHtml += '<div class="width100 azura-element azura-element-type-column azura-element-type-azuraaccordionitem" data-typename="AzuraAccordionItem">';

            itemHtml += '<span class="azura-element-title"><i class="fa fa-cogs"></i>  Accordion Item</span>';

        itemHtml += '<div class="azura-element-tools">';
            itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
            itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
            itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
        itemHtml += '</div>';

    itemHtml += '</div>';
    itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraAccordionItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
itemHtml += '</div>';
        jQuery(this).closest('.azura-sortable').append(itemHtml);
    });

    jQuery('body').on('click', '.tabToggleAddItem', function(event){
        event.stopPropagation();
        event.preventDefault();


        var itemHtml = '<div style="display: block;" class="width100 azura-element-block" data-typename="AzuraTabToggleItem">';
    itemHtml += '<div class="width100 azura-element azura-element-type-azuratabtoggleitem" data-typename="AzuraTabToggleItem">';

            itemHtml += '<span class="azura-element-title"><i class="fa fa-cube"></i>  Tab & Toggle Item</span>';

        itemHtml += '<div class="azura-element-tools">';
            itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
            itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
            itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
        itemHtml += '</div>';

    itemHtml += '</div>';
    itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraTabToggleItem","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
itemHtml += '</div>';
        jQuery(this).closest('.azura-sortable').append(itemHtml);
    });

    jQuery('body').on('click', '.socialAddButton', function(event){
        event.stopPropagation();
        event.preventDefault();

        var itemHtml = '<div style="display: block;" class="azp_col-md-6 azura-element-block" data-typename="AzuraSocialButtonsButton">';
    itemHtml += '<div class="width100 azura-element azura-element-type-column azura-element-type-azuracarouselslideritem" data-typename="AzuraSocialButtonsButton">';

            itemHtml += '<span class="azura-element-title"><i class="fa fa-comments-o"></i>  Button</span>';

        itemHtml += '<div class="azura-element-tools">';
            itemHtml += '<i class="fa fa-edit azura-element-tools-configs"></i>';
            itemHtml += '<i class="fa fa-copy azura-element-tools-copy"></i>';
            itemHtml += '<i class="fa fa-times azura-element-tools-remove"></i>';
        itemHtml += '</div>';

    itemHtml += '</div>';
    itemHtml += '<div class="azura-element-settings-saved saved" data="'+encodeURIComponent('{"type":"AzuraSocialButtonsButton","id": "0", "published": "1", "language": "*", "content":"","attrs":{}}')+'"></div>';
itemHtml += '</div>';
        jQuery(this).closest('.azura-sortable').append(itemHtml);
    });

    jQuery('body').on('click', '.azura-element-tools-configs', function(event) {
    	event.stopPropagation();
    	event.preventDefault();

    	jQuery('.azura-element-block.current-editing').removeClass('current-editing');

    	var parent = jQuery(this).parent().parent().parent();

    	var type = parent.attr('data-typeName');

    	var height = 600;
    	var width = 600;
        if(type == 'AzuraHtml'){
            width = 1000;
        }

    	parent.addClass('current-editing');


		var elementData = parent.children('.azura-element-settings-saved').attr('data');

        var dataObject = decodeURIComponent(elementData);
            dataObject = jQuery.parseJSON(dataObject);
            dataObject.content = encodeURIComponent(dataObject.content);
            dataObject.shortcode = '';

            elementData = JSON.stringify(dataObject);
            elementData = encodeURIComponent(elementData);


		/*jQuery.fancybox.open({
    		maxWidth	: 1000,
			maxHeight	: 600,
			fitToView	: true,
			width		: width,
			height		: height,
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none',
			type:'ajax',
			href: window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.config&eletype='+type.toLowerCase()+'&data='+elementData,
			closeBtn : false,
			helpers: {
				overlay : {
					closeClick : false,
					locked : false
				}
			}


    	});*/

        var action = window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.config';//&eletype='+type.toLowerCase()+'&data='+elementData,
            
        $.post(
            action,
            {
                eletype : type.toLowerCase(),
                data : elementData
            },
            function(data){
                jQuery.fancybox.open({
                    maxWidth    : 1000,
                    maxHeight   : 600,
                    fitToView   : true,
                    width       : width,
                    height      : height,
                    autoSize    : false,
                    closeClick  : false,
                    openEffect  : 'none',
                    closeEffect : 'none',
                    //type:'ajax',
                    //href: window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.config&eletype='+type.toLowerCase()+'&data='+elementData,
                    type : 'html',
                    content: data,
                    closeBtn : false,
                    helpers: {
                        overlay : {
                            closeClick : false,
                            locked : false
                        }
                    }


                });
            },

            'html'

        ).fail(function(err){

            //$("#result").hide().html('<div class="error">'+err.msg+'</div>').fadeIn(1500)
        
        });


    });
	
	function copyChild(parent){
		if(parent.children().children().is('.azura-sortable')){
		 	var context = parent.children().children('.azura-sortable');
		 	jQuery('>.azura-element-block', context).each(function(){
		 		var parent = jQuery(this);

		 		var elementData = parent.children('.azura-element-settings-saved');
		 		var elementDataObject =  JSON.parse(decodeURIComponent(elementData.attr('data')));

		 		elementDataObject.id = 0;

		 		elementData.attr('data', encodeURIComponent(JSON.stringify(elementDataObject)));

		 		copyChild(parent);

		 	});
		}

 		var elementData = parent.children('.azura-element-settings-saved');

 		var elementDataObject =  JSON.parse(decodeURIComponent(elementData.attr('data')));

 		elementDataObject.id = 0;

 		elementData.attr('data', encodeURIComponent(JSON.stringify(elementDataObject)));

	}

	jQuery('body').on('click', '.azura-element-tools-copy', function(event) {
    	event.stopPropagation();
    	event.preventDefault();

    	var parent = jQuery(this).parent().parent().parent();

    	parent.after(parent.outerHTML());

    	var tempElement = parent.next();
    	copyChild(tempElement);

        addSortable();

    });

	jQuery('body').on('click', '.azura-element-tools-showhide', function(event) {
        event.stopPropagation();
        event.preventDefault();
        var $this = jQuery(this);
        if($this.is('.ishide')){
            $this.removeClass('ishide');
            $this.parent().parent().parent().children().children('.elementchildren').removeClass('ishide');
            $this.parent().parent().parent().children('.azuraAddElementWrapper').removeClass('ishide');

            $this.parent().parent().parent().find('.elementchildren,.azura-element-tools-showhide,.azuraAddElementWrapper').each(function(){
                if(jQuery(this).is('.ishide')){
                    jQuery(this).removeClass('ishide');
                }
            });
        }else{
            $this.addClass('ishide');
            $this.parent().parent().parent().children().children('.elementchildren').addClass('ishide');
            $this.parent().parent().parent().children('.azuraAddElementWrapper').addClass('ishide');

            $this.parent().parent().parent().find('.elementchildren,.azura-element-tools-showhide,.azuraAddElementWrapper').each(function(){
                if(!jQuery(this).is('.ishide')){
                    jQuery(this).addClass('ishide');
                }
            });
        }
    });

    jQuery('body').on('click', '.azura-element-tools-levelup', function(event) {
    	event.stopPropagation();
    	event.preventDefault();
    	var $this = jQuery(this);
    	var $parentBlock = $this.parent().parent().parent();
    	if($parentBlock.parent().is('.elementchildren')){
    		$parentBlock.parent().parent().parent().before($parentBlock.outerHTML());
    		$parentBlock.remove();
    	}
    });


    jQuery('body').on('click', '.azura-setting-btn-cancel', function(event) {
    	event.stopPropagation();
    	event.preventDefault();

    	jQuery.fancybox.close();

    });

    function parseLayout(layout){
        var tu = layout.substr(0,1);
        var mau = layout.substr(1,1);
        var col_layout = 'azp_col-md-12';
        switch (mau){
            case '2': 
                if(tu == '1'){
                    col_layout = 'azp_col-md-6';
                }
                break;
            case '3':
                if(tu == '1'){
                    col_layout = 'azp_col-md-4';
                }else if(tu == '2'){
                    col_layout = 'azp_col-md-8';
                }
                break;
            case '4':
                if(tu == '1'){
                    col_layout = 'azp_col-md-3';
                }else if(tu == '2'){
                    col_layout = 'azp_col-md-6';
                }else if(tu == '3'){
                    col_layout = 'azp_col-md-9';
                }
                break;
            case '6':
                if(tu == '1'){
                    col_layout = 'azp_col-md-2';
                }else if(tu == '2'){
                    col_layout = 'azp_col-md-4';
                }else if(tu == '3'){
                    col_layout = 'azp_col-md-6';
                }else if(tu == '4'){
                    col_layout = 'azp_col-md-8';
                }else if(tu == '5'){
                    col_layout = 'azp_col-md-10';
                }
                break;
        }

        return col_layout;
    }

    function parseCustomLayout(layout){
        layout = layout.split("/");
        if(layout.length == 2){
            var tu = layout[0];
            var mau = layout[1];
            var col_layout = 'azp_col-md-12';
            switch (mau){
                case '2': 
                    if(tu == '1'){
                        col_layout = 'azp_col-md-6';
                    }
                    break;
                case '3':
                    if(tu == '1'){
                        col_layout = 'azp_col-md-4';
                    }else if(tu == '2'){
                        col_layout = 'azp_col-md-8';
                    }
                    break;
                case '4':
                    if(tu == '1'){
                        col_layout = 'azp_col-md-3';
                    }else if(tu == '2'){
                        col_layout = 'azp_col-md-6';
                    }else if(tu == '3'){
                        col_layout = 'azp_col-md-9';
                    }
                    break;
                case '5':
                    if(tu == '1'){
                        col_layout = 'azp_col-md-15';
                    }else if(tu == '2'){
                        col_layout = 'azp_col-md-25';
                    }else if(tu == '3'){
                        col_layout = 'azp_col-md-35';
                    }else if(tu == '4'){
                        col_layout = 'azp_col-md-45';
                    }
                    break;
                case '6':
                    if(tu == '1'){
                        col_layout = 'azp_col-md-2';
                    }else if(tu == '2'){
                        col_layout = 'azp_col-md-4';
                    }else if(tu == '3'){
                        col_layout = 'azp_col-md-6';
                    }else if(tu == '4'){
                        col_layout = 'azp_col-md-8';
                    }else if(tu == '5'){
                        col_layout = 'azp_col-md-10';
                    }
                    break;
                case '12':
                    if(tu < 12){
                        col_layout = 'azp_col-md-'+tu;
                    }

                    break;
            }

            return col_layout;             
        }
        
    }

    function addColumnEle(parent, layout,restored){
        var col_layout = parseLayout(layout);
        var html = '<div class="'+col_layout+' azura-element-block" data-typeName="AzuraColumn">';
            html += '<div class="width100 azura-element azura-element-type-azuracolumn" data-typeName="AzuraColumn">';

                    html += '<span class="azura-element-title"><i class="fa fa-columns"></i>  Column</span>';

                html += '<div class="azura-element-tools">';
                    html += '<i class="fa fa-eye azura-element-tools-showhide"></i>';
                    html += '<i class="fa fa-edit azura-element-tools-configs"></i>';
                    html += '<i class="fa fa-times azura-element-tools-remove"></i>';
                html += '</div>';

            html += '</div>';

            html += '<div class="azura-element-type-azuracolumn-container">';
                html += '<div class="azura-sortable  elementchildren clearfix" >';
                if(restored != undefined){

                    html += restored;
                }
                html += '</div>';
            html += '</div>';
            
            html += '<!-- /.azura-element-type-azurarow-container -->';
            html += '<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"  style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
            
            html += '<div class="azura-element-settings-saved saved" data="'+ encodeURIComponent('{"type":"AzuraColumn","id": "0","published":"1","language":"*", "content":"","attrs":{"columnwidthclass": "'+col_layout+'"}}')+'"></div>';
        html += '</div>';

        parent.append(html);
    }

    function addCustomColumnEle(parent, layout,restored){
        var col_layout = parseCustomLayout(layout);
        var html = '<div class="'+col_layout+' azura-element-block" data-typeName="AzuraColumn">';
            html += '<div class="width100 azura-element azura-element-type-azuracolumn" data-typeName="AzuraColumn">';

                    html += '<span class="azura-element-title"><i class="fa fa-columns"></i>  Column</span>';

                html += '<div class="azura-element-tools">';
                    html += '<i class="fa fa-eye azura-element-tools-showhide"></i>';
                    html += '<i class="fa fa-edit azura-element-tools-configs"></i>';
                    html += '<i class="fa fa-times azura-element-tools-remove"></i>';
                html += '</div>';

            html += '</div>';

            html += '<div class="azura-element-type-azuracolumn-container">';
                html += '<div class="azura-sortable  elementchildren clearfix" >';
                if(restored != undefined){

                    html += restored;
                }
                html += '</div>';
            html += '</div>';
            
            html += '<!-- /.azura-element-type-azurarow-container -->';
            html += '<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"  style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
            
            html += '<div class="azura-element-settings-saved saved" data="'+ encodeURIComponent('{"type":"AzuraColumn","id": "0","published":"1","language":"*", "content":"","attrs":{"columnwidthclass": "'+col_layout+'"}}')+'"></div>';
        html += '</div>';

        parent.append(html);
    }

    function restoreCol(parent){
        var restored = new Array();
        var index = 0;
        jQuery('>.azura-element-block', parent).each(function(){
            restored[index] = jQuery(this).children().children('.elementchildren').html();
            index++;
        });
        return restored;
    }

    jQuery('body').on('click', '.set-width', function(event) {
        event.stopPropagation();
        event.preventDefault();

        jQuery('.set-width.azura-active').removeClass('azura-active');
        jQuery(this).addClass('azura-active');

        var rowContainer = jQuery(this).parent().parent().parent().children().children('.elementchildren');

        var layout = jQuery(this).attr('data-layout').trim();

        layout = layout.split("_");

        var restored = restoreCol(rowContainer);

        rowContainer.html('');

        for (index = 0; index < layout.length; ++index) {
            addColumnEle(rowContainer,layout[index],restored[index]);
        }
    });

    jQuery('body').on('click','.set-width-custom-button', function(event){
        event.stopPropagation();
        event.preventDefault();

        var rowContainer = jQuery(this).parent().parent().parent().parent().children().children('.elementchildren');

        var layout = jQuery(this).parent().children('.set-width-column').val();

        layout = layout.split("+");

        var restored = restoreCol(rowContainer);

        rowContainer.html('');

        for (index = 0; index < layout.length; ++index) {
            addCustomColumnEle(rowContainer,layout[index],restored[index]);
        }

    });

    

    jQuery('body').on('click', '.azura-setting-btn-save', function(event) {
    	event.stopPropagation();
    	event.preventDefault();
    	var parent = jQuery(this).parent().parent();

    	var elementName = parent.find('input[name="elementName"]').val();

    	var elementPublished = parent.find('input[name="elementPubLang[published]"]:checked').val();

    	var elementCurrentEditing = jQuery('.azura-element-block.current-editing');

    	var elementCurrentEditingType = elementCurrentEditing.children('.azura-element').attr('data-typeName');

    	var elementData = elementCurrentEditing.children('.azura-element-settings-saved');

    	var elementDataObject = JSON.parse(decodeURIComponent(elementData.attr('data')));

    	elementDataObject.name = elementName;
    	elementDataObject.published = elementPublished; console.log(elementPublished);

        if(elementCurrentEditingType == 'AzuraHtml'){
            AzuraHtmlSetting(parent, elementDataObject);
        }else{
            autoSaveElement(elementDataObject,parent);
        }

    	elementData.attr('data', encodeURIComponent(JSON.stringify(elementDataObject)));

    	elementCurrentEditing.removeClass('current-editing');
    	jQuery.fancybox.close();

    });



	function autoSaveElementAttrs(elementDataObject, parent){
        parent.find('[name^="elementAttrs"]').each(function(){
            var attrArr = /elementAttrs\[(.+)\]/g.exec(jQuery(this).attr('name'));
            if(attrArr.length > 1){
                var attrName = attrArr[1];
            }else{
                var attrName = '';
            }

            if(attrName !== ''){
                if(jQuery(this).is('input')){
                    var inputType = jQuery(this).attr('type');
                    if(typeof inputType !== undefined){
                        if(inputType == 'radio'|| inputType == 'checkbox'){
                            elementDataObject.attrs[attrName] = parent.find('input[name="elementAttrs['+attrName+']"]:checked').val();
                        }else{
                            elementDataObject.attrs[attrName] = parent.find('input[name="elementAttrs['+attrName+']"]').val();
                        }
                    }
                }else if(jQuery(this).is('select')){
                    elementDataObject.attrs[attrName] = parent.find('select[name="elementAttrs['+attrName+']"] option:selected').val();
                }else if(jQuery(this).is('textarea')){
                    elementDataObject.attrs[attrName] = parent.find('textarea[name="elementAttrs['+attrName+']"]').val();
                }
            }
            
        });

     
	}

	function autoSaveElementContent(elementDataObject, parent){
        if(parent.find('[name^="elementContent"]').length > 0){
            if(parent.find('[name^="elementContent"]').length === 1){
                var contentEle = parent.find('[name^="elementContent"]').eq(0);
                var contentArr = /elementContent\[(.+)\]/g.exec(contentEle.attr('name'));
                if(contentArr.length > 1){
                    var contentName = contentArr[1];
                }else{
                    var contentName = '';
                }

                if(contentName !== ''){
                    if(contentEle.is('input')){
                        var inputType = contentEle.attr('type');
                        if(typeof inputType !== undefined){
                            if(inputType == 'radio'|| inputType == 'checkbox'){
                                elementDataObject.content = parent.find('input[name="elementContent['+contentName+']"]:checked').val();
                            }else{
                                elementDataObject.content = parent.find('input[name="elementContent['+contentName+']"]').val();
                            }
                        }
                    }else if(contentEle.is('select')){
                        elementDataObject.content = parent.find('select[name="elementContent['+contentName+']"] option:selected').val();
                    }else if(contentEle.is('textarea')){
                        elementDataObject.content = parent.find('textarea[name="elementContent['+contentName+']"]').val();
                    }
                }
            }else{
                elementDataObject.content = {};

                parent.find('[name^="elementContent"]').each(function(){
                    var contentArr = /elementContent\[(.+)\]/g.exec(jQuery(this).attr('name'));
                    if(contentArr.length > 1){
                        var contentName = contentArr[1];
                    }else{
                        var contentName = '';
                    }

                    if(contentName !== ''){
                        if(jQuery(this).is('input')){
                            var inputType = jQuery(this).attr('type');
                            if(typeof inputType !== undefined){
                                if(inputType == 'radio'|| inputType == 'checkbox'){
                                    elementDataObject.content[contentName] = parent.find('input[name="elementContent['+contentName+']"]:checked').val();
                                }else{
                                    elementDataObject.content[contentName] = parent.find('input[name="elementContent['+contentName+']"]').val();
                                }
                            }
                        }else if(jQuery(this).is('select')){
                            elementDataObject.content[contentName] = parent.find('select[name="elementContent['+contentName+']"] option:selected').val();
                        }else if(jQuery(this).is('textarea')){
                            elementDataObject.content[contentName] = parent.find('textarea[name="elementContent['+contentName+']"]').val();
                        }
                    }
                    
                });

            }
        }
        

	}

    function autoSaveElement(elementDataObject, parent){
        autoSaveElementAttrs(elementDataObject, parent);
        autoSaveElementContent(elementDataObject, parent);
    }

	
	function AzuraHtmlSetting(parent, elementDataObject) {
        autoSaveElementAttrs(elementDataObject, parent);
		var html = '';
        html = parent.find('iframe').contents().find('iframe').contents().find('body').html();
		if(!html){
			html = parent.find('iframe').contents().find('textarea#AzuraTextEditor').val();
		}
		elementDataObject.content = html;
	}
	

	function f() {
	  	f.count = ++f.count || 1
	  	return f.count;
	}


	function hasChildRecurse(parent, AzuraElementDatasObject,PageShortcodeArrayObjects,elementsSettingArrayObjects,level){
		 AzuraElementDatasObject.hasChild = 0;
		 AzuraElementDatasObject.level = level;
		 if(parent.children().children().is('.azura-sortable')){
		 	var hasChildID = f();
		 	AzuraElementDatasObject.hasChild = 1;
		 	AzuraElementDatasObject.hasChildID = hasChildID;

		 	PageShortcodeArrayObjects[PageShortcodeArrayObjects.length] = AzuraElementDatasObject;

		 	elementsSettingArrayObjects[elementsSettingArrayObjects.length] = AzuraElementDatasObject;
		 	var context = parent.children().children('.azura-sortable');
            var isHasChild = false;
		 	jQuery('>.azura-element-block', context).each(function(){
		 		var parent = jQuery(this);
		 		var AzuraElementDatas =  decodeURIComponent(parent.children('.azura-element-settings-saved').attr('data'));
		 		var AzuraElementDatasObject = jQuery.parseJSON(AzuraElementDatas);

		 		AzuraElementDatasObject.hasParentID = hasChildID;
		 		
                isHasChild = true;

		 		AzuraElementDatasObject.level = level+1;

		 		hasChildRecurse(parent, AzuraElementDatasObject,PageShortcodeArrayObjects,elementsSettingArrayObjects,level+1);

		 	});
            if(isHasChild){
                level++;
            }
		 }else{
		 	PageShortcodeArrayObjects[PageShortcodeArrayObjects.length] = AzuraElementDatasObject;

		 	elementsSettingArrayObjects[elementsSettingArrayObjects.length] = AzuraElementDatasObject;

		 }
	}

	function savePage(){
		var PageShortcodeArrayObjects = new Array();

		var elementsSettingArrayObjects = new Array();

		jQuery('.azura-sortable.azura-elements-page >.azura-element-block').each(function(index, val) {

			 var $this = jQuery(this);

			 var AzuraElementDatas =  decodeURIComponent($this.children('.azura-element-settings-saved').attr('data')); 

			 var AzuraElementDatasObject = jQuery.parseJSON(AzuraElementDatas);

			 var level = 0;

			 hasChildRecurse($this,AzuraElementDatasObject,PageShortcodeArrayObjects,elementsSettingArrayObjects,level);

		});


		var PageElementsArrayText = new Array();
		var elementsSettingArrayObjectsText = new Array();

		for	(index = 0; index < PageShortcodeArrayObjects.length; ++index) {

			var ElementArrayText = '{ "type" :"'+ PageShortcodeArrayObjects[index].type + '", "id" :"' + PageShortcodeArrayObjects[index].id + '"}';

			PageElementsArrayText[PageElementsArrayText.length] = ElementArrayText;

			elementsSettingArrayObjectsText[elementsSettingArrayObjectsText.length] = JSON.stringify(PageShortcodeArrayObjects[index]);

		}

		PageElementsArrayText = JSON.stringify(PageElementsArrayText);

		//jQuery('#jform_elementsArray').val(encodeURIComponent(PageElementsArrayText));
        jQuery('#jform_elementsArray').val('');
		jQuery('#jform_elementsSettingArray').val(encodeURIComponent(JSON.stringify(elementsSettingArrayObjectsText)));
		var PageShortcodeText = '';
		var ElementsShortcodeArray = new Array();
		for	(index = 0; index < PageShortcodeArrayObjects.length; ++index) {
			var attrsText = '';
			var attrs = PageShortcodeArrayObjects[index].attrs;
			for(var attr in attrs){
				if(attrs.hasOwnProperty(attr)){
			       attrsText += (attr + "=\"" + attrs[attr]+"\" ");
			    }
			}

		    PageShortcodeText += ('['+PageShortcodeArrayObjects[index].type + ' ' + attrsText + ']' + PageShortcodeArrayObjects[index].content + '['+'/'+PageShortcodeArrayObjects[index].type+']');

		    ElementsShortcodeArray[ElementsShortcodeArray.length] = encodeURIComponent('['+PageShortcodeArrayObjects[index].type + ' ' + attrsText + ']' + PageShortcodeArrayObjects[index].content + '['+'/'+PageShortcodeArrayObjects[index].type+']');
		}

		//jQuery('#jform_elementsShortcodeArray').val(encodeURIComponent(JSON.stringify(ElementsShortcodeArray)));
        jQuery('#jform_elementsShortcodeArray').val('');

		//jQuery('#jform_shortcode').val(encodeURIComponent(PageShortcodeText));
        jQuery('#jform_shortcode').val('');
	}

	jQuery('#toolbar-apply button').click(function(event){
		event.preventDefault();
		event.stopPropagation();

		savePage();

		jQuery('#adminForm input[name="task"]').val('page.apply');
		jQuery('#adminForm').submit();
	});

    jQuery('#toolbar-apply button').attr('onclick','');

    jQuery('#toolbar-save button').attr('onclick','');

    jQuery('#toolbar-save-new button').attr('onclick','');

    jQuery('#toolbar-save-copy button').attr('onclick','');


    jQuery('#toolbar-save button').click(function(event){
        event.preventDefault();
        event.stopPropagation();

        savePage();

        jQuery('#adminForm input[name="task"]').val('page.save');
        jQuery('#adminForm').submit();
    });

    jQuery('#toolbar-save-new button').click(function(event){
        event.preventDefault();
        event.stopPropagation();

        savePage();

        jQuery('#adminForm input[name="task"]').val('page.save2new');
        jQuery('#adminForm').submit();
    });

    jQuery('#toolbar-save-copy button').click(function(event){
        event.preventDefault();
        event.stopPropagation();

        savePage();

        jQuery('#adminForm input[name="task"]').val('page.save2copy');
        jQuery('#adminForm').submit();
    });

});
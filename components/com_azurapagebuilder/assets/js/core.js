/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

 function addSortable(){
        jQuery( ".azura-element-content.azurarow" ).sortable({
            items: '> div > .azura-element-content.azuracolumn',
            forcePlaceholderSize: true,
            handle: '.azura-element-move',
            cursor: 'move',
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


 function popupEdit(parent){
    var type = parent.attr('data-type');

    var id = parent.attr('data-id');

    var height = 600;
    var width = 600;
    if(type == 'AzuraHtml'){
        width = 1000;
    }
    jQuery.fancybox('body',{
        beforeShow: function() {
            this.wrap.draggable(
            { 
                handle: ".element_config_header" ,
                cursor: "move"
            });
        },
        maxWidth    : 1000,
        maxHeight   : 600,
        fitToView   : true,
        width       : width,
        height      : height,
        autoSize    : false,
        autoCenter  : false,
        closeClick  : false,
        openEffect  : 'none',
        closeEffect : 'none',
        type:'ajax',
        href: window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.edit&eletype='+type+'&id='+id+'&tmpl=component',
        closeBtn : false,
        helpers: {
            overlay : null 
        }


    });    
 }

 jQuery('body').on('azura.popupedit',function(event, data){
    var parent = jQuery('.azura-element-content.current-editing');
    popupEdit(parent);
 });

 function azuraAddCarouselElement(){
    var html = '<div data-type="AzuraCarouselSliderItem" data-id="0" class="azura-element-content azuracarouselslideritem current-editing isNew"><div class="azura-element-tools azuracarouselslideritem"><span>Slide Item</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
    jQuery('.current-adding-element').append(html);
    jQuery('.current-adding-element').removeClass('current-adding-element');

    jQuery('body').trigger('azura.popupedit',{eletype: 'AzuraCarouselSliderItem'});
 }

 function azuraAddMasterSlideElement(){
    var html = '<div data-type="AzuraMasterSliderItem" data-id="0" class="azura-element-content azuramasterslideritem current-editing isNew"><div class="azura-element-tools azuramasterslideritem"><span>Slide Item</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
    jQuery('.current-adding-element').append(html);
    jQuery('.current-adding-element').removeClass('current-adding-element');

    jQuery('body').trigger('azura.popupedit',{eletype: 'AzuraMasterSliderItem'});
 }

 function azuraAddBxSlideElement(){
    var html = '<div data-type="AzuraBxSliderItem" data-id="0" class="azura-element-content azurabxslideritem current-editing isNew"><div class="azura-element-tools azurabxslideritem"><span>Slide Item</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
    jQuery('.current-adding-element').append(html);
    jQuery('.current-adding-element').removeClass('current-adding-element');

    jQuery('body').trigger('azura.popupedit',{eletype: 'AzuraBxSliderItem'});
 }

 function azuraAddFlexSlideElement(){
    var html = '<div data-type="AzuraFlexSliderItem" data-id="0" class="azura-element-content azuraflexslideritem current-editing isNew"><div class="azura-element-tools azuraflexslideritem"><span>Slide Item</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
    jQuery('.current-adding-element').append(html);
    jQuery('.current-adding-element').removeClass('current-adding-element');

    jQuery('body').trigger('azura.popupedit',{eletype: 'AzuraFlexSliderItem'});
 }

 function azuraAddBsCarouselElement(){
    var html = '<div data-type="AzuraBsCarouselItem" data-id="0" class="azura-element-content azurabscarouselitem current-editing isNew"><div class="azura-element-tools azurabscarouselitem"><span>Slide Item</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
    jQuery('.current-adding-element').append(html);
    jQuery('.current-adding-element').removeClass('current-adding-element');

    jQuery('body').trigger('azura.popupedit',{eletype: 'AzuraBsCarouselItem'});
 }

 function azuraAddAccElement(){
    var html = '<div data-type="AzuraAccordionItem" data-id="0" class="azura-element-content azuraaccordionitem current-editing isNew"><div class="azura-element-tools azuraaccordionitem"><span>Accordion Item</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
    jQuery('.current-adding-element').append(html);
    jQuery('.current-adding-element').removeClass('current-adding-element');

    jQuery('body').trigger('azura.popupedit',{eletype: 'AzuraAccordionItem'});
 }

 function azuraAddTabElement(){
    var html = '<div data-type="AzuraTabToggleItem" data-id="0" class="azura-element-content azuratabtoggleitem current-editing isNew"><div class="azura-element-tools azuratabtoggleitem"><span>Tab Item</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
    jQuery('.current-adding-element').append(html);
    jQuery('.current-adding-element').removeClass('current-adding-element');

    jQuery('body').trigger('azura.popupedit',{eletype: 'AzuraTabToggleItem'});
 }
 function azuraAddElement(data,topage){
    var dataHtml = decodeURIComponent(data);
    var type = jQuery(dataHtml).data('typename');
    if(topage == '1'){
        var html = '<div data-type="'+type+'" data-id="0" class="azura-element-content '+type.toLowerCase()+' current-editing isNew"><div class="azura-element-tools '+type.toLowerCase()+'"><span>'+type.substr(5)+'</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
        jQuery('.azura-elements-page').append(html);

    }else{
        var html = '<div data-type="'+type+'" data-id="0" class="azura-element-content '+type.toLowerCase()+' current-editing isNew"><div class="azura-element-tools '+type.toLowerCase()+'"><span>'+type.substr(5)+'</span><i class="fa fa-edit azura-element-tools-configs"></i><!--<i class="fa fa-times azura-element-tools-remove"></i>--></div></div>';
        jQuery('.current-adding-element').append(html);
        jQuery('.current-adding-element').removeClass('current-adding-element');
    }
        
    SqueezeBox.close();
    jQuery('body').trigger('azura.popupedit',{eletype: type});
 }


    function parseLayout(layout){
        var tu = layout.substr(0,1);
        var mau = layout.substr(1);
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

    function updateAjax(eleObject){
        jQuery.ajax({
            url: window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.updateele',
            type :'POST',
            cache: false,
            async: false,
            dataType:'json',
            data: {'eledata': encodeURIComponent(JSON.stringify(eleObject))},
            success: function(data){
                console.log(data.msg);
            }
        });
    }

    function updateChild(level,haschildid,restored){
        jQuery(restored).find('.azura-element-content').each(function(){
            var eleLevel = parseInt(jQuery(this).attr('data-level'));
            if((eleLevel - level) === 1){
                var eleObject = {}

                eleObject.id = jQuery(this).attr('data-id');
                eleObject.hasParentID = haschildid;

                updateAjax(eleObject);
            }
        });
    }

    function updateShortcode(elementCurrentEditing){
        jQuery.ajax({
            url: window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.reloadEle',
            type :'POST',
            cache: false,
            async: false,
            dataType:'html',
            data: {'id': elementCurrentEditing.attr('data-id')},
            success: function(data){
                if(data === 'false'){
                    alert('There was a error!');
                }else{
                    elementCurrentEditing.after(data);

                    var elementCurrentEditingNext = elementCurrentEditing.next();

                    elementCurrentEditing.remove();
                    
                }
            }
         });
    }

    function addColumnEle(parent, layout,restored){

        var col_layout = parseLayout(layout);

        var colObject = {}

        colObject.id = 0;
        colObject.type = 'AzuraColumn';
        colObject.name = '';
        colObject.pageID = window.pageID;
        colObject.published = 1;

        if(parent.is('.azura-elements-page')){
            colObject.level = 0;
        }else{
            colObject.level = parseInt(parent.attr('data-level'))+1;
            colObject.hasParentID = parent.attr('data-haschildid');
        }

        colObject.attrs = {};

        colObject.hasChild = 1;
        colObject.attrs.columnwidthclass = col_layout;

        jQuery.ajax({
            url: window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.savecol',
            type :'POST',
            cache: false,
            async: false,
            dataType:'json',
            data: {'eledata': encodeURIComponent(JSON.stringify(colObject))},
            success: function(data){
                if(data.info === 'success'){
                    console.log(data.haschildid);
                    if(restored != undefined){
                        updateChild(data.level,data.haschildid, restored);
                    }
                }else{
                    console.log(data.msg);
                }
            }
         });
    }

    function restoreCol(parent){
        var restored = new Array();
        var index = 0;
        var rowLevel = parseInt(parent.attr('data-level'));
        parent.find('.azura-element-content.azuracolumn').each(function(){
            var colLevel = parseInt(jQuery(this).attr('data-level'));
            if((colLevel - rowLevel) === 1){
                restored[index] = jQuery(this).html();
                index++;
            }
        });
        return restored;
    }

    function deleteEle(id){
        if(id > 0){
            jQuery.ajax({
                url: window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.deleteele',
                type :'POST',
                cache: false,
                async: false,
                dataType:'json',
                data: {'id': id},
                success: function(data){
                    if(data.info === 'error'){
                        console.log('There was a error!');
                    }else{
                        return true;
                    }
                }
            });
        }
    }

    function deleteChildren(parent){
        var pLevel = parseInt(parent.attr('data-level'));
        parent.find('.azura-element-content').each(function(){
            var cLevel = parseInt(jQuery(this).attr('data-level'));
            if((cLevel - pLevel) === 1){
                deleteChildren(jQuery(this));
            }
        });
        deleteEle(parent.attr('data-id'));
    }

    function deleteCol(parent){
        var rowLevel = parseInt(parent.attr('data-level'));
        parent.find('.azura-element-content.azuracolumn').each(function(){
            var colLevel = parseInt(jQuery(this).attr('data-level'));
            if((colLevel - rowLevel) === 1){
                var id = jQuery(this).attr('data-id');

                deleteEle(id);
            }
        });
        return;
    }

  jQuery(function($) {


    jQuery('body').on('click', '.set-width', function(event) {
        event.stopPropagation();
        event.preventDefault();

        jQuery('.set-width.azura-active').removeClass('azura-active');
        jQuery(this).addClass('azura-active');

        var rowContainer = jQuery(this).closest('.azura-element-content.azurarow'); 

        var layout = jQuery(this).attr('data-layout').trim();

        layout = layout.split("_");

        var restored = restoreCol(rowContainer);


        deleteCol(rowContainer);

        for (index = 0; index < layout.length; ++index) {
            addColumnEle(rowContainer,layout[index],restored[index]);
        }

        updateShortcode(rowContainer);
    });

    jQuery('body').on('click','.azuraAddElementPage', function(event) {
        event.preventDefault();
        SqueezeBox.initialize({});
        SqueezeBox.open(window.adComBaseUrl+'index.php?option=com_azurapagebuilder&view=elements&topage=1&tmpl=component', {
            handler: 'iframe',
            size: {x: 890, y: 390}
        });
    });

    jQuery('body').on('click','.azuraAddBsCarouselElement', function(event) {
        event.preventDefault();
        jQuery('.current-adding-element').removeClass('current-adding-element');
        jQuery(this).parent().parent().addClass('current-adding-element');
        azuraAddBsCarouselElement();
    });

    jQuery('body').on('click','.azuraAddBxSlideElement', function(event) {
        event.preventDefault();
        jQuery('.current-adding-element').removeClass('current-adding-element');
        jQuery(this).parent().parent().addClass('current-adding-element');
        azuraAddBxSlideElement();
    });

    jQuery('body').on('click','.azuraAddMasterSlideElement', function(event) {
        event.preventDefault();
        jQuery('.current-adding-element').removeClass('current-adding-element');
        jQuery(this).parent().parent().addClass('current-adding-element');
        azuraAddMasterSlideElement();
    });

    jQuery('body').on('click','.azuraAddFlexSlideElement', function(event) {
        event.preventDefault();
        jQuery('.current-adding-element').removeClass('current-adding-element');
        jQuery(this).parent().parent().addClass('current-adding-element');
        azuraAddFlexSlideElement();
    });

    jQuery('body').on('click','.azuraAddCarouselElement', function(event) {
        event.preventDefault();
        jQuery('.current-adding-element').removeClass('current-adding-element');
        jQuery(this).parent().parent().addClass('current-adding-element');
        azuraAddCarouselElement();
    });

    jQuery('body').on('click','.azuraAddAccElement', function(event) {
        event.preventDefault();
        jQuery('.current-adding-element').removeClass('current-adding-element');
        jQuery(this).parent().parent().addClass('current-adding-element');
        azuraAddAccElement();
    });

    jQuery('body').on('click','.azuraAddTabElement', function(event) {
        event.preventDefault();
        jQuery('.current-adding-element').removeClass('current-adding-element');
        jQuery(this).parent().parent().addClass('current-adding-element');
        azuraAddTabElement();
    });

    jQuery('body').on('click','.azuraAddElement', function(event) {
        event.preventDefault();
        jQuery('.current-adding-element').removeClass('current-adding-element');
        jQuery(this).parent().parent().addClass('current-adding-element');
        SqueezeBox.initialize({});
        SqueezeBox.open(window.adComBaseUrl+'index.php?option=com_azurapagebuilder&view=elements&topage=0&tmpl=component', {
            handler: 'iframe',
            size: {x: 890, y: 390}
        });
    });


    jQuery('.azura-element-content').each(function(){
        var $this = jQuery(this);
        var toolsDiv = $this.children('.azura-element-tools');
        var $thisW = $this.width();
        var toolsDivW = toolsDiv.width();
        if($this.is('.azurarow')){

        }else if($this.is('.azuracolumn')){

        }else if($this.is('.azurasection')){

        }else if($this.is('.azuratabtoggle')){

        }else if($this.is('.azuraaccordion')){

        }else if($this.is('.azurabscarousel')){

        }else{
            toolsDiv.css({
                left: $thisW/2 - toolsDivW/2,
            });
        }
    });

    jQuery('body').on('click', '.azura-element-tools-configs', function(event) {
        event.stopPropagation();
        event.preventDefault();

        jQuery('.azura-element-content.current-editing').removeClass('current-editing');

        var parent = jQuery(this).closest('.azura-element-content');

        parent.addClass('current-editing');

        popupEdit(parent);


    });

    jQuery('body').on('click','.azura-element-tools-remove',function(event){
        event.stopPropagation();
        event.preventDefault();

        var el = jQuery(this).parent().parent();

        deleteChildren(el);

        el.remove();
    });

    jQuery('body').on('click', '.azura-setting-btn-cancel', function(event) {
        event.stopPropagation();
        event.preventDefault();

        var curr = jQuery('.azura-element-content.current-editing');
        if(curr.is('.isNew')){
            curr.remove();
        }else{
            curr.removeClass('current-editing');
        }

        jQuery.fancybox.close();

    });
    jQuery('body').on('click', '.azura-setting-btn-save', function(event) {
        event.stopPropagation();
        event.preventDefault();
        var parent = jQuery(this).parent().parent();

        var elementName = parent.find('input[name="elementName"]').val();

        var elementPublished = parent.find('input[name="elementPubLang[published]"]:checked').val();

        var elementCurrentEditing = jQuery('.azura-element-content.current-editing');

        var parentContent = elementCurrentEditing.parent().closest('.azura-element-content');

        var elementCurrentEditingType = elementCurrentEditing.attr('data-type');

        var elementDataObject = {}

        elementDataObject.id = elementCurrentEditing.attr('data-id');
        elementDataObject.type = elementCurrentEditing.attr('data-type');
        elementDataObject.name = elementName;
        elementDataObject.pageID = window.pageID;
        elementDataObject.published = elementPublished;

        if(parentContent.is('.azura-elements-page')){
            elementDataObject.level = 0;
        }else{
            elementDataObject.level = parseInt(parentContent.attr('data-level'))+1;
            elementDataObject.hasParentID = parentContent.attr('data-haschildid');
        }

        elementDataObject.attrs = {};

        switch(elementCurrentEditingType){
            case 'AzuraSection':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraContainer':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraColumn':
                elementDataObject.hasChild = 1;
                elementDataObject.attrs.columnwidthclass= elementCurrentEditing.attr('data-columnwidthclass');
                break;

            case 'AzuraTabToggle':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraRow':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraCarouselSlider':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraCarouselSliderItem':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraTeam':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraServicesSlider':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraServicesSliderItem':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraSuperSlides':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraSuperSlidesItem':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraAccordion':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraSocialButtons':
                elementDataObject.hasChild = 1;
                break;
            case 'AzuraBsCarousel':
            	elementDataObject.hasChild = 1;
                break;
            case 'AzuraBxSlider':
            	elementDataObject.hasChild = 1;
                
                break;
            case 'AzuraBxSliderItem':
            	elementDataObject.hasChild = 1;
                
                break;
        }

        if(elementCurrentEditingType == 'AzuraHtml'){
            AzuraHtmlSetting(parent, elementDataObject);
        }else{
            autoSaveElement(elementDataObject,parent);
        }

        $.ajax({
            url: window.adComBaseUrl+'index.php?option=com_azurapagebuilder&task=element.saveedit',
            type :'POST',
            cache: false,
            async: false,
            dataType:'html',
            data: {'eledata': encodeURIComponent(JSON.stringify(elementDataObject))},
            success: function(data){
                if(data === 'false'){
                    alert('There was a error!');
                }else{
                    elementCurrentEditing.after(data);

                    var elementCurrentEditingNext = elementCurrentEditing.next();

                    elementCurrentEditing.remove();

                    elementCurrentEditingNext.addClass('current-editing');

                    var toolsDiv = elementCurrentEditingNext.children('.azura-element-tools');
                    var $thisW = elementCurrentEditingNext.width();
                    var toolsDivW = toolsDiv.width();
                    
                    if(!elementCurrentEditingNext.is('.azurasection')){
                        toolsDiv.css({
                            left: $thisW/2 - toolsDivW/2,
                        });
                    }
                    
                }
            }
         });

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

});
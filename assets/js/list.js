jQuery(document).ready(function() {
    diamondmain();
    
});

jQuery(window).bind("load", function() {
        jQuery('.testSelAll.SumoUnder').insertAfter(".sumo_diamond_certificates .CaptionCont.SelectBox");
        jQuery('.SlectBox.SumoUnder').insertAfter(".sumo_gemfind_diamond_origin .CaptionCont.SelectBox");
        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    var backvaluefiltermode = getCookie('shopifysavebackvaluefiltermode');
    if(backvaluefiltermode){
        var filtermode =  jQuery('#search-diamonds-form #filtermode').val();
        if(filtermode != backvaluefiltermode){
            jQuery('#navbar #'+backvaluefiltermode+' a').trigger("click");
            jQuery.cookie("shopifysavebackvaluefiltermode", '', {
                path: '/',
                expires: -1
            });
        }
        
    }
});

function diamondmain($){
        jQuery.noConflict();
        var $searchModule = jQuery('#search-diamonds');
        //console.log(jQuery('#search-diamonds-form #baseurl').val());

        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        var filtermode =  jQuery('#search-diamonds-form #filtermode').val();

        var backvaluefiltermode = getCookie('shopifysavebackvaluefiltermode');
        console.log("ajax-filtermode- "+filtermode);
        console.log("ajax-backvaluefiltermode---"+backvaluefiltermode);
        
        if(filtermode == 'navstandard'){
            var backvaluecookie = getCookie('shopifysavebackvalue');    
        }else if(filtermode == 'navfancycolored'){
            var backvaluecookie = getCookie('shopifysavebackvaluefancy'); 
        }else{
            var backvaluecookie = getCookie('shopifysavebackvaluelabgrown');
        }

        
        if(filtermode == 'navfancycolored'){
          var diamondcookiedata = getCookie('savefiltercookiefancy');  
        }else if(filtermode == 'navlabgrown'){
          var diamondcookiedata = getCookie('savefiltercookielabgrown');  
        }else if(filtermode == 'navstandard'){
          var diamondcookiedata = getCookie('shopifysavefiltercookie');  
        }else{
          var diamondcookiedata = '';
        }


        
        
        var searchdiamondform = jQuery('#search-diamonds-form').serialize();
        

        jQuery.ajax({
            url: jQuery('#search-diamonds-form #baseurl').val()+'diamondtools/loadfilter',
            data: {savedfilter:diamondcookiedata,searchformdata:searchdiamondform,savebackvalue:backvaluecookie},
            type: 'POST',
            //dataType: 'json',
            cache: true,
            beforeSend: function(settings) {
                jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
            },
            success: function(response) {
//                console.log(response);
                jQuery('#filter-main-div').html(response);
                jQuery("#search-diamonds-form #submit").trigger("click");
                jQuery('button.accordion').click(function(e) {
                    e.preventDefault();
                    jQuery('button.accordion').toggleClass("active");
                    jQuery('.filter-advanced .panel').css('max-height', '383px');
                    jQuery('.filter-advanced .panel').toggleClass('cls-for-hide');
                });
                
                /*if(jQuery('#filtermode').val() != 'navlabgrown'){*/
                    
                    jQuery('.certificate-div p.select-all').click(function(){
                        
                        if(jQuery('.certificate-div p.select-all').hasClass('partial') && jQuery('.certificate-div p.select-all').hasClass('selected')){
                            jQuery('.certificate-div .selall ul.options li.selected').each(function(){
                                jQuery(this).trigger('click');
                            });    
                            jQuery('.certificate-div .selall ul.options li').each(function(){
                                    jQuery(this).trigger('click');
                            });
                        } else if(!jQuery('.certificate-div p.select-all').hasClass('partial') && jQuery('.certificate-div p.select-all').hasClass('selected')){
                            jQuery('.certificate-div .selall ul.options li').each(function(){
                                jQuery(this).trigger('click');
                            });
                        } else if(jQuery('.certificate-div p.select-all').hasClass('partial') && !jQuery('.certificate-div p.select-all').hasClass('selected')){                        
                            jQuery('.certificate-div .selall ul.options li.selected').each(function(){
                                jQuery(this).trigger('click');
                            });    
                            jQuery('.certificate-div .selall ul.options li').each(function(){
                                    jQuery(this).trigger('click');
                            });
                        } else {
                        jQuery('.certificate-div .selall ul.options li').each(function(){
                            jQuery(this).trigger('click');
                        });
                        }
                    });                

                    //carat slider
                    var carat_slider = jQuery("#noui_carat_slider")[0];
                    //var carat_slider = document.getElementById('noui_carat_slider');
                    var $carat_min_input = jQuery(carat_slider).find("input[data-type='min']");
                    var $carat_max_input = jQuery(carat_slider).find("input[data-type='max']");

                    var $carat_min_val = parseFloat(jQuery(carat_slider).attr('data-min'))
                    var $carat_max_val = parseFloat(jQuery(carat_slider).attr('data-max'));
                    
                    var $start_carat_min = parseFloat($carat_min_input.val());
                    var $start_carat_max = parseFloat($carat_max_input.val());

                    var carat_slider_object = noUiSlider.create(carat_slider, {
                        start: [$start_carat_min, $start_carat_max],
                        //tooltips: [true, wNumb({decimals: 2})],
                        connect: true,
                        step: 0.01,
                        range: {
                            'min': $carat_min_val,
                            'max': $carat_max_val
                        },
                        format: wNumb({
                            decimals: 2,
                            prefix: '',
                            thousand: '',
                        })
                    });
                    carat_slider.noUiSlider.on('update', function( values, handle ) {
                        var carat_value_show = values[handle];
                        if ( handle ) {
                            $carat_max_input.val(carat_value_show);
                        } else {
                            $carat_min_input.val(carat_value_show);
                        }
                    });

					var $carat_input1 = jQuery(carat_slider).find("input.slider-left");
			        var $carat_input2 = jQuery(carat_slider).find("input.slider-right");
			        var carat_inputs = [$carat_input1, $carat_input2];
			        slider_update_textbox(carat_inputs,carat_slider);

                    carat_slider.noUiSlider.on('change', function( values, handle ) {
                        jQuery("#search-diamonds-form #submit").trigger("click");
                    });

                    //Price slider
                    var price_slider = jQuery("#price_slider")[0];
                    var $price_min_input = jQuery(price_slider).find("input[data-type='min']");
                    var $price_max_input = jQuery(price_slider).find("input[data-type='max']");

                    var $price_min_val = parseFloat(jQuery(price_slider).attr('data-min'))
                    var $price_max_val = parseFloat(jQuery(price_slider).attr('data-max'));

                    var $start_price_min = parseFloat($price_min_input.val());
                    var $start_price_max = parseFloat($price_max_input.val());

                    var first_half_interval = 200;
                    var last_half_interval = 2500;
                    
                    if( $price_min_val > 10000 ){
                        var range = {
                            'min': [$price_min_val, first_half_interval],
                            '50%': [10000, last_half_interval],
                            'max': [$price_max_val]
                        }
                    } else {
                        var range = {
                            'min': [$price_min_val, first_half_interval],                    
                            'max': [$price_max_val]
                        }
                    }

                    var price_slider_object = noUiSlider.create(price_slider, {
                        start: [$start_price_min, $start_price_max],
                        //tooltips: [true, wNumb({decimals: 2})],
                        connect: true,
                        step: 1,
                        range: range,
                        format: wNumb({
                            decimals: 0,
                            prefix: '',
                            thousand: ',',
                        })
                    });

                    price_slider.noUiSlider.on('update', function( values, handle ) {
                        var price_value_show = values[handle];
                        if ( handle ) {
                            $price_max_input.val(price_value_show);
                        } else {
                            $price_min_input.val(price_value_show);
                        }
                    });

                    var $price_input1 = jQuery(price_slider).find("input.slider-left");
                    var $price_input2 = jQuery(price_slider).find("input.slider-right");
                    var price_inputs = [$price_input1, $price_input2];
                    slider_update_textbox(price_inputs,price_slider);

                    price_slider.noUiSlider.on('change', function( values, handle ) {
                        jQuery("#search-diamonds-form #submit").trigger("click");
                    });

                    // depth slider 
                    var depth_slider = jQuery("#depth_slider")[0];
                    var $depth_min_input = jQuery(depth_slider).find("input[data-type='min']");
                    var $depth_max_input = jQuery(depth_slider).find("input[data-type='max']");

                    var $depth_min_val = parseFloat(jQuery(depth_slider).attr('data-min'))
                    var $depth_max_val = parseFloat(jQuery(depth_slider).attr('data-max'));

                    var $start_depth_min = parseFloat($depth_min_input.val());
                    var $start_depth_max = parseFloat($depth_max_input.val());

                    var depth_slider_object = noUiSlider.create(depth_slider, {
                        start: [$start_depth_min, $start_depth_max],
                        //tooltips: [true, wNumb({decimals: 2})],
                        connect: true,
                        step: 1,
                        range: {
                            'min': $depth_min_val,
                            'max': $depth_max_val
                        },
                        format: wNumb({
                            decimals: 0,
                            prefix: '',
                            thousand: '',
                        })
                    });

                    depth_slider.noUiSlider.on('update', function( values, handle ) {
                        var depth_value_show = values[handle];
                        if ( handle ) {
                            $depth_max_input.val(depth_value_show);
                        } else {
                            $depth_min_input.val(depth_value_show);
                        }
                    });

                    
                    var $depth_input1 = jQuery(depth_slider).find("input.slider-left");
                    var $depth_input2 = jQuery(depth_slider).find("input.slider-right");
                    var depth_inputs = [$depth_input1, $depth_input2];
                    slider_update_textbox(depth_inputs,depth_slider);

                    depth_slider.noUiSlider.on('change', function( values, handle ) {
                        jQuery("#search-diamonds-form #submit").trigger("click");
                    });
                    
                    var table_slider = jQuery("#tableper_slider")[0];
                    var $table_min_input = jQuery(table_slider).find("input[data-type='min']");
                    var $table_max_input = jQuery(table_slider).find("input[data-type='max']");

                    var $table_min_val = parseFloat(jQuery(table_slider).attr('data-min'))
                    var $table_max_val = parseFloat(jQuery(table_slider).attr('data-max'));

                    var $start_table_min = parseFloat($table_min_input.val());
                    var $start_table_max = parseFloat($table_max_input.val());

                    var table_slider_object = noUiSlider.create(table_slider, {
                        start: [$start_table_min, $start_table_max],
                        //tooltips: [true, wNumb({decimals: 2})],
                        connect: true,
                        step: 1,
                        range: {
                            'min': $table_min_val,
                            'max': $table_max_val
                        },
                        format: wNumb({
                            decimals: 0,
                            prefix: '',
                            thousand: '',
                        })
                    });

                    table_slider.noUiSlider.on('update', function( values, handle ) {
                        var table_value_show = values[handle];
                        if ( handle ) {
                            $table_max_input.val(table_value_show);
                        } else {
                            $table_min_input.val(table_value_show);
                        }
                    });


                    var $table_input1 = jQuery(table_slider).find("input.slider-left");
                    var $table_input2 = jQuery(table_slider).find("input.slider-right");
                    var table_inputs = [$table_input1, $table_input2];
                    slider_update_textbox(table_inputs,table_slider);

                    table_slider.noUiSlider.on('change', function( values, handle ) {
                        jQuery("#search-diamonds-form #submit").trigger("click");
                    });

                    //Custom Slider class
                /*var labelSlider = function($slider, $select) {

                    var self = this;
                    this.slider = $slider;
                    this.select = $select;
                    this.items = $select.children();
                    this.qty = $select.children().length;
                    this.width = 0;
                    this.height = 0;
                    this.start = $select.find('option:selected:first').index();
                    this.end = $select.find('option:selected:last').index() + 1;
                    this.slider.slider({
                        min: 0,
                        max: this.qty,
                        range: true,
                        values: [this.start, this.end],
                        slide: function(e, ui) {
                            if (ui.values[1] - ui.values[0] < 1)
                                return false;
                        },
                        change: function(e, ui) {
                            for (var i = 0; i < self.qty; i++)
                                if (i >= ui.values[0] && i < ui.values[1]) {
                                    self.items.eq(i).attr('selected', 'selected');
                                } else {
                                    self.items.eq(i).removeAttr('selected');
                                }
                        }
                    }).touchit();
                    var options = [];
                    this.items.each(function() {
                        options.push('<b>' + $(this).text() + '</b>')
                    });
                    this.width = 100 / options.length;
                    this.slider.after('<div class="ui-slider-legend"><p class="first" style="width:' + this.width + '%;"><span style=""></span>' +
                        options.join('</p><p style="width:' + this.width + '%;"><span style=""></span>') + '</p></div>');
                };
                var numberSlider = function(type, decimal) {
                    decimal = decimal === undefined ? false : true;
                    if(type == 'price'){
                        var maxPrice = jQuery('div.price-right input.slider-right-val').val();
                        var rules = {
                        price: [
                            [0, maxPrice, 0.5]
                        ]
                        };
                    }
                    if(type == 'tableper'){
                        var maxtableper = jQuery('div.tableper-main input.slider-right-val').val();
                        var rules = {
                         tableper: [
                            [0, maxtableper, 1]
                        ]
                        };
                    }
                    if(type == 'depth'){
                        var maxdepth = jQuery('div.depth-main input.slider-right-val').val();
                        var rules = {
                        depth: [
                            [0, maxdepth, 1]
                        ]
                        };
                    }

                    var createArrayByRule = function(rule) {
                        var a = [],
                            b = [];
                        for (var i = 0; i < rule.length; i++)
                            for (var j = rule[i][0]; j <= rule[i][1]; j += rule[i][2])
                                a.push(j);
                        for (var i = 0; i < a.length; i++)
                            b.push(i * 10);
                        return {
                            trueValues: a,
                            values: b
                        };
                    };

                    var findNearest = function(includeLeft, includeRight, value, values) {

                        var nearest = null,
                            diff = null;
                        for (var i = 0; i < values.length; i++) {
                            if ((includeLeft && values[i] <= value) || (includeRight && values[i] >= value)) {
                                var newDiff = Math.abs(value - values[i]);
                                if (diff == null || newDiff < diff) {
                                    nearest = values[i];
                                    diff = newDiff;
                                }
                            }
                        }
                        return nearest;
                    };

                    var getRealValue = function(sliderValue, tv, values, d) {

                        for (var i = 0; i < values.length; i++) {
                            if (d) {
                                if (Math.round(values[i] * 100) >= Math.round(sliderValue * 100))
                                    return tv[i];
                            } else {
                                if (values[i] >= sliderValue)
                                    return tv[i];
                            }
                        }
                        return 0;
                    };

                    var getFakeValue = function(inputValue, tv, values, d) {

                        for (var i = 0; i < tv.length; i++) {
                            if (d) {
                                if (Math.round(tv[i] * 100) >= Math.round(inputValue * 100))
                                    return values[i];
                            } else {
                                if (tv[i] >= inputValue)
                                    return values[i];
                            }
                        }
                        return 0;

                    };

                    var setRangeValues = function(side, value) {

                        value = getFakeValue(value, arrayByRule.trueValues, arrayByRule.values, decimal);
                        $slider.slider("values", side, value);
                    };

                    var arrayByRule = createArrayByRule(rules[type]),
                        $slider = jQuery("#" + type + "_slider"),
                        $leftVal = $slider.find('.slider-left'),
                        $rightVal = $slider.find('.slider-right'),
                        rangeMin = parseFloat(rules[type][0][0]),
                        rangeMax = parseFloat(rules[type][0][1]);
                        
                        $slider.slider({
                        orientation: 'horizontal',
                        range: true,
                        min: arrayByRule.values[0],
                        max: arrayByRule.values[arrayByRule.values.length - 1],
                        values: [arrayByRule.values[0], arrayByRule.values[arrayByRule.values.length - 1]],
                        slide: function(event, ui) {
                            var includeLeft = event.keyCode != jQuery.ui.keyCode.RIGHT,
                                includeRight = event.keyCode != jQuery.ui.keyCode.LEFT,
                                value = findNearest(includeLeft, includeRight, ui.value, arrayByRule.values),
                                n = getRealValue(value, arrayByRule.trueValues, arrayByRule.values, decimal);

                            if (ui.value == ui.values[0]) {
                                $slider.slider('values', 0, value);
                                decimal ? $leftVal.val(n.toFixed(2)) : $leftVal.val(n);
                            } else {
                                $slider.slider('values', 1, value);
                                decimal ? $rightVal.val(n.toFixed(2)) : $rightVal.val(n);
                            }
                        },
                        stop: function(){
                            jQuery("#search-diamonds-form #submit").trigger("click");
                        },
                        create: function(event, ui) {
                            setRangeValues(0, $leftVal.val());
                            setRangeValues(1, $rightVal.val());
                        }

                    }).addClass(type).touchit();

                    $leftVal.on('keyup blur', function(e) {
                    var v = this.value = this.value.replace(/[^0-9\.]/g,''),
                        currentRightValue = getRealValue($slider.slider('values', 1), arrayByRule.trueValues, arrayByRule.values, decimal);

                    if ((e.type == 'keyup' && e.keyCode == 13) || e.type == 'blur') {
                        if (v.length) {
                            if (v < rangeMin) {
                                setRangeValues(0, rangeMin);
                                this.value = rangeMin;
                            } else if (v > currentRightValue) {
                                setRangeValues(0, currentRightValue);
                                this.value = currentRightValue;
                            } else {
                                setRangeValues(0, v);
                            }
                        } else {
                            setRangeValues(0, rangeMin);
                            this.value = rangeMin;
                        }
                        jQuery( "#search-diamonds-form #submit" ).trigger( "click" );
                    }
                    
                });

                $rightVal.on('keyup blur', function(e) {
                    var v = this.value = this.value.replace(/[^0-9\.]/g,''),
                        currentLeftValue = getRealValue($slider.slider('values', 0), arrayByRule.trueValues, arrayByRule.values, decimal);

                    if ((e.type == 'keyup' && e.keyCode == 13) || e.type == 'blur') {
                        if (v.length) {
                            if (v > rangeMax) {
                                setRangeValues(1, rangeMax);
                                this.value = rangeMax;
                            } else if (v < currentLeftValue) {
                                setRangeValues(1, currentLeftValue);
                                this.value = currentLeftValue;
                            } else {
                                setRangeValues(1, v);
                            }
                        } else {
                            setRangeValues(1, rangeMax);
                            this.value = rangeMax;
                        }
                        jQuery( "#search-diamonds-form #submit" ).trigger( "click" );
                    }
                    
                });
        };*/

        jQuery(window).keydown(function(event) {
            if (event.keyCode == 13)
                return false;
        });

        //If search module container exists hook slider to DOM
        /*if ($searchModule.length) {
            //if (jQuery('#carat_slider').length)
              //  new numberSlider('carat', true);
            if (jQuery('#price_slider').length)
                new numberSlider('price', true);
            if (jQuery('#tableper_slider').length)
                new numberSlider('tableper', true);
            if (jQuery('#depth_slider').length)
                new numberSlider('depth', true);
            $searchModule.find('.ui-slider-handle:even').addClass('left-handle');
            $searchModule.find('.ui-slider-handle:odd').addClass('right-handle');
        }*/
        jQuery('input:checkbox').change(function() {
                if(jQuery(this).attr('name') == 'diamond_fancycolor[]'){
                        jQuery.ajax({
                            url: jQuery('#search-diamonds-form #baseurl').val()+'diamondtools/loadshape',
                            data: jQuery('#search-diamonds-form').serialize(),
                            type: 'POST',
                            dataType: 'json',
                            //cache: true,
                            beforeSend: function(settings) {
                                jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
                            },
                            success: function(response) {
                                jQuery('ul#shapeul li').css('display','none')
                                jQuery.each(response.shapes, function(key,value) {
                                  jQuery('li.'+value).css('display','block');
                                }); 
                            }
                        });
                    }
                if (jQuery(this).is(':checked')) {
                    jQuery(this).parent().addClass('selected active');
                    jQuery("#search-diamonds-form #submit").trigger("click");
                } else {
                    jQuery(this).parent().removeClass('selected active');
                    jQuery("#search-diamonds-form #submit").trigger("click");
                }
        });

        if(jQuery("#filtermode").val() == 'navfancycolored'){
            var element =  document.getElementById("navfancycolored");
            if (typeof(element) != 'undefined' && element != null){
                document.getElementById("navfancycolored").className = "active";                
            }
            if (typeof(document.getElementById("navstandard")) != 'undefined' && document.getElementById("navstandard") != null){
                document.getElementById("navstandard").className = "";           
            }
            if (typeof(document.getElementById("navlabgrown")) != 'undefined' && document.getElementById("navlabgrown") != null){
                document.getElementById("navlabgrown").className = "";             
            }
            
        } else if(jQuery("#filtermode").val() == 'navlabgrown'){
            var element =  document.getElementById("navlabgrown");
            if (typeof(element) != 'undefined' && element != null){
                document.getElementById("navlabgrown").className = "active";                
            }
            if (typeof(document.getElementById("navstandard")) != 'undefined' && document.getElementById("navstandard") != null){
                document.getElementById("navstandard").className = "";
            }
            if (typeof(document.getElementById("navfancycolored")) != 'undefined' && document.getElementById("navfancycolored") != null){
                document.getElementById("navfancycolored").className = "";    
            }
        } else {
            var element =  document.getElementById("navstandard");
            if (typeof(element) != 'undefined' && element != null){
                document.getElementById("navstandard").className = "active";                
            }
            if (typeof(document.getElementById("navfancycolored")) != 'undefined' && document.getElementById("navfancycolored") != null){
                document.getElementById("navfancycolored").className = "";
            }
            if (typeof(document.getElementById("navlabgrown")) != 'undefined' && document.getElementById("navlabgrown") != null){
                document.getElementById("navlabgrown").className = "";   
            }            
        }
               
                
    },
    error: function(xhr, status, errorThrown) {
        console.log('Error happens. Try again.');
        console.log(errorThrown);
        }
    });


}

function SaveFilter() {
        jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
        var shapeCheckboxes = jQuery("input[name='diamond_shape[]']");
        var shapeList = [];
        shapeCheckboxes.each(function() {
            if (this.checked === true) {
                shapeList.push(jQuery(this).val());
            }
        });
        var cutCheckboxes = jQuery("input[name='diamond_cut[]']");
        var CutGradeList = [];
        cutCheckboxes.each(function() {
            if (this.checked === true) {
                CutGradeList.push(jQuery(this).val());
            }
        });
        var colorCheckboxes = jQuery("input[name='diamond_color[]']");
        var ColorList = [];
        colorCheckboxes.each(function() {
            if (this.checked === true) {
                ColorList.push(jQuery(this).val());
            }
        });
        var clarityCheckboxes = jQuery("input[name='diamond_clarity[]']");
        var ClarityList = [];
        clarityCheckboxes.each(function() {
            if (this.checked === true) {
                ClarityList.push(jQuery(this).val());
            }
        });
        var polishCheckboxes = jQuery("input[name='diamond_polish[]']");
        var polishList = [];
        polishCheckboxes.each(function() {
            if (this.checked === true) {
                polishList.push(jQuery(this).val());
            }
        });
        var symmetryCheckboxes = jQuery("input[name='diamond_symmetry[]']");
        var SymmetryList = [];
        symmetryCheckboxes.each(function() {
            if (this.checked === true) {
                SymmetryList.push(jQuery(this).val());
            }
        });
        var fluorescenceCheckboxes = jQuery("input[name='diamond_fluorescence[]']");
        var FluorescenceList = [];
        fluorescenceCheckboxes.each(function() {
            if (this.checked === true) {
                FluorescenceList.push(jQuery(this).val());
            }
        });

        var fancycolorCheckboxes = jQuery("input[name='diamond_fancycolor[]']");
        var FancycolorList = [];
        fancycolorCheckboxes.each(function() {
            if (this.checked === true) {
                FancycolorList.push(jQuery(this).val());
            }
        });


        var intintensityCheckboxes = jQuery("input[name='diamond_intintensity[]']");
        var intintensityList = [];
        intintensityCheckboxes.each(function() {
            if (this.checked === true) {
                intintensityList.push(jQuery(this).val());
            }
        });

        var certiCheckboxes = jQuery("select#certi-dropdown");
        var certificatelist = [];
        certificatelist.push(jQuery(certiCheckboxes).val());
        var caratMin = jQuery("div#noui_carat_slider input.slider-left").val();
        var caratMax = jQuery("div#noui_carat_slider input.slider-right").val();
        var PriceMin = jQuery("div#price_slider input.slider-left").val();
        var PriceMax = jQuery("div#price_slider input.slider-right").val();
        var depthMin = jQuery("div#depth_slider input.slider-left").val();
        var depthMax = jQuery("div#depth_slider input.slider-right").val();
        var tableMin = jQuery("div#tableper_slider input.slider-left").val();
        var tableMax = jQuery("div#tableper_slider input.slider-right").val();
        var SOrigin = jQuery("select#gemfind_diamond_origin").val();
        var orderBy = jQuery("input#orderby").val();
        var direction = jQuery("input#direction").val();
        var currentPage = jQuery("input#currentpage").val();
        var itemperpage = jQuery("input#itemperpage").val();
        var viewMode = jQuery("input#viewmode").val();
        var filtermode = jQuery("input#filtermode").val();
        var did = jQuery("input#did").val();
        var formdata = {
            'shapeList': shapeList.toString(),
            'caratMin': caratMin,
            'caratMax': caratMax,
            'PriceMin': PriceMin,
            'PriceMax': PriceMax,
            'certificate': certificatelist.toString(),
            'SymmetryList': SymmetryList.toString(),
            'polishList': polishList.toString(),
            'depthMin': depthMin,
            'depthMax': depthMax,
            'tableMin': tableMin,
            'tableMax': tableMax,
            'FluorescenceList': FluorescenceList.toString(),
            'CutGradeList': CutGradeList.toString(),
            'ColorList': ColorList.toString(),
            'ClarityList': ClarityList.toString(),
            'FancycolorList': FancycolorList.toString(),
            'IntintensityList': intintensityList.toString(),
            'Filtermode': filtermode,
            'SOrigin': SOrigin,
            'currentPage': currentPage,
            'orderBy': orderBy,
            'direction': direction,
            'viewmode': viewMode,
            'itemperpage': itemperpage,
            'did': did,
        };
        var expire = new Date();
        expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
        if(filtermode == 'navfancycolored'){
            jQuery.cookie("savefiltercookiefancy", JSON.stringify(formdata), {
                path: '/',
                expires: expire
            });
        } else if(filtermode == 'navstandard') {
            jQuery.cookie("shopifysavefiltercookie", JSON.stringify(formdata), {
                path: '/',
                expires: expire
            });    
        } else {
            jQuery.cookie("savefiltercookielabgrown", JSON.stringify(formdata), {
                path: '/',
                expires: expire
            });  
        }
        
        setTimeout(
            function() {
                jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
            }, 400);
}

function ResetFilter() {
    
        jQuery.cookie("shopifysavefiltercookie", '', {
            path: '/',
            expires: -1
        });
        jQuery.cookie("savefiltercookiefancy", '', {
            path: '/',
            expires: -1
        });
        jQuery.cookie("savefiltercookielabgrown", '', {
            path: '/',
            expires: -1
        });
        jQuery.cookie("savebackvaluefancy", '', {
            path: '/',
            expires: -1
        });
        jQuery.cookie("shopifysavebackvalue", '', {
            path: '/',
            expires: -1
        });
        jQuery.cookie("savebackvaluelabgrown", '', {
            path: '/',
            expires: -1
        });
        jQuery.cookie("shopifysavebackvaluefiltermode", '', {
            path: '/',
            expires: -1
        });
        window.location.reload();
    
}

function slider_update_textbox(slider_inputs,slidername){
    // Listen to keydown events on the input field. 
    slider_inputs.forEach(function (input, handle) {
        input.change(function () {
            var vals = parseFloat(this.value);
            if(handle){
                slidername.noUiSlider.set([null, vals]);
            } else {
                slidername.noUiSlider.set([vals, null]);
            }
            jQuery("#search-diamonds-form #submit").trigger("click");
        });  
        /*input.keydown(function (e) {
            if(this.name == "price[from]" || this.name == "price[to]")
            {
                if (window.event) // IE
                {
                    if ((e.keyCode < 48 || e.keyCode > 57) & e.keyCode != 8 && e.keyCode != 44 && e.keyCode != 188 ) {
                        event.returnValue = false;
                        return false;
                    }
                }
                else { // Fire Fox
                    if ((e.which < 48 || e.which > 57) & e.which != 8 && e.which != 44 && e.keyCode != 188 ) {
                        e.preventDefault();
                        return false;
                    }
                }
            }
            if(this.name == "diamond_carats[from]" || this.name == "diamond_carats[to]")
            {
                if (window.event) // IE
                {
                    if ((e.keyCode < 48 || e.keyCode > 57) & e.keyCode != 8 && e.keyCode != 44 && e.keyCode != 190) {
                        event.returnValue = false;
                        return false;
                    }
                }
                else { // Fire Fox
                    if ((e.which < 48 || e.which > 57) & e.which != 8 && e.which != 44 && e.keyCode != 190) {
                        e.preventDefault();
                        return false;
                    }
                }
            }
        });  */              
        input.keyup(function (e) {
            var values = slidername.noUiSlider.get();
            var value = parseFloat(values[handle]);
            // [[handle0_down, handle0_up], [handle1_down, handle1_up]]
            var steps = slidername.noUiSlider.steps();
            // [down, up]
            var step = steps[handle];
            var position;
            // 13 is enter,
            // 38 is key up,
            // 40 is key down.
            switch (e.which) {

                case 13:
                var vals = parseFloat(this.value);
                if(handle){
                    slidername.noUiSlider.set([null, vals]);
                } else {
                    slidername.noUiSlider.set([vals, null]);
                }                        
                jQuery("#search-diamonds-form #submit").trigger("click");
                break;

                case 38:
                position = step[1];
                    // false = no step is set
                    if (position === false) {
                        position = 1;
                    }
                    // null = edge of slider
                    if (position !== null) {
                        var vals = parseInt(value + position);
                        if(handle){
                            slidername.noUiSlider.set([null, vals]);
                        } else {
                            slidername.noUiSlider.set([vals, null]);
                        }
                    }
                    jQuery("#search-diamonds-form #submit").trigger("click");
                    break;
                case 40:
                    position = step[0];
                    if (position === false) {
                        position = 1;
                    }

                    if (position !== null) {
                        var vals = parseFloat(value - position);
                        if(handle){
                            slidername.noUiSlider.set([null, vals]);
                        } else {
                            slidername.noUiSlider.set([vals, null]);
                        }                                
                    }
                    jQuery("#search-diamonds-form #submit").trigger("click");
                    break;
                }
        });
    });
}
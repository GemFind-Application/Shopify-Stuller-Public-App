   var $searchModule = jQuery('#search-diamonds');
    jQuery(document).ready(function($) {
            $('#search-diamonds-form #submitby').val('ajax');
                $('form#search-diamonds-form').submit(function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $('#search-diamonds-form').attr('action'),
                        data: $('#search-diamonds-form').serialize(),
                        type: 'POST',
                        //dataType: 'json',
                        cache: true,
                        beforeSend: function(settings) {
                            $('.loading-mask.gemfind-loading-mask').css('display', 'block');
                        },
                        success: function(response) {
                            
                            $('.result').html(response);
                            $('.loading-mask.gemfind-loading-mask').css('display', 'none');
                            if (($('div.number-of-search strong').html() < 20) && ($('#currentpage').val() > 1)) {
                                $('#currentpage').val(1);
                                $("#search-diamonds-form #submit").trigger("click");
                            }
                            var mode = $("input#viewmode").val();
                            if (mode == 'grid') {
                                $('li.grid-view a').addClass('active');
                                $('li.list-view a').removeClass('active');
                                $('#list-mode').addClass('cls-for-hide');
                                $('#grid-mode, #gridview-orderby, div.grid-view-sort').removeClass('cls-for-hide');
                            }

                            $('.change-view-result li a').click(function() {
                                $(this).addClass('active');
                                $(".table-responsive input:checkbox[name=compare]").prop("checked", false);
                                if ($(this).parent('li').attr('class') == 'list-view') {
                                    $('li.grid-view a').removeClass('active');
                                    $('#list-mode').removeClass('cls-for-hide');
                                    $('#grid-mode, div.grid-view-sort').addClass('cls-for-hide');
                                    $("input#viewmode").val('list');
                                } else {
                                    $('li.list-view a').removeClass('active');
                                    $('#list-mode').addClass('cls-for-hide');
                                    $('#grid-mode, div.grid-view-sort').removeClass('cls-for-hide');
                                    $("input#viewmode").val('grid');
                                }
                            });

                            $(".search-product-grid .trigger-info").click(function(e) {
                                $(this).parent().next().toggleClass('active');
                                e.stopPropagation();
                            });

                            $(".search-product-grid .product-inner-info").click(function(e) {
                                e.stopPropagation();
                            });

                            $(document).click(function(e) {
                                $(".search-product-grid .product-inner-info").removeClass('active');
                            });

                            $("#gridview-orderby option").each(function() {
                                if ($(this).val() == $("input#orderby").val()) {
                                    $(this).attr("selected", "selected");
                                    return;
                                }
                            });
                            if($("input#direction").val() == 'ASC'){
                                $('#ASC').addClass('active');
                                $('#DESC').removeClass('active');
                            } else{
                                $('#DESC').addClass('active');
                                $('#ASC').removeClass('active');
                            }

                            $("#pagesize option").each(function() {
                                if ($(this).val() == $("input#itemperpage").val()) {
                                    $(this).attr("selected", "selected");
                                    return;
                                }
                            });
                            $("#gemfind_diamond_origin").change(function() {
                                $("#search-diamonds-form #submit").trigger("click");
                            });

                            $('th#' + $("input#orderby").val()).addClass($("input#direction").val());
                            $('#gridview-orderby').SumoSelect({
                                forceCustomRendering: true,
                                triggerChangeCombined:false
                            });

                            $('#pagesize').SumoSelect({
                                forceCustomRendering: true,
                                triggerChangeCombined:false
                            });
                            $('.pagesize.SumoUnder').insertAfter(".sumo_pagesize .CaptionCont.SelectBox");
                            $('.gridview-orderby.SumoUnder').insertAfter(".sumo_gridview-orderby .CaptionCont.SelectBox");

                            $("input[name='compare']").change(function() {
                                var maxAllowed = 5;
                                var cnt = $("input[name='compare']:checked").length;
                                if (cnt > maxAllowed) {
                                    $(this).prop("checked", "");
                                    alert('You can select maximum ' + maxAllowed + ' diamonds to compare!');
                                }
                            });
                            $('#searchdidfield').keydown(function(e) {
                                if (e.keyCode == 13) {
                                    $('#searchdid').trigger('click');
                                }
                            });
                            $('#searchdid').click(function(){
                                if($('#searchdidfield').val() !=""){
                                    $('input#did').val($('#searchdidfield').val());
                                    $("#search-diamonds-form #submit").trigger("click");
                                } else {
                                    $('input#searchdidfield').effect("highlight", {color: '#f56666'}, 2000);
                                    return false;
                                }
                            });
                            if($('input#did').val()){
                                $('#searchintable').addClass('executed');
                            }
                            $('#searchdidfield').val($('input#did').val());
                            $('input#did').val('');
                            $('#resetsearchdata').click(function(){
                               $('#searchdidfield').val();
                               $('input#did').val('');
                               $("#search-diamonds-form #submit").trigger("click");
                            });
                            $('.loading-mask.gemfind-loading-mask').css('display', 'none');
                        },
                        error: function(xhr, status, errorThrown) {
                            $('.loading-mask.gemfind-loading-mask').css('display', 'none');
                            console.log('Error happens. Try again.');
                            console.log(errorThrown);
                        }
                    });
                });      
            });

function fnSort(strSort) {
    var orderBy = document.getElementById("orderby").value;
    var direction = document.getElementById("direction").value;
    if (strSort == orderBy) {
        if (direction == "ASC")
            direction = 'DESC';
        else
            direction = 'ASC';
    } else {
        direction = 'ASC';
    }
    orderBy = strSort;
    direction = direction;
    document.getElementById("orderby").value = strSort;
    document.getElementById("direction").value = direction;
    document.getElementById("currentpage").value = 1;
    document.getElementById("submit").click();
}

function gridSort(selectObject) {
    var orderBy = document.getElementById("orderby").value;
    var direction = document.getElementById("direction").value;
    var selectedvalue = selectObject.value;
    orderBy = selectedvalue;
    direction = direction;
    document.getElementById("orderby").value = selectedvalue;
    document.getElementById("direction").value = direction;
    document.getElementById("currentpage").value = 1;
    document.getElementById("submit").click();
}

function gridDire(selectedvalue) {
    var direction = document.getElementById("direction").value;
    var selectedvalue = selectedvalue;
    if (direction != selectedvalue) {
        direction = selectedvalue;
    }
    if(direction == "ASC"){
        document.getElementById('DESC').className = "";
        document.getElementById('ASC').className = "active";
    } else {
        document.getElementById('DESC').className = "active";
        document.getElementById('ASC').className = "";
    }
    document.getElementById("direction").value = direction;
    document.getElementById("currentpage").value = 1;
    document.getElementById("submit").click();
}

function ItemPerPage(selectObject){
    var resultperpage = document.getElementById("itemperpage").value;
    var selectedvalue = selectObject.value;
    resultperpage = selectedvalue;
    document.getElementById("itemperpage").value = selectedvalue;
    document.getElementById("currentpage").value = 1;
    document.getElementById("submit").click();
}

function PagerClick(intpageNo) {
    document.getElementById("currentpage").value = intpageNo;
    document.getElementById("submit").click();
}


function mode(targetid) {
    var id = targetid.id;
    var items = document.getElementById("navbar").getElementsByTagName("li");
    for (var i = 0; i < items.length; ++i) {
      items[i].className = "";
    }
    document.getElementById(id).className = "active";
    if(id != 'navcompare')
    document.getElementById("filtermode").value = id;
    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
    diamondmain();
}

function SetBackValue(diamondid) {
  
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
        var viewMode = jQuery("input#viewmode").val();
        var did = diamondid;
        var filtermode = jQuery("input#filtermode").val();
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
            'FancycolorList': FancycolorList.toString(),
            'IntintensityList': intintensityList.toString(),            
            'CutGradeList': CutGradeList.toString(),
            'ColorList': ColorList.toString(),
            'ClarityList': ClarityList.toString(),
            'SOrigin': SOrigin,
            'currentPage': currentPage,
            'orderBy': orderBy,
            'direction': direction,
            'viewmode': viewMode,
            'filtermode':filtermode,
            'did': did,
        };
        
        console.log(formdata);
        var expire = new Date();
        expire.setTime(expire.getTime() + 0.5 * 3600 * 1000);
        jQuery.cookie("shopifysavebackvaluefiltermode",filtermode , {
            path: '/',
            expires: expire
            });

        if(filtermode == 'navfancycolored'){
            jQuery.cookie("shopifysavebackvaluefancy", JSON.stringify(formdata), {
            path: '/',
            expires: expire
            });
        } else if(filtermode == 'navstandard'){
            jQuery.cookie("shopifysavebackvalue", JSON.stringify(formdata), {
            path: '/',
            expires: expire
            });            
        } else {
            jQuery.cookie("shopifysavebackvaluelabgrown", JSON.stringify(formdata), {
            path: '/',
            expires: expire
            });  
        }

    
}
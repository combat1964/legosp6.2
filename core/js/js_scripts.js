$.tabs = function(selector, start) {
    $(selector).each(function(i, element) {
        $($(element).attr('rel')).css('display', 'none');
        $(element).click(function() {
            $(selector).each(function(i, element) {
                $(element).removeClass('selected');
                $($(element).attr('rel')).css('display', 'none');
            });
            $(this).addClass('selected');
            $($(this).attr('rel')).css('display', 'block');
        });
    });
    if (!start) {
        start = $(selector + ':first').attr('rel');
    }
    $(selector + '[rel=\'' + start + '\']').trigger('click');
};

var defaultFontSize = 100;
var currentFontSize = defaultFontSize;
//// Json Constants Array

var JSonData = ConstJS.constants;

function setFontSize(fontSize) {
    document.body.style.fontSize = fontSize + '%';
}

function changeFontSize(sizeDifference) {
    currentFontSize = parseInt(currentFontSize, 10) + parseInt(sizeDifference * 5, 10);
    if (currentFontSize > 180) {
        currentFontSize = 180;
    } else if (currentFontSize < 60) {
        currentFontSize = 60;
    }
    setFontSize(currentFontSize);
}

function revertStyles() {
    currentFontSize = defaultFontSize;
    changeFontSize(0);
}

function show_hide_param(id) {
    optid = '#opt' + id;
    id = '#' + id;
    if ($(id).is(":visible")) {
        $(optid).removeClass('switcher-on').addClass('switcher-off');
        $(id).hide();
    } else {
        $(optid).removeClass('switcher-off').addClass('switcher-on');
        $(id).show();
    }

}

//// VOTES /////////////////////////////

var OPT_ID = 3;
var OPT_TITLE = 4;
var OPT_VOTES = 5;

//var OPT_ID = 0;
//var OPT_TITLE = 1;
//var OPT_VOTES = 2;

var votedID;
var idvote = 0;

$(document).ready(function() {
    $("#voteform").submit(formProcess);
    // setup the submit handler

    if ($("#vote-results").length > 0) {
        animateResults();
    }
    if ($("#vote_send")) {
        idvote = $("#vote_send").attr("value");

        if ($.cookie("vote_id_" + idvote)) {
            $("#vote-container").empty();
            votedID = $.cookie("vote_id_" + idvote);
            $.getJSON("../includes/votes.php?vote_res=none&type=ajax", loadResults);
        }
    }
});

function formProcess(event) {
    event.preventDefault();
    var id = $("input[@name='vote_res']:checked").attr("value");
    id = id.replace("opt", '');
    idvote = $("#vote_send").attr("value");
    $("#vote-container").fadeOut("slow", function() {
        $(this).empty();
        votedID = id;
        $.getJSON("../includes/votes.php?vote_res=" + id + "&idvote=" + idvote + "&type=ajax", loadResults);
    });
}

function animateResults() {
    $("#vote-results div").each(function() {
        var percentage = $(this).next().text();
        $(this).css({
            width: "0%"
        }).animate({
            width: percentage
        }, 'slow');
    });
}

function loadResults(data) {
    var total_votes = 0;
    var percent;

    for (id in data) {
        total_votes = total_votes + parseInt(data[id][OPT_VOTES]);
    }

    var results_html = "<div id='vote-results'>\n<dl class='graph'>\n";
    for (id in data) {
        percent = 0;
        if (data[id][OPT_VOTES] > 0)
            percent = Math.round((parseInt(data[id][OPT_VOTES]) / parseInt(total_votes)) * 100);
        if (data[id][OPT_ID] !== votedID) {
            results_html = results_html + "<dd  style='float:left;' class='bar-container'><div id='bar" + data[id][OPT_ID] + "'style='width:0%; float:left; '><nobr><b>" + data[id][OPT_TITLE] + "</b></nobr></div><strong>" + percent + "%</strong></dd>\n";
        } else {
            results_html = results_html + "<dd class='bar-container' style='float:left;'><div id='bar" + data[id][OPT_ID] + "'style='width:0%;background-color:#FD5300; float:left;'><nobr><b>" + data[id][OPT_TITLE] + "</b></nobr></div><strong>" + percent + "%</strong></dd>\n";
        }
    }
    results_html = results_html + "</dl><p style='clear: both;'>Total Votes: " + total_votes + "</p></div>\n";

    $("#vote-container").append(results_html).fadeIn("slow", function() {
        animateResults();
    });
}

//// EOF VOTES //////////////////////////

//// Validate Functions


function open_window(link, w, h)//opens new window
{
    var win = "width=" + w + ",height=" + h + ",menubar=no,location=no,resizable=yes,scrollbars=yes";
    newWin = window.open(link, 'newWin', win);
    newWin.focus();
}

//// Payment functions

function checkSubmit() {
    if (document.getElementById("idto").value != "")
        return true;
    else {
        alert("������� ����� �������� � ������� 9059103456");
        return false;
    }
}

//// Mode Functions

function moduleSearch() {
    location = "./index.php?searchstring=" + $('#mod_search_searchword').attr('value');
}

function cart_update(obect) {
    var array_input = document.getElementsByTagName('input');

    for (var i = 0; i < array_input.length; i++) {
        if ((array_input[i].name.split('_')[1]) == 'instock') {
            id = array_input[i].name.split('_')[2];
            if ((Math.round(array_input[i].value * 100) / 100) < (Math.round(document.getElementById('count_' + id).value * 100) / 100)) {
                alert('���������� ����������� ��������� ���������� ��� ' + document.getElementById('name' + id).innerHTML);
                return false;

            }
        }
    }
    $('#' + obect).submit();

}


function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: JSonData.PAGE_LANG,
        includedLanguages: 'en, ru'
    }, 'google_translate_element');
}

if (document.getElementsByClassName) {

    getElementsByClass = function(classList, node) {
        return (node || document).getElementsByClassName(classList)
    }
} else {

    getElementsByClass = function(classList, node) {
        var node = node || document, list = node.getElementsByTagName('*'), length = list.length, classArray = classList.split(/\s+/), classes = classArray.length, result = [], i, j
        for (i = 0; i < length; i++) {
            for (j = 0; j < classes; j++) {
                if (list[i].className.search('\\b' + classArray[j] + '\\b') != -1) {
                    result.push(list[i])
                    break
                }
            }
        }

        return result
    }
}

$(document).ready(function() {

    /*$('#products').slides({
        preload: true,
        effect: 'slide, fade',
        crossfade: true,
        slideSpeed: 350,
        fadeSpeed: 500,
        generateNextPrev: true,
        generatePagination: false
    });*/

    $('input[name|="captcha"]').attr('autocomplete', 'off');

    $("#phone").keypress(function(event) {
        var key, keyChar;
        if (!event)
            var event = window.event;

        if (event.keyCode)
            key = event.keyCode;
        else if (event.which)
            key = event.which;
        if (key == null || key == 0 || key == 8 || key == 13 || key == 37 || key == 39 || key == 46 || key == 9)
            return true;
        keyChar = String.fromCharCode(key);

        if (!/^[-\d]$/.test(keyChar))
            return false;
    });

    $("#zip").keypress(function(event) {
        var key, keyChar;
        if (!event)
            var event = window.event;

        if (event.keyCode)
            key = event.keyCode;
        else if (event.which)
            key = event.which;
        if (key == null || key == 0 || key == 8 || key == 13 || key == 37 || key == 39 || key == 46 || key == 9)
            return true;
        keyChar = String.fromCharCode(key);

        if (!/^[-\d]$/.test(keyChar))
            return false;

    });

    $('.chk').click(function() {
        var curid = this.id.split('_')[1];
        var hurl = $('#hc' + curid).val();

        if ($('#inpc' + curid).val() == 0) {
            check = curid;
            uncheck = 0;
            $('#sc_' + curid).html('<img src="./images/ok.png" alt="" />');
            $('#c_ul').append('<li style="padding: 2px 5px;" id="dtr_' + curid + '"><a href="./' + hurl + '" title="' + $('#dp' + curid).attr('alt') + '"><img src="' + $('#dp' + curid).attr('src') + '" class="c_img" height="50" alt="" /></a></li>');
            $('#inpc' + curid).val(['1']);
            var image = $('#dp' + curid).offset();
            var comp = $('#compare').offset();

            $('#dp' + curid).before('<img src="' + $('#dp' + curid).attr('src') + '" id="temp" style="position: absolute; top: ' + image.top + 'px; left: ' + image.left + 'px;" />');

            params = {
                top: comp.top + 'px',
                left: comp.left + ($('#c_ul li').size() * 60) + 'px',
                opacity: 0.0,
                width: '50px',
                heigth: '50px'
            };

            $('#temp').animate(params, 'slow', false, function() {
                $('#temp').remove();
            });

        } else {
            check = 0;
            uncheck = curid;
            $('#dtr_' + curid + ', #ic_' + curid).remove();
            $('#sc_' + curid).html('');
            $('#inpc' + curid).val(['0']);
        }

        poststr = "check=" + check + "&uncheck=" + uncheck;
        $.ajax({
            type: "GET",
            url: "./includes/compare.php",
            data: poststr
        });

        if ($('#c_ul li').size() == 0) {
            $('#compare').hide(500);
        } else {
            $('#compare').show(500);
        }
    });

    $('.dell_chk').click(function() {
        var curid = this.id.split('_')[1];
        check = 0;
        uncheck = curid;

        poststr = "check=" + check + "&uncheck=" + uncheck;
        $.ajax({
            type: "GET",
            url: "./includes/compare.php",
            data: poststr
        });
        //alert($('.comp tr').size());
        $('#dtr_' + curid).hide(500).remove();
        if ($('.comp tr').size() == 2) {
            location = "./index.php?clear_compare";
        }
    });

    $(".variants .product_option").change(function() {
        if ($(this).is('input')) {
            if ($(this).is(':checked'))
                var_change = $(this).val();
            else
                var_change = 0;
        } else
            var_change = $(this).val();

        parent_block = $(this).closest('.variants');
        productID = $('.productid', parent_block).val();
        options = $('select.product_option,input.product_option:checked', parent_block);
        opt_v = '';
        options.each(function() {
            opt_v += $(this).val() + ',';
        });
        poststr = "productID=" + productID + "&variants=" + opt_v + "&var_change=" + var_change;
        $.ajax(
                {
                    type: "GET",
                    url: "./includes/product_detailed.php",
                    data: poststr,
                    success: function(response) {
                        var return_data = eval("(" + response + ")");
                        $('.vnewprice' + productID).html(return_data.Price);
                        if (return_data.Picture) {
                            pct = return_data.Picture;
                            if ($("div").is("#products")) {
                                snewpic = pct + '.jpg';
                                $('#adp' + productID).attr('href', './products_pictures/' + pct + '-B.jpg');
                            } else {
                                snewpic = pct + '-S.jpg';
                            }
                            $('#dp' + productID).attr("src", './products_pictures/' + snewpic);

                        }

                    }
                });

    });

    // Ajax cart function
    $('.ajaxcart').click(function() {
        var curid = this.id.split('_')[1];
        var option = '';
        var new_priceid = "newprice" + curid;
        var countp = 'count_' + curid;
        variants = $('.variants select.product_option,.variants input.product_option:checked', $(this).closest('.item-page-info'));
        variants.each(function() {
            option += ',' + $(this).val();
        });
        if (document.getElementById(countp))
            $kol = document.getElementById(countp).value;
        else
            $kol = 1;
        poststr = "shopping_cart=yes&add2cart=" + curid + "&type=ajax&kol=" + $kol + "&opt=" + option;
        $.ajax(
                {
                    type: "GET",
                    url: "./includes/shopping_cart.php",
                    data: poststr,
                    success: function(response)
                    {

                        if (response == -1) {
                            alert('Product not found');
                            return false;
                        }
                        var return_data = eval("(" + response + ")");
                        //JSON.parse();

                        $('#sci').text(return_data.info.count);
                        $('#sci').css("font-weight", "bold");
                        $('#scs').text(return_data.info.cost);
                        var image = $('#dp' + curid).offset();
                        var cart = $('#module_cart').offset();

                        $('body').append('<img src="' + $('#dp' + curid).attr('src') + '" id="temp" style="position: absolute; top: ' + image.top + 'px; left: ' + image.left + 'px;" />');

                        params = {
                            top: cart.top + 'px',
                            left: cart.left + 'px',
                            opacity: 0.0,
                            width: $('#module_cart').width(),
                            heigth: $('#module_cart').height()
                        };

                        $('#temp').animate(params, 'slow', false, function() {
                            $('#temp').remove();
                        });
                    }
                });
        return false;
    });

    // Jcarusel hits show
    $(".carouselDiv .jCarouselLite").jCarouselLite({
        auto: JSonData.CONF_HITS_FRIQ,
        speed: JSonData.CONF_HITS_SPEED,
        vertical: true,
        visible: JSonData.CONF_SCROLL_HITS,
        easeInQuad: "easeOutQuad"
    });

    // HideSlide hits show
    var fadeTime = 2000 * (JSonData.CONF_HITS_FRIQ / 10000);
    var i = $('.slide').length;
    var x = 0;
    showSlide();
    function showSlide() {
        curSlide = "#slide" + x;
        if (x == 0) {
            prevSlide = "#slide" + (i - 1);
        } else {
            prevSlide = "#slide" + (x - 1);
        }
        $(prevSlide).fadeOut(fadeTime, function() {
            $(curSlide).fadeIn(fadeTime);
            if (x == (i - 1)) {
                x = 0;
            } else {
                x++;
            }
        });
        setTimeout(showSlide, JSonData.CONF_HITS_FRIQ);
    }

    // Tabs

    if (JSonData.REVIEW_SAVED != 1) {
        $.tabs('.tabs a');
    } else {
        $.tabs('.tabs a', '#tab_review');
    }

    // Search
    $('#mod_search_searchword').keydown(function(e) {
        if (e.keyCode == 13) {
            moduleSearch();
        }
    });

    // Tag cloud
    //$('#tag').tagcloud({centrex:70, centrey:80, init_motion_x:50, init_motion_y:50, min_font_size:10, max_font_size:16});
    if (!$('#myCanvas').tagcanvas({
        textColour: '#000000',
        outlineColour: '#ff00ff',
        reverse: true,
        depth: 0.8,
        maxSpeed: 0.05
    }, 'tags')) {
        // something went wrong, hide the canvas container
        $('#myCanvasContainer').hide();
    }

});

$(document).ready(function() {
    $("a.thickbox").fancybox();

    //$(".gallery:eq(0)  a[rel^='example_group']").prettyPhoto();

    $("a.iframe").fancybox({
        'type': 'iframe',
        'overlayShow': 'TRUE',
        'hideOnOverlayClick': 'FALSE',
        'height': 900,
        'width': 1100

    });

    $("a[rel=example_group]").fancybox();

    $("a[rel=example_group1]").fancybox({
        'transitionIn': 'none',
        'transitionOut': 'none',
        'titlePosition': 'over',
        'titleFormat': function(title, currentArray, currentIndex, currentOpts) {
            return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
        }
    });


    $("#custinfo_form").validate();
    $("form:has(#captcha)").submit(function(rf) {
        captcha = $("#captcha").val();
        jQuery.ajax({
            url: "core/core_captcha.php",
            type: "POST",
            async: false,
            data: {'captcha': captcha},
            success: function(data) {
                data = data.replace(/^\s+/, "");
                if (data != '1') {
                    $("#results").html('');
                    $("#results").show();
                    $("#results").append(data);
                    rf.preventDefault();
                }
            }
        });

    });

});

function go_cap() {
    var okoshko = "<input type='text' name='captcha' /><img style='margin-left: 15px;' src='./core/core_captcha.php?" + Math.random() + "' alt='' /><img id='cap' style='cursor:pointer;' border='0' src='./images/reload.jpg' onclick='go_cap()' alt='' />";
    document.getElementById("cap").src = "./core/core_captcha.php?" + Math.random();
}
 function queryParams(params) {
        return {
            limit: params.pageSize,
            offset: params.pageSize * (params.pageNumber - 1),
            search: params.searchText,
            name: params.sortName,
            order: params.sortOrder,
            categoryID: $('input[name=categoryID]').val()
        };
    }

 function operateFormatter(value, row, index) {
     return [
         '<span class="edit ml10" title="Edit">',
         '<i class="glyphicon glyphicon-edit"></i>',
         '</span>',
         '<span class="remove ml10" title="Remove">',
         '<i class="glyphicon glyphicon-remove"></i>',
         '</span>'
     ].join('');
 }

 window.operateEvents = {
     'click .like': function (e, value, row, index) {
         alert('You click like icon, row: ' + JSON.stringify(row.productID));
         console.log(value, row, index);
     },
     'click .edit': function (e, value, row, index) {
         window.location=location.protocol+'//'+location.hostname+location.pathname+'?dpt=catalog&sub=products_edit&productID='+row.productID;
         return false;
     },
     'click .remove': function (e, value, row, index) {
         alert('You click remove icon, row: ' + JSON.stringify(row));
         console.log(value, row, index);
     }
 };
$(document).ready(function(){
    
    
    $('.fixed-table-toolbar').prepend($('#active').html());
    var table=$('#products').bootstrapTable({
        url: './includes/admin/sub/catalog_products.php?ajax',
        onUncheck: function(row) {
          $('output input[value='+row.productID+']').remove();
          if ($('output input').length==0) $('#active').hide();

        },
        onCheck: function(row) {
                $('#active').show();
                $('output').append('<input class="pid" name="product[]" value="'+row.productID+'" type="hidden">');
            },
        onAll: function(name, args) {
            //console.log('Event: onAll, data: ', args);
        },
        onCheckAll: function() {

            productsID=$('table#products tbody td:nth-child(2)');
            productsID.each(function (i) {
                $('output').append('<input class="pid" name="product[]" value="'+$(this).text()+'" type="hidden">');
                $('#active').show();
            });
        },
        onUncheckAll: function() {
            $('output').text('');
            $('#active').hide();
        }
    });

    $(document).on('click','.add_product',function(){
        window.location=location.protocol+'//'+location.hostname+location.pathname+'?dpt=catalog&sub=products_edit&categoryID='+$('input[name=categoryID]').val();
    });
    $('.list-category').on('click','li i',function(){
        var e=$(this);
        var pli=$(this).parent();
        if ($('ul',pli).length){
            $('ul',pli).remove();
            e.removeClass('glyphicon-minus').addClass('glyphicon-plus');
        }
        else{
            e.removeClass('glyphicon-plus').addClass('glyphicon-minus');
        url="./includes/admin/sub/catalog_products.php?ajax&sub&categoryID="+$(this).parent().data('id');
        $.get(url,function( data ) {
            pli.append('<ul>'+data+'</ul>');
        })
            .fail(function() {
                alert( "error" );
            })
        }

    });
    $('.list-category').on('click','li span',function(){
        $('.list-category span').removeClass('active');
        $(this).addClass('active');
        url="./includes/admin/sub/catalog_products.php?ajax&categoryID="+$(this).parent().data('id');
        $('input[name=categoryID]').val($(this).parent().data('id'));
        table.bootstrapTable('refresh',{pageNumber:1});
        var pli=$(this).parent();
        if ($('ul',pli).length){
        }
        else{
            $(this).prev('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            url="./includes/admin/sub/catalog_products.php?ajax&sub&categoryID="+$(this).parent().data('id');
            $.get(url,function( data ) {
                pli.append('<ul>'+data+'</ul>');
            })
                .fail(function() {
                    alert( "error" );
                })
        }
    });
    $(document).on('click','.list-category-btn',function(){
        if ($('.list-category').is(":visible"))
        {
            $(this).removeClass('glyphicon-backward').addClass('glyphicon-forward');
            $('.list-category').hide();
            $(this).parent().removeClass('col-sm-9').addClass('col-sm-12');
        }
        else{
            $('.list-category').show();
            $(this).parent().removeClass('col-sm-12').addClass('col-sm-9');
            $(this).removeClass('glyphicon-forward').addClass('glyphicon-backward');
        }

    });
    $(document).on('change','#active select', function(){
        var r=$(this).val();
        $.post('./includes/admin/sub/catalog_products.php?ajax&action='+r, $('.pid').serialize(), function( data ) {
            table.bootstrapTable('refresh');
            $("#active select option[value='0']").prop('selected',true);
            $('output').text('');
            $('#active').hide();

        });
    })
    $.extend($.expr[':'],{
        selct_level: function(a,i,m) {

            if(!m[3]||!(/^(<|>)\d+$/).test(m[3])) {return false;}
            return m[3].substr(0,1) === '>' ?
                $(a).data('level') > m[3].substr(1) : $(a).data('level') < m[3].substr(1);
        }
    });
    $(document).on('submit','.ajaxform',function(){
        $("body").append('<div class="progress rezult"><div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div>');
        var str = $(this).serialize();
        $.post("./includes/admin/sub/catalog_categories.php?ajax",str, function(data) {
        }).always(function() {
            $('.progress.rezult').remove();
            //alert('Save OK');
        });
        return false;
    });
    $(document).on('click','a.delajax',function(){
        url=$(this).attr('href')+'&ajax&noinclude';
        tr=$(this).closest('tr');
        $.get(url,function(data) {
            tr.remove();
        });
        return false;
    });
   $(document).on('click','input.enabled',function(){
       $(this).next('input').val('0');
       if (this.checked)
           $(this).next('input').val('1');
   });
    $(document).on("click",'th.footable-sortable', function() {
        alert('sort');

    });
   
    $(document).on('click','span.category',function(){
        parent=$(this).closest('tr');
        level=parent.data('level');
        nex_level=parent.next('tr').data('level');
        if (level==undefined) level=1;
        //alert('$(\'tr:selct_level(<'+(level+1)+')\'');
        if (nex_level>level)
        {
            $(parent).nextUntil($('tr:selct_level(<'+(level+1)+')')).remove();
            return false;
        }

        categoryID=$(this).data('categotyid');
        url='./includes/admin/sub/catalog_categories.php?ajax&categoryID='+categoryID+'&level='+level;
        $.get( url, function( data )
        {
            $(parent).after(data);
        });

        return false;
    });
   $("input[name$='insert_type']").click(function(){
   var radio_value = $(this).val();
   if(radio_value=='2') 
    $("#pamans_update").show(1000);
   else  
    $("#pamans_update").hide(500);
   });
   $("input[name$='navig_next']").click(function(){  
     $('option:selected', '#pages_list').removeAttr('selected').next('option').attr('selected', 'selected');
     $('#pages_list').submit();
   });
   $("input[name$='navig_prev']").click(function(){  
     $('option:selected', '#pages_list').removeAttr('selected').prev('option').attr('selected', 'selected');
     $('#pages_list').submit();
   });
    $("input[name$='navig_first']").click(function(){  
     $('#pages_list option:first').attr("selected", "selected");
     $('#pages_list').submit();
   });
    $("input[name$='navig_last']").click(function(){  
     $('#pages_list option:last').attr("selected", "selected");
     $('#pages_list').submit();
   });  

});

function show_hide(id)
{
  $('#'+id).toggle('slow');
}

function checkautores() 
{
   if ($('#enable_autores').is(":checked"))
	{$('#thumbnail').attr('disabled', 'disabled'); $('#big_picture').attr('disabled', 'disabled');} 
   else 
	{$('#thumbnail').removeAttr('disabled'); $('#big_picture').removeAttr('disabled');}
}

function confirmDelete(id, ask, url) //confirm order delete
	{
		temp = window.confirm(ask);
		if (temp) window.location=url+id;
	}

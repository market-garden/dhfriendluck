function addCategory( name ){

    var a = $( "li[id]:first" ).clone();
    $.post( APP+'/Index/addCategory',{
        name:name
    },function( txt ){
        if( -2 == txt ){
            Alert( "不允许分类名重复添加" );
            return ;
        }
        if( -1 != txt ){
            var a = $( "li[id]:first" ).clone();
            a.attr( 'id',"cate"+$.trim(txt) );
            a.children().children().html( "<input name=\"name["+txt+"]\" type=\"text\" class=\"TextH20\" onBlur=\"this.className=\'TextH20\'\" onFocus=\"this.className=\'Text2\'\" value=\""+name+"\"/>" );
            a.find( 'div[id]' ).html( 0 );

            a.append( "<div class=\"left\" style=\"width: 10%;\"><a href=\"javascript:deleteCategory("+txt+")\">[移除]</a></div>" );
            $( "li[id]:last" ).after( a );
            $( '#insertCategory' ).val( "" );
        }else{
            Alert( "添加分类失败" );
        }
    });
}


function photo_size(name){
    $(name +" img").each(function(){

        var width = 500;
        var height = 500;
        var image = $(this);
        image.addClass('hand');
        image.bind('click',function(){
            window.open(image.attr('src'),"图片显示",'width='+image.width()+',height='+image.height());
        });
        if (image.width() > image.height()){
            if(image.width()>width){
                image.width(width);
                image.height(width/image.width()*image.height());
            }
        }
        else{
            if(image.height()>height){
                image.height(height);
                image.width(height/image.height()*image.width());
            }
        }


    });
}

$(function(){
    photo_size('#blog_con');
});
    
function deleteCategoryBlog( toCate,formCate ){
    $.post( APP+'/Index/deleteCategory',{
        id:formCate,
        toCate:toCate
    },function( txt ){
        if( -1 != txt ){
            if( toCate != null ){
                var c1 = $( '#c'+toCate ).text();
                var c2 = $( '#c'+formCate ).text();
                $( '#c'+toCate ).html(parseInt(c1)+parseInt(c2) );
            }
            $( '#cate'+formCate ).remove();
        }else{
            Alert( "删除分类失败" );
        }
        ymPrompt.close();
    })
}
function deleteCategory( id ){
    var count = $( '#c'+id ).text();
    if( count > 0 ){
        ymPrompt.win({
            message:APP+'/Index/deleteCateFrame/id/'+id+"/count/"+count,
            width:300,
            height:150,
            title:'此分类内还有日志',
            maxBtn:false,
            minBtn:false,
            iframe:true
        });
        return;
    }
    if( Confirm( {
        message:"是否确定删除这条日志?",
        handler:function( txt ){
            if( 'ok' == txt ){
                $.post( APP+'/Index/deleteCategory',{
                    id:id
                },function( txt ){
                    if( -1 != txt ){
                        $( '#cate'+id ).remove();
                    }else{
                        Alert( "删除分类失败" );
                    }
                });
            }
        }
        } ) ){

}
}
function deleteBlog( url ){
    if( Confirm( {
        message:"是否确定删除这条日志?",
        handler:function( txt ){
            if( 'ok' == txt ){
                window.location.href=url;
            }
        }
        } ) ){

}
}
function deleteCommentCount( appid ){
    //计数
    $.post(APP+'/Index/deleteSuccess',{
        id:appid
    },function(result){
        $('#commentCount').text(result);
    });
}
function commentSuccess(json){
    //计数
    $.post(APP+'/Index/commentSuccess',{
        data:json
    },function(result){
        $('#commentCount').text(result);
    });
}

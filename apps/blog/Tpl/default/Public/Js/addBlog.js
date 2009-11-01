/*
  tinyMCE.init({
  theme : "advanced",
  mode : "textareas",
  language: "zh",
  dialog_type: "modal",
  theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifyright,justifyfulla,|,bullist,numlist,|,cut,copy,paste,|,outdent,indent,|,link,unlink,|,image,html,fullscreen,preview,|,emotions,media",
  theme_advanced_buttons2: "formatselect,fontselect,fontsizeselect,styleselect,forecolor,backcolor",
  remove_script_host : false,
  auto_focus : "mce_editor_0",
  theme_advanced_buttons3:"",
  theme_advanced_toolbar_align : "left",
  theme_advanced_toolbar_location: "top",
  theme_advanced_statusbar_location : "bottom",
  //onchange_callback: "autoSave",
  plugins: "fullscreen,preview,emotions,media,advimage",
	theme_advanced_styles : "Code=codeStyle;Quote=quoteStyle",
  external_image_list_url : "./myimagelist.js"
  });
$(pageInit);
function pageInit()
{
	$('#content').xheditor(true,{tools:'simple',uploadUrl:"upload.php",uploadExt:"jpg,jpeg,gif,png"});
}
  */
//bkLib.onDomLoaded(function() {
//	//new nicEditor().panelInstance('content');
//	//new nicEditor({fullPanel : true}).panelInstance('content');
//	new nicEditor({iconsPath : PUBLIC+'/js/nicEditor/src/nicEditorIcons.gif',buttonList:['fontSize','bold','italic','underline','html','image','center','right','ol','ul','color']}).panelInstance('content');
////	new nicEditor({buttonList : ['fontSize','bold','italic','underline','strikeThrough','subscript','superscript','html','image']}).panelInstance('area4');
//});


function getMessage(){
  ymPrompt.confirmInfo({
          icoCls:'',
          msgCls:'confirm',
          message:'请输入分类名：<br><input type=\'text\' id=\'newCategory\' onfocus=\'this.select()\' />',
          title:'新添加分类',
          height:150,
          handler:addCategory,
          autoClose:false
          });
}
function check(){
  if( $( '#title' ).val().length>30 ){
    Alert( "标题不得大于30个中文字符" );
    return false;
  }
  return true;
}
$( document ).ready( function(){
    $( '#title' ).blur(function(){
      if( $( '#title' ).val().length>30  ){
        Alert( "标题不得大于30个中文字符" );
      }
      })

    });
/**
 * addCategory 
 * 添加分类
 * @param category $category 
 * @access public
 * @return void
 */
function addCategory(tp){
    if( tp!="ok" ){
              select = $( '#select' );
              select.children( ':first' ).attr( 'selected',true );
      return ymPrompt.close();
    }

    category = $( '#newCategory' ).val();
    if( category != "" ){
      $.post( APP+"/Index/addCategory",{name:category},function( txt ){
        ymPrompt.close();
        if( -1 == txt ){
          Alert( '添加失败' );
        }else if(  -2  == txt){
          Alert( '分类名冲突' );
        }else if( -3 == txt  ){
            Alert( '添加失败，分类名不能为空' );
        }else{
          select = $( '#group' );
          select.before( select.children(':first').clone().val(txt).html(category));
		      $("#select option[value='"+txt+"']").attr( 'selected',true );
          return;
        }
        
      })
    
    }else{
    ymPrompt.close();
    Alert( '请输入分类名' );
  }
              select = $( '#select' );
              select.children( ':first' ).attr( 'selected',true );
}

/**
 * changePrivacy 
 * 隐私按钮改变时
 * @param _this  $_this  
 * @access public
 * @return void
 */
function changePrivacy( _this ){
  if( 3 == _this.val() ){
    $( '#password' ).show();
  }else{
    $( '#password' ).hide();
  }
}

function changeCategory( _this ){
	if( 0 == _this.val()){
		getMessage();
	}
}

/**
 * autosave 
 * 自动保存
 * @param inst $inst 
 * @param time  $time  
 * @access public
 * @return void
 */
function autosave(){
  if( $( '#saveButton' ).attr( 'disabled' ) == "true" ){
    return;
  }
  $( '#saveButton' ).attr( 'disabled',true );
  //TODO 更换编辑器，这里必须修改；
  var content  = KE.util.getData('content');
  var title    = $( "input[name='title']" ).val( );
  var category = $( "select[name='category']" ).val();
  var privacy  = $( "select[name='privacy']" ).val();
  var mention  = $( "#ui_fri_ids" ).val();
  var password = $( "#password" ).val();
  var cc     = $( "#cc" ).val();
  //自动保存
  if( content.length>0 && $( '#saveId' ).val() == ""){
    //buttonclass = $( '#saveButton' ).attr( 'class' );
    //$( '#saveButton' ).attr( 'class','btn_w' );
    $.ajax( {
      type: 'POST',
      url: APP+'/Index/autosave',
      data:"title="+title+"&content="+content+"&category="+category+"&privacy="+privacy+"&password="+password+"&mention="+mention+"&cc="+cc,
      success:function( result ){
        if( result != -1 ){
          string = result.split( ',' );
          $( '#autoSave' ).html( title+" 已于"+string[0]+"保存在草稿箱" );
          $( '#saveId' ).val( string[1] );
          $( '#autoSave' ).fadeIn('slow');
              setTimeout( function(){
                $( '#autoSave' ).fadeOut( 'slow' );
                },3000 );
        }
        $( '#saveButton' ).removeAttr( 'disabled');
        //$( '#saveButton' ).attr( 'class',butonclass );
      }
      })
  }else{
	  $( '#saveButton' ).removeAttr( 'disabled');
    if($( '#saveId' ).val() == "") return false;
  }
  //修改自动保存的记录
  if ( $( '#saveId' ).val() != "" ){

    //buttonclass = $( '#saveButton' ).attr( 'class' );
    //$( '#saveButton' ).attr( 'class','btn_w' );
    var updata = $( '#saveId' ).val();
    $.ajax( {
      type: 'POST',
      url: APP+'/Index/autosave',
      data:"title="+title+"&content="+content+"&category="+category+"&privacy="+privacy+"&password="+password+"&mention="+mention+"&cc="+cc+"&updata="+updata,
      success:function( result ){
        if( result != -1 ){
          string = result.split( ',' );
          $( '#autoSave' ).html( title+" 已于"+string[0]+"保存在草稿箱" );
          $( '#saveId' ).val( string[1] );
          $( '#autoSave' ).fadeIn('slow');
              setTimeout( function(){
                $( '#autoSave' ).fadeOut( 'slow' );
                },3000 );
          $( '#saveButton' ).removeAttr( 'disabled');
        }
       // $( '#saveButton' ).removeAttr( 'disabled');
        //$( '#saveButton' ).attr( 'class',butonclass );
      }
      })

  }
  $( '#saveButton' ).removeAttr( 'disabled');
  return true;
}




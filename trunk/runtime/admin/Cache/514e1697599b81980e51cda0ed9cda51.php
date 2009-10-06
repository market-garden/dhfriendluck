<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>站点配置</title>
        <link href="../Public/css/layout.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="container">
                <form action="__URL__/doIndex" method="post" enctype="multipart/form-data">        
            <table width="100%" border="0" cellpadding="4" cellspacing="0" class="form">

                    <tr class="top">
                        <td colspan="2" class="tit">站点配置</td>
                    </tr>

                    <tr>
                        <th class="th" width="140px" valign="top">站点名称：</th>
                        <td class="td"><input name="site_name" type="text" id="textfield" value="<?php echo ($opt['site_name']); ?>" /></td>
                    </tr>
                    <tr>
                        <th class="th" width="140px" valign="top">是否启用名片：</th>
                        <td class="td">
                         <select name="tips" id="select">
                   <option value="0"  <?php if($opt["tips"] == '0'){ ?>  selected="true" <?php } ?> >关闭</option>
                   <option value="1"  <?php if($opt["tips"] == '1'){ ?>  selected="true" <?php } ?> >启用</option>

                                                        </select>

                        </td>
                    </tr>
                    <tr>
                        <th class="th" valign="top">slogan：</th>
                        <td class="td"><input name="slogan" type="text" id="textfield2" size="50" value="<?php echo ($opt['slogan']); ?>"/></td>
                    </tr>
                    <tr>
                        <th class="th" valign="top">页面头信息描述：</th>
                        <td class="td">
                            <p><input name="site_header" type="text" id="textfield3" size="50" value="<?php echo ($opt['site_header']); ?>"/></p>
                            <p>若有多个，用|分割</p>
                        </td>
                    </tr>
                    <tr>
                        <th class="th" valign="top">页面头信息关键字：</th>
                        <td class="td">
                            <p><input name="site_keyword" type="text" id="textfield4" size="50" value="<?php echo ($opt['site_keyword']); ?>"/></p>
                            <p>若有多个，用|分割</p>

                        </td>
                    </tr>
                    <tr>
                        <th class="th" valign="top">选择模板：</th>
                        <td class="td"><p>
                                <select name="template" id="select">
									<?php if(is_array($themelist)): ?><?php $i = 0;?><?php $__LIST__ = $themelist?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php $isselected = (trim($vo['filename']) == $opt['template'])?'selected="true"':'' ?>
										<option value="<?php echo ($vo["filename"]); ?>" <?php echo ($isselected); ?>><?php echo ($vo["filename"]); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                                </select>
                            </p>
                            <p>站点模板目录存放在 ./template 下面。其中 default 目录为默认风格，不能删除。 </p></td>
                    </tr>
                      <tr>
                        <th class="th" valign="top">网站logo：</th>
                        <td class="td">
                        <p><a href="__THEME__/images/logo.jpg" target="_blank"><img src="__THEME__/images/logo.jpg"/></a></p>	
                        <p>
                            <input type="file" name="logo" value="" size="30"> <a href="http://thinksns.55.la" target="_blank">在线制作logo</a>(logo大小：137px × 40px)
                            </p>
                            </td>
                    </tr>
                    <tr>
                        <th class="th" valign="top">站点联系邮箱：</th>
                        <td class="td"><input name="email" type="text" id="textfield5" value="<?php echo ($opt['email']); ?>" /></td>
                    </tr>

                    <tr>
                        <th class="th" valign="top">首页动态显示条数：</th>
                        <td class="td"><input name="home_feed" type="text" id="textfield5" value="<?php echo ($opt['home_feed']); ?>" /></td>
                    </tr>
                    <th class="th" valign="top">动态优化选择：</th>
                    <td class="td">
                        <select name="feed_filter" id="select">
                            <option value="mini"  <?php if($opt["feed_filter"] == 'mini'){ ?>  selected="true" <?php } ?> >心情</option>
                            <option value="group"  <?php if($opt["feed_filter"] == 'group'){ ?>  selected="true" <?php } ?> >群组</option>
                            <option value="share"  <?php if($opt["feed_filter"] == 'share'){ ?>  selected="true" <?php } ?> >分享</option>
                            <option value="photo"  <?php if($opt["feed_filter"] == 'photo'){ ?>  selected="true" <?php } ?> >相册</option>
                            <option value="blog"  <?php if($opt["feed_filter"] == 'blog'){ ?>  selected="true" <?php } ?> >日志</option>
                            <option value="vote"  <?php if($opt["feed_filter"] == 'vote'){ ?>  selected="true" <?php } ?> >投票</option>
                            <option value="event"  <?php if($opt["feed_filter"] == 'event'){ ?>  selected="true" <?php } ?> >活动</option>
                            <option value="friend"  <?php if($opt["feed_filter"] == 'friend'){ ?>  selected="true" <?php } ?> >好友</option>
                            <option value="app"  <?php if($opt["feed_filter"] == 'app'){ ?>  selected="true" <?php } ?> >应用</option>
                            <option value="user"  <?php if($opt["feed_filter"] == 'user'){ ?>  selected="true" <?php } ?> >用户</option>
                        </select></td>
                    </tr>
                     <th class="th" valign="top">动态优化条数：</th>
                    <td class="td">
                         <input name="feed_filter_count" type="text" value="<?php echo ($opt['feed_filter_count']); ?>" />
                    </tr>

                    <tr>
                        <th class="th" valign="top">ICP/IP/域名备案：</th>
                        <td class="td"><p>
                                <input name="icp" type="text" id="textfield6" value="<?php echo ($opt['icp']); ?>" />
                            </p>
                            <p>			            (例如 京ICP备04000001号，显示在所有页面的最下面)</p></td>
                    </tr>
                    <tr>
                        <th class="th" valign="top">举报可选理由：</th>
                        <td class="td"><p>
                                <textarea name="report_reason" cols="50" id="textfield7"><?php echo ($opt['report_reason']); ?></textarea>
                            </p>
                            <p>预设举报可选理由，每行一个 </p></td>
                    </tr>
                    <tr>
                        <th class="th" valign="top">站点关闭访问：</th>
                        <td class="td"><input type="radio" name="site_close" id="radio" value="1" class="radio" <?php if($opt["site_close"] == 1){ ?>  checked="true" <?php } ?>/>
						    是  
                                <input type="radio" name="site_close" id="radio2" value="radio" class="0" <?php if($opt["site_close"] == 0){ ?>  checked="true" <?php } ?>/>
						      否</td>
                                    </tr>
                                    <tr>

                                        <tr>
                                            <th class="th" valign="top">游客访问模式：</th>
                                            <td class="td"><input type="radio" name="guest" id="radio" value="1" class="radio" <?php if($opt["guest"] == 1){ ?>  checked="true" <?php } ?>/>
						    是  
                                                    <input type="radio" name="guest" id="radio2" value="radio" class="0" <?php if($opt["guest"] == 0){ ?>  checked="true" <?php } ?>/>
						      否</td>
                                                        </tr>

						<tr>
						  <th class="th" valign="top">IM是否开启：</th>
						  <td class="td"><input type="radio" name="is_im_open" id="radio" value="1" class="radio" <?php if($opt["is_im_open"] == 1){ ?>  checked="true" <?php } ?>/>
						    是  
						      <input type="radio" name="is_im_open" id="radio2" value="0" class="0" <?php if($opt["is_im_open"] == 0){ ?>  checked="true" <?php } ?>/>
						      否</td>
		    </tr>			                 <tr>
                        <th class="th" width="160" valign="top">页面开启验证码功能：</th>
                        <td class="td"><p>
                                <input name="verify[login]" value="1" type="checkbox" class="checkbox" <?php if($verify["login"] == "1"){ ?> checked="checked" <?php } ?>/>
                                登录页面
                                <input name="verify[reg]" value="1" <?php if($verify["reg"] == "1"){ ?> checked="checked" <?php } ?> type="checkbox" class="checkbox" />
                            注册页面 </p>
                            </td>
                    </tr>
                                                        <tr>

                                                            <th class="th" valign="top">关闭访问理由：</th>
                                                            <td class="td"><textarea name="site_close_reason" cols="50" id="textfield9"><?php echo ($opt['site_close_reason']); ?></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <th valign="top">&nbsp;</th>
                                                            <td class="td"><input type="submit" class="button" id="button" value="提交" /></td>
                                                        </tr>

                                                        </table>
	 													</form>                                   
                                                        </div>
                                                        </body>
                                                        </html>
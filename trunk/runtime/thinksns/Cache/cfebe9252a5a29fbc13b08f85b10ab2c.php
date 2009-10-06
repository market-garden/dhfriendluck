<?php if(empty($fri_feeds)){ ?>
<div class="cGray2 lh35 alC f14px" style="margin:20px 0">暂时没有动态</div>
<?php }else{ ?>

<?php if(is_array($fri_feeds)): ?><?php $i = 0;?><?php $__LIST__ = $fri_feeds?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$feed): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div class="Fli" id="a_feed_<?php echo ($feed["id"]); ?>">
        <div class="ico_img"><img src="<?php echo ($feed["icon"]); ?>" /></div>
        <div class="LC" style="overflow:hidden;">
            <!--动态title-->
            <div class="tit" alt="<?php echo ($feed["id"]); ?>" id="feed_title_<?php echo ($feed["id"]); ?>">
                <div class="cl"><?php echo ($feed["title"]); ?></div>

                <div class="cr">
                    <div class="lh20"><em><?php echo (friendlyDate($feed["cTime"],'month')); ?></em></div>
                    <?php if(MODULE_NAME == "Home" || ($mid == $uid) ){ ?>
                        <?php if(MODULE_NAME == "Home"){ ?>
                            <div class="pt5"> <a href="javascript:del_feed(<?php echo ($feed["id"]); ?>);" class="del">删除</a></div>
                        <?php }else{ ?>
                            <div class="pt5"> <a href="javascript:del_feed(<?php echo ($feed["id"]); ?>,1);" class="del">删除</a></div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="c"></div>
            </div>
            <!--end-->
            <!--动态body-->
            <div class="RC" id="feed_body_<?php echo ($feed["id"]); ?>">
                <div <?php if($feed["type"] == "mini"){ ?> class="bg_huifu" <?php } ?> >
                    <?php if($feed['type'] == 'mini'){ ?>
                    
<input id="mini_comm_num_<?php echo ($feed['id']); ?>" value="<?php echo ($feed['comment_num']); ?>" type="hidden">
<div id="mini_comment_item_<?php echo ($feed['id']); ?>">
  <?php if(is_array($feed['comment'])): ?><?php $k = 0;?><?php $__LIST__ = $feed['comment']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$comm): ?><?php ++$k;?><?php $mod = ($k % 2 )?><?php if($k ==2 && $feed["comment_num"] != 2){ ?>
    <span id="feed_comm_middle_<?php echo ($feed['id']); ?>">
    <div class="RLI bg01 btmline"><a href="javascript:getOtherComm(<?php echo ($feed['id']); ?>)" id="zhan_kai_<?php echo ($feed['id']); ?>">显示全部<?php echo ($feed["comment_num"]); ?>条</a></div>
    </span>
    <?php } ?>
    <div class="RLI bg01 btmline">
      <div class="user_img"><a href="__APP__/space/<?php echo ($comm["uid"]); ?>"><img src="<?php echo (getUserFace($comm["uid"])); ?>" /></a></div>
      <div class="RLC">
        <h4><a href="__APP__/space/<?php echo ($comm["uid"]); ?>"><strong><?php echo (getUserName($comm["uid"])); ?></strong></a><span class="cGray2"><?php echo (friendlyDate($comm["cTime"])); ?></span></h4>
        <p><?php echo ($comm["comment"]); ?><a href="javascript:huifu_other(<?php echo ($feed['id']); ?>,<?php echo ($comm["uid"]); ?>,'<?php echo (getUserName($comm["uid"])); ?>');">回复</a></p>
      </div>
      <div class="c"></div>
    </div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
</div>
<div class="RLI bg01" id="post_con_<?php echo ($feed['id']); ?>" style="display:none;">
  <div class="user_img"><img src="<?php echo (getUserFace($mid)); ?>" /></div>
  <div class="RLC">
    <div class="input_box" style="width:370px">
      <textarea name="textarea" cols="" style="height:50px; line-height:18px; width:368px;"  id="mini_con_<?php echo ($feed['id']); ?>" onblur="blur_mini_con(<?php echo ($feed['id']); ?>);"></textarea>
      <input type="button" class="btn_b" value="回 复" onclick="post_mini_con(<?php echo ($feed['id']); ?>);"/>
    </div>
  </div>
  <div class="c"></div>
</div>
<div class="input_box bg01" id="small_con_<?php echo ($feed['id']); ?>">
  <textarea name="textarea2" cols="" rows="3" style="height:25px; line-height:25px; margin:5px; width:412px;" class="cGray2" onclick="pre_comment(this,<?php echo ($feed['id']); ?>);"> 添加回复</textarea>
</div>
<input type="hidden" id="hf_other_uid_<?php echo ($feed['id']); ?>">
 <?php echo ($feed["body"]); ?>
                    <?php }else{ ?>
                    <?php echo (html_output2($feed["body"])); ?>
                    <?php } ?>
                </div>
                <div class="c"></div>

            </div>
            <!--end-->
        </div>

    </div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
<?php } ?>
<input type='hidden' id='feed_type' value="<?php echo ($type); ?>">
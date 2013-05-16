<style>
/*
===================
管理操作
===================
*/
.pop_operat_reason {
	border-top:1px dotted #ccc;
}
.pop_operat_reason dt, .pop_operat_management dt {
	float:left;
	width:85px;
	line-height:26px;
}
.pop_operat_reason dd, .pop_operat_management dd {
	overflow:hidden;
	line-height:26px;
}
.pop_operat_management dl {
	padding:0 0 10px 0;
}
.pop_operat_management ul {
	padding-bottom:5px;
}
.pop_operat_management li {
	margin-bottom:3px;
	float:left;
	width:100%;
	border:0 none;
	background:#f7f7f7;
	padding:3px 0;
	border-radius: 3px;
}
.pop_operat_management li .ct {
	display:none;
	padding:5px 10px 10px;
}
.pop_operat_management li .hd {
	padding:0 5px;
}
.pop_operat_management li .hd label {
	display:inline-block;
 background:url(<?php echo htmlspecialchars(Wind::getComponent('response')->getData('G', 'url', 'images'), ENT_QUOTES, 'UTF-8');
?>/management/arrow_down.png) right center no-repeat;
	padding:0 5px;
	width:365px;
}
.pop_operat_management li.current .hd label {
 background:url(<?php echo htmlspecialchars(Wind::getComponent('response')->getData('G', 'url', 'images'), ENT_QUOTES, 'UTF-8');
?>/management/arrow_up.png) right center no-repeat;
}
.pop_operat_management li.current {
	background:#f1f1f1;
}
.pop_operat_management li.current .ct {
	display:block;
}
.pop_operat_management .title {
	padding:0 0 15px 15px;
}
.pop_operat_management .merger {
	padding:0 0 10px 15px;
}
.pop_operat_del {
	font-size:14px;
	padding:10px 15px 20px;
}
/*
===================
高亮
===================
*/
.sub_high_light {
}
.sub_high_light a {
	display:inline-block;
	padding:3px 5px;
	border:1px solid #ccc;
	background:#fff;
	margin-right:4px;
	vertical-align:middle;
}
.sub_high_light a:hover {
	text-decoration:none;
	border-color:#1377a9;
}
.sub_high_light a.current {
	background-color:#2298d3;
	border-color:#1377a9 #2298d3 #2298d3;
	color:#fff;
}
.sub_high_light .color_pick {
	margin-right:15px;
}
</style>
<div style="width:440px;">
  <form action="<?php echo WindUrlHelper::createUrl('bbs/manage/' . $doaction);?>" method="post" id="J_post_manage_ajaxForm">
    <div class="pop_top J_drag_handle"><a href="#" class="pop_close J_close">关闭</a><strong><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8');?></strong>(&nbsp;<span id="J_manage_checked_count" class="red"><?php echo htmlspecialchars($count, ENT_QUOTES, 'UTF-8');?></span>&nbsp;
      <?php if ($action == 'ban'){?>
      个
      <?php }else{?>
      篇
      <?php }?>
      )</div>
    <div class="pop_cont">
      <?php 
$combined1 = array('topped','digest','highlight','up');
$combined2 = array('down','lock');

  if (Pw::inArray($action, $combined1)) { ?>
      <div class="pop_operat_management">
        <ul class="cc">
          <?php  if ($operateThread['topped']) { ?>
          <li class="<?php echo htmlspecialchars(Pw::isCurrent($action == 'topped'), ENT_QUOTES, 'UTF-8');?>">
            <div class="hd">
              <input name="actions[]" type="checkbox" value="topped" class="J_toggle"<?php echo htmlspecialchars(Pw::ifcheck($action == 'topped'), ENT_QUOTES, 'UTF-8');?> />
              <label class="J_toggle">置顶</label>
            </div>
            <div class="ct">
              <select id="J_topped_select" class="select_3 mr20" name="topped">
                <option value="1">本版置顶</option>
                <?php  if ($operateThread['topped_type'] > 1) { ?>
                <option value="2"<?php echo htmlspecialchars(Pw::isSelected($defaultTopped == 2), ENT_QUOTES, 'UTF-8');?>>分类置顶</option>
                <?php  } 
  if ($operateThread['topped_type'] > 2) { ?>
                <option value="3"<?php echo htmlspecialchars(Pw::isSelected($defaultTopped == 3), ENT_QUOTES, 'UTF-8');?>>全局置顶</option>
                <?php  } ?>
                <option value="0" id="J_topped_cancel">取消置顶</option>
              </select>
              有效期：
              <input id="J_topped_time" name="topped_overtime" value="<?php echo htmlspecialchars($toppedOvertime, ENT_QUOTES, 'UTF-8');?>" type="date" class="input length_2 J_date" />
              <!--  
						<div id="J_topped_forums" style="display:none;">
							<select name="topped_fids[]" class="select_3" size="5" multiple>
								<?php echo $forumOption;?>
							</select>
						</div>
						--> 
            </div>
          </li>
          <?php  } 
  if ($operateThread['digest']) { ?>
          <li class="<?php echo htmlspecialchars(Pw::isCurrent($action == 'digest'), ENT_QUOTES, 'UTF-8');?>">
            <div class="hd">
              <input name="actions[]" type="checkbox" value="digest" class="J_toggle"<?php echo htmlspecialchars(Pw::ifcheck($action == 'digest'), ENT_QUOTES, 'UTF-8');?> />
              <label class="J_toggle">精华</label>
            </div>
            <div class="ct">
              <select id="J_digest_select" class="select_3 mr20" name="digest">
                <option value="1">设为精华</option>
                <option value="0">取消精华</option>
              </select>
            </div>
          </li>
          <?php  } 
  if ($operateThread['highlight']) { ?>
          <li class="<?php echo htmlspecialchars(Pw::isCurrent($action == 'highlight'), ENT_QUOTES, 'UTF-8');?>">
            <div class="hd">
              <input name="actions[]" type="checkbox" value="highlight" class="J_toggle"<?php echo htmlspecialchars(Pw::ifcheck($action == 'highlight'), ENT_QUOTES, 'UTF-8');?> />
              <label class="J_toggle">加亮</label>
            </div>
            <div class="ct">
              <div class="sub_high_light"> <a data-id="J_font_bold" href="#" class="J_font_style<?php echo htmlspecialchars(Pw::isCurrent($hightlightStyle['bold']), ENT_QUOTES, 'UTF-8');?>">加粗</a> <a data-id="J_font_italic" href="#" class="J_font_style<?php echo htmlspecialchars(Pw::isCurrent($hightlightStyle['italic']), ENT_QUOTES, 'UTF-8');?>">斜体</a> <a data-id="J_font_underline" href="#" class="J_font_style<?php echo htmlspecialchars(Pw::isCurrent($hightlightStyle['underline']), ENT_QUOTES, 'UTF-8');?>">下划线</a> <span class="color_pick J_color_pick"><em class="J_bg" style="<?php  if ($hightlightStyle['color']) { ?>background:<?php echo htmlspecialchars($hightlightStyle['color'], ENT_QUOTES, 'UTF-8');?>;<?php  } ?>"></em></span>
                <input class="J_hidden_color" type="hidden" name="color" value="<?php echo htmlspecialchars($hightlightStyle['color'], ENT_QUOTES, 'UTF-8');?>" />
                有效期：
                <input name="highlight_overtime" value="<?php echo htmlspecialchars($hightlightOvertime, ENT_QUOTES, 'UTF-8');?>" type="date" class="input length_2 J_date" />
              </div>
              <input<?php echo htmlspecialchars(Pw::ifcheck($hightlightStyle['bold']), ENT_QUOTES, 'UTF-8');?> class="dn" type="checkbox" name="bold" value="1" id="J_font_bold" />
              <input<?php echo htmlspecialchars(Pw::ifcheck($hightlightStyle['italic']), ENT_QUOTES, 'UTF-8');?> class="dn" type="checkbox" name="italic" value="1" id="J_font_italic" />
              <input<?php echo htmlspecialchars(Pw::ifcheck($hightlightStyle['underline']), ENT_QUOTES, 'UTF-8');?> class="dn" type="checkbox" name="underline" value="1" id="J_font_underline" />
            </div>
          </li>
          <?php  } 
  if ($operateThread['up']) { ?>
          <li class="<?php echo htmlspecialchars(Pw::isCurrent($action == 'up'), ENT_QUOTES, 'UTF-8');?>">
            <div class="hd">
              <input name="actions[]" type="checkbox" value="up" class="J_toggle"<?php echo htmlspecialchars(Pw::ifcheck($action == 'up'), ENT_QUOTES, 'UTF-8');?> />
              <label class="J_toggle">提前</label>
            </div>
            <div class="ct">提前时间：
              <input name="uptime" id="J_uptime" type="number" class="input length_2 mr5" />
              小时</div>
          </li>
          <?php  } ?>
        </ul>
      </div>
      <?php  } elseif (Pw::inArray($action, $combined2)) { 
 
		if ($defaultValues['lock']['locked'] == 1){
			$_checked_locked1 = ' checked';
		} else if ($defaultValues['lock']['locked'] == 2){
			$_checked_locked2 = ' checked';
		} else {
			$_checked_locked0 = ' checked';
		}
		?>
      <div class="pop_operat_management">
        <ul class="cc">
          <?php  if ($operateThread['lock']) { ?>
          <li class="<?php echo htmlspecialchars(Pw::isCurrent($action == 'lock'), ENT_QUOTES, 'UTF-8');?>">
            <div class="hd">
              <input name="actions[]" type="checkbox" value="lock" data-id="J_toggle_lock" class="J_toggle"<?php echo htmlspecialchars(Pw::ifcheck($action == 'lock'), ENT_QUOTES, 'UTF-8');?> />
              <label class="J_toggle">锁定</label>
            </div>
            <div class="ct" id="J_toggle_lock">
              <label class="mr20">
                <input name="locked" type="radio" value="0"<?php echo htmlspecialchars(Pw::ifcheck($defaultLocked == 0), ENT_QUOTES, 'UTF-8');?> />
                解除锁定</label>
              <label class="mr20">
                <input name="locked" type="radio" value="1"<?php echo htmlspecialchars(Pw::ifcheck($defaultLocked == 1), ENT_QUOTES, 'UTF-8');?> />
                锁定</label>
              <label class="mr20">
                <input name="locked" type="radio" value="2"<?php echo htmlspecialchars(Pw::ifcheck($defaultLocked == 2), ENT_QUOTES, 'UTF-8');?> />
                关闭</label>
            </div>
          </li>
          <?php  } 
  if ($operateThread['down']) { ?>
          <li class="<?php echo htmlspecialchars(Pw::isCurrent($action == 'down'), ENT_QUOTES, 'UTF-8');?>">
            <div class="hd">
              <input name="actions[]" type="checkbox" value="down" data-id="J_toggle_down" class="J_toggle"<?php echo htmlspecialchars(Pw::ifcheck($action == 'down'), ENT_QUOTES, 'UTF-8');?> />
              <label class="J_toggle">压帖</label>
            </div>
            <div class="ct" id="J_toggle_down"> <span class="mr20">押后时间：
              <input name="downtime" type="number" class="input length_2 mr5" />
              小时</span>
              <label>
                <input name="downed" type="checkbox" value="1" checked="checked" />
                不允许上浮</label>
            </div>
          </li>
          <?php  } ?>
        </ul>
      </div>
      <?php  } elseif ($action == 'delete') { ?>
      <div class="not_content_mini"><i></i>确定要删除选中的主题？</div>
      <?php  } elseif ($action == 'move') { ?>
      <div class="pop_operat_management">
        <dl class="cc">
          <dt>移&nbsp;动&nbsp;到：</dt>
          <dd>
            <select name="fid" class="select_3 mr10" id="J_postmanage_forum" data-url="<?php echo Wind::getComponent('response')->getData('G', 'url', 'base'),'/','index.php?c=cate&a=topictypes'; ?>">
              <option value="0">请选择版块</option>
              <?php echo $option_html;?>
            </select>
          </dd>
        </dl>
        <dl class="cc" id="J_postmanage_topic" style="display:none;">
          <dt>主题分类：</dt>
          <dd> 
            <!-- <input type="hidden" name="topic_type_id" value="" id="J_topic_type_id" /> -->
            <select id="J_postmanage_topictype" class="mr10" name="topictype">
              <option value="0">请选择分类</option>
            </select>
            <select id="J_postmanage_subtopictype" class="mr10" style="display:none;" name="sub_topictype">
            </select>
          </dd>
        </dl>
        <input name="actions[]" type="hidden" value="move"/>
      </div>
      <?php  } elseif ($action == 'copy') { ?>
      <div class="pop_operat_management">
        <dl class="cc">
          <dt>复&nbsp;制&nbsp;到：</dt>
          <dd>
            <select name="fid" class="select_3 mr10" id="J_postmanage_forum" data-url="<?php echo Wind::getComponent('response')->getData('G', 'url', 'base'),'/','index.php?c=cate&a=topictypes'; ?>">
              <option value="0">请选择版块</option>
              <?php echo $option_html;?>
            </select>
          </dd>
        </dl>
        <dl class="cc" id="J_postmanage_topic" style="display:none;">
          <dt>主题分类：</dt>
          <dd> 
            <!-- <input type="hidden" name="topic_type_id" value="" id="J_topic_type_id" /> -->
            <select id="J_postmanage_topictype" class="mr10" name="topictype">
              <option value="0">请选择分类</option>
            </select>
            <select id="J_postmanage_subtopictype" class="mr10" style="display:none;" name="sub_topictype">
            </select>
          </dd>
        </dl>
        <input name="actions[]" type="hidden" value="copy"/>
      </div>
      <?php  } elseif ($action == 'type') { ?>
      <div class="pop_operat_management">
        <dl class="cc">
          <dt>主题分类：</dt>
          <dd>
            <input type="hidden" name="topic_type_id" value="" id="J_topic_type_id" />
            <select id="J_postmanage_type_topictype" class="mr10" name="topictype">
              <option value="0">请选择分类</option>
              <?php  foreach($topicTypes as $k => $v) { ?>
              <option value="<?php echo htmlspecialchars($k, ENT_QUOTES, 'UTF-8');?>"><?php echo htmlspecialchars(strip_tags($v['name']), ENT_QUOTES, 'UTF-8');?></option>
              <?php  } ?>
            </select>
            <select id="J_postmanage_subtopictype" class="mr10" style="display:none;" name="sub_topictype">
              <option value="0">请选择二级分类</option>
            </select>
          </dd>
        </dl>
        <input name="actions[]" type="hidden" value="type"/>
      </div>
      <script>var THREAD_SORT = <?php echo WindJson::encode($topicTypes, 'UTF-8');?>;</script> 
      <!--===========用户禁止================-->
      <?php  } elseif ($action == 'ban') { ?>
      <div class="pop_operat_management">
        <dl class="cc">
          <dt>禁止对象：</dt>
          <dd>
            <?php foreach ($userNames as $_item) {
 echo htmlspecialchars($_item['username'], ENT_QUOTES, 'UTF-8');?>
            <span class="s1" style="display:none">(管理员权限点判断)</span>,
            <input type="hidden" value="<?php echo htmlspecialchars($_item['uid'], ENT_QUOTES, 'UTF-8');?>" name="uids[]" />
            <?php }?>
        </dl>
        <dl class="cc">
          <dt>禁止类型：</dt>
          <dd>
            <label class="mr10">
              <input type="checkbox" value="1" name="types[]" />
              禁止发布</label>
            <label class="mr10">
              <input type="checkbox" value="2" name="types[]" />
              禁止头像</label>
            <label class="mr10">
              <input type="checkbox" value="4" name="types[]" />
              禁止签名</label>
          </dd>
        </dl>
        <dl class="cc">
          <dt>有效期至：</dt>
          <dd>
            <input type="text" value="" name="end_time" class="input J_datetime date">
          </dd>
        </dl>
      </div>
      <?php }?>
      <div class="pop_operat_reason">
        <dl class="cc">
          <dt>操作理由：</dt>
          <dd>
            <input name="reason" type="text" id="J_resson_input" placeholder="写点操作理由吧" class="input length_5 mb10" />
            <select id="J_resson_select" class="select_5" size="5">
              <?php  foreach ($manageReason as $val) {
						$val = trim($val);
						if (!$val) continue;
						($val == '------') && $val = '----------------------------';
					?>
              <option><?php echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8');?></option>
              <?php }?>
            </select>
          </dd>
        </dl>
      </div>
    </div>
    <div class="pop_bottom cc">
      <button type="submit" class="btn btn_submit fr" id="J_sub_topped">提交</button>
      <span class="fl">
      <label class="mr10">
        <input name="sendnotice" type="checkbox" value="1" />
        发送通知</label>
      <?php if ($action == 'ban') {
 if (1 === $right['delCurrentThread']) {?>
      <label class="mr10">
        <input type="checkbox" name="delete[current]" value="1" />
        删除当前主题</label>
      <?php }if (1 === $right['delForumThread']) {?>
      <label class="mr10">
        <input type="checkbox" name="delete[forum]" value="1" />
        删除本版主题</label>
      <?php }if(1 === $right['delSiteThread']) {?>
      <label class="mr10">
        <input type="checkbox" name="delete[site]" value="1" />
        删除全站主题</label>
      <?php }
  } elseif ($action == 'delete') { ?>
      <label>
        <input name="deductCredit" type="checkbox" value="1" checked />
        扣除积分</label>
      <?php  } elseif (1) { ?>
      <!--label><input name="params[_other][print]" type="checkbox" value="" />显示印戳</label-->
      <?php  } ?>
      </span> </div>
    <input type="hidden" name="csrf_token" value="<?php echo WindSecurity::escapeHTML(Wind::getComponent('windToken')->saveToken('csrf_token')); ?>"/>
  </form>
</div>

<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	/* 更新自定义橱窗 */
	$(document).ready(function(){
		$('form[name="update_topic"]').on('submit',function(e){
			e.preventDefault();
			$('form[name="update_topic"]').ajaxSubmit({
				success:function(data){
					dataType:"json",
						ecjia.admin.showmessage(data);
				}
			});
		});
	});
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link}<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a" ><i class="fontello-icon-reply"></i>{$action_link.text}</a>{/if} -->
	</h3>
</div>
<!-- start goods form -->
<div class="row-fluid ">
	<div class="span12">
		<div class="tabbable">
			<form class="form-horizontal" action="{$form_action}" method="post" enctype="multipart/form-data" name="update_topic" data-edit-url="{RC_Uri::url('article/admin/edit')}">
                <fieldset>
					<div class="control-group control-group-small" >
						<label class="control-label">{t}关联专题：{/t}</label>
						<div class="controls">
							<select name="topic_link" class="w350">
								<option value="0">请选择</option>
								<!--{foreach from=$topic item=rs}-->
                                <option {if $id eq $rs.topic_id} selected="selected" {/if} value="{$rs.topic_id}">{$rs.title}</option>
								<!-- {/foreach} -->
							</select>
							<span class="input-must">*</span>
						</div>
					</div>

					<div class="control-group control-group-small" >
						<label class="control-label">{t}专题页名称：{/t}</label>
						<div class="controls">
							<input class="f_l w350" type="text" name="topic_name" value="{$file.Name}" placeholder="在此输入专题页名称" />
							<span class="input-must">*</span>
							<!-- {if $overdue} -->
							<span class="input-must">{$overdue}</span>
							<!-- {/if} -->
						</div>
					</div>

					<div class="control-group control-group-small" >
						<label class="control-label">{t}专题页备注：{/t}</label>
						<div class="controls">
							<textarea class="f_l w350" type="text" name="topic_desc" value="{$goods.goods_name|escape}" placeholder="在此输入专题页备注" >{$file.Description}</textarea>
							<span class="input-must">*</span>
						</div>
					</div>

					<div class="control-group control-group-small" >
						<label class="control-label">{t}编辑模式：{/t}</label>
						<div class="controls">
                            <input type="radio" name="is_show" id="" value="1" {if $cat_info.is_show}checked="checked"{/if}  /><span>{t}可视化编辑{/t}</span>
                            <input type="radio" name="is_show" id="" value="0" {if !$cat_info.is_show}checked="checked"{/if}  /><span>{t}纯代码编辑{/t}</span>
						</div>
					</div>
				</fieldset>

				<h3 class="heading">专题内容</h3>
				<div class="control-group control-group-small" >
					<div class="row-fluid">
						<div class="span12">
							{if 1}
							<textarea class="span12" name="content">{$template_html}</textarea>
							{else}
							{ecjia:editor content=$template_html textarea_name='html'}
							{/if}
						</div>
					</div>
				</div>
				<div class="control-group control-group-small">
					<div class="controls">
						<input type="hidden" name="tpl" value="{$template}">
						<input class="btn btn-gebo" type="submit" value="保存">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- end goods form -->
<!-- {/block} -->

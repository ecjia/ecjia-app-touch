<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	/* 更新自定义橱窗 */
	$(document).ready(function(){
		$('form[name="update_template"]').on('submit',function(e){
			e.preventDefault();
			$('form[name="update_template"]').ajaxSubmit({
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
			<form class="form-horizontal active" name="update_template" action="{$form_url}" method="post">
				<div class="control-group control-group-small">
					<label class="control-label">模板名称：</label>
					<div class="controls">
						<input class="span10" type="text" name="template_name" value="{$file.Name}" size="40">
					</div>
				</div>
				<div class="control-group control-group-small">
					<label class="control-label">模板描述：</label>
					<div class="controls">
						<textarea class="span10 h100" name="template_desc">{$file.Description}</textarea>
					</div>
				</div>
				<div class="control-group control-group-small">
					<label class="control-label">模板文件：</label>
					<div class="controls">
						<textarea class="span10 h600" name="html">{$template_html}</textarea>
					</div>
				</div>
				<div class="control-group control-group-small">
					<div class="controls">
						<input type="hidden" name="tpl" value="{$template}">
						<input class="btn btn-gebo" type="submit" value="确定">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- end goods form -->
<!-- {/block} -->

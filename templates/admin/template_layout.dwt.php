<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.admin_template_setup.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<h3 class="heading">
	<!-- {if $ur_here}{$ur_here}{/if} -->
</h3>
<div class="row-fluid">
	<div class="mock-table span12" data-url="{url path='warehouse/admin/get_region_list'}">
		<div class="list media_captcha wookmark warehouse">
			<ul>
            <!-- {foreach from=$template_files item=val} -->
				<li class="thumbnail">
					<div class="bd">
						<div class="model-title ware_name">{$val.Name}</div>
					</div>

					<div class="input">
						<a href="{url path='touch/admin_layout/edit' args="template_file={$val.File}"}" title="{t}编辑地区{/t}"><i class="fontello-icon-edit" title="{t}编辑主题{/t}"></i></a>
					</div>
				</li>
            <!-- {/foreach} -->
			</ul>
		</div>
	</div>
</div>
<!-- {/block} -->

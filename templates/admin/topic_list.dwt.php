<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="btn" href="{$action_link.href}" id="sticky_a" style="float:right;margin-top:-3px;">{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="mock-table span12" data-url="{url path='warehouse/admin/get_region_list'}">

						<div class="list media_captcha wookmark warehouse">
							<ul>
								<!-- {foreach from=$topic_page_list item=val key=key} -->
								<li class="thumbnail">
									<div class="bd">
										<div class="model-title ware_name">{$val}</div>
									</div>

									<div class="input">
										<a href="{url path='touch/admin_topic/edit' args="value={$key}"}" title="{t}编辑地区{/t}"><i class="fontello-icon-edit" title="{t}编辑主题{/t}"></i></a>
										<a data-toggle="ajaxremove" data-msg="{t}您确定要删除此主题吗？{/t}" href="{url path='touch/admin_topic/drop_topic' args="value={$key}"}" title="{t}删除主题{/t}"><i class="fontello-icon-trash ecjiafc-red" title="{t}删除仓库{/t}"></i></a>
									</div>
								</li>
								<!-- {/foreach} -->
								<li class="thumbnail add-ware-house">
									<a class="more"  href="{url path='touch/admin_topic/add'}">
										<i class="fontello-icon-plus"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- {/block} -->
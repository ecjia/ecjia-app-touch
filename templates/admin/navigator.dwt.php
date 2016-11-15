<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.admin_nav.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>		
</div>
<div id="navigator">
	<input class="hide_url" type="hidden" value="{url path='touch/admin_navigator/update_nav'}">
	<div class="row-fluid nav_main">
		<div class="span3 nav_add">
			<div class="accordion" id="accordion2">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle is-accordion" data-parent="#accordion2" data-toggle="collapse" href="#collapseTwo2">{t}页面{/t}</a>
					</div>
					<div class="accordion-body collapse in" id="collapseTwo2">
						<div class="accordion-inner">
							<!-- {foreach from=$pagenav item=nav} -->
							<label><input class="nav_url" type="checkbox" value="{$nav.url}" data-icon="{$nav.icon}" data-bgc="{$nav.bgc}" /><input class="nav_icon" type="hidden" value="{$nav.icon}" /><span class="nav_name">{$nav.name}</span></label>
							<!-- {/foreach} -->
							<p><a class="checkall btn btn-mini" href="javascript:;">{t}全选{/t}</a><a class="btn btn-mini btn-info f_r addtolist m_b10" href="javascript:;">{t}添加至菜单{/t}</a></p>
						</div>
					</div>
				</div>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle is-accordion" data-parent="#accordion2" data-toggle="collapse" href="#collapseOne2">{t}链接{/t}</a>
					</div>
					<div class="accordion-body collapse" id="collapseOne2">
						<div class="accordion-inner">
							<p>{t}URL：{/t}<input class="span10 nav_url" type="text" name="nav_url" /></p>
							<p>{t}文字：{/t}<input class="span10 nav_name" type="text" /></p>
							<p><a class="btn btn-mini btn-info f_r addtolist m_b10" href="javascript:;">{t}添加至菜单{/t}</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span9 nav_edit">
			<div class="nav_edit_hd">
				<label for="nav_name">{t}菜单名称：{/t}</label><input type="hidden" name="nav_type" value="{$nav_type}" /><input id="navlist_name" type="text" name="navlist_name" value="{$nav_name}" readonly /><input type="hidden" id="navlist_del" name="navlist_del" value="0" /><a class="btn btn-info f_r navlist_submit" data-url="{url path='touch/admin_navigator/edit_nav'}" href="javascript:;">{t}保存排序{/t}</a>
			</div>
			<div class="nav_edit_bd">
				<div class="control-group formSep">
					<h3>{t}菜单结构{/t}</h3>
					<span class="help-block">{t}拖放各个项目到您喜欢的顺序，点击右侧的箭头可进行更详细的设置。{/t}</span>
					<div class="controls moveaccordion">
						<div class="span5">
							<!-- {foreach from=$navdb item=nav key=id} -->
							<div class="w-box" id="{$nav.type}{$id}" value="{$id}">
								<div class="w-box-header">
									{$nav.name}
									<span class="fontello-icon-down-open portlet-toggle"></span>
								</div>
								<div class="w-box-content hide">
									<form action="{url path='touch/admin_navigator/update_nav'}" method="post" name="icon-submit">
										<input type="hidden" name="id" value="{$nav.id}" />
										<input type="hidden" name="vieworder" value="{$nav.vieworder}" />
										<p>{t}URL:{/t}<input class="span12" type="text" name="url" value="{$nav.url}" /></p>
										<p>{t}导航标签{/t}<input class="span12" type="text" name="name" value="{$nav.name}" /></p>
										<div>{t}标签图标：{/t}
											<input type="hidden" name="icon" value="{$nav.icon}">
											<div class="fileupload {if $nav.icon}fileupload-exists{else}fileupload-new{/if}" data-provides="fileupload" style="display:inline">
												<div class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">
													{if $nav.icon_path}
													<img src="{$nav.icon_path}" alt="图片预览" />
													{/if}
												</div>
											<span class="btn btn-file">
												<span  class="fileupload-new">浏览</span>
												<span  class="fileupload-exists">修改</span>
												<input type='file' name='iconimg' size="35"/>
											</span>
												<a class="btn fileupload-exists" data-toggle="removefile" data-msg="{t}您确定要删除此标签图标吗？{/t}" data-href='{url path="touch/admin_navigator/del_icon" args="id={$nav.id}"}' {if $nav.icon_path}data-removefile="true"{/if}>删除</a>
											</div>
										</div>
										<p>
											<label>
												<input type="checkbox" name="ifshow" {if $nav.ifshow}checked{/if} />{t}是否显示{/t}
											</label>
											<label>
												<input type="checkbox" name="opennew" {if $nav.opennew}checked{/if} />{t}是否新窗口{/t}
											</label>
										</p>
										<p><a class="btn btn-mini btn-danger del_nav">{t}移除{/t}</a><input type="submit" class="btn btn-mini btn-success btn-submit" value="确定" style="float:right;"></p>
									</form>
								</div>
							</div>
							<!-- {/foreach} -->
						</div>
					</div>
				</div>
				<!-- <div class="control-group">
					<h3>{t}菜单设置{/t}</h3>
					<label class="help-block">{t}主题位置{/t}</label>
					<div class="nav_set">
						<label>
							<input name="nav1" type="checkbox" value="option1">{t}首页/列表页菜单{/t}
							<span class="help-inline">{t}（当前设置：底部）{/t}</span>
						</label>
						<label>
							<input name="nav1" type="checkbox" value="option1">{t}页脚菜单{/t}
							<span class="help-inline">{t}（当前设置：底部）{/t}</span>
						</label>
						<label>
							<input name="nav1" type="checkbox" value="option1">{t}播放页菜单{/t}
							<span class="help-inline">{t}（当前设置：底部）{/t}</span>
						</label>
						<label>
							<input name="nav1" type="checkbox" value="option1">{t}新闻页菜单{/t}
							<span class="help-inline">{t}（当前设置：底部）{/t}</span>
						</label>
					</div>
				</div> -->
			</div>
			<div class="nav_edit_ft">
				<a class="btn btn-info f_r navlist_submit" data-url="{url path='touch/admin_navigator/edit_nav'}" href="javascript:;">{t}保存排序{/t}</a>
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->
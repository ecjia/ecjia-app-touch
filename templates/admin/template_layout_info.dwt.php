<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<style media="screen">
    .touch-layout * {
        box-sizing: border-box;
    }
    .touch-layout {
        position: relative;
        width: 100%;
    }
        .touch-layout .touch-layout-left {
            width: 100%;
            padding-right: 400px;
            margin-bottom: 20px;
            min-height: 650px;
        }
            .touch-layout ul {
                margin: 0;
            }
            .touch-layout .touch-layout-left .template-lib-list {
                position: relative;
                display: block;
                border: 1px solid #ccc;
                padding: 10px;
            }
            .touch-layout .touch-layout-left .template-lib-list h4 {
                line-height: 50px;
                font-size: 1.4em;
                border-bottom: 1px solid #ccc;
                margin-bottom: 10px;
            }
            .touch-layout .touch-layout-left .template-lib-list li {
                display: inline-block;
                width: 48%;
                margin-right: 2%;
                vertical-align: top;
            }
            .touch-layout .touch-layout-left .template-lib-list li:nth-of-type(2n) {
                margin-right: 0;
            }
            .touch-layout .touch-layout-left .template-lib-list li:nth-of-type(2n+1) {
                clear: both;
            }
            .touch-layout li {
                position: relative;
                border: 1px solid #e5e5e5;
                background: #f7f7f7;
                margin-bottom: 10px;
                padding: 15px;
                list-style: none;
                cursor: move;
                padding-right: 70px;
            }
                .touch-layout li .edit {
                    display: none;
                    position: absolute;
                    top: 50%;
                    right: 15px;
                    margin-top: -1em;
                }
                .touch-layout li:hover .edit {
                    display: block;
                }
                    .touch-layout li .edit a {
                        font-size: 1.4em;
                    }
            .touch-layout li:hover {
                border: 1px solid #999;
            }
            .ui-dragging {
                width: 340px;
            }
            .touch-layout li.layout-placeholder {
                border: 1px dashed #999;
                background: #fefefe;
                height: 50px;
            }
        .touch-layout .touch-layout-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 380px;
            background: #eee;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 45px 20px 100px;
            height: 650px;
        }
        .touch-layout .touch-layout-right .template-name {
            position: absolute;
            top: 10px;
            left: 50%;
            font-size: 1em;
            margin-left: -120px;
            width: 240px;
            text-align: center;
            background: #ededed;
            padding: 2px;
            border: 1px solid #ccc;
            border-radius: 20px;
            overflow: hidden;
        }
        .touch-layout .touch-layout-right .phone {
            position: relative;
            padding: 10px;
            height: 100%;
            background: #fff;
            overflow-y: scroll;
        }
        .touch-layout .touch-layout-right .btn {
            position: absolute;
            left: 50%;
            margin-left: -30px;
            width: 60px;
            height: 60px;
            border-radius: 100%;
            bottom: 20px;
        }
</style>
<script type="text/javascript">
$(function() {
    $( ".page-lib" ).sortable({
		placeholder	: 'layout-placeholder',
		cursor		: 'move',
		distance	: 2,
		containment	: 'document',
        revert: false
    });
    $( ".template-lib li" ).draggable({
		connectToSortable	: '.page-lib',
		handle				: '.template-lib li',
		distance			: 2,
		helper				: 'clone',
		zIndex				: 100,
		containment			: 'document',
        cursorAt:{
            top:25,
            left:170
        },
    });
    $( "ul, li" ).disableSelection();
});
(function(ecjia, $) {
    ecjia.admin.touch_layout = {
        init : function() {
            this.page_lib_del();
        },

        page_lib_del : function() {
            $('.touch-layout-right .edit .fontello-icon-trash').on('click', function(e) {
                e.preventDefault();
                console.log($(this).parents('li'));
                $(this).parents('li').remove();
            });
        }
    }
    ecjia.admin.touch_layout.init();
})(ecjia, $);
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link}<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a" ><i class="fontello-icon-reply"></i>{$action_link.text}</a>{/if} -->
	</h3>
</div>

<div class="touch-layout">
    <div class="touch-layout-left">
        <div class="template-lib-list">
            <h4>可用橱窗列表
        		<a class="btn" href="{url path='touch/admin_showcase/add'}" style="float:right;margin-top:10px;">{t}添加自定义橱窗{/t}</a>
            </h4>
            <ul class="template-lib">
                <!-- {foreach from=$template_libs item=lib} -->
                <li class="ui-state-default">
                    <!-- {$lib.Name} -->
                    <input type="hidden" name="regions[]" value="{$lib.File}" />
                    <div class="edit">
                        <a href="{url path='touch/admin_showcase/edit' args="value={$lib.File}"}"><i class="fontello-icon-edit"></i></a>
                        <a href="#"><i class="fontello-icon-trash ecjiafc-red"></i></a>
                    </div>
                </li>
                <!-- {/foreach} -->
            </ul>
        </div>
    </div>
    <form action="{url path='touch/admin_layout/update'}" method="post">
        <div class="touch-layout-right">
            <div class="template-name">
                {$template_info.Name}
            </div>
            <div class="phone">
                <ul class="page-lib">
            	<!-- {foreach from=$temp_options item=lib} -->
                    <li class="ui-state-highlight">
                        <!-- {$lib.name} -->
                        <input type="hidden" name="regions[]" value="{$lib.library}" />

                        <div class="edit">
                            <a href="#"><i class="fontello-icon-eye"></i></a>
                            <a href="#"><i class="fontello-icon-trash ecjiafc-red"></i></a>
                        </div>
                    </li>
            	<!-- {/foreach} -->
                </ul>
            </div>
            <input type="hidden" name="region_name" value="{$region_name}">
            <input type="hidden" name="template_file" value="{$curr_template}">
            <button class="btn" type="submit">保存</button>
        </div>
    </form>
</div>



<!-- {/block} -->

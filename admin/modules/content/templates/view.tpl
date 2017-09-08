{alias_context code="admin:content"}

<div class="panelLayout module-content method-edit">

	{strip}
		{assign var=cookieName value="submenu:collapsed"}
		<nav class="secondaryPanel secondaryPanel--objectsTree{if $smarty.cookies.$cookieName} secondaryPanel--collapsed{/if}">
			<div class="secondaryPanel__triggerArea">
	            <div class="secondaryPanel__trigger">
	                <img src="{$smary.const.ADMIN_WWW}images/secondaryPanel-trigger.png" alt="" />
	            </div>
	        </div>

	        <ul class="secondaryPanel__objectTools">
            	<li class="secondaryPanel__objectTool" id="deleteConfirm">
            		<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{request_url}#trash"></use></svg>
            		<span>{alias code=delete}</span>
            	</li>
            	<li class="secondaryPanel__objectTool" id="moveConfirm">
            		<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{request_url}#parvietot"></use></svg>
            		<span>{alias code=move}</span>
            	</li>
            	<li class="secondaryPanel__objectTool" id="copyConfirm">
            		<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{request_url}#kopet"></use></svg>
            		<span>{alias code=copy}</span>
            	</li>
            </ul>

			<form class="secondaryPanel__objectsForm" id="objectForm" action="index.php?module=content&amp;do=" method="post">
				{$objects_tree}
			</form>

		</nav>
	{/strip}

	<div class="primaryPanel">
		<div class="primaryPanel__content">
			<div class="header">
				<div class="padding">
					<h2>
						{if $_module->active_object.name}
							{$_module->active_object.name|escape}
						{/if}
					</h2>
					{*<button style="float:right; margin-top: -2px;" onclick="jQuery(body).toggleClass('fullscreen');this.blur();">toggle fullscreen</button>*}
				</div>
			</div>


			
				{if $objectModules}
					<ul id="leafObjectModules" >
						{foreach from = $objectModules item = module }
                            <li class="pure-form">
                                <a href="?module=content&amp;object_module={$module.module_name|escape}&amp;object_id={$smarty.get.object_id|escape}" class="pure-btn" >
                                    <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{request_url}#tiesibas">
                                    </use></svg>{alias code=$module.module_name context=admin:moduleNames}
                                </a>
                            </li>
						{/foreach}
					</ul>
				{/if}
	            {if !$_module->active_object}
	                {include file="search.tpl"}
	            {/if}
				{$content}
			


		</div>
	</div>
	{* start of old bad code :/  *}
	<div id="objectPanel"></div>
	<div id="loading">
		<img src="modules/content/img/loading.gif" alt="" />
	</div>
	{* end of old bad code :) *}
</div>

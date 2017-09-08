{alias_fallback_context code="admin:emails"}
{include file=$_module->useWidget('i18nInput') name=subject className=focusOnReady }
{include file=$_module->useWidget('i18nInput') name=plain type=textarea descriptionAlias=variables descriptionAliasVars=$variables fieldWrapClass="pure-u-1 content-space plainText"}
{include file=$_module->useWidget('i18nInput') name=html type=richtext descriptionAlias=variables descriptionAliasVars=$variables}
{alias_fallback_context code="admin:leafBaseModule"}
<?php

// sets default alias context for current template

function smarty_function_alias_context($params, & $smarty)
{
	require_once(SHARED_PATH . 'classes/smarty_plugins/alias_cache.class.php');
    // validate params
    // 'code' must be set to string
    if (
        (!isset($params['code']))
    )
    {
        return 'missing code argument for alias_context.';
    }
    elseif (!is_string($params['code']))
    {
        return 'bad code argument for alias_context.';
    }
    $code = $params['code'];

    alias_cache::setContext($smarty, $code);

    if (isset($params['fallback']))
    {
        if (!is_string($params['fallback']))
        {
            return 'bad fallback argument for alias_context.';
        }
        $fallbackCode = $params['fallback'];
        alias_cache::setFallbackContext($smarty, $fallbackCode);


    }

    return null;
}

?>

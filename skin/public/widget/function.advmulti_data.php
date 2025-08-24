<?php
function smarty_function_advmulti_data($params, $smarty){
	$modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
	$advmulti = new plugins_advmulti_public($modelTemplate);
	$assign = isset($params['assign']) ? $params['assign'] : 'advmulti';
	$smarty->assign($assign,$advmulti->getAdvMultis($params));
}
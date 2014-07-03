<?php return function($key, $val){
	if($val) {
		?><div class="<?=$key; ?>"><?=$val; ?></div><?
	}
} ?>

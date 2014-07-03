<?php return function($adr){?>

	<div class="adr">

		<?=mixin('vcard:item', 'street-address', $adr->streetAddress) ?>
		<?=mixin('vcard:item', 'locality', $adr->locality) ?>
		<?=mixin('vcard:item', 'country-name', $adr->countryName) ?>

	</div>

<? } ?>

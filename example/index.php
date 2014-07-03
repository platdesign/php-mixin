<?php



	include '../mixin.php';
	include '../mixinlibs/vcard/vcard.php';



	$adr = (object)[
		'streetAddress'	=>	'Alysiastr 123',
		'locality'		=>	'Darween'
	];




	echo mixin('vcard:adr', $adr);


	echo mixin::vcard_adr($adr);


	echo mixin::render('vcard:adr', [$adr]);

?>
<?php
/**
 *
 *
 * @file
 * @ingroup Extensions
 * @package MediaWiki
 *
 * @links http://mediawiki.org/wiki/Extension:IDProvider Documentation
 * @links https://github.com/gesinn-it/IDProvider/blob/master/README.md Documentation
 * @links https://github.com/gesinn-it/IDProvider Source code
 *
 * @author Simon Heimler, 2015
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 */


if (function_exists('wfLoadExtension')) {

	wfLoadExtension('QRLite');

	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['QRLite'] = __DIR__ . '/i18n';

	wfWarn(
		'Deprecated PHP entry point used for the IDProvider extension. Please use wfLoadExtension("QRLite"); instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;


} else {

}

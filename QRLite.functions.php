<?php
/**
 * The actual QRLite Functions
 *
 * They can be used in a programmatic way through this class
 * Use the static getId function as the main entry point
 *
 * @file
 * @ingroup Extensions
 */
class QRLiteFunctions {


	/**
	 * Corresponds to the QRLite-increment API usage
	 *
	 * @param array $params	Associative array. See the API / Parser function for usage
	 *
	 * @return string|int
	 *
	 * @throws Exception
	 */
	public static function generateQRCode($params = array()) {

		include "lib/phpqrcode/qrlib.php";

		// Defaults and escaping
		$content = self::paramGet($params, 'prefix', '___MAIN___');

		$format = self::paramGet($params, 'format', 'svg');

		$size = self::paramGet($params, 'size', 6);
		$margin = self::paramGet($params, 'margin', 0);

        $image = '';


        if ($format === 'svg') {
            // Create a temporary svg file, as the library would otherwise print the result to the page itself
            $tmpfname = tempnam("/tmp", "SVGLite_") . '.svg';
            QRcode::svg($content, $tmpfname, QR_ECLEVEL_M, $size, $margin);
            $svgContent = file_get_contents($tmpfname);
            unlink($tmpfname);
            $image = '<div class="svg-container" title="' . $content . '">' . $svgContent . '</div>';
        } else if ($format === 'png') {
            $tmpfname = tempnam("/tmp", "SVGLite_") . '.png';
            QRcode::png($content, $tmpfname, QR_ECLEVEL_M, $size, $margin);
            $pngContent = file_get_contents($tmpfname);
            unlink($tmpfname);
            $image = '<img src="" alt="Smiley face" height="42" width="42">';
        }



        $downloadButtons = '';
		$result = '<div class="qrlite-result">' . $image . $downloadButtons . '</div>';


		return $result;
	}



	//////////////////////////////////////////
    // HELPER FUNCTIONS                     //
    //////////////////////////////////////////

	/**
	 * Helper function, that safely checks whether an array key exists
	 * and returns the trimmed value. If it doesn't exist, returns $default or null
	 *
	 * @param array $params
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public static function paramGet($params, $key, $default = null) {
		if (isset($params[$key])) {
			return trim($params[$key]);
		} else {
			return $default;
		}
	}

	/**
	 * Debug function that converts an object/array to a <pre> wrapped pretty printed JSON string
	 *
	 * @param $obj
	 * @return string
	 */
	public static function toJSON($obj) {
		header('Content-Type: application/json');
		echo json_encode($obj, JSON_PRETTY_PRINT);
		die();
	}

}

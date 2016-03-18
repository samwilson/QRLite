<?php

require_once "lib/phpqrcode/qrlib.php";

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



		// Defaults and escaping
		$content = self::paramGet($params, 'prefix', '___MAIN___');

		$format = self::paramGet($params, 'format', 'svg');

		$size = self::paramGet($params, 'size', 6);
		$margin = self::paramGet($params, 'margin', 0);

		$ecc = self::paramGet($params, 'ecc', 2);

		// TODO: Doesn't seem to work
		$eccLevel = QR_ECLEVEL_M;
		if ($ecc === 1) {
			$eccLevel = QR_ECLEVEL_L;
		} else if ($ecc === 2) {
			$eccLevel = QR_ECLEVEL_M;
		} else if ($ecc === 3) {
			$eccLevel = QR_ECLEVEL_Q;
		} else if ($ecc === 4) {
			$eccLevel = QR_ECLEVEL_H;
		}

        $image = '';

		try {
			if ($format === 'svg') {
				// Create a temporary svg file, as the library would otherwise print the result to the page itself
				$tmpfname = tempnam("/tmp", "SVGLite_") . '.svg';
				QRcode::svg($content, $tmpfname, $eccLevel, $size, $margin);
				$svgContent = file_get_contents($tmpfname);

				unlink($tmpfname);
				$image = '<span class="svg-container" title="' . $content . '">' . $svgContent . '</span>';
			} else if ($format === 'png') {
				$tmpfname = tempnam("/tmp", "SVGLite_") . '.png';
				QRcode::png($content, $tmpfname, $eccLevel, $size, $margin);
				$pngContent = file_get_contents($tmpfname);
				unlink($tmpfname);
				$image = '<img src="data:image/png;base64,' . base64_encode($pngContent) . '" alt="' . $content . '">';
			}
		} catch (Exception $e) {
			$image = '<span class="error-message">' . $e->getMessage() . '</span>'; ;
		}

        $downloadButtons = '';
		$result = '<span class="qrlite-result">' . $image . $downloadButtons . '</span>';

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

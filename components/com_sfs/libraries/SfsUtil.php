<?php

class SfsUtil {
	public static function popup($url, $width, $height, $closable = 1) {
		$popupURL = 'index.php?option=com_sfs&view=popup&action=open&u={URL}&w='. $width. '&h=' . $height . '&closable=' . (int)$closable;
		$url = JRoute::_($url, false);
		$link = JRoute::_($popupURL, false);	
		$link = str_replace('{URL}', urlencode($url), $link);
		
		$app = &JFactory::getApplication();
		$app->redirect($link);
		$app->close();
	}
	
	public static function popupError($message, $width = 400, $height = 200) {
		$url = 'index.php?option=com_sfs&view=popup&action=error&tmpl=component&message=' . urlencode($message);
		self::popup($url, $width, $height);
	}

	public static function distance($lat1, $lon1, $lat2, $lon2) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		return round($miles * 1.609344,2);
	}

	public static function format_description($description)
	{
		$lines = explode("\n", $description);
		foreach($lines as &$line) {
			$line = trim($line);
			if(strpos($line, '##') == 0) {
				$line = str_replace('##', "<strong>",$line );
				$line = $line . '</strong>';
			}
		}
		$description = implode("\n", $lines);
		$description = nl2br($description);
		return (string)$description;
	}

    public static function getRandomString($length = 5) {
//        $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
        $validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
        $validCharNumber = strlen($validCharacters);
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }
        return $result;
    }

    public static function getLatLng($address)
    {
        // We get the JSON results from this request
        $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
        // We convert the JSON to an array
        $geo = json_decode($geo, true);
        // If everything is cool
        if ($geo['status'] = 'OK') {
            $latlng = $geo['results'][0]['geometry']['location'];
            return $latlng;
        }
        return false;
    }
}

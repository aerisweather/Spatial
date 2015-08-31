<?php

namespace Aeris\Spatial\Util;

use Aeris\Spatial\Geometry\Coordinate;


/**
 * Earth's Radius in KM
 */
const EARTH_RADIUS = 6371.0;


/**
 * Returns the direction (in degrees) between two coordinates.
 *
 * @param Coordinate $coordA
 * @param Coordinate $coordB
 * @return float
 */
function bearing(Coordinate $coordA, Coordinate $coordB) {
	// http://stackoverflow.com/questions/1971585/mapping-math-and-javascript
	// http://stackoverflow.com/questions/3209899/determine-compass-direction-from-one-lat-lon-to-the-other
	list($lat1, $lon1, $lat2, $lon2) = array_map(
		'deg2Rad',
		[$coordA->getLat(), $coordA->getLon(), $coordB->getLat(), $coordB->getLon()]
	);
	$dLon = $lon2 - $lon1;
	$y = sin($dLon) * cos($lat2);
	$x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dLon);

	$bearing = rad2deg(atan2($y, $x));

	while ($bearing < 0) {
		$bearing += 360;
	}

	return $bearing;
}

/**
 * Returns the compass rose direction between two coordinates
 *
 * @param Coordinate $coordA
 * @param Coordinate $coordB
 * @return string
 */
function compassDirection(Coordinate $coordA, Coordinate $coordB) {
	// https://www.dougv.com/2009/07/calculating-the-bearing-and-compass-rose-direction-between-two-latitude-longitude-coordinates-in-php/
	$directions = [
		'NNE', 'NE', 'ENE', 'E',
		'ESE', 'SE', 'SSE', 'S',
		'SSW', 'SW', 'WSW', 'W',
		'WNW', 'NW', 'NNW', 'N',
	];
	$bearing = bearing($coordA, $coordB);

	$bearingIndex = round($bearing / 22.5) - 1;
	return $directions[$bearingIndex];
}
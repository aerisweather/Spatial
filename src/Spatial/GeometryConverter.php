<?php


namespace Aeris\Spatial;

use Aeris\Spatial\Exception\InvalidGeoJsonException;

class GeometryConverter {

	/**
	 * Maps GeoJson Geometry objects to their associated
	 * ConvertibleGeometryInterface class.
	 *
	 * @var string[]
	 */
	protected static $geoJsonGeometryWktMap = [
		'Polygon' => '\Aeris\Spatial\Geometry\Polygon',
		'MultiPolygon' => '\Aeris\Spatial\Geometry\MultiPolygon'
	];

	public static function geoJsonToWkt($geoJson) {
		$geoJsonData = json_decode($geoJson, true);

		self::validateGeoJson($geoJson);

		$type = $geoJsonData['type'];
		$geometryType = @self::$geoJsonGeometryWktMap[$type];

		if (!$geometryType) {
			throw new \InvalidArgumentException("Unable to convert geoJson of type $type: " .
				"no matching ConvertibleGeometryInterface can be found.");
		}

		$polygonCoords = $geoJsonData['coordinates'];
		$polygon = call_user_func([$geometryType, 'FromArray'], $polygonCoords);

		return $polygon->toWkt();
	}

	private static function validateGeoJson($geoJson) {
		$type = @$geoJson['type'];
		$coords = @$geoJson['coordinates'];

		$failureReason = null;
		if (!$type) {
			$failureReason = 'No type is defined.';
		}
		if (!$coords) {
			$failureReason = 'No coordinates are defined.';
		}

		if ($failureReason) {
			throw new InvalidGeoJsonException("The GeoJson object is not " .
				"a valid Geometry object: $failureReason");
		}
	}

}
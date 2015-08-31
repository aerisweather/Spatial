<?php


namespace Aeris\Spatial\Geometry;


use Aeris\Spatial\Util;

class MultiPolygon implements ConvertibleGeometryInterface {
	const WKT_ID = 'MULTIPOLYGON';

	/** @var Polygon[] */
	private $polygons;

	/**
	 * @param Polygon[] $polygons
	 */
	public function __construct(array $polygons = []) {
		$this->polygons = $polygons;
	}

	/**
	 * @param array $multiPolygonData
	 * @return static
	 */
	public static function FromArray(array $multiPolygonData) {
		$polygons = array_map(function(array $polygonData) {
			return Polygon::FromArray($polygonData);
		}, $multiPolygonData);

		return new static($polygons);
	}

	/**
	 * From Coordinates (with buffer)
	 *
	 * Creates a MultiPolygon from supplied
	 * @param Coordinate[] $coordinates
	 * @param float $buffer The radius in KM of how large of a buffer to put around each point
	 * @param float $projectionRadius Spherical projection in KM, defaults to Earth's radius
	 * @return static
	 */
	public static function FromCoordinatesWithBuffer(array $coordinates, $buffer, $projectionRadius = Util\EARTH_RADIUS) {
		$polygons = array_map(
			function ($coordinate) use ($buffer, $projectionRadius) {
				return Polygon::FromCoordinateWithBufferBb($coordinate, $buffer, $projectionRadius);
			}, $coordinates);
		return new static($polygons);
	}

	public static function FromFeatureCollection(array $geoJson) {
		$mPoly = new static();
		$features = $geoJson['features'];

		foreach ($features as $feat) {
			$type = $feat['type'];
			if ($type === 'Feature') {
				$subPoly = static::FromFeature($feat);
			}
			else if ($type === 'FeatureCollection') {
				$subPoly = static::FromFeatureCollection($feat);
			}
			else {
				throw new \InvalidArgumentException("Unable to convert GeoJson FeatureCollection to MultiPolygon: " .
					"expected feature types of 'Feature' or 'FeatureCollection', but found $type");
			}

			$mPoly->merge($subPoly);
		}

		return $mPoly;
	}

	public static function FromFeature(array $geoJson) {
		$mPoly = new static();

		$type = $geoJson['geometry']['type'];
		$coords = $geoJson['geometry']['coordinates'];

		if ($type === 'Polygon') {
			$poly = Polygon::FromArray($coords);
			$mPoly->addPolygon($poly);
		}
		// Merge MultPoly's into main MultiPoly
		else if ($type === 'MultiPolygon') {
			$subMPoly = MultiPolygon::FromArray($coords);
			$mPoly->merge($subMPoly);
		}
		else {
			throw new \InvalidArgumentException("Unable to convert GeoJson Feature to MultiPolygon: " .
				"expected geometry type of Polygon or MultiPolygon, but found $type");
		}

		return $mPoly;
	}

	/**
	 * @param bool $includeId
	 *        If false, the WKT string will not include
	 *        the geometry identifier.
	 *
	 * @return string
	 */
	public function toWkt($includeId = true) {
		$wkt = $includeId ? self::WKT_ID : '';

		$polygonWkts = array_map(function(Polygon $polygon) {
			return $polygon->toWkt(false);
		}, $this->polygons);

		$wkt .= '(';
		$wkt .= join(',', $polygonWkts);
		$wkt .= ')';

		return $wkt;
	}

	public function addPolygon(Polygon $polygon) {
		$this->polygons[] = $polygon;
	}

	/**
	 * Add the polygons from the provided
	 * MultiPolygon to this MultiPolygon.
	 *
	 * @param MultiPolygon $mPoly
	 */
	public function merge(MultiPolygon $mPoly) {
		$this->polygons = array_merge($this->polygons, $mPoly->getPolygons());
	}

	/**
	 * @return Polygon[]
	 */
	public function getPolygons() {
		return $this->polygons;
	}

	/**
	 * @param Polygon[] $polygons
	 */
	public function setPolygons(array $polygons) {
		$this->polygons = $polygons;
	}
}
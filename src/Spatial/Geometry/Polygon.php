<?php


namespace Aeris\Spatial\Geometry;
use Aeris\Spatial\Util;


/**
 * A polygon is a shape defined by an
 * exterior LineRing, and an optional interior LineRing.
 */
class Polygon implements ConvertibleGeometryInterface {
	const WKT_ID = 'POLYGON';

	/** @var LineRing */
	private $exterior;

	/** @var LineRing|null */
	private $interior;

	public function __construct(LineRing $exterior, LineRing $interior = null) {
		$this->exterior = $exterior;
		$this->interior = $interior;
	}

	public static function FromArray(array $polygon) {
		$exterior = $polygon[0];
		$interior = @$polygon[1];
		$hasInterior = !is_null($interior);

		return new static(
			LineRing::FromArray($exterior),
			($hasInterior ? LineRing::FromArray($interior) : null)
		);
	}

	/**
	 * From Coordinate With Buffer Bounding Box
	 *
	 * Creates a Minimum Bounding Rectangle / Bounding Box for a the supplied `coordinate`, ensuring that the bounding
	 * box covers `radius`
	 *
	 * @param Coordinate $coordinate
	 * @param float $radius The radius in KM of how large of a buffer to put around each point
	 * @param float $projectionRadius Spherical projection in KM, defaults to Earth's radius
	 * @return static
	 */
	public static function FromCoordinateWithBufferBb(Coordinate $coordinate, $radius, $projectionRadius = Util\EARTH_RADIUS) {
		//Latitudes
		$maxLat = $coordinate->getLat() + rad2deg($radius / $projectionRadius);
		$minLat = $coordinate->getLat() - rad2deg($radius / $projectionRadius);
		//Longitudes
		$maxLon = $coordinate->getLon() + rad2deg($radius / $projectionRadius / cos(deg2rad($coordinate->getLat())));
		$minLon = $coordinate->getLon() - rad2deg($radius / $projectionRadius / cos(deg2rad($coordinate->getLat())));

		//Start with the NE corner on around, like CSS?
		$bbLineRing = new LineRing(
			[
				new Coordinate($maxLon, $maxLat),
				new Coordinate($maxLon, $minLat),
				new Coordinate($minLon, $minLat),
				new Coordinate($minLon, $maxLat),
				new Coordinate($maxLon, $maxLat)
			]
		);
		return new static($bbLineRing);
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

		$wkt .= '(';
		$wkt .= $this->exterior->toWkt(false);

		if (!is_null($this->interior)) {
			$wkt .= ',' . $this->interior->toWkt(false);
		}

		$wkt .= ')';

		return $wkt;
	}
}
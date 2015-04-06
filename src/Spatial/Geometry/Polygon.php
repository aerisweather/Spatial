<?php


namespace Aeris\Spatial\Geometry;


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
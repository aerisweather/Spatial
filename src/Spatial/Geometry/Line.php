<?php


namespace Aeris\Spatial\Geometry;

/**
 * A collection of coordinates
 */
class Line implements ConvertibleGeometryInterface {
	const WKT_ID = 'LINESTRING';

	/** @var Coordinate[] */
	private $coordinates;

	/**
	 * @param Coordinate[] $coordinates
	 */
	public function __construct(array $coordinates = []) {
		$this->coordinates = $coordinates;
	}

	/**
	 * @param array[] $coordinates Array of [lat:number, lon:number]
	 * @return LineRing
	 */
	public static function FromArray(array $coordinates) {
		$coordinateObjects = array_map(function(array $coord) {
			return Coordinate::FromArray($coord);
		}, $coordinates);

		return new static($coordinateObjects);
	}

	/**
	 * @return Coordinate[]
	 */
	public function getCoordinates() {
		return $this->coordinates;
	}

	/**
	 * @param Coordinate[] $coordinates
	 */
	public function setCoordinates(array $coordinates) {
		$this->coordinates = $coordinates;
	}

	/**
	 * @param bool $includeId
	 * @return string
	 */
	public function toWkt($includeId = true) {
		$wkt = $includeId ? self::WKT_ID : '';

		$coordWkts = array_map(function(Coordinate $coord) {
			return $coord->toWkt($includeId = false);
		}, $this->coordinates);

		$wkt .= '(' . join(',', $coordWkts) . ')';

		return $wkt;
	}
}
<?php


namespace Aeris\Spatial\Geometry;


class Coordinate implements ConvertibleGeometryInterface {

	const WKT_ID = 'POINT';

	/** @var number */
	protected $lon;
	/** @var number */
	protected $lat;

	/**
	 * @param number $lat
	 * @param number $lon
	 */
	public function __construct($lon, $lat) {
		$this->lon = $lon;
		$this->lat = $lat;
	}

	public static function FromArray(array $lonLat) {
		return new static($lonLat[0], $lonLat[1]);
	}

	public function toWkt($includeId = true) {
		$wkt = $includeId ? self::WKT_ID : '';
		$wkt .= join(' ', [$this->lon, $this->lat]);

		return $wkt;
	}

	/**
	 * @return number
	 */
	public function getLon() {
		return $this->lon;
	}

	/**
	 * @param number $lon
	 */
	public function setLon($lon) {
		$this->lon = $lon;
	}

	/**
	 * @return number
	 */
	public function getLat() {
		return $this->lat;
	}

	/**
	 * @param number $lat
	 */
	public function setLat($lat) {
		$this->lat = $lat;
	}

	/**
	 * @param Coordinate $coordinate
	 * @return bool
	 */
	public function isEqual(Coordinate $coordinate) {
		return $coordinate->getLon() === $this->getLon() &&
		$coordinate->getLat() === $this->getLat();
	}

}
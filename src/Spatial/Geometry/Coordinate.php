<?php


namespace Aeris\Spatial\Geometry;


class Coordinate implements ConvertibleGeometryInterface {

	const WKT_ID = 'POINT';

	/** @var number */
	protected $lat;
	/** @var number */
	protected $lon;

	/**
	 * @param number $lat
	 * @param number $lon
	 */
	public function __construct($lat, $lon) {
		$this->lat = $lat;
		$this->lon = $lon;
	}

	public static function FromArray(array $latLon) {
		return new static($latLon[0], $latLon[1]);
	}

	public function toWkt($includeId = true) {
		$wkt = $includeId ? self::WKT_ID : '';
		$wkt .= join(' ', [$this->lat, $this->lon]);

		return $wkt;
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
	 * @param Coordinate $coordinate
	 * @return bool
	 */
	public function isEqual(Coordinate $coordinate) {
		return $coordinate->getLat() === $this->getLat() &&
		$coordinate->getLon() === $this->getLon();
	}

}
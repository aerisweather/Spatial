<?php


namespace Aeris\Spatial\Geometry;


use Aeris\Spatial\Util;

class Coordinate implements ConvertibleGeometryInterface
{

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

	/**
	 * Distance
	 *
	 * Get the distance (in KM) from this point to another. This formula is derrived from a site NOAA linked to from their
	 * website.
	 *
	 * @see http://www.nhc.noaa.gov/gccalc.shtml
	 * @see http://williams.best.vwh.net/avform.htm#Intro
	 * @param Coordinate $otherCoordinate
	 * @param float $projectionRadius Spherical projection in KM, defaults to Earth's radius
	 * @return float
	 */
	public function getDistance(Coordinate $otherCoordinate, $projectionRadius = Util::EARTH_RADIUS) {
		$latFrom = deg2rad($this->getLat());
		$lonFrom = deg2rad($this->getLon());
		$latTo   = deg2rad($otherCoordinate->getLat());
		$lonTo   = deg2rad($otherCoordinate->getLon());

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
							   cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * $projectionRadius;
	}

}
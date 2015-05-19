<?php


namespace Aeris\Spatial\Geometry;


use Aeris\Spatial\Exception\InvalidGeometryException;

class LineRing extends Line {

	/**
	 * @param Coordinate[] $coordinates
	 * @throws InvalidGeometryException
	 */
	public function __construct(array $coordinates = []) {
		$this->validateCoordinates($coordinates);
		parent::__construct($coordinates);
	}

	/**
	 * @param Coordinate[] $coordinates
	 * @throws InvalidGeometryException
	 */
	public function setCoordinates(array $coordinates) {
		$this->validateCoordinates($coordinates);
		parent::setCoordinates($coordinates);
	}



	/**
	 * @param Coordinate[] $coordinates
	 */
	protected function validateCoordinates(array $coordinates) {
		if (count($coordinates) < 2) {
			return;
		}

		/** @var Coordinate $lastCoord */
		$lastCoord = end($coordinates);
		/** @var Coordinate $firstCoord */
		$firstCoord = reset($coordinates);

		if (!$firstCoord->isEqual($lastCoord)) {
			throw new InvalidGeometryException("A LineRing's first coordinate must " .
				"equal its last coordinate.");
		}
	}

}
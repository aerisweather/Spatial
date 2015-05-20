<?php


namespace Aeris\SpatialTest\Geometry;


use Aeris\Spatial\Geometry\Coordinate;

class CoordinateTest extends \PHPUnit_Framework_TestCase {

	/** @test */
	public function Distance_near() {
		$coordA = new Coordinate(-100, 45);
		$coordB = new Coordinate(-90, 40);
		$this->assertEquals(989, round($coordA->getDistance($coordB)));
	}

	/**
	 * @test
	 *
	 * NOTE: This differs from Javascript implementations from around the internet, thinking that Javascript uses double
	 * precision floats, PHP can only use single precision floats
	 */
	public function Distance_far() {
		$coordA = new Coordinate(0, 0);
		$coordB = new Coordinate(10, 10);
		$this->assertEquals(1569, round($coordA->getDistance($coordB)));
	}

	/**
	 * @test
	 */
	public function Distance_short() {
		$coordA = new Coordinate(0, 0);
		$coordB = new Coordinate(-1, 1);
		$this->assertEquals(157, round($coordA->getDistance($coordB)));
	}
}
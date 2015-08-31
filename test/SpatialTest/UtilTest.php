<?php


namespace Aeris\SpatialTest;


use Aeris\Spatial\Geometry\Coordinate;
use Aeris\Spatial\Util;

class UtilTest extends \PHPUnit_Framework_TestCase {

	/** @test */
	public function bearingTest() {
		$minneapolis = new Coordinate(-93.251953125, 44.9336963896947);
		$chicago = new Coordinate(-87.71484375, 41.80407814427237);

		$this->assertEquals(125.93766052151, Util\bearing($minneapolis, $chicago), '', 0.000000001);
		$this->assertEquals(309.74293632484, Util\bearing($chicago, $minneapolis), '', 0.000000001);
	}

	/** @test */
	public function bearingTest_sameLocation() {
		$this->assertEquals(0, Util\bearing(new Coordinate(45, -90), new Coordinate(45, -90)));
	}

	/** @test */
	public function compassDirectionTest() {
		$mplsDowntown = new Coordinate(-93.26491355895996, 44.97591021304562);

		$uOfM = new Coordinate(-93.22929382324219, 44.97481729709929);
		$this->assertEquals('E', Util\compassDirection($mplsDowntown, $uOfM));
		$this->assertEquals('W', Util\compassDirection($uOfM, $mplsDowntown));

		$scienceMuseum = new Coordinate(-93.0984878540039, 44.940015254076535);
		$this->assertEquals('ESE', Util\compassDirection($mplsDowntown, $scienceMuseum));
		$this->assertEquals('WNW', Util\compassDirection($scienceMuseum, $mplsDowntown));

		$edenPrairie = new Coordinate(-93.4768295288086, 44.85148787683413);
		$this->assertEquals('SW', Util\compassDirection($mplsDowntown, $edenPrairie));
		$this->assertEquals('NE', Util\compassDirection($edenPrairie, $mplsDowntown));
	}

	/** @test */
	public function compassDirectionTest_sameLocation() {
		$this->assertEquals('N', Util\compassDirection(new Coordinate(45, -90), new Coordinate(45, -90)));
	}

}
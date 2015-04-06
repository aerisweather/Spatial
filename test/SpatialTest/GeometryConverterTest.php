<?php


namespace Aeris\SpatialTest;


use Aeris\Spatial\GeometryConverter;

class GeometryConverterTest extends \PHPUnit_Framework_TestCase {

	/** @test */
	public function geoJsonToWkt_polygon_noInterior() {
		$geoJson = [
			'type' => 'Polygon',
			'coordinates' => [
				$exterior = [
					[100, 0],    // E-A
					[101, 0],    // E-B
					[101, 1],    // E-C
					[100, 1],    // E-D
					[100, 0],    // E-E
				]
			]
		];
		$wkt = GeometryConverter::geoJsonToWkt(json_encode($geoJson));

		$this->assertEquals(
			'POLYGON(' .
				// exterior
				'(' .
					'100 0,' .  // E-A
					'101 0,' .  // E-B
					'101 1,' .  // E-C
					'100 1,' .  // E-D
					'100 0' .  // E-E
				')' .
			')',
			$wkt
		);
	}

	/** @test */
	public function geoJsonToWkt_polygon_hasInterior() {
		$geoJson = [
			'type' => 'Polygon',
			'coordinates' => [
				$exterior = [
					[100, 0],  // E-A
					[101, 0],  // E-B
					[101, 1],  // E-C
					[100, 1],  // E-D
					[100, 0]  // E-E
				],
				$interior = [
					[100.2, 0.2],  // I-A
					[100.8, 0.2],  // I-B
					[100.8, 0.8],  // I-C
					[100.2, 0.8],  // I-D
					[100.2, 0.2]  // I-E
				]
			]
		];

		$wkt = GeometryConverter::geoJsonToWkt(json_encode($geoJson));

		$this->assertEquals(
			'POLYGON(' .
				// exterior
				'(' .
					'100 0,' .  // E-A
					'101 0,' .  // E-B
					'101 1,' .  // E-C
					'100 1,' .  // E-D
					'100 0' .  // E-E
				'),' .
				// interior
				'(' .
					'100.2 0.2,' .  // I-A
					'100.8 0.2,' .  // I-B
					'100.8 0.8,' .  // I-C
					'100.2 0.8,' .  // I-D
					'100.2 0.2' .  // I-E
				')' .
			')',
			$wkt
		);
	}

	/** @test */
	public function geoJsonToWkt_multiPolygon() {
		$geoJson = [
			"type" => "MultiPolygon",
			"coordinates" => [
				$poly_A = [
					$exterior_A = [
						[102, 2],			// EA-A
						[103, 2],			// EA-B
						[103, 3],			// EA-C
						[102, 3],			// EA-D
						[102, 2],			// EA-E
					]
				],
				$poly_B = [
					$exterior_B = [
						[200, 0],			// EB-A
						[201, 0],			// EB-B
						[201, 1],			// EB-C
						[200, 1],			// EB-D
						[200, 0],			// EB-E
					],
					$interior_B = [
						[200.2, 0.2],	// IB-A
						[200.8, 0.2],	// IB-B
						[200.8, 0.8],	// IB-C
						[200.2, 0.8],	// IB-D
						[200.2, 0.2],	// IB-E
					]
				]
			]
		];
		
		$wkt = GeometryConverter::geoJsonToWkt(json_encode($geoJson));

		$expectedWkt = 'MULTIPOLYGON(' .
			// poly_A
			'(' .
				// exterior_A
				'(' .
					'102 2,' .    // EA-A
					'103 2,' .    // EA-B
					'103 3,' .    // EA-C
					'102 3,' .    // EA-D
					'102 2' .     // EA-E
				')' .
			'),' .

			// poly_B
			'(' .
				// exterior_B
				'(' .
					'200 0,' .    // IB-A
					'201 0,' .    // IB-B
					'201 1,' .    // IB-C
					'200 1,' .    // IB-D
					'200 0' .     // IB-E
				'),' .
				// interior_B
				'(' .
					'200.2 0.2,' .// IB-A
					'200.8 0.2,' .// IB-B
					'200.8 0.8,' .// IB-C
					'200.2 0.8,' .// IB-D
					'200.2 0.2' . // IB-E
				')' .
			')' .
		')';
		$this->assertEquals(
			$expectedWkt,
			$wkt
		);
	}

}
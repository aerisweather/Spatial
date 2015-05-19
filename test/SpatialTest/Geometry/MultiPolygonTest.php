<?php


namespace Aeris\SpatialTest\Geometry;


use Aeris\Spatial\Geometry\Coordinate;
use Aeris\Spatial\Geometry\MultiPolygon;
use Aeris\Spatial\Geometry\Polygon;

class MultiPolygonTest extends \PHPUnit_Framework_TestCase {

	/** @test */
	public function FromFeatureCollection_featuresWithPolygons() {
		$geoJson = [
			'type' => 'FeatureCollection',
			'features' => [
				[
					'type' => 'Feature',
					'geometry' => [
						'type' => 'Polygon',
						'coordinates' => [
							[
								[100, 0],
								[100, 100],
								[0, 100],
								[0, 0],
								[100, 0],
							]
						]
					],
				],
				[
					'type' => 'Feature',
					'geometry' => [
						'type' => 'Polygon',
						'coordinates' => [
							[
								[200, 0],
								[200, 200],
								[0, 200],
								[0, 0],
								[200, 0]
							]
						]
					]
				]
			],
		];

		$mPoly = MultiPolygon::FromFeatureCollection($geoJson);

		$this->assertEquals([
			Polygon::FromArray([
				[
					[100, 0],
					[100, 100],
					[0, 100],
					[0, 0],
					[100, 0]
				]
			]),
			Polygon::FromArray([
				[
					[200, 0],
					[200, 200],
					[0, 200],
					[0, 0],
					[200, 0]
				]
			])
		], $mPoly->getPolygons());
	}

	/** @test */
	public function FromFeatureCollection_featuresWithMultiPolygons() {
		$geoJson = [
			'type' => 'FeatureCollection',
			'features' => [
				[
					'type' => 'Feature',
					'geometry' => [
						'type' => 'MultiPolygon',
						'coordinates' => [
							$polygonA = [
								[
									[100, 0],
									[100, 100],
									[0, 100],
									[0, 0],
									[100, 0],
								]
							],
							$polygonB = [
								[
									[101, 1],
									[101, 101],
									[1, 101],
									[1, 1],
									[101, 1],
								]
							]
						]
					],
				],
				[
					'type' => 'Feature',
					'geometry' => [
						'type' => 'MultiPolygon',
						'coordinates' => [
							$polygonA = [
								[
									[200, 0],
									[200, 200],
									[0, 200],
									[0, 0],
									[200, 0],
								]
							],
							$polygonB = [
								[
									[201, 1],
									[201, 201],
									[1, 201],
									[1, 1],
									[201, 1],
								]
							]
						]
					]
				]
			],
		];

		$mPoly = MultiPolygon::FromFeatureCollection($geoJson);

		$this->assertEquals([
			Polygon::FromArray([
				[
					[100, 0],
					[100, 100],
					[0, 100],
					[0, 0],
					[100, 0]
				]
			]),
			Polygon::FromArray([
				[
					[101, 1],
					[101, 101],
					[1, 101],
					[1, 1],
					[101, 1],
				]
			]),
			Polygon::FromArray([
				[
					[200, 0],
					[200, 200],
					[0, 200],
					[0, 0],
					[200, 0]
				]
			]),
			Polygon::FromArray([
				[
					[201, 1],
					[201, 201],
					[1, 201],
					[1, 1],
					[201, 1],
				]
			])
		], $mPoly->getPolygons());
	}

	/** @test */
	public function FromFeatureCollection_withFeatureCollections() {
		$geoJson = [
			'type' => 'FeatureCollection',
			'features' => [
				[
					'type' => 'FeatureCollection',
					'features' => [
						[
							'type' => 'Feature',
							'geometry' => [
								'type' => 'Polygon',
								'coordinates' => [
									[
										[100, 0],
										[100, 100],
										[0, 100],
										[0, 0],
										[100, 0],
									]
								]
							],
						]
					],
				],
				[
					'type' => 'FeatureCollection',
					'features' => [
						[
							'type' => 'Feature',
							'geometry' => [
								'type' => 'Polygon',
								'coordinates' => [
									[
										[200, 0],
										[200, 200],
										[0, 200],
										[0, 0],
										[200, 0],
									]
								]
							],
						]
					],
				],
			],
		];

		$mPoly = MultiPolygon::FromFeatureCollection($geoJson);

		$this->assertEquals([
			Polygon::FromArray([
				[
					[100, 0],
					[100, 100],
					[0, 100],
					[0, 0],
					[100, 0]
				]
			]),
			Polygon::FromArray([
				[
					[200, 0],
					[200, 200],
					[0, 200],
					[0, 0],
					[200, 0]
				]
			])
		], $mPoly->getPolygons());
	}

	/** @test */
	public function FromCoordinatesWithBuffer_simple() {
		$coordinates = [
			new Coordinate(5, 5)
		];
		$mPoly = MultiPolygon::FromCoordinatesWithBuffer($coordinates, 100);
		$this->assertEquals([
				Polygon::FromArray([
				   [
					   [5.9027568683526, 5.8993216059187],
					   [5.9027568683526, 4.1006783940813],
					   [4.0972431316474, 4.1006783940813],
					   [4.0972431316474, 5.8993216059187],
					   [5.9027568683526, 5.8993216059187]
				   ]
				])
			],
			$mPoly->getPolygons());
	}

	/** @test */
	public function FromCoordinatesWithBuffer_multiCoord() {
		$coordinates = [
			new Coordinate(0, 0),
			new Coordinate(5, 5)
		];
		$mPoly = MultiPolygon::FromCoordinatesWithBuffer($coordinates, 100);
		$this->assertEquals([
			Polygon::FromArray([
				   [
					   [0.89932160591873, 0.89932160591873],
					   [0.89932160591873, -0.89932160591873],
					   [-0.89932160591873, -0.89932160591873],
					   [-0.89932160591873, 0.89932160591873],
					   [0.89932160591873, 0.89932160591873]
				   ]
				]),
			Polygon::FromArray([
				   [
					   [5.9027568683526, 5.8993216059187],
					   [5.9027568683526, 4.1006783940813],
					   [4.0972431316474, 4.1006783940813],
					   [4.0972431316474, 5.8993216059187],
					   [5.9027568683526, 5.8993216059187]
				   ]
			   ])
			],
			$mPoly->getPolygons());
	}

}
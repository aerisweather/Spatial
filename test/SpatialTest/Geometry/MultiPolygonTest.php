<?php


namespace Aeris\SpatialTest\Geometry;


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

}
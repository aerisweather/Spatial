Spatial
=======================

Representations of spatial data.

# Installation

```
composer require aeris-spatial
```


# Components

## GeometryConverter

The `GeometryConverter` component may be used to convert geometry data between different formats. The following conversions are currently supported:
 
* GeoJson Polygon to WKT
* GeoJson MultiPolygon to WKT

Additional conversions can be made by using `Geometry` objects directly, via the `ConvertibleGeometryInterface::FromArray()` and `ConvertibleGeometryInterface::toWkt()` methods (see [Geometry](#geometry) component documentation)

### Examples

```php
use Aeris\Spatial\GeometryConverter;

$geoJson = json_encode([
  'type' => 'Polygon',
  'coordinates' => [
    [
      [100, 0],
      [101, 0],
      [101, 1],
      [100, 1],
      [100, 0],
    ]
  ]
]);
$wkt = GeometryConverter::geoJsonToWkt($geoJson);

$this->assertEquals(
  'POLYGON((100 0,101 0,101 1,100 1,100 0))',
  $wkt
);
```

## Geometry

`Geometry` components are object representations of spatial data structures. Objects implementing `\Aeris\Spatial\Geometry\ConvertibleGeometryInterface` may be easily converted between array and WKT (string) formats using the `FromArray(array $data)` and `toWkt()` methods

Some `Geometry` components may implement additional conversion methods. For example, `\Aeris\Spatial\Geometry\MultiPolygon` implements a `FromFeatureCollection()` method, which makes it easy to convert a GeoJson feature collection to a `MultiPolygon` object.


### Examples

The following example demonstrates converting a GeoJson FeatureCollection into a MultiPolygon object.

```php
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
```

You could then use the MultiPolygon object to execute a MySql spatial query:

```php
$myQuery = 'SELECT * FROM `places` ' .
 'WHERE ST_CONTAINS(GeomFromText(' . $mPoly->toWKT() . '), `places`.`point`)' 
```

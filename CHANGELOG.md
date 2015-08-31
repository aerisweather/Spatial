# 1.3.0

* ADD: New utility methods: `Util\bearing`, `Util\compassDirection`


# 1.2.0

* ADD: Distance calculation for two coordinates.

# 1.1.0

* ADD: MultiPolygon::FromCoordinatesWithBuffer - Creates bounding boxes around each passed Coordinate and returns them
       in a MultiPolygon container.
* ADD: Polygon::FromCoordinateWithBufferBb - Creates a bounding box around the passed Coordinate and returns a Polygon.

# 1.0.1

* FIX: Coordinate lon/lat was reversed. 
       Note that Coord::toWkt() was also using the
       reversed order, so this would not have broken
       most use cases.

# 1.0.0

Initial release of Aeris\Spatial package.

Includes:

* `GeometryConverter` (Polygon/MultiPolygon GeoJSON --> WKT only)
* `ConvertibleGeometryInterface` objects, implementing `toWkt()` and `FromArray()`
* `MultiPolygon::FromFeatureCollection()`
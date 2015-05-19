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
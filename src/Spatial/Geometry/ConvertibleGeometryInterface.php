<?php


namespace Aeris\Spatial\Geometry;


interface ConvertibleGeometryInterface {

	/** @return ConvertibleGeometryInterface */
	public static function FromArray(array $arr);

	/**
	 * @param bool $includeId
	 * 				If false, the WKT string will not include
	 * 				the geometry identifier.
	 *
	 * @return string
	 */
	public function toWkt($includeId = true);
}
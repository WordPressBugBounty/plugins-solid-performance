<?php

namespace SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns;

use SolidWP\Performance\StellarWP\DB\DB;

/**
 * @since 1.0.0
 */
trait GroupByStatement {
	/**
	 * @var array
	 */
	protected $groupByColumns = [];

	/**
	 * @return $this
	 */
	public function groupBy( $tableColumn ) {
		if ( ! in_array( $tableColumn, $this->groupByColumns, true ) ) {
			$this->groupByColumns[] = DB::prepare( '%1s', $tableColumn );
		}

		return $this;
	}

	protected function getGroupBySQL() {
		return ! empty( $this->groupByColumns )
			? [ 'GROUP BY ' . implode( ',', $this->groupByColumns ) ]
			: [];
	}
}

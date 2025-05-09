<?php

namespace SolidWP\Performance\StellarWP\DB\QueryBuilder;

use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\Aggregate;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\CRUD;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\FromClause;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\GroupByStatement;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\HavingClause;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\JoinClause;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\LimitStatement;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\MetaQuery;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\OffsetStatement;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\OrderByStatement;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\SelectStatement;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\TablePrefix;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\UnionOperator;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\Concerns\WhereClause;

/**
 * @since 1.0.0
 */
class QueryBuilder {
	use Aggregate;
	use CRUD;
	use FromClause;
	use GroupByStatement;
	use HavingClause;
	use JoinClause;
	use LimitStatement;
	use MetaQuery;
	use OffsetStatement;
	use OrderByStatement;
	use SelectStatement;
	use TablePrefix;
	use UnionOperator;
	use WhereClause;

	/**
	 * @return string
	 */
	public function getSQL() {
		$sql = array_merge(
			$this->getSelectSQL(),
			$this->getFromSQL(),
			$this->getJoinSQL(),
			$this->getWhereSQL(),
			$this->getGroupBySQL(),
			$this->getHavingSQL(),
			$this->getOrderBySQL(),
			$this->getLimitSQL(),
			$this->getOffsetSQL(),
			$this->getUnionSQL()
		);

		// Trim double spaces added by DB::prepare
		return str_replace(
			[ '   ', '  ' ],
			' ',
			implode( ' ', $sql )
		);
	}
}

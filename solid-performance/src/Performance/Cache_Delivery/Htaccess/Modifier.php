<?php
/**
 * The htaccess modifier.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

/**
 * The htaccess modifier.
 *
 * @package SolidWP\Performance
 */
final class Modifier {

	public const START_TAG = '# BEGIN KadencePerformance';
	public const END_TAG   = '# END KadencePerformance';

	/**
	 * @var array<string, string> Legacy tags to check for. The start_tag => end_tag pairs.
	 */
	private array $legacy_tags = [
		'# BEGIN SolidPerformance' => '# END SolidPerformance',
	];

	/**
	 * Prepend our htaccess rules to the existing htaccess content.
	 *
	 * @param string $content The existing htaccess content.
	 * @param string $rules The rules to insert.
	 *
	 * @return string
	 */
	public function prepend( string $content, string $rules ): string {
		// Rules already exist, no replacement needed.
		if ( $this->find_tags( $content ) !== null ) {
			return $content;
		}

		return $this->wrap( $rules ) . PHP_EOL . $content;
	}

	/**
	 * Force prepend (replace) the existing rules to the existing htaccess content.
	 *
	 * @param string $content The htaccess content.
	 * @param string $rules The rules to insert.
	 *
	 * @return string
	 */
	public function force_prepend( string $content, string $rules ): string {
		$tags = $this->find_tags( $content );

		if ( $tags === null ) {
			return $this->wrap( $rules ) . PHP_EOL . $content;
		}

		[ $start_tag, $end_tag ] = $tags;

		// Ensure we remove the line break after our end tag before replacing.
		$regex = sprintf(
			'/%s.*?\s*%s\R?/s',
			preg_quote( $start_tag, '/' ),
			preg_quote( $end_tag, '/' )
		);

		return preg_replace( $regex, $this->wrap( $rules ), $content );
	}

	/**
	 * Remove our rules from the htaccess content.
	 *
	 * @param string $content The content of the htaccess file.
	 *
	 * @return string
	 */
	public function remove( string $content ): string {
		$tags = $this->find_tags( $content );

		if ( $tags === null ) {
			return $content;
		}

		[ $start_tag, $end_tag ] = $tags;

		return preg_replace(
			sprintf(
				'/%s.*?%s\s*/s',
				preg_quote( $start_tag, '/' ),
				preg_quote( $end_tag, '/' )
			),
			'',
			$content
		) ?? $content;
	}

	/**
	 * Determine if the current htaccess file has our rules in it.
	 *
	 * This also checks for legacy tags from previous brand names.
	 *
	 * @param string $content The htaccess content.
	 *
	 * @return bool
	 */
	public function has_rules( string $content ): bool {
		return $this->find_tags( $content ) !== null;
	}

	/**
	 * Find which start/end tags are present in the content.
	 *
	 * Checks the current tags first, then falls back to legacy tags.
	 *
	 * @param string $content The htaccess content.
	 *
	 * @return array{0: string, 1: string}|null The [start_tag, end_tag] pair, or null if none found.
	 */
	private function find_tags( string $content ): ?array {
		$tag_pairs = array_merge(
			[ self::START_TAG => self::END_TAG ],
			$this->legacy_tags,
		);

		foreach ( $tag_pairs as $start_tag => $end_tag ) {
			$regex = sprintf(
				'/%s.*?%s/s',
				preg_quote( $start_tag, '/' ),
				preg_quote( $end_tag, '/' )
			);

			if ( preg_match( $regex, $content ) ) {
				return [ $start_tag, $end_tag ];
			}
		}

		return null;
	}

	/**
	 * Wrap our htaccess rules in our start/end tags.
	 *
	 * @param string $rules The htaccess rules.
	 *
	 * @return string
	 */
	private function wrap( string $rules ): string {
		return self::START_TAG . PHP_EOL . trim( $rules ) . PHP_EOL . self::END_TAG . PHP_EOL;
	}
}

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

	public const START_TAG = '# BEGIN SolidPerformance';
	public const END_TAG   = '# END SolidPerformance';

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
		if ( $this->has_rules( $content ) ) {
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
		if ( ! $this->has_rules( $content ) ) {
			return $this->prepend( $content, $rules );
		}

		// Ensure we remove the line break after our end tag before replacing.
		$regex = sprintf(
			'/%s.*?\s*%s\R?/s',
			preg_quote( self::START_TAG, '/' ),
			preg_quote( self::END_TAG, '/' )
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
		if ( ! $this->has_rules( $content ) ) {
			return $content;
		}

		return preg_replace(
			sprintf(
				'/%s.*?%s\s*/s',
				preg_quote( self::START_TAG, '/' ),
				preg_quote( self::END_TAG, '/' )
			),
			'',
			$content
		) ?? $content;
	}

	/**
	 * Determine if the current htaccess file has our rules in it.
	 *
	 * @param string $content The htaccess content.
	 *
	 * @return bool
	 */
	public function has_rules( string $content ): bool {
		$regex = sprintf(
			'/%s.*?%s/s',
			preg_quote( self::START_TAG, '/' ),
			preg_quote( self::END_TAG, '/' )
		);

		return (bool) preg_match( $regex, $content );
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

<?php

abstract class SomeThing {

	/**
	 * Active (non-deprecated) function.
	 */
	function activeFunction() {}

	/**
	 * @deprecated x.x
	 * @codeCoverageIgnore
	 */
	public /* don't get confused by this */ function correctDocblock() {
		// Code
    } // end function

    public function functionWithoutDocblock() {}

	/**
	 * @codeCoverageIgnore
	 * @deprecated x.x
	 */
	private static function correctDocblockReverseOrder() {}

	/**
	 * @deprecated x.x
	 *
	 * @param string $param1 Short desc.
	 * @param string $param2 Short desc.
	 *
	 * @return bool
	 *
	 * @codeCoverageIgnore
	 */
	protected function correctDocblockOtherTagsIrrelevant() {}

	/**
	 * @deprecated x.x
	 *
	 * codeCoverageIgnore
	 */
	private static function ignoreNotAsTag() {}

	/**
	 * @deprecated x.x
	 *
	 * This is a sentence containing the phrase codeCoverageIgnore, but should not be regarded as the tag.
	 */
	final static protected function missingStaticCodeCoverageIgnore() {}

	/**
	 * @deprecated x.x
	 */
	public abstract function missingCodeCoverageIgnore();

	/**
	 * @deprecated x.x
	 * @codeCoverageIgnore
	 */
	#[ReturnTypeWillChange]
	private static function withAttributeCorrectDocblock() {}

	/**
	 * @deprecated x.x
	 */
	#[ReturnTypeWillChange]
	#[AnotherAttribute]
	public function withAttributesMissingCodeCoverageIgnore() {}

	/**
	 * Docblock which can't be found as the inline comments for the attribute confuse the sniff.
	 */
	#[fietsbel] // added a fietsbel decorator
	public function unreachableDocblockTrailingComment() {}

	/**
	 * Docblock which can't be found as the inline comments for the attribute confuse the sniff.
	 */
	// I added a fietsbel decorator
	#[fietsbel]
	public function unreachableDocblockInlineCommentAboveAttribute() {}

	/**
	 * Docblock which is found as the comment is within the attribute itself.
	 *
	 * @deprecated x.x
	 */
	#[fietsbel /*comment*/]
	public function inlineCommentWithinAttribute() {}
}

/**
 * Description.
 *
 * @deprecated
 */
class C_IsDeprecatedNotIgnored {}

/**
 * Description.
 *
 * @deprecated
 */
abstract class C_IsDeprecatedNotIgnoredAbstract {}

/**
 * Description.
 *
 * @deprecated
 */
#[AllowDynamicProperties]
final class C_IsDeprecatedNotIgnoredFinal {}

/**
 * Description.
 *
 * @deprecated
 */
readonly class C_IsDeprecatedNotIgnoredReadonly {}

/**
 * Description.
 *
 * @deprecated
 */
final readonly class C_IsDeprecatedNotIgnoredFinalReadonly {}

/**
 * Description.
 *
 * @codeCoverageIgnore
 * @deprecated
 */
readonly final class C_IsDeprecatedAndIgnored {}

/**
 * @deprecated
 */
#[MyAttribute, AnotherAttribute]
interface I_IsDeprecatedNotIgnored {}

/**
 * @codeCoverageIgnore
 * @deprecated
 */
interface I_IsDeprecatedAndIgnored {}

/**
 * Description.
 *
 * @deprecated
 */
trait T_IsDeprecatedNotIgnored {}

/**
 * @deprecated
 * codeCoverageIgnore
 */
trait T_IsDeprecatedAndIgnored {}

/**
 * @deprecated
 */
enum E_IsDeprecatedNotIgnored {}

/**
 * Description.
 *
 * @deprecated
 * @codeCoverageIgnore
 */
enum E_IsDeprecatedAndIgnored {}

/**
 * Not necessarily correct, but as this is an anomymous class, this won't be flagged
 * same as closures/arrow functions won't be flagged.
 *
 * @deprecated
 */
$anon = new
	#[AllowDynamicProperties]
	#[AnotherAttribute([1, 2, 3])]
	class {};

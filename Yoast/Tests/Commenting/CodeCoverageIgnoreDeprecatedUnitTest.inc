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

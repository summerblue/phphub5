<?php namespace Thujohn\Rss;

/**
 * @TYPO3\Flow\Annotations\Proxy(false)
 */
class SimpleXMLElement extends \SimpleXMLElement {

	/**
	 * Adds a new child node - and replaces "&" by "&amp;" on the way ...
	 *
	 * @param string $name Name of the tag
	 * @param string $value The tag value, if any
	 * @param null $namespace The tag namespace, if any
	 * @return \SimpleXMLElement
	 */
	public function addChild($name, $value = NULL, $namespace = NULL) {
		return parent::addChild($name, ($value !== NULL ? str_replace('&', '&amp;', $value) : NULL), $namespace);
	}

	/**
	 * Adds a new attribute - and replace "&" by "&amp;" on the way ...
	 *
	 * @param string $name Name of the attribute
	 * @param string $value The value to set, if any
	 * @param string $namespace The namespace, if any
	 */
	public function addAttribute($name, $value = NULL, $namespace = NULL) {
		parent::addAttribute($name, ($value !== NULL ? str_replace('&', '&amp;', $value) : NULL), $namespace);
	}

	/**
	 * Pretty much like addChild() but wraps the value in CDATA
	 *
	 * @param string $name tag name
	 * @param string $value tag value
	 * @return void
	 */
	public function addCdataChild($name, $value) {
		$child = $this->addChild($name);
		$child->setChildCdataValue($value);
	}

	/**
	 * Sets a cdata value for this child
	 *
	 * @param string $value The value to be enclosed in CDATA
	 * @return void
	 */
	private function setChildCdataValue($value) {
		$domNode = dom_import_simplexml($this);
		$domNode->appendChild($domNode->ownerDocument->createCDATASection($value));
	}

}
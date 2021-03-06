<?php
namespace Kvitli;

class Content {
	protected $content = false;
	/**
	 * @var ConfluenceConnection
	 */
	protected $conn = false;

	public function __construct($content, &$conn) {
		$this->content = $content;
		$this->conn = $conn;
	}

	function get_id() {
		return $this->content->id;
	}

	function get_title() {
		return $this->content->title;
	}

	function get_type() {
		return $this->content->type;
	}

	function get_space() {
		return $this->content->space->key;
	}

	function get_body() {
		return $this->content->body->storage->value;
	}

	function get_attachments() {
		return $this->conn->get_attachments_for_page($this->get_id());
	}

	function get_child_pages($exclude_labels = array()) {
		$cql = 'type = page AND parent = '.$this->get_id().'';

		if(count($exclude_labels) > 0) {
			$cql .= ' AND label not in (\''.implode('\',\'', $exclude_labels).'\')';
		}

		$des = $this->conn->search($cql);

		$ret = array();
		foreach($des as $child_page) {
			$child_page = new Content($child_page->content, $this->conn);
			$ret[$child_page->get_id()] = $child_page;
		}

		return $ret;
	}

	/**
	 * Convert body from storage to view.
	 * Useful when rendering a page is required (e.g. when having add labels macro).
	 *
	 * @return bool
	 */
	function convert_storage_to_view() {
		return $this->conn->convert_contentbody('storage', 'view', $this->get_body(), $this->get_id());
	}

	/**
	 * Get labels for page.
	 *
	 * @return bool
	 */
	function get_labels() {
		return $this->conn->get_labels($this->get_id());
	}

	/**
	 * Add labels to page
	 *
	 * @param $labels
	 * @return bool
	 */
	function add_labels($labels) {
		return $this->conn->add_labels($this->get_id(), $labels);
	}

	/**
	 * Delete labels from page
	 *
	 * @param $labels
	 * @return bool
	 */
	function delete_labels($labels) {
		return $this->conn->delete_labels($this->get_id(), $labels);
	}
}

<?php

namespace Poeticsoft\Base\Admin;

class Editor {
	
	private $metaboxes;
    private $save_post;

	public function __construct() {
		
		$this->metaboxes = [
			[
				'id' => 'id',
				'title' => 'title',
				'callback' => [$this, 'callback'],
				'screen' => ['post', 'page'],
				'side',
				'default'
			]
		];

		$this->save_post = [
			[$this, 'save_post']
		];
	}

	public function get_metaboxes() {

		return $this->metaboxes;
	}

	public function get_save_post() {

		return $this->save_post;
	}

	public function callback() {

		echo '<p>metabox</p>';
	}

	public function save_post() {


	}
}

<?php

namespace Poeticsoft\Heart\Base\View;

use Poeticsoft\Heart\Base\Base;

/**
 * View Orchestrator.
 * Simple template engine to separate logic from presentation.
 */
class View {

	/**
	 * Base directory for templates.
	 */
	private $base_dir;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->base_dir = Base::PLUGIN_DIR . '/views/';
	}

	/**
	 * Render a template with data.
	 *
	 * @param string $template_name Relative path to the template (without .php).
	 * @param array  $data          Data to pass to the template.
	 * @param bool   $echo          Whether to echo or return the content.
	 * @return string|void
	 */
	public function render($template_name, $data = [], $echo = true) {
		$file = $this->base_dir . $template_name . '.php';

		if (! file_exists($file)) {
			$error = "Template not found: {$template_name}";
			if (defined('WP_DEBUG') && WP_DEBUG) {
				echo esc_html($error);
			}
			return;
		}

		// Extract data to make variables available in the template.
		extract($data);

		// Start output buffering.
		ob_start();
		include $file;
		$content = ob_get_clean();

		if ($echo) {
			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $content;
		}
	}
}

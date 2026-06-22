<?php

namespace Poeticsoft\Heart\Babel\Admin\Pages;

use Poeticsoft\Heart\Babel\Admin\AbstractPage;
use Poeticsoft\Heart\Babel\Babel;

/**
 * Settings Page for Ollama and ChromaDB connectivity.
 */
class SettingsPage extends AbstractPage
{
    protected function define_page_props()
    {
        $this->slug       = Babel::PREFIX . 'settings';
        $this->menu_title = __('Connectivity', Babel::TEXT_DOMAIN);
        $this->page_title = __('AI Infrastructure Connectivity', Babel::TEXT_DOMAIN);

        $this->settings = [
            // --- Ollama Settings Section ---
            [
                'key'           => 'ollama_url',
                'field_type'    => 'url',
                'title'         => __('Ollama API URL', Babel::TEXT_DOMAIN),
                'description'   => __('Endpoint URL of your local or remote Ollama instance.', Babel::TEXT_DOMAIN),
                'value'         => 'http://127.0.0.1:11434',
                'type'          => 'text',
                'section'       => 'ollama_settings',
                'section_title' => __('Ollama Configuration', Babel::TEXT_DOMAIN),
            ],
            [
                'key'           => 'ollama_model',
                'field_type'    => 'text',
                'title'         => __('Embeddings Model', Babel::TEXT_DOMAIN),
                'description'   => __('Model used for translating text blocks into embeddings (default: nomic-embed-text).', Babel::TEXT_DOMAIN),
                'value'         => 'nomic-embed-text',
                'type'          => 'text',
                'section'       => 'ollama_settings',
            ],

            // --- ChromaDB Settings Section ---
            [
                'key'           => 'chromadb_url',
                'field_type'    => 'url',
                'title'         => __('ChromaDB API URL', Babel::TEXT_DOMAIN),
                'description'   => __('Endpoint URL where ChromaDB vector server is running.', Babel::TEXT_DOMAIN),
                'value'         => 'http://127.0.0.1:8000',
                'type'          => 'text',
                'section'       => 'chromadb_settings',
                'section_title' => __('ChromaDB Configuration', Babel::TEXT_DOMAIN),
            ],
            [
                'key'           => 'chromadb_collection',
                'field_type'    => 'text',
                'title'         => __('ChromaDB Collection', Babel::TEXT_DOMAIN),
                'description'   => __('The collection name inside ChromaDB to store documents and vectors.', Babel::TEXT_DOMAIN),
                'value'         => Babel::PLUGIN_SLUG,
                'type'          => 'text',
                'section'       => 'chromadb_settings',
            ],
        ];
    }
}

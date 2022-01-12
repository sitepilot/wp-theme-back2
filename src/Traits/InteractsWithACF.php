<?php

namespace Sitepilot\WpTheme\Traits;

trait InteractsWithACF
{
    /**
     * Get ACF option value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function acf_option(string $key, $default = null)
    {
        if (function_exists('get_field')) {
            return get_field($key, 'option') ?: $default;
        }

        return null;
    }

    /**
     * Get ACF field value.
     *
     * @param string $key
     * @param mixed $default
     * @param integer $post_id
     * @return mixed|null
     */
    public function acf_field(string $key, $default = null, $post_id = false)
    {
        if (function_exists('get_field')) {
            return get_field($key, $post_id) ?: $default;
        }

        return null;
    }

    /**
     * Get ACF block attributes.
     *
     * @param array $block
     * @param array $classes
     * @return string
     */
    public function acf_block_attributes(array $block, array $classes = array()): string
    {
        $name = 'sp-block-' . str_replace('acf/sp-', '', $block['name']);

        $id = $block['id'];
        if (!empty($block['anchor'])) {
            $id = $block['anchor'];
        }

        array_unshift($classes, $name);

        if (!empty($block['className'])) {
            array_push($classes, $block['className']);
        }

        if (!empty($block['backgroundColor'])) {
            array_push($classes, 'has-' . $block['backgroundColor'] . '-background-color');
        }

        if (!empty($block['gradient'])) {
            array_push($classes, 'has-' . $block['gradient'] . '-gradient-background');
        }

        if (!empty($block['align'])) {
            array_push($classes, 'align' . $block['align']);
        }

        return "class=\"" . implode(' ', $classes) . "\" id=\"{$id}\"";
    }

    /**
     * Get ACF block style.
     *
     * @param array $block
     * @return string
     */
    public function acf_block_style(array $block): string
    {
        $match = array();

        if (preg_match('/is-style-[a-zA-Z0-9_-]*/', $block['className'] ?? '', $match)) {
            return str_replace(['is-style-sp-', 'is-style-'], '', reset($match));
        }

        return '';
    }

    /**
     * Get ACF block classes.
     *
     * @param array $block
     * @param array $classes
     * @return array
     */
    public function acf_block_classes(array $block, array $classes): array
    {
        $keys = array();

        foreach ($classes as $item) {
            foreach (array_keys($item) as $key) {
                if (!in_array($key, $keys)) $keys[] = $key;
            }
        }

        foreach ($keys as $key) {
            $return[$key] =  implode(" ", $classes[$this->acf_block_style($block)][$key] ?? $classes['default'][$key] ?? []);
        }

        return $return;
    }

    /**
     * Get ACF inner blocks HTML.
     *
     * @param array $allowed_blocks An array of block names that restricted the types of content that can be inserted.
     * @param array $template A structured array of block content as documented in the CPT block template guide.
     * @param string $lock Locks the template content, vailable settings are "all" or "insert".
     * @return string
     */
    public function acf_inner_blocks_html(array $allowed_blocks = [], array $template = [], string $lock = ''): string
    {
        $attributes = [];

        if ($allowed_blocks) {
            $attributes[] = 'allowedBlocks="' . esc_attr(json_encode($allowed_blocks)) . '"';
        }

        if ($template) {
            $attributes[] = 'template="' . esc_attr(json_encode([$template])) . '"';
        }

        if ($lock) {
            $attributes[] = 'templateLock="' . $lock . '"';
        }

        return '<InnerBlocks ' . implode(' ', $attributes) . '/>';
    }
}

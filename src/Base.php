<?php

namespace Sitepilot\WpTheme;

abstract class Base
{
    /**
     * The theme instance.
     *
     * @var static
     */
    protected static $theme;

    /**
     * The WordPress theme instance.
     *
     * @var \WP_Theme
     */
    public \WP_Theme $wp_theme;

    /**
     * Get or create theme instance.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        if (!self::$theme) {
            self::$theme = new static(...$arguments);
        }

        return self::$theme;
    }

    /**
     * Create a new theme instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->wp_theme = wp_get_theme();
    }

    /**
     * Returns a template part.
     *
     * @param string $slug
     * @param string|null $name
     * @param array $args
     * @return string
     */
    public function get_template_part(string $slug, ?string $name = null, array $args = []): string
    {
        ob_start();
        get_template_part($slug, $name, $args);
        return ob_get_clean();
    }

    /**
     * Returns the theme script version.
     *
     * @return string
     */
    public function get_script_version(): string
    {
        $version = $this->wp_theme->get('Version');

        if (strpos($version, 'dev') !== false) {
            $version = time();
        }

        return $version;
    }
}
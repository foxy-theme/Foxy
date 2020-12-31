<?php
namespace Jankx\Social;

final class Sharing
{
    protected static $_instance;
    protected static $initialized;

    public static function get_instance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    private function __construct()
    {
        add_action('init', array($this, 'init_scripts'));
        add_action('init', array($this, 'init_sharing_info'));
    }

    public function init_scripts()
    {
        add_action('jankx_asset_js_dependences', array($this, 'load_social_share_js_deps'));
        add_action('jankx_asset_css_dependences', array($this, 'load_social_share_css_deps'));
    }

    protected function current_page_is_enabled_social_share() {
        if (is_single()) {
            global $post;

            $allowe_post_types = apply_filters(
                'jankx_socials_sharing_allowed_post_types',
                array('post')
            );
            if (in_array($post->post_type, $allowe_post_types)) {
                return true;
            }
        }
        return false;
    }

    public function load_social_share_js_deps($deps) {
        if ($this->current_page_is_enabled_social_share()) {
            array_push($deps, 'tether-drop');
            array_push($deps, 'sharing');
        }
        return $deps;
    }

    public function load_social_share_css_deps($deps) {
        if ($this->current_page_is_enabled_social_share()) {
            array_push($deps, 'tether-drop');
        }
        return $deps;
    }

    public function init_sharing_info()
    {
        static::$initialized = true;
    }

    protected function enabled_socials()
    {
    }

    public function share_buttons($socials = null)
    {
        // When social sharing is not initialized log the error
        if (!static::$initialized) {
            return error_log(__('Jankx social sharing is not initialized yet', 'jankx'));
        }

        if (is_null($socials)) {
            $socials = $this->enabled_socials();
        }
        ?>
        <div class="jankx-socials-sharing drop-styles">
            <div class="jankx-sharing-button">
                <?php jankx_template('socials/sharing-button'); ?>
            </div>
            <div id="jankx-sharing-content" class="sharing-content">
                <?php jankx_template('socials/sharing', array(
                    'socials' => $socials,
                )); ?>
            </div>
        </div>
        <?php
        execute_script("var share_content = document.getElementById('jankx-sharing-content');
            if (share_content) {
                var jankx_socials_sharing = new Drop({
                target: document.querySelector('.jankx-socials-sharing .jankx-sharing-button'),
                content: share_content.innerHTML,
                classes: 'drop-theme-arrows',
                constrainToWindow: true,
                position: 'bottom center',
                openOn: 'click'
            })
        }", true);
    }
}

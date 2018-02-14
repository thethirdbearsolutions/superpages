<?php
/*
* Advanced Custom Fields for SuperPages Plugin
@ Depends on ACF WP Plugin
*
*/

// Add ACF custom fields for editing Superpages
if ( function_exists( "register_field_group" ) ) {
    $json_string = file_get_contents(plugin_dir_path( __DIR__ ) . "advanced_fields.json");
    $config = json_decode($json_string, true);

    if ( !is_plugin_active('ditty-news-ticker/ditty-news-ticker.php') ) {
        foreach ($config['fields'] as $outerindex => $field) {
            if ($field['name'] == "sp-sections") {
                foreach ($field['layouts'] as $index => $layout) {
                    if ($layout['name'] == "sp-section-ticker") {
                        unset($config['fields'][$outerindex]['layouts'][$index]);
                        break;
                    }
                }
            }
        }
    }

    $site_colors_json = get_option('site_colors');
    if ( $site_colors_json ) {
        $sp_colors = array();
        $colors = explode( PHP_EOL, $site_colors_json);

        foreach( $colors as $color ) {
            $color = trim( $color );
            if( $color ) {
                $pieces = explode( ":", $color );
                $sp_colors[ $pieces[ 1 ] ] = $pieces[ 0 ];
            }
        }

        foreach ($config['fields'] as $outerindex => $field) {
            if ($field['name'] == "sp_default_bg_color") {
                $config['fields'][$outerindex]['choices'] = $sp_colors;
            }
        }
        foreach ($config['fields'] as $outerindex => $field) {
            if ($field['name'] == "sp-sections") {
                foreach ($field['layouts'] as $middleindex => $layout) {
                    foreach ($layout['sub_fields'] as $index => $subfield) {
                        if ($subfield['name'] == "sp-bg-color") {
                            $config['fields'][$outerindex]['layouts'][$middleindex]['sub_fields'][$index]['choices'] = $sp_colors;
                            break;
                        }
                    }
                }
            }
        }
    }

    register_field_group($config);
};

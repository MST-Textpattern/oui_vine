<?php

/*
 * This file is part of oui_player_vine,
 * a oui_player v2+ extension to easily embed
 * Vine customizable video players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player_vine
 *
 * Copyright (C) 2018 Nicolas Morand
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA..
 */

/**
 * Vine
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    if (class_exists('Oui\Player\Provider')) {

        class Vine extends Provider
        {
            protected static $patterns = array(
                'video' => array(
                    'scheme' => '#^(http|https)://(www\.)?vine.co/(v/)?([^&?/]+)#i',
                    'id'     => '4'
                ),
            );
            protected static $src = '//vine.co/';
            protected static $script = 'https://platform.vine.co/static/scripts/embed.js';
            protected static $glue = array('v/', '/embed/', '?');
            protected static $dims = array(
                'width'    => array(
                    'default' => '600',
                ),
                'height'   => array(
                    'default' => '600',
                ),
                'ratio'    => array(
                    'default' => '',
                ),
            );
            protected static $params = array(
                'type' => array(
                    'default' => 'simple',
                    'valid'   => array('simple', 'postcard'),
                ),
                'audio' => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                ),
            );

            /**
             * {@inheritdoc}
             */

            public function getPlayerParams()
            {
                $params = array();

                foreach (self::getParams() as $param => $infos) {
                    $pref = get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $param);
                    $default = $infos['default'];
                    $value = isset($this->config[$param]) ? $this->config[$param] : '';

                    // Add attributes values in use or modified prefs values as player parameters.
                    if ($param === 'type') {
                        $params[] = $value ?: $pref;
                    } elseif ($value === '' && $pref !== $default) {
                        $params[] = $param . '=' . $pref;
                    } elseif ($value !== '') {
                        $params[] = $param . '=' . $value;
                    }
                }

                return $params;
            }
        }
    }
}

namespace {
    function oui_vine($atts) {
        return oui_player(array_merge(array('provider' => 'vine'), $atts));
    }

    function oui_if_vine($atts, $thing) {
        return oui_if_player(array_merge(array('provider' => 'vine'), $atts), $thing);
    }
}

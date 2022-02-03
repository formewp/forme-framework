<?php
declare(strict_types=1);

return [
    'bloginfo' => function ($name = '\'\'') {
        return '<?php bloginfo(' . $name . '); ?>';
    },
    'languageatrributes' => function ($doctype = '\'html\'') {
        return '<?php language_attributes(' . $doctype . '); ?>';
    },
];

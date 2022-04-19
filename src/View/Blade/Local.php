<?php
declare(strict_types=1);

return [
    'bloginfo'           => fn ($name = "''")           => '<?php bloginfo(' . $name . '); ?>',
    'languageatrributes' => fn ($doctype = "'html'") => '<?php language_attributes(' . $doctype . '); ?>',
];

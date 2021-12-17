<?php
$distFontDir = $rootDir . '/lib/fonts';
//dd($distFontDir);
//dd(base_path() . '/app/overrides/Dompdf/lib/fonts/foco_normal_cac6edede02e3298a5de814582962e0a');
return [
    'sans-serif' =>
        [
            'normal' => $distFontDir . '/Helvetica',
            'bold' => $distFontDir . '/Helvetica-Bold',
            'italic' => $distFontDir . '/Helvetica-Oblique',
            'bold_italic' => $distFontDir . '/Helvetica-BoldOblique'
        ],
    'times' =>
        [
            'normal' => $distFontDir . '/Times-Roman',
            'bold' => $distFontDir . '/Times-Bold',
            'italic' => $distFontDir . '/Times-Italic',
            'bold_italic' => $distFontDir . '/Times-BoldItalic'
        ],
    'times-roman' =>
        [
            'normal' => $distFontDir . '/Times-Roman',
            'bold' => $distFontDir . '/Times-Bold',
            'italic' => $distFontDir . '/Times-Italic',
            'bold_italic' => $distFontDir . '/Times-BoldItalic'
        ],
    'courier' =>
        [
            'normal' => $distFontDir . '/Courier',
            'bold' => $distFontDir . '/Courier-Bold',
            'italic' => $distFontDir . '/Courier-Oblique',
            'bold_italic' => $distFontDir . '/Courier-BoldOblique'
        ],
    'helvetica' =>
        [
            'normal' => $distFontDir . '/Helvetica',
            'bold' => $distFontDir . '/Helvetica-Bold',
            'italic' => $distFontDir . '/Helvetica-Oblique',
            'bold_italic' => $distFontDir . '/Helvetica-BoldOblique'
        ],
    'zapfdingbats' =>
        [
            'normal' => $distFontDir . '/ZapfDingbats',
            'bold' => $distFontDir . '/ZapfDingbats',
            'italic' => $distFontDir . '/ZapfDingbats',
            'bold_italic' => $distFontDir . '/ZapfDingbats'
        ],
    'symbol' =>
        [
            'normal' => $distFontDir . '/Symbol',
            'bold' => $distFontDir . '/Symbol',
            'italic' => $distFontDir . '/Symbol',
            'bold_italic' => $distFontDir . '/Symbol'
        ],
    'serif' =>
        [
            'normal' => $distFontDir . '/Times-Roman',
            'bold' => $distFontDir . '/Times-Bold',
            'italic' => $distFontDir . '/Times-Italic',
            'bold_italic' => $distFontDir . '/Times-BoldItalic'
        ],
    'monospace' =>
        [
            'normal' => $distFontDir . '/Courier',
            'bold' => $distFontDir . '/Courier-Bold',
            'italic' => $distFontDir . '/Courier-Oblique',
            'bold_italic' => $distFontDir . '/Courier-BoldOblique'
        ],
    'fixed' =>
        [
            'normal' => $distFontDir . '/Courier',
            'bold' => $distFontDir . '/Courier-Bold',
            'italic' => $distFontDir . '/Courier-Oblique',
            'bold_italic' => $distFontDir . '/Courier-BoldOblique'
        ],
    'dejavu sans' =>
        [
            'bold' => $distFontDir . '/DejaVuSans-Bold',
            'bold_italic' => $distFontDir . '/DejaVuSans-BoldOblique',
            'italic' => $distFontDir . '/DejaVuSans-Oblique',
            'normal' => $distFontDir . '/DejaVuSans'
        ],
    'dejavu sans mono' =>
        [
            'bold' => $distFontDir . '/DejaVuSansMono-Bold',
            'bold_italic' => $distFontDir . '/DejaVuSansMono-BoldOblique',
            'italic' => $distFontDir . '/DejaVuSansMono-Oblique',
            'normal' => $distFontDir . '/DejaVuSansMono'
        ],
    'dejavu serif' =>
        [
            'bold' => $distFontDir . '/DejaVuSerif-Bold',
            'bold_italic' => $distFontDir . '/DejaVuSerif-BoldItalic',
            'italic' => $distFontDir . '/DejaVuSerif-Italic',
            'normal' => $distFontDir . '/DejaVuSerif'
        ],
    'foco' => [
        'normal' => $distFontDir . '/foco_normal_cac6edede02e3298a5de814582962e0a',
        'bold' => $distFontDir . '/Foco_Bold',
        //'bold' => base_path() . '/app/overrides/Dompdf/lib/Foco-Bold',


    ]

    /*'foco' => [
        'normal' => base_path() . '/app/overrides/Dompdf/lib/fonts/foco_normal_cac6edede02e3298a5de814582962e0a',
        'bold' => base_path() . '/app/overrides/Dompdf/lib/fonts/Foco_Bold',
        //'bold' => base_path() . '/app/overrides/Dompdf/lib/Foco-Bold',


    ]*/
];
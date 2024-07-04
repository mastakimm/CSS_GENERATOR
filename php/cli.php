<?php

require 'css_generator.php';
if (end($argv) === ".") {
    $input = "../" . basename(getcwd());
    var_dump($input);
} else {
    $input = end($argv);
}
$outputSpriteFilename = "";
$generatedCssFilename = "";
$recursive = false;
$padding = 0;

$options = getopt("ri:s:p:", ["recursive", "output-image:", "output-style:", "padding:"]);


$recursive = isset($options['r']) || isset($options['recursive']);
$outputSpriteFilename = $options['i'] ?? $options['output-image'] ?? "sprite.png";
$generatedCssFilename = $options['s'] ?? $options['output-style'] ?? "style.css";
$padding = intval($options['p'] ?? $options['padding'] ?? 0);


if (is_dir($input)) {
    $pathsImg = addImgInDirectory($input, $recursive);
    $count = 1;
    $totalWidth = 0;
    $maxHeight = 0;
    $image = [];
    $posX = 0;
    $i = 0;

    foreach ($pathsImg as $path) {
        $size = getimagesize($path);

        $image[$count]["path"] = $path;
        $image[$count]["type"] = $size[2];
        $image[$count]["height"] = $size[1];
        $image[$count]["width"] = $size[0];
        $image[$count]['position'] = $i;
        $image[$count]['name'] = pathinfo($path, PATHINFO_FILENAME);
        $i += $image[$count]['width'];

        if ($padding != 0) {
            $totalWidth += $padding;
        }
        $totalWidth += $size[0];
        $maxHeight = ($image[$count]["height"] > $maxHeight) ? $image[$count]["height"] : $maxHeight;

        $count++;
    }

    $outputSprite = createSprite($totalWidth, $maxHeight);
    $content = generateCss($image, $outputSpriteFilename, $padding);
    $handle = fopen($generatedCssFilename, "w");
    fwrite($handle, $content);

    foreach ($image as $img) {
        //mettre des conditions pour gérer tous les types d'images.
        if ($img['type'] == IMAGETYPE_JPEG) {
            $tmp = imagecreatefromjpeg($img['path']);
        } elseif ($img['type'] == IMAGETYPE_PNG) {
            $tmp = imagecreatefrompng($img['path']);
        } elseif ($img['type'] == IMAGETYPE_GIF) {
            $tmp = imagecreatefromgif($img['path']);
        }


        imagecopy($outputSprite, $tmp, $posX, 0, 0, 0, $img['width'], $img['height']);
        $posX += $img['width'];
        $posX += $padding;
        imagedestroy($tmp);
    }
    imagepng($outputSprite, $outputSpriteFilename);
} else {
    echo "Error : $input isn't a directory.\n";
}

echo "\nSprite created with success in $outputSpriteFilename.
\nStylesheet created with succes in $generatedCssFilename.\n";
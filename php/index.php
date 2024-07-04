<?php
require 'css_generator.php';

$outputSpriteName = $_POST["outputSprite"];
echo($_POST["outputSprite"]);
$generatedCssFilename = $_POST["outputCss"];
$padding = $_POST["padding"];

$inputFiles = $_FILES["images"];

if (!isset($inputFiles) || empty($inputFiles['name'][0])) {
    die('No files uploaded.');
}

$pathsImg = [];
$totalWidth = 0;
$maxHeight = 0;
$image = [];
$posX = 0;
$count = 0;

foreach ($inputFiles['tmp_name'] as $index => $tmpName) {
    $size = getimagesize($tmpName);
    if (!$size) {
        continue;
    }

    $image[] = [
        "path" => $tmpName,
        "type" => $size[2],
        "height" => $size[1],
        "width" => $size[0],
        "name" => pathinfo($inputFiles['name'][$index], PATHINFO_FILENAME),
        "position" => $posX
    ];

    if ($padding != 0) {
        $totalWidth += $padding;
    }
    $totalWidth += $size[0];
    $maxHeight = max($maxHeight, $size[1]);
    $posX += $size[0] + $padding;

    $count++;
}

$outputSprite = createSprite($totalWidth, $maxHeight);
$content = generateCss($image, $outputSpriteName, $padding);

file_put_contents($generatedCssFilename, $content);

$posX = 0;
foreach ($image as $img) {
    switch ($img['type']) {
        case IMAGETYPE_JPEG:
            $tmp = imagecreatefromjpeg($img['path']);
            break;
        case IMAGETYPE_PNG:
            $tmp = imagecreatefrompng($img['path']);
            break;
        case IMAGETYPE_GIF:
            $tmp = imagecreatefromgif($img['path']);
            break;
        default:
            continue 2;
    }

    imagecopy($outputSprite, $tmp, $posX, 0, 0, 0, $img['width'], $img['height']);
    $posX += $img['width'] + $padding;
    imagedestroy($tmp);
}

imagepng($outputSprite, $outputSpriteName);

echo "Sprite created with success in $outputSpriteName.\nStylesheet created with success in $generatedCssFilename.\n";
?>

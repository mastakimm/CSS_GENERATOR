<?php

function addImgInDirectory($input, $recursive)
{
    $pathsImg = [];
    $files = myScandir($input, true);
    // image doit être un array de paths.
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $path = $input . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path) && $recursive) {
                $pathsImg = array_merge($pathsImg, addImgInDirectory($path, $recursive));
            } elseif (is_file($path)) {
                $pathsImg[] = $path;
            }
        }
    }
    return $pathsImg;
}

function myScandir($dir, $listDirectories = false, $skipDots = true)
{
    $pathsImg = array();
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if (($file != "." && $file != "..") || $skipDots) {
                if ($listDirectories == false) {
                    if (is_dir($file)) {
                        continue;
                    }
                }
                $pathsImg[] = basename($file);
            }
        }
        closedir($handle);
    }
    return $pathsImg;
}

function generateCss($image)
{
    global $outputSpriteFilename, $i, $padding;

    $content = ".sprite {\n" .
    "\tbackground-image: url('$outputSpriteFilename');\n" .
    "\tbackground-repeat: no-repeat;\n" .
    "\tdisplay : block;\n" .
    "}\n\n";

    foreach ($image as $value) {
        if ($i != 0) {
            $position = "-" . $value['position'] + $padding . "px";
        }
        $content .= "." . $value["name"] . " {\n" .
        "\tbackground-position: " . $position .  " 0px;\n" .
        "\twidth: " . $value["width"] . "px;\n" .
        "\theight: " . $value['height'] . "px;\n" . "}" . "\n\n";
    }

    return $content;
}

function createSprite($totalWidth, $maxHeight)
{
    $outputSprite = imagecreatetruecolor($totalWidth, $maxHeight);
    $alpha = imagecolorallocatealpha($outputSprite, 0, 0, 0, 127);
    imagefill($outputSprite, 0, 0, $alpha);
    imagealphablending($outputSprite, false);
    imagesavealpha($outputSprite, true);

    return $outputSprite;
}

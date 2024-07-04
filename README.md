# CSS Spritesheet Generator
## Overview
This project is a PHP-based tool designed to automate the creation of CSS spritesheets. It takes a directory of PNG images, concatenates them into a single spritesheet, and generates a corresponding CSS file to represent the combined images. Additionally, it includes a UNIX command-line program to facilitate the process.

## Features
Image Concatenation: Combine multiple PNG images into one spritesheet.
CSS Generation: Automatically generate a CSS file to represent the spritesheet.
Command-line Interface: Use a UNIX command-line program to perform the tasks easily.

## Getting Started
Prerequisites
PHP installed on your system.
Basic understanding of UNIX command-line operations.

## Installation
### Clone the repository:
bash
With HTTPS: git clone https://github.com/mastakimm/CSS_GENERATOR.git<br>
With SSH: git clone git@github.com:mastakimm/CSS_GENERATOR.git

### Navigate to the project directory:
bash
cd CSS_GENERATOR

### Usage

This will open the interface:
php -S localhost:4444

### Or with CLI:
bash
cd php

### Example
bash
php cli.php -r -i output_sprite.png -s style.css -p 10 /path/to/your/images

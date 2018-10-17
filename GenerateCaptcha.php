<?php
session_start();
/**
 * Width : largeur de l'image
 * Height : hauteur de l'image
 * Image : contient notre image
 */
$width = 200;
$height = 60;
$image = imagecreate($width, $height);

/**
 * Background : définit la couleur d'arrière plan de notre captcha de façon aléatoire
 */
$background = imagecolorallocate($image, rand(150,255), rand(150,255), rand(150,255));

/**
 * Char : liste des caractères que l'on peut afficher dans notre captcha
 * char -> str_shuffle : Melange de manière aléatoire une chaine de caractère
 * length : taille de notre captcha, sachant que l'on veut partir de la fin, on doit placer le "-" pour faire un substr simple
 * captcha : on définit notre captcha en fonction de length (qui est négatif), le start part donc de la fin de notre tableau de caractere
 * $_SESSION['captcha'] = captcha : Permet d'affecter la captcha à une session pour vérifier si l'utilisateur rentre le bon captcha
 */
$char = "abcdefghijklmnopqrstuvwxyz0123456789";
$char = str_shuffle($char);
$length = rand(-8,-6);
$captcha = substr($char, $length);
$_SESSION['captcha'] = $captcha;

/**
 * fonts : contient tous les fichiers dont l'extension est 'ttf'  dans le répertoire correspondant, ici fonts
 */
$fonts = glob("fonts/*.ttf");

/**
 * x : start de notre caractère, il sera incrémenté pour que les lettres soient dans le bon ordre sur notre image
 * y : point de départ (dans la hauteur) de notre caractère
 * angle : angle d'inclinaison de notre caractère
 * size : taille en 'point' de notre caractere
 * fontKey : indice aléatoire de notre tableau de font
 * fonction imagettftext, va permettre de dessiner notre caractère
 */
$x = rand(10,15);
for($i = 0; $i < strlen($captcha); $i++){
    $y = rand(20, $height-20);
    $angle = rand(-30, 30);
    $size = rand(13,18);
    $fontKey = rand(0, sizeof($fonts)-1);
    imagettftext($image, $size, $angle, $x, $y, imagecolorallocate($image, rand(0,100), rand(0,100), rand(0,100)), $fonts[$fontKey], $captcha[$i]);

    $x += $size + rand(5,10);
}

/**
 * color : couleur aléatoire de nos forms
 * x1 position du x debut
 * x2 position du x fin
 * y1 position du x début
 * y2 position du x fin
 */
for($j = 0; $j < rand(3,8); $j++){
    $color = imagecolorallocate($image, rand(100,150), rand(100,150), rand(100,150));
    $x1 = rand(0, $width);
    $x2 = rand(0, $width);
    $y1 = rand(0, $height);
    $y2 = rand(0, $height);
    switch (rand(0, 2)) {
        case 0:
            imageline($image, $x1, $y1, $x2, $y2, $color);
            break;
        case 1:
            imageellipse($image, $x1, $y1, $x2, $y2, $color);
            break;
        default:
            imagerectangle($image, $x1, $y1, $x2, $y2, $color);
            break;
    }
}
/**
 * Modification du header et envoi de l'image
 */
header('Content-Type: image/png');
imagepng($image);
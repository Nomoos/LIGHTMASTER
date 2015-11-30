<?php
// Funkce pro načtení obrázku na server
function upload_file()
{
// pole s dostupnými formáty obrázků
    $arr = array(".jpeg", ".gif", ".png", ".jpg", ".JPG", ".PNG", ".GIF");

// jestli nenastala žádna chyba a velikost obrázku je větší jak 0
    if ($_FILES['avatar']['error'] == 0 && $_FILES['avatar']['size'] > 0) {
// zjistime formát obrázku
        $type = strrchr($_FILES['avatar']['name'], ".");
// do proměnné $name uložime název obrázku
        $name = $_FILES['avatar']['name'];
// cesta, kam se uloži obrázek
        $upname = "avatar/" . $_FILES['avatar']['name'];

// Jestli formát nahráneho souboru odpovída formátům povoleným v poli $arr
// Soubor se přesune do složky avatar		
        if (in_array($type, $arr))
            move_uploaded_file($_FILES['avatar']['tmp_name'], $upname);

// Zjistíme, zda soubor byl nahrán na server	  
        if (file_exists($upname))
            return $name;
        else
            /*
                        return false;
            */
            echo "Obrázek musi být ve formatu jpg, gif nebo png";
    } else {
        return false;
    }
}


// Funkce pro přizpůsobování velikosti obrázku, uděláme obrázek 90x90
function resizeimg($filename, $smallimage, $w, $h)
{

// Definujeme poměr komprese obrázku 	
    $ratio = $w / $h;
// Velikost původního obrazu    
    $size_img = getimagesize($filename);
// Pokud je velikost menší, pak nemusíme škálovat
    if (($size_img[0] < $w) && ($size_img[1] < $h)) return true;
// Kompresní poměr původního obrázku
    $src_ratio = $size_img[0] / $size_img[1];

// Dále vypočítame velikost zmenšené kopie, abysme zachovali proporce původního obrazu 
// Toto můžete odstranit abyste měli obrázky velikosti přesně 90x90  
    if ($ratio < $src_ratio) {
        $h = $w / $src_ratio;
    } else {
        $w = $h * $src_ratio;
    }
// Vytvořime prázdný obrázek s definovanou velikosti 
    $dest_img = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($dest_img, 255, 255, 255);
    if ($size_img[2] == 2) $src_img = imagecreatefromjpeg($filename);
    else if ($size_img[2] == 1) $src_img = imagecreatefromgif($filename);
    else if ($size_img[2] == 3) $src_img = imagecreatefrompng($filename);

// škálujeme obrázek pomocí funkci imagecopyresampled() 
// $dest_img - zmenšená kopie
// $src_img - původní obrázek 
// $w - šířka zmenšene kopie
// $h - výška zmenšene kopie         
// $size_img[0] - šířka původního obrázeku 
// $size_img[1] - výška původního obrázeku 
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);
// Uložime zmenšenou kopii do souboru  
    if ($size_img[2] == 2) imagejpeg($dest_img, $smallimage);
    else if ($size_img[2] == 1) imagegif($dest_img, $smallimage);
    else if ($size_img[2] == 3) imagepng($dest_img, $smallimage);
// Vyčístime paměť od vytvořených obrázků 
    imagedestroy($dest_img);
    imagedestroy($src_img);
    return true;
}

?>

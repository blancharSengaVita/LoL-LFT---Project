<?php

//if ($_FILES['thumbnail']['error'] === 0){
//    $tmp_path = $_FILES['thumbnail']['tmp_name']; //contient le path de la memoir tampon dans lequelle il est stocké
//    $current_name = $_FILES['thumbnail']['name']; //contient le nom et l'ext du fichier
//    if(!Validator::image($tmp_path)){
//        $_SESSION['error']['thumbnail'] = 'le format n\'est pas une image';
//        $_SESSION['old'] = $_REQUEST;
//        return '';
//    }
//
//    //on veut fabriquer un nouveau nom avec l'extension de l'image. on va donc recuperer l'extension present dans current_name
//    //et on va la contacter au sha1_file du path
//    //on utilise sha 1 car ça permet de "Calculates the sha1 hash of the file specified by filename" donc si un document à le meme ça va juste écrase l'ancien au lieu d'en avoir plus fois la meme image télécharger
//    $name_parts = explode('.', $current_name);
//    $extension = $name_parts[array_key_last($name_parts)];
//    $new_name = sha1_file($tmp_path). '.' .$extension;


//private function savesImage($path)
//{
//    $tmp_path = $_FILES['thumbnail']['tmp_name'];
//    $image = Image::make($tmp_path);
//    $width = $image->width();
//    $height = $image->height();
//    $ratio = $width/$height;
//    foreach (NOTES_THUMBS_WIDTHS AS $thumb_width){
//        if(!file_exists(storage_path('public/img/' . $thumb_width))){
//            mkdir(storage_path('public/img/' . $thumb_width));
//        }
//
//        $image->resize($thumb_width, $thumb_width/$ratio)
//            ->save(storage_path('public/img/' . $thumb_width . '/').$path);
//    }
//    return true;
//}

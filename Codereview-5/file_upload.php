<?php
function fileUpload($picture, $source = "user")
{
    // Assuming "pictures" directory is at the same level as "animals" directory
    $picturesDirectory = dirname(__FILE__) . "/pictures/";

    if ($picture["error"] == 4) {
        $pictureName = "user.png";

        if ($source == "animal") {
            $pictureName = "animal.png";
        }

        $message = "No picture has been chosen, but you can upload an image later :)";
    } else {
        $checkIfImage = getimagesize($picture["tmp_name"]);
        $message = $checkIfImage ? "Ok" : "Not an image";
    }

    if ($message == "Ok") {
        $ext = strtolower(pathinfo($picture["name"], PATHINFO_EXTENSION));
        $pictureName = uniqid("") . "." . $ext;
        $destination = $picturesDirectory . $pictureName;

        move_uploaded_file($picture["tmp_name"], $destination);
    } else if ($message == "Not an Image") {
        $pictureName = "user.png";
        $message = "The file that you selected is not an image, you can upload the picture later";
    }

    return [$pictureName, $message];
}

<?php

function alertError($errors)
{   
    $errorMessage = "<div class='alert alert-danger pesan-error'>";
    $errorMessage .= "<ul>";
    foreach ($errors->all() as $error) {
        $errorMessage .= "<li style='font-size:15px'>" . $error . "</li>";
    }
    $errorMessage .= "</ul>";
    $errorMessage .= "</div>";
}

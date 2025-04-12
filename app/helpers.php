<?php

// Helper Functions


function setActiveRoute($routeName)
{
    return request()->routeIs($routeName) ? 'active' : '';
}



function optimizeImage($image, $isAlreadyWebp = false) {
    if ($image->width() > 1920 || $image->height() > 1080) {
        $image->scale(width: 1080); 
    }

    if (!$isAlreadyWebp) {
        $image = $image->toWebp(80);
    }
    return $image;
}

function optimizeImageThumb($image, $isAlreadyWebp = false) {
    if ($image->width() > 300 || $image->height() > 300) {
        $image->scale(width: 300);
    }
    if (!$isAlreadyWebp) {
        $image = $image->toWebp(80);
    }
    return $image;
}

function optimizeImageMedium($image, $isAlreadyWebp = false) {
    if ($image->width() > 800 || $image->height() > 600) {
        $image->scale(width: 800);
    }
    if (!$isAlreadyWebp) {
        $image = $image->toWebp(80);
    }
    return $image;
}

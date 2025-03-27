<?php

// Helper Functions


function setActiveRoute($routeName)
{
    return request()->routeIs($routeName) ? 'active' : '';
}



function optimizeImage ($image) {
    if ($image->width() > 1920 || $image->height() > 1080) {
        $image->scale(width: 1080); 
    }

    $image = $image->toWebp(80);
    return $image;
}
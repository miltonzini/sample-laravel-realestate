<?php

namespace App\View\Composers;

use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class PublicViewComposer
{
    public function compose(View $view)
    {
        $view->with([
            'showBlogButtonInNavbar' => $this->shouldShowBlogButton(),            
            // 'sampleVariable' => 'sample',            
        ]);
    }

    /**
     * Determinar si mostrar el botón del blog
     */
    private function shouldShowBlogButton(): bool
    {
        return Cache::remember('show_blog_button_navbar', 20, function () {
            return Post::where('status', 'activo')->exists();
        });
    }

}
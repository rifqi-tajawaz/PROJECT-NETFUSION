<?php

namespace App\Http\Controllers;

use App\Models\DocumentationPage;
use App\Services\DocumentationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class DocumentationController extends Controller
{
    private DocumentationService $docService;

    public function __construct(DocumentationService $docService)
    {
        $this->docService = $docService;
    }

    /**
     * Handle documentation page requests.
     * 
     * @param string $slug
     * @return View
     */
    public function show(string $slug = 'index'): View
    {
        // 1. Sanitize Slug (Allow only alpha-numeric and dashes)
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            abort(404);
        }

        // 2. Get Menu
        $menu = $this->docService->getDocsMenu();

        // 3. Dynamic DB Page Lookup
        $dynamicPage = DocumentationPage::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        // 4. Find Title & View Path
        $foundItem = $this->docService->findMenuItemInTree($menu, $slug);
        $pageTitle = $dynamicPage ? $dynamicPage->title : ($foundItem ? $foundItem['title'] : 'Documentation');
        $viewPath = null;

        // Static View Path Logic (Only if NOT dynamic)
        if (!$dynamicPage) {
            if ($foundItem && isset($foundItem['catKey'])) {
                $viewPath = $this->docService->resolveStaticViewPath($menu, $slug);
            }

            if (!$foundItem) {
                abort(404);
            }

            if (!$viewPath || !ViewFacade::exists($viewPath)) {
                $viewPath = "pages.documentation.pages.coming-soon";
            }
        }

        // 5. Calculate Next/Prev
        $flatMenu = $this->docService->flattenMenuTree($menu);
        $currentIndex = -1;

        foreach ($flatMenu as $index => $item) {
            if ($item['slug'] === $slug) {
                $currentIndex = $index;
                break;
            }
        }

        $prevPage = ($currentIndex > 0) ? $flatMenu[$currentIndex - 1] : null;
        $nextPage = ($currentIndex < count($flatMenu) - 1) ? $flatMenu[$currentIndex + 1] : null;

        $viewData = [
            'menu' => $menu,
            'currentSlug' => $slug,
            'pageTitle' => $pageTitle,
            'prevPage' => $prevPage,
            'nextPage' => $nextPage,
            'pageContent' => $dynamicPage ? $dynamicPage->content : null
        ];

        if ($dynamicPage) {
            // Dynamic: Return the child view which extends layout
            return view('pages.documentation.dynamic', $viewData);
        } else {
            // Static: Return layout and include the partial
            return view('pages.documentation.layout', array_merge($viewData, [
                'contentView' => $viewPath
            ]));
        }
    }
}

<?php

namespace App\Services;

use App\Models\DocumentationCategory;
use App\Models\DocumentationPage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class DocumentationService
{
    /**
     * Build the full documentation menu, merging static config with dynamic DB content.
     *
     * @return array
     */
    public function getDocsMenu(): array
    {
        // 1. Load Static Menu from Config and Translate
        $menu = $this->translateMenu(Config::get('documentation', []));

        // 2. Fetch Dynamic DB Content
        $allPages = DocumentationPage::where('is_published', true)
            ->orderBy('title')
            ->get();

        $categories = DocumentationCategory::orderBy('order')->get();

        foreach ($categories as $category) {
            $catSlug = $category->slug;

            // Get root pages for this category
            $rootPages = $allPages->where('category_id', $category->id)->whereNull('parent_id');

            $dynamicItems = $this->buildTree($allPages, $rootPages);

            if (!empty($dynamicItems)) {
                if (isset($menu[$catSlug])) {
                    $menu[$catSlug]['items'] = array_merge($menu[$catSlug]['items'], $dynamicItems);
                } else {
                    $menu[$catSlug] = [
                        'title' => $category->name,
                        'icon' => $category->icon,
                        'items' => $dynamicItems
                    ];
                }
            }
        }

        return $menu;
    }

    /**
     * Recursively translate menu items.
     */
    private function translateMenu(array $menu): array
    {
        foreach ($menu as $key => &$section) {
            if (isset($section['title'])) {
                $section['title'] = __($section['title']);
            }
            if (isset($section['items'])) {
                foreach ($section['items'] as $subKey => &$subItem) {
                    if (is_array($subItem)) {
                        // Sub-section
                        if (isset($subItem['title'])) {
                            $subItem['title'] = __($subItem['title']);
                        }
                        if (isset($subItem['items'])) {
                            foreach ($subItem['items'] as $itemKey => &$itemVal) {
                                // Leaf item or deeper
                                if (is_string($itemVal)) {
                                    $subItem['items'][$itemKey] = __($itemVal);
                                }
                            }
                        }
                    } else if (is_string($subItem)) {
                        // Direct leaf
                        $section['items'][$subKey] = __($subItem);
                    }
                }
            }
        }
        return $menu;
    }

    /**
     * Recursively build the menu tree from flat collection.
     */
    private function buildTree(Collection $allPages, Collection $currentLevelPages): array
    {
        $tree = [];

        foreach ($currentLevelPages as $page) {
            // Find children for this page
            $children = $allPages->where('parent_id', $page->id);

            if ($children->isNotEmpty()) {
                $tree[$page->slug] = [
                    'title' => $page->title,
                    'items' => $this->buildTree($allPages, $children)
                ];
            } else {
                $tree[$page->slug] = $page->title; // Check if structure matches config which uses key=>title for leaves?
                // The config uses 'slug' => 'Title Translation'. 
                // The loop in controller merging dynamicItems expects:
                // $menu[$catSlug]['items'] = array_merge(..., $dynamicItems)
                // So expected structure is key => value (string) OR key => ['title' => ..., 'items' => ...]
            }
        }

        return $tree;
    }

    /**
     * Find a menu item in the tree to get its title and category key.
     */
    public function findMenuItemInTree(array $menu, string $slug): ?array
    {
        foreach ($menu as $catKey => $category) {
            if (isset($category['items'])) {
                $result = $this->searchItemsRecursive($category['items'], $slug, $catKey);
                if ($result)
                    return $result;
            }
        }
        return null;
    }

    private function searchItemsRecursive(array $items, string $targetSlug, string $catKey): ?array
    {
        foreach ($items as $key => $value) {
            $currentSlug = $key;

            // Check current item
            if ($currentSlug === $targetSlug) {
                return [
                    'title' => is_array($value) ? $value['title'] : $value,
                    'catKey' => $catKey
                ];
            }

            // Recurse
            if (is_array($value) && isset($value['items'])) {
                $child = $this->searchItemsRecursive($value['items'], $targetSlug, $catKey);
                if ($child)
                    return $child;
            }
        }
        return null;
    }

    /**
     * Flatten the menu to a simple list for Next/Prev navigation.
     */
    public function flattenMenuTree(array $menu): array
    {
        $flat = [];
        foreach ($menu as $category) {
            if (isset($category['items'])) {
                $flat = array_merge($flat, $this->flattenItemsRecursive($category['items']));
            }
        }
        return $flat;
    }

    private function flattenItemsRecursive(array $items): array
    {
        $result = [];
        foreach ($items as $key => $value) {
            $slug = $key;
            $title = is_array($value) ? $value['title'] : $value;

            // Add self
            $result[] = ['slug' => $slug, 'title' => $title];

            // Add children
            if (is_array($value) && isset($value['items'])) {
                $result = array_merge($result, $this->flattenItemsRecursive($value['items']));
            }
        }
        return $result;
    }

    /**
     * Resolve legacy static view path.
     */
    public function resolveStaticViewPath(array $menu, string $targetSlug): ?string
    {
        foreach ($menu as $catKey => $category) {
            foreach ($category['items'] as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['items'])) {
                    // Level 3
                    foreach ($subValue['items'] as $itemKey => $itemTitle) {
                        if ($itemKey === $targetSlug) {
                            return "pages.documentation.pages.{$catKey}.{$subKey}.{$itemKey}";
                        }
                    }
                } else {
                    // Level 2
                    if ($subKey === $targetSlug) {
                        return "pages.documentation.pages.{$catKey}.{$subKey}";
                    }
                }
            }
        }
        return null;
    }
}

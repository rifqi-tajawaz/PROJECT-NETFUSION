<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FaqPublicController extends Controller
{
    /**
     * Display the public FAQ page with grouped sections.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $faqs = Faq::where('is_published', true)->get();

        // Define Category Metadata (Colors, Icons) with Localized Titles
        $categoryMeta = [
            'General' => [
                'title' => __('faq.cat_general'), // General & Onboarding
                'icon' => 'bx-rocket',
                'color' => 'primary'
            ],
            'Billing' => [
                'title' => __('faq.cat_account'), // Account & Billing
                'icon' => 'bx-wallet',
                'color' => 'success'
            ],
            'Tools' => [
                'title' => __('faq.cat_tools'), // Tools & Utilities
                'icon' => 'bx-wrench',
                'color' => 'danger'
            ],
            'Technical' => [
                'title' => __('faq.cat_technical'), // Technical & Scripts
                'icon' => 'bx-code-block',
                'color' => 'info'
            ],
            'Reseller' => [
                'title' => __('faq.cat_reseller'), // Reseller & Partnership
                'icon' => 'bx-store-alt',
                'color' => 'secondary'
            ],
            'Security' => [
                'title' => __('faq.cat_security'), // Security & Privacy
                'icon' => 'bx-shield-quarter',
                'color' => 'dark'
            ],
            'Troubleshooting' => [
                'title' => __('faq.cat_troubleshooting'), // Troubleshooting
                'icon' => 'bx-support',
                'color' => 'warning'
            ],
        ];

        // Group FAQs by DB Category
        $groupedFaQ = $faqs->groupBy('category');

        // Build ordered sections based on metadata keys
        $sections = [];
        foreach ($categoryMeta as $dbKey => $meta) {
            // Only show section if it has FAQs
            if ($groupedFaQ->has($dbKey) && $groupedFaQ[$dbKey]->isNotEmpty()) {
                $sections[] = (object) [
                    'id' => Str::slug($dbKey), // e.g., 'general', 'billing'
                    'title' => $meta['title'],
                    'icon' => $meta['icon'],
                    'color' => $meta['color'],
                    'items' => $groupedFaQ[$dbKey]
                ];
            }
        }

        return view('pages.faq', compact('sections', 'faqs'));
    }
}

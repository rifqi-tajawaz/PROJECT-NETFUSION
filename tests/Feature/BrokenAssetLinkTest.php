<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\DeviceFingerprintMiddleware;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\EnsureTwoFactorVerified;

class BrokenAssetLinkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that key pages load assets correctly.
     */
    public function test_assets_are_loadable_on_key_pages(): void
    {
        // Define pages to test.
        // Some require auth, some don't.
        $pages = [
            ['url' => '/login', 'auth' => false],
            // Use route helper if possible, but hardcoded paths are fine for this test to ensure URL stability
            ['url' => '/mikrotik-suite/dashboard/', 'auth' => true],
            ['url' => '/user-profile', 'auth' => true],
            ['url' => '/support', 'auth' => false],
            ['url' => '/support/ticket', 'auth' => true],
            ['url' => '/faq', 'auth' => false],
        ];

        // Create a user for auth pages
        $user = User::factory()->create();

        foreach ($pages as $page) {
            if ($page['auth']) {
                $response = $this->actingAs($user)
                    ->withoutMiddleware([
                        DeviceFingerprintMiddleware::class,
                        EnsureEmailIsVerified::class,
                        EnsureTwoFactorVerified::class,
                    ])
                    ->get($page['url']);
            } else {
                $response = $this->followingRedirects()->get($page['url']);
            }

            // Assert page loads successfully
            $response->assertStatus(200);

            // Parse HTML
            $content = $response->getContent();

            $crawler = new \Symfony\Component\DomCrawler\Crawler($content);

            // Check images
            $crawler->filter('img')->each(function ($node) use ($page) {
                $src = $node->attr('src');
                $this->verifyAsset($src, $page['url']);
            });

            // Check scripts
            $crawler->filter('script')->each(function ($node) use ($page) {
                $src = $node->attr('src');
                if ($src) {
                    $this->verifyAsset($src, $page['url']);
                }
            });

            // Check stylesheets
            $crawler->filter('link[rel="stylesheet"]')->each(function ($node) use ($page) {
                $href = $node->attr('href');
                $this->verifyAsset($href, $page['url']);
            });
        }
    }

    private function verifyAsset(?string $url, string $sourcePage): void
    {
        if (empty($url)) {
            return;
        }

        // Skip data URIs
        if (str_starts_with($url, 'data:')) {
            return;
        }

        // Skip javascript:
        if (str_starts_with($url, 'javascript:')) {
            return;
        }

        // Handle absolute URLs
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
             $appUrl = config('app.url');
             // normalize appUrl to not have trailing slash
             $appUrl = rtrim($appUrl, '/');

             if (str_starts_with($url, $appUrl)) {
                 // Convert full URL to relative path if it matches app URL
                 // e.g. http://localhost/build/foo -> /build/foo
                 $url = substr($url, strlen($appUrl));
             } else {
                 // External URL, skip
                 return;
             }
        }

        // Remove query strings and fragments
        $path = parse_url($url, PHP_URL_PATH);

        if (empty($path)) {
            return;
        }

        // Construct local file path
        // public_path() uses the base path of the application
        $publicPath = public_path($path);

        // Check file existence
        if (!File::exists($publicPath)) {
            // Provide a meaningful error message
            $this->fail("Broken asset link found on page '{$sourcePage}': {$url} (Resolves to {$publicPath})");
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecaptchaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recaptcha:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check reCAPTCHA configuration and status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('reCAPTCHA Status Check');
        $this->line('==================');

        // Check if reCAPTCHA is enabled
        $enabled = config('recaptcha.enabled', false);
        $siteKey = env('RECAPTCHA_SITE_KEY');
        $secretKey = env('RECAPTCHA_SECRET_KEY');

        $this->line('Enabled: ' . ($enabled ? '✓ Yes' : '✗ No'));
        $this->line('Site Key: ' . ($siteKey ? substr($siteKey, 0, 10) . '...' : 'Not set'));
        $this->line('Secret Key: ' . ($secretKey ? substr($secretKey, 0, 10) . '...' : 'Not set'));

        $shouldDisplay = \App\Helpers\RecaptchaHelper::shouldDisplayRecaptcha();
        $shouldValidate = \App\Helpers\RecaptchaHelper::shouldValidateRecaptcha();

        $this->line('Should Display: ' . ($shouldDisplay ? '✓ Yes' : '✗ No'));
        $this->line('Should Validate: ' . ($shouldValidate ? '✓ Yes' : '✗ No'));

        if ($enabled && $siteKey && $secretKey) {
            $this->info("\n✓ reCAPTCHA is properly configured");
        } else {
            $this->error("\n✗ reCAPTCHA is not properly configured");

            if (!$enabled) {
                $this->line("- Set RECAPTCHA_ENABLED=true in .env");
            }
            if (!$siteKey) {
                $this->line("- Set RECAPTCHA_SITE_KEY in .env");
            }
            if (!$secretKey) {
                $this->line("- Set RECAPTCHA_SECRET_KEY in .env");
            }

            $this->line("\nTo disable reCAPTCHA temporarily, run:");
            $this->line("  php artisan env:set RECAPTCHA_ENABLED=false");
        }

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate 2FA secret for user
     */
    public function generateSecret(User $user): array
    {
        $secret = $this->google2fa->generateSecretKey();

        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($this->generateRecoveryCodes())),
        ])->save();

        return [
            'secret' => $secret,
            'qr_code' => $this->generateQrCodeSvg($user->email, $secret)
        ];
    }

    /**
     * Get 2FA QR Code for existing secret
     */
    public function getQrCode(User $user): ?array
    {
        if (!$user->two_factor_secret) {
            return null;
        }

        $secret = decrypt($user->two_factor_secret);

        return [
            'secret' => $secret,
            'qr_code' => $this->generateQrCodeSvg($user->email, $secret)
        ];
    }

    /**
     * Verify the 2FA code
     */
    public function verify(User $user, string $code): bool
    {
        if (!$user->two_factor_secret) {
            return false;
        }

        return $this->google2fa->verifyKey(
            decrypt($user->two_factor_secret),
            $code
        );
    }

    /**
     * Generate SVG QR Code
     */
    protected function generateQrCodeSvg(string $email, string $secret): string
    {
        $g2faUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($g2faUrl);
    }



    /**
     * Get decrypted recovery codes
     */
    public function getRecoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
    }

    /**
     * Use a recovery code (remove it from list)
     */
    public function useRecoveryCode(User $user, string $code): bool
    {
        $codes = $this->getRecoveryCodes($user);
        $key = array_search($code, $codes);

        if ($key !== false) {
            unset($codes[$key]);

            $user->forceFill([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($codes))),
            ])->save();

            return true;
        }

        return false;
    }

    /**
     * Generate Recovery Codes
     */
    protected function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))->map(function () {
            return \Illuminate\Support\Str::random(10) . '-' . \Illuminate\Support\Str::random(10);
        })->toArray();
    }
}

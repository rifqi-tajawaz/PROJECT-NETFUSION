<?php

declare(strict_types=1);

namespace App\Services\MikrotikSuite\Wireless;

use Illuminate\Support\Facades\Log;

class WirelessLinkPlannerService
{
    /**
     * Calculate Wireless Link Budget and Throughput
     *
     * @param array $data Validated request data
     * @return array Calculation results
     */
    public function calculate(array $data): array
    {
        try {
            $d = $data['distance']; // km
            $f = $data['frequency']; // MHz
            $f_ghz = $f / 1000;

            // FREE SPACE PATH LOSS (FSPL)
            // FSPL = 20*log10(d) + 20*log10(f) + 32.44
            $fspl = 20 * log10((float) $d) + 20 * log10((float) $f) + 32.44;

            // Link Budget Calculation
            $tx_power = $data['site_a_tx_power'];
            $tx_gain = $data['site_a_ant_gain'];
            $tx_loss = $data['site_a_cable_loss'] ?? 0;
            $hA = $data['site_a_height'];

            $rx_gain = $data['site_b_ant_gain'];
            $rx_loss = $data['site_b_cable_loss'] ?? 0;
            $rx_sens = $data['site_b_rx_sens'];
            $hB = $data['site_b_height'];

            // RECEIVED SIGNAL STRENGTH (RSS)
            // RSS = TX_Power + TX_Gain - TX_Loss - FSPL + RX_Gain - RX_Loss
            $rss = $tx_power + $tx_gain - $tx_loss - $fspl + $rx_gain - $rx_loss;

            // FRESNEL ZONE (60% clearance usually required)
            // r = 17.32 * sqrt(d / (4 * f_GHz)) for mid-point
            $fresnel_radius = 17.32 * sqrt($d / (4 * $f_ghz));
            $fresnel_60 = $fresnel_radius * 0.6;

            // Check Obstructions
            $obstructions = [];
            $is_blocked = false;

            if (isset($data['obstacles']) && is_array($data['obstacles'])) {
                foreach ($data['obstacles'] as $obs) {
                    $d1 = floatval($obs['distance']); // distance from A
                    $obsH = floatval($obs['height']);

                    if ($d1 <= 0 || $d1 >= $d) {
                        continue; // Ignore if out of bounds
                    }

                    // 1. Calculate Fresnel Radius at d1
                    $d2 = $d - $d1;
                    $r_at_d1 = 17.32 * sqrt(($d1 * $d2) / ($d * $f_ghz));
                    $r60_at_d1 = $r_at_d1 * 0.6;

                    // 2. Calculate LOS Height at d1 (Geometry/Flat Earth)
                    $losH = $hA + ($hB - $hA) * ($d1 / $d);

                    // 3. Clearance
                    $clearance = $losH - $obsH;

                    $status = 'clear';
                    if ($obsH >= $losH) {
                        $status = 'blocked';
                        $is_blocked = true;
                    } elseif ($clearance < $r60_at_d1) {
                        $status = 'warning';
                    }

                    $obstructions[] = [
                        'distance' => $d1,
                        'height' => $obsH,
                        'fresnel_limit' => round($losH - $r60_at_d1, 2),
                        'status' => $status
                    ];
                }
            }

            // Apply penalty if blocked
            if ($is_blocked) {
                $rss -= 20;
            }

            // FADE MARGIN
            $fade_margin = $rss - $rx_sens;

            // Rain Attenuation
            $rain_loss = 0;
            $rain_rate = $data['rain_rate'] ?? 0;
            if ($rain_rate > 0) {
                $rain_loss = ($rain_rate * 0.01) * $d;
            }
            $rss_rain = $rss - $rain_loss;
            $fade_margin_rain = $rss_rain - $rx_sens;

            // Signal Quality Classification
            $quality = 'critical';
            $quality_color = 'danger';
            $quality_text = 'Unusable';

            if ($is_blocked) {
                $quality = 'critical';
                $quality_color = 'danger';
                $quality_text = 'Blocked';
            } elseif ($fade_margin_rain >= 20) {
                $quality = 'excellent';
                $quality_color = 'success';
                $quality_text = 'Excellent';
            } elseif ($fade_margin_rain >= 15) {
                $quality = 'good';
                $quality_color = 'primary';
                $quality_text = 'Good';
            } elseif ($fade_margin_rain >= 10) {
                $quality = 'fair';
                $quality_color = 'warning';
                $quality_text = 'Fair';
            } elseif ($fade_margin_rain >= 5) {
                $quality = 'poor';
                $quality_color = 'orange';
                $quality_text = 'Poor';
            }

            // --- THROUGHPUT ESTIMATION ---
            $bw = $data['channel_width'] ?? 80;
            $proto = $data['protocol'] ?? '802.11ac';
            $polarization = $data['polarization'] ?? '2x2';

            $chains = 2;
            if ($polarization === '1x1')
                $chains = 1;
            if ($polarization === '2x2')
                $chains = 2;
            if ($polarization === '3x3')
                $chains = 3;
            if ($polarization === '4x4')
                $chains = 4;

            $base_rate = $this->estimateBaseRate($proto, $rss_rain);

            // Scale by Bandwidth
            $bw_scale = 1;
            if ($bw == 40)
                $bw_scale = 2.1;
            if ($bw == 80)
                $bw_scale = 4.5;
            if ($bw == 160)
                $bw_scale = 9;

            // Scale by Chains
            $phy_rate = $base_rate * $bw_scale * $chains;

            // Estimate Real Throughput
            $est_udp = round($phy_rate * 0.8, 1);
            $est_tcp = round($phy_rate * 0.6, 1);

            if ($rss_rain < -85) {
                $est_udp = 0;
                $est_tcp = 0;
                $quality_text = 'No Link';
            }

            return [
                'fspl' => round($fspl, 2),
                'rss' => round($rss, 2),
                'rss_rain' => round($rss_rain, 2),
                'fade_margin' => round($fade_margin, 2),
                'fade_margin_rain' => round($fade_margin_rain, 2),
                'fresnel_radius' => round($fresnel_radius, 2),
                'fresnel_60' => round($fresnel_60, 2),
                'rain_loss' => round($rain_loss, 2),
                'obstructions' => $obstructions,
                'throughput' => [
                    'phy' => round($phy_rate, 1),
                    'udp' => $est_udp,
                    'tcp' => $est_tcp
                ],
                'quality' => [
                    'status' => $quality,
                    'color' => $quality_color,
                    'text' => $quality_text
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Wireless Link Planner Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function estimateBaseRate(string $proto, float $signal): float
    {
        $base_rate = 0.0;

        if ($proto === '802.11n') {
            if ($signal >= -61)
                $base_rate = 72.2;
            elseif ($signal >= -64)
                $base_rate = 65.0;
            elseif ($signal >= -66)
                $base_rate = 57.8;
            elseif ($signal >= -70)
                $base_rate = 43.3;
            elseif ($signal >= -74)
                $base_rate = 28.9;
            elseif ($signal >= -77)
                $base_rate = 21.7;
            elseif ($signal >= -79)
                $base_rate = 14.4;
            elseif ($signal >= -82)
                $base_rate = 7.2;
        } elseif ($proto === '802.11ac') {
            if ($signal >= -51)
                $base_rate = 86.7;
            elseif ($signal >= -54)
                $base_rate = 78.0;
            elseif ($signal >= -59)
                $base_rate = 72.2;
            elseif ($signal >= -60)
                $base_rate = 65.0;
            elseif ($signal >= -62)
                $base_rate = 57.8;
            elseif ($signal >= -66)
                $base_rate = 43.3;
            elseif ($signal >= -71)
                $base_rate = 28.9;
            elseif ($signal >= -75)
                $base_rate = 14.4;
            elseif ($signal >= -82)
                $base_rate = 7.2;
        } elseif ($proto === '802.11ax') {
            if ($signal >= -47)
                $base_rate = 143.0;
            elseif ($signal >= -50)
                $base_rate = 129.0;
            elseif ($signal >= -52)
                $base_rate = 114.0;
            elseif ($signal >= -55)
                $base_rate = 103.0;
            elseif ($signal >= -58)
                $base_rate = 86.0;
            elseif ($signal >= -62)
                $base_rate = 72.0;
            elseif ($signal >= -66)
                $base_rate = 57.0;
            elseif ($signal >= -71)
                $base_rate = 43.0;
            elseif ($signal >= -75)
                $base_rate = 28.0;
            elseif ($signal >= -82)
                $base_rate = 14.0;
        }

        return $base_rate;
    }
}

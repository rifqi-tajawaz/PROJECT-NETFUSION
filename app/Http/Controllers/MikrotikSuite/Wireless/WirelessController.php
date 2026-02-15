<?php

namespace App\Http\Controllers\MikrotikSuite\Wireless;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WirelessController extends Controller
{
    public function antennaCalculator()
    {
        return view('mikrotik-suite.wireless.antenna-calculator');
    }

    public function calculateAntenna(Request $request): JsonResponse
    {
        $request->validate([
            'distance' => 'required|numeric|min:0.1',
            'frequency' => 'required|numeric|min:0.1',
            'obstacle_height' => 'required|numeric|min:0',
        ]);

        $d = $request->input('distance'); // km
        $f = $request->input('frequency'); // GHz
        $h_obs = $request->input('obstacle_height'); // m

        // Fresnel Zone Radius (60%)
        // r = 17.32 * sqrt(d / (4 * f))
        $r_full = 17.32 * sqrt($d / (4 * $f));
        $r_60 = $r_full * 0.6;

        // Earth Curvature (Approximation)
        // h = (d^2) / 8R (Not exact for mid-path, simplistic model: 0.0785 * (d/2)^2)
        // Standard reference often uses: h = d^2 / 12.75 (d in km, h in m, full path) -> Sagitta at midpoint?
        // Let's stick to the formula used in JS previously: 0.0785 * (d/2)^2
        $curvature = 0.0785 * pow($d / 2, 2);

        $requiredH = $h_obs + $curvature + $r_60;

        return response()->json([
            'status' => 'success',
            'fresnel_radius_60' => round($r_60, 2),
            'earth_curvature' => round($curvature, 2),
            'recommended_height' => round($requiredH, 2),
            'fresnel_full' => $r_full
        ]);
    }

    public function frequencyUnlock()
    {
        return view('mikrotik-suite.wireless.frequency-unlock');
    }

    public function generateFrequencyUnlock(Request $request): JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'mode' => 'required|string',
            'country' => 'required|string',
            'width' => 'required|string',
        ]);

        $iface = $request->input('interface');
        $mode = $request->input('mode');
        $country = $request->input('country');
        $width = $request->input('width');

        $script = "/interface wireless set [ find default-name=\"{$iface}\" ] frequency-mode=\"{$mode}\" channel-width=\"{$width}\"";

        if ($country !== 'no_country_set') {
            $script .= " country=\"{$country}\"";
        } else {
            $script .= " country=no_country_set";
        }

        $script .= " disabled=no\n";

        if ($mode === 'superchannel') {
            $script .= "\n# Optional: Wide Scan List\n";
            $script .= "/interface wireless set [ find default-name=\"{$iface}\" ] scan-list=default,2000-6000 allow-remote-repair=yes";
        }

        return response()->json(['status' => 'success', 'script' => $script]);
    }

    public function linkBudgetCalculator()
    {
        return view('mikrotik-suite.wireless.link-budget-calculator');
    }

    public function calculateLinkBudget(Request $request): JsonResponse
    {
        $request->validate([
            'frequency' => 'required|numeric|min:1',
            'distance' => 'required|numeric|min:0.01',
            'tx_power' => 'required|numeric',
            'tx_gain' => 'required|numeric',
            'rx_gain' => 'required|numeric',
            'cable_loss' => 'required|numeric|min:0',
            'sensitivity' => 'required|numeric',
        ]);

        $freqMHz = $request->input('frequency');
        $distKm = $request->input('distance');
        $txPwr = $request->input('tx_power');
        $txGain = $request->input('tx_gain');
        $rxGain = $request->input('rx_gain');
        $cLoss = $request->input('cable_loss');
        $sens = $request->input('sensitivity');

        // FSPL = 20log10(d) + 20log10(f) + 32.44
        $fspl = (20 * log10($distKm)) + (20 * log10($freqMHz)) + 32.44;

        // RSL = TxPwr + TxGain + RxGain - CableLoss - FSPL
        $rsl = $txPwr + $txGain + $rxGain - $cLoss - $fspl;

        // Margin
        $margin = $rsl - $sens;

        // Fresnel 60% (Freq in GHz for formula)
        // r = 17.32 * sqrt(d / (4 * f_ghz))
        $f_ghz = $freqMHz / 1000;
        $f1 = (17.32 * sqrt($distKm / (4 * $f_ghz))) * 0.6;

        return response()->json([
            'status' => 'success',
            'fspl' => round($fspl, 1),
            'rsl' => round($rsl, 1),
            'margin' => round($margin, 1),
            'fresnel_radius_60' => round($f1, 1)
        ]);
    }

    public function linkPlanner()
    {
        return view('mikrotik-suite.wireless.link-planner');
    }

    public function lockpackCreator()
    {
        return view('mikrotik-suite.wireless.lockpack-creator');
    }

    public function generateLockpack(Request $request): JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'frequencies' => 'required|string', // Comma separated
        ]);

        $iface = $request->input('interface');
        $list = $request->input('frequencies');

        if (empty($list)) {
            return response()->json(['status' => 'error', 'message' => 'No frequencies selected']);
        }

        $script = "/interface wireless set [ find default-name={$iface} ] scan-list=\"{$list}\"";

        return response()->json(['status' => 'success', 'script' => $script]);
    }

    public function minipciCompatibility()
    {
        return view('mikrotik-suite.wireless.minipci-compatibility');
    }
}


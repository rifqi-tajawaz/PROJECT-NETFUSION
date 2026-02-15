<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ToolsController extends Controller
{
    // Default Mikhmon-style simple template
    private $defaultTemplate = '<!-- Default Template -->
<table class="voucher" style="width: 200px; border: 1px solid black; padding: 5px; font-family: sans-serif;">
    <tr>
        <td style="text-align: center;">
            <h3 style="margin: 0;">%u_hotspot%</h3>
        </td>
    </tr>
    <tr>
        <td>
            Code: <b>%u_user%</b><br>
            Price: %u_money%<br>
            Validity: %u_time%
        </td>
    </tr>
</table>';

    public function uploadLogo()
    {
        return view('netfusion.tools.upload-logo');
    }

    public function templateEditor()
    {
        // Load custom template or default
        $template = Storage::exists('NetFusion/template.html')
            ? Storage::get('NetFusion/template.html')
            : $this->defaultTemplate;

        return view('netfusion.tools.template-editor', compact('template'));
    }

    public function saveTemplate(Request $request)
    {
        $request->validate(['html_content' => 'required|string']);

        // Save to private storage (not directly public accessible)
        Storage::put('NetFusion/template.html', $request->html_content);

        return back()->with('success', 'Template saved successfully!');
    }

    public function resetTemplate()
    {
        Storage::delete('NetFusion/template.html');
        return back()->with('success', 'Template restored to default!');
    }

    public function previewTemplate(Request $request)
    {
        $content = $request->input('html_content', '');

        // Mock Data for Preview (Mikhmon Compatibility)
        $hotspotname = "NetFusion WiFi";
        $username = "user8821";
        $password = "ab555";
        $price = "Rp 5.000"; // formatted for display
        $getsprice = "5000"; // raw for logic (if/else)
        $validity = "30d";
        $timelimit = "30d";
        $datalimit = "2 GB";
        $v_opsi = "theme1";

        // Extended Mikhmon Variables
        $num = "123";
        $usermode = "vc";
        $qr = "yes";
        $dnsname = session('router_session')['dns_name'] ?? "mks.net";

        // Dynamic Logo Logic (Matches Upload Logo Feature)
        $sName = session('router_session') ? \Illuminate\Support\Str::slug(session('router_session')['name']) : 'default';
        $customLogo = "images/logo-{$sName}.png";
        $logo = file_exists(public_path($customLogo)) ? asset($customLogo) : asset('images/logo.png');

        $qrcode = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=example" style="width:50px;">';

        // Capture Output
        ob_start();
        try {
            // DANGEROUS: Only allow for admin tools
            eval ('?>' . $content);
        } catch (\Throwable $e) {
            echo '<div style="color:red; font-weight:bold;">Template Error: ' . $e->getMessage() . '</div>';
        }
        $rendered = ob_get_clean();

        return response()->json(['html' => $rendered]);
    }
}

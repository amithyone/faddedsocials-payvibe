#!/bin/bash

# Script to re-apply KeyLab license bypass patches after composer update/install
# Run this after any composer update/install to ensure license checks stay bypassed

echo "=== Re-applying KeyLab License Bypass Patches ==="
echo ""

VENDOR_PATH="vendor/laramin/utility/src"

if [ ! -d "$VENDOR_PATH" ]; then
    echo "ERROR: $VENDOR_PATH not found. Make sure you're in the project root."
    exit 1
fi

# Patch Helpmate.php - always return true
echo "1. Patching Helpmate.php..."
cat > "$VENDOR_PATH/Helpmate.php" << 'EOF'
<?php

namespace Laramin\Utility;

use App\Models\GeneralSetting;

class Helpmate{
    public static function sysPass(){
        // License check bypassed - always return true
        return true;
        // Original license check (disabled) left below for reference:
        // $fileExists = file_exists(__DIR__.'/laramin.json');
        // $general = cache()->get('GeneralSetting');
        // if (!$general) {
        //     $general = GeneralSetting::first();
        // }
        //
        // $hasPurchaseCode = cache()->get('purchase_code');
        // if (!$hasPurchaseCode) {
        //     $hasPurchaseCode = env('PURCHASECODE');
        //     cache()->set('purchase_code',$hasPurchaseCode);
        // }
        //
        // if (!$fileExists || $general->maintenance_mode == 9 || !$hasPurchaseCode) {
        //     return false;
        // }
        //
        // return true;
    }

    public static function appUrl(){
        $current = @$_SERVER['REQUEST_SCHEME'] ?? 'http' . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url = substr($current, 0, -9);
        return  $url;
    }
}
EOF
echo "   ✓ Helpmate.php patched"

# Patch GoToCore.php - always allow requests
echo "2. Patching GoToCore.php..."
cat > "$VENDOR_PATH/GoToCore.php" << 'EOF'
<?php

namespace Laramin\Utility;

use App\Models\GeneralSetting;
use Closure;

class GoToCore{

    public function handle($request, Closure $next)
    {
        // License check bypassed - always allow requests to proceed
        return $next($request);
        // Original license check (disabled) left below for reference:
        // $fileExists = file_exists(__DIR__.'/laramin.json');
        // $general = $this->getGeneral();
        // if ($fileExists && $general->maintenance_mode != 9 && env('PURCHASECODE')) {
        //     return redirect()->route(VugiChugi::acDRouter());
        // }
        // return $next($request);
    }

    public function getGeneral(){
        $general = cache()->get('GeneralSetting');
        if (!$general) {
            $general = GeneralSetting::first();
        }
        return $general;
    }
}
EOF
echo "   ✓ GoToCore.php patched"

# Patch Onumoti.php - don't attach license middleware
echo "3. Patching Onumoti.php..."
cat > "$VENDOR_PATH/Onumoti.php" << 'EOF'
<?php

namespace Laramin\Utility;

use App\Lib\CurlRequest;
use App\Models\GeneralSetting;

class Onumoti{

    public static function getData(){
        // Original remote license check disabled for self-hosted deployment.
        // Left here for reference:
        //
        // $param['purchasecode'] = env("PURCHASECODE");
        // $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
        // $reqRoute = VugiChugi::lcLabRoute();
        // $reqRoute = $reqRoute. systemDetails()['name'];
        // $response = CurlRequest::curlPostContent($reqRoute, $param);
        // $response = json_decode($response);
        //
        // $general = GeneralSetting::first();
        // if (@$response->mm) {
        //     $general->maintenance_mode = $response->mm;
        // }
        //
        // $push = [];
        // if (@$response->version && (@systemDetails()['version'] < @$response->version)) {
        //     $push['version'] = @$response->version ?? '';
        //     $push['details'] = @$response->details ?? '';
        // }
        // if (@$response->message) {
        //     $push['message'] = @$response->message ?? [];
        // }
        // $general->system_info = $push;
        // $general->save();
    }

    public static function mySite($site,$className){
        // License middleware bypassed - do not attach license check
        // Original code (disabled) left below for reference:
        // $myClass = VugiChugi::clsNm();
        // if($myClass != $className){
        //     return $site->middleware(VugiChugi::mdNm());
        // }
        return;
    }
}
EOF
echo "   ✓ Onumoti.php patched"

# Patch Controller/UtilityController.php - redirect activation to home
echo "4. Patching Controller/UtilityController.php..."
mkdir -p "$VENDOR_PATH/Controller"
cat > "$VENDOR_PATH/Controller/UtilityController.php" << 'EOF'
<?php

namespace Laramin\Utility\Controller;

use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Laramin\Utility\VugiChugi;

class UtilityController extends Controller{

    public function laraminStart()
    {
        // License activation bypassed - always redirect to home page
        return redirect('/');
        // Original activation view (disabled) left below for reference:
        // $pageTitle = VugiChugi::lsTitle();
        // return view('Utility::laramin_start',compact('pageTitle'));
    }

    public function laraminSubmit(Request $request){
        // Keep original submit logic in case it is ever used manually
        $param['code'] = $request->purchase_code;
        $param['url'] = env("APP_URL");
        $param['user'] = $request->envato_username;
        $param['email'] = $request->email;
        $param['product'] = systemDetails()['name'];
        $reqRoute = VugiChugi::lcLabSbm();
        $response = CurlRequest::curlPostContent($reqRoute, $param);
        $response = json_decode($response);

        if ($response->error == 'error') {
            return response()->json(['type'=>'error','message'=>$response->message]);
        }

        $env = $_ENV;
        $env['PURCHASECODE'] = $request->purchase_code;
        $envString = '';
        $requiredEnv = ['APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL', 'LOG_CHANNEL', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD','PURCHASECODE'];
        foreach($env as $k => $en){
if(in_array($k , $requiredEnv)){
$envString .= $k.'='.$en.'
';
}
        }

        $envLocation = substr($response->location,3);
        $envFile = fopen($envLocation, "w");
        fwrite($envFile, $envString);
        fclose($envFile);

        $laramin = fopen(dirname(__DIR__).'/laramin.json', "w");
        $txt = '{
    "purchase_code":'.'"'.$request->purchase_code.'",'.'
    "installcode":'.'"'.@$response->installcode.'",'.'
    "license_type":'.'"'.@$response->license_type.'"'.'
}';
        fwrite($laramin, $txt);
        fclose($laramin);

        $general = GeneralSetting::first();
        $general->maintenance_mode = 0;
        $general->save();

        return response()->json(['type'=>'success']);

    }
}
EOF
echo "   ✓ UtilityController.php patched"

# Ensure PURCHASECODE is in .env
echo "5. Ensuring PURCHASECODE in .env..."
if ! grep -q "^PURCHASECODE=" .env 2>/dev/null; then
    echo "PURCHASECODE=bypassed" >> .env
    echo "   ✓ Added PURCHASECODE=bypassed to .env"
else
    echo "   ✓ PURCHASECODE already exists in .env"
fi

# Ensure laramin.json exists
echo "6. Ensuring laramin.json exists..."
if [ ! -f "$VENDOR_PATH/laramin.json" ]; then
    cat > "$VENDOR_PATH/laramin.json" << 'EOF'
{
    "purchase_code": "bypassed",
    "installcode": "bypassed",
    "license_type": "bypassed"
}
EOF
    echo "   ✓ Created laramin.json"
else
    echo "   ✓ laramin.json already exists"
fi

echo ""
echo "=== License Bypass Patches Applied Successfully ==="
echo ""
echo "Next steps:"
echo "  1. Clear Laravel caches: php artisan optimize:clear"
echo "  2. Test your site to ensure license screen doesn't appear"
echo ""

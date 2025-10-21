<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use App\Models\MerchantCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MerchantController extends Controller
{
public function index(Request $request)
{
    // Get pagination parameters
    $page = $request->get('page', 1);
    $perPage = $request->get('per_page', 16); // Default 16 items per page
    $offset = ($page - 1) * $perPage;

    $userId = $request->get('userid');

    // Log::info('Function index The userID is : '.$userId);

    // Start with base query
    $query = Merchant::where('visibility', 1)
        ->where('deleted', 0);

    // Apply zone filtering if user has a zone_id
    $user = null;
    if (!empty($userId) && $userId != 0) {
        $user = User::find($userId);
        if ($user && $user->zone_id) {
            // Filter merchants by user's zone
            $query->whereHas('zones', function ($zoneQuery) use ($user) {
                $zoneQuery->where('zones.id', $user->zone_id);
            });
        }
    }

    // Apply star filter at database level
    if ($request->has('star')) {
        $star = (int) $request->star;
        $query->where('star', $star);
    }

    // Apply category filter at database level if possible
    if ($request->has('category_id')) {
        $categoryId = (int) $request->category_id;
        // If your database supports JSON queries (MySQL 5.7+, PostgreSQL)
        $query->whereJsonContains('categories', $categoryId);
    }

    // Check if user ID is provided and not 0 or null for distance sorting
    if (!empty($userId) && $userId != 0 && $user) {
        // If user exists and has coordinates, order by distance (أقرب)
        if (!empty($user->latitude) && !empty($user->longitude)) {
            // Get all merchants first
            $allMerchants = $query->get();

            // Handle fallback for databases that don't support JSON queries for categories
            if ($request->has('category_id') && $allMerchants->isEmpty()) {
                // Fallback: get merchants without category filter and filter in PHP
                $fallbackQuery = Merchant::where('visibility', 1)
                    ->where('deleted', 0);

                // Apply zone filtering in fallback too
                if ($user->zone_id) {
                    $fallbackQuery->whereHas('zones', function ($zoneQuery) use ($user) {
                        $zoneQuery->where('zones.id', $user->zone_id);
                    });
                }

                if ($request->has('star')) {
                    $star = (int) $request->star;
                    $fallbackQuery->where('star', $star);
                }

                $allMerchants = $fallbackQuery->get();
                
                // Filter by category in PHP
                $categoryId = (int) $request->category_id;
                $allMerchants = $allMerchants->filter(function ($merchant) use ($categoryId) {
                    return in_array($categoryId, $merchant->categories ?? []);
                })->values();
            }
            
            // Calculate distance for each merchant and sort
            $allMerchants = $allMerchants->map(function ($merchant) use ($user) {
                $lat1 = deg2rad($user->latitude);
                $lon1 = deg2rad($user->longitude);
                $lat2 = deg2rad($merchant->latitude);
                $lon2 = deg2rad($merchant->longitude);
                
                $dlat = $lat2 - $lat1;
                $dlon = $lon2 - $lon1;
                
                $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
                $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                $distance = 6371 * $c; // Distance in kilometers
                
                $merchant->distance = $distance;
                return $merchant;
            })->sortBy('distance');
            
            // Get total count for pagination
            $totalCount = $allMerchants->count();
            
            // Apply pagination manually and remove distance field
            $merchants = $allMerchants->skip($offset)->take($perPage)->map(function ($merchant) {
                unset($merchant->distance); // Remove distance field from response
                return $merchant;
            })->values();
            
        } else {
            // If user doesn't have coordinates, use default ordering
            $query->orderBy('order_item');
            
            // Handle category fallback for non-coordinates case
            $totalCount = $query->count();
            
            if ($request->has('category_id') && $totalCount === 0) {
                // Fallback: get all merchants and filter in PHP
                $fallbackQuery = Merchant::where('visibility', 1)
                    ->where('deleted', 0)
                    ->orderBy('order_item');
                    
                if ($request->has('star')) {
                    $star = (int) $request->star;
                    $fallbackQuery->where('star', $star);
                }
                
                $allMerchants = $fallbackQuery->get();
                
                // Filter by category in PHP
                $categoryId = (int) $request->category_id;
                $filteredMerchants = $allMerchants->filter(function ($merchant) use ($categoryId) {
                    return in_array($categoryId, $merchant->categories ?? []);
                })->values();
                
                $totalCount = $filteredMerchants->count();
                $merchants = $filteredMerchants->slice($offset, $perPage)->values();
            } else {
                $merchants = $query->skip($offset)->take($perPage)->get();
            }
        }
    } else {
        // If no user ID or user ID is 0/null, use default ordering
        $query->orderBy('order_item');
        
        // Handle category fallback for no user case
        $totalCount = $query->count();
        
        if ($request->has('category_id') && $totalCount === 0) {
            // Fallback: get all merchants and filter in PHP
            $fallbackQuery = Merchant::where('visibility', 1)
                ->where('deleted', 0)
                ->orderBy('order_item');
                
            if ($request->has('star')) {
                $star = (int) $request->star;
                $fallbackQuery->where('star', $star);
            }
            
            $allMerchants = $fallbackQuery->get();
            
            // Filter by category in PHP
            $categoryId = (int) $request->category_id;
            $filteredMerchants = $allMerchants->filter(function ($merchant) use ($categoryId) {
                return in_array($categoryId, $merchant->categories ?? []);
            })->values();
            
            $totalCount = $filteredMerchants->count();
            $merchants = $filteredMerchants->slice($offset, $perPage)->values();
        } else {
            $merchants = $query->skip($offset)->take($perPage)->get();
        }
    }
    
    // Add formatted categories description to each merchant
    foreach ($merchants as $merchant) {
        $merchant->description = $merchant->getFormattedCategories();
    }
    
    return response()->json([
        'data' => $merchants,
        'pagination' => [
            'current_page' => (int) $page,
            'per_page' => (int) $perPage,
            'total' => $totalCount,
            'total_pages' => ceil($totalCount / $perPage),
            'has_more' => $page * $perPage < $totalCount,
            'from' => $totalCount > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $totalCount)
        ],
        'filters' => [
            'category_id' => $request->get('category_id'),
            'star' => $request->get('star')
        ]
    ]);
}    public function getTermsAndConditionsHtml()
{
    return response()->json([
        "title" => "الشروط والأحكام",
        "data" => '
        <div style="direction: rtl; text-align: right;">
            <h3>تمهيد</h3>
            <p>مرحبًا بكم في تطبيق “لوفارد”، المتخصص في تقديم خدمات الهدايا، الزهور، والحلويات. يرجى قراءة الأحكام والشروط بعناية، حيث يعد استخدامكم للتطبيق موافقة على الالتزام بها.</p>

            <h3>أولاً: تعريفات</h3>
            <ol>
                <li>التطبيق: تطبيق “لوفارد” المخصص لتقديم خدمات الزهور والهدايا.</li>
                <li>المستخدم: أي شخص يقوم باستخدام التطبيق أو الاستفادة من خدماته.</li>
                <li>الخدمات: كافة الخدمات المتاحة عبر التطبيق، بما في ذلك اختيار الهدايا، تنسيق الزهور، وإرسالها.</li>
            </ol>

            <h3>ثانيًا: استخدام التطبيق</h3>
            <ol start="4">
                <li>يلتزم المستخدم بتقديم معلومات صحيحة ودقيقة عند إنشاء الحساب أو تقديم الطلبات.</li>
                <li>يُحظر استخدام التطبيق لأي أغراض غير قانونية أو تخالف القوانين المحلية أو حقوق الملكية الفكرية.</li>
            </ol>

            <h3>ثالثًا: الطلبات والدفع</h3>
            <ol start="6">
                <li>جميع الطلبات المقدمة عبر التطبيق تخضع لتوفر المنتجات، وسيتم إخطار المستخدم في حال عدم توفر المنتج المطلوب.</li>
                <li>تُحدد الأسعار بالريال السعودي وتشمل ضريبة القيمة المضافة إن كانت مطبقة.</li>
                <li>يلتزم المستخدم بسداد المبلغ المستحق عبر وسائل الدفع المعتمدة داخل التطبيق.</li>
            </ol>

            <h3>رابعًا: سياسة الإلغاء والاسترجاع</h3>
            <ol start="9">
                <li>يمكن إلغاء الطلب قبل بدء تنفيذه وفقًا للمدة المحددة في سياسة الإلغاء.</li>
                <li>المبالغ المدفوعة للطلبات المنفذة غير قابلة للاسترداد إلا في حال وجود خطأ واضح في المنتج أو الخدمة.</li>
                <li>يتحمل المستخدم مسؤولية تقديم معلومات توصيل دقيقة لتجنب الأخطاء.</li>
            </ol>

            <h3>خامسًا: الخصوصية وحماية البيانات</h3>
            <ol start="12">
                <li>يتم التعامل مع بيانات المستخدمين بسرية تامة وفق سياسة الخصوصية الخاصة بالتطبيق.</li>
                <li>يحق للتطبيق استخدام البيانات المجمعة لتحسين الخدمات دون المساس بخصوصية المستخدمين.</li>
            </ol>

            <h3>سادسًا: المسؤولية القانونية</h3>
            <ol start="14">
                <li>تطبيق “لوفارد” غير مسؤول عن أي أضرار أو خسائر ناتجة عن الاستخدام غير الصحيح للتطبيق.</li>
                <li>في حال وجود خطأ في الخدمة، يسعى التطبيق لحله بأفضل طريقة ممكنة لضمان رضا المستخدم.</li>
            </ol>

            <h3>سابعًا: الملكية الفكرية</h3>
            <p>جميع محتويات التطبيق، بما في ذلك النصوص، الصور، التصاميم، والشعارات، هي ملكية حصرية لتطبيق “لوفارد” ولا يجوز نسخها أو إعادة استخدامها دون إذن خطي مسبق.</p>

            <h3>ثامنًا: التعديلات على الشروط والأحكام</h3>
            <p>يحتفظ التطبيق بحقه في تعديل هذه الشروط والأحكام في أي وقت، وسيتم إخطار المستخدمين بأي تغييرات عبر التطبيق أو البريد الإلكتروني المسجل.</p>

            <h3>تاسعًا: القانون الواجب التطبيق</h3>
            <p>تخضع هذه الشروط والأحكام لأنظمة المملكة العربية السعودية، ويتم حل أي نزاعات أمام الجهات القضائية المختصة داخل المملكة.</p>

            <h3>عاشرًا: التواصل</h3>
            <p>للاستفسارات أو الشكاوى يمكنكم التواصل معنا عبر:</p>
            <ul>
                <li>البريد الإلكتروني: lovardapp@gmail.com</li>
                <li>رقم الهاتف: 0552204826</li>
            </ul>
        </div>'
    ]);
}


 public function indexMerchantCategories(Request $request)
    {
    $merchants = MerchantCategorie::get();
    
    return response()->json($merchants);}


public function show(Merchant $merchant)
{
    $attributes = [];


    $user = Auth::guard('sanctum')->user();
    if ($user && $user->latitude && $user->longitude && $merchant->latitude && $merchant->longitude) {
        $distance = $this->calculateDistance(
            $user->latitude,
            $user->longitude,
            $merchant->latitude,
            $merchant->longitude
        ) ;
    } else {
        $distance = '-';
    }

$merchant->distance = (string) $distance;

    return response()->json($merchant);
}

public function getByType($type, Request $request)
{
    // Get pagination parameters
    $page = $request->get('page', 1);
    $perPage = $request->get('per_page', 15); // Default 15 items per page
    $offset = ($page - 1) * $perPage;
    $userId = $request->get('userid');
    // Log::info('Function GetByType The userID is : '.$userId);

    // Build the query
    $query = Merchant::where('type_merchant_id', $type)
        ->where('visibility', 1)
        ->where('deleted', 0);

    // Apply zone filtering if user has a zone_id
    $user = null;
    if (!empty($userId) && $userId != 0) {
        $user = User::find($userId);
        if ($user && $user->zone_id) {
            // Filter merchants by user's zone
            $query->whereHas('zones', function ($zoneQuery) use ($user) {
                $zoneQuery->where('zones.id', $user->zone_id);
            });
        }
    }

    // Check if user ID is provided and not 0 or null for distance sorting
    if (!empty($userId) && $userId != 0 && $user) {
        // If user exists and has coordinates, order by distance (أقرب)
        if ($user && !empty($user->latitude) && !empty($user->longitude)) {
            // Get all merchants first
            $allMerchants = $query->get();
            
            // Calculate distance for each merchant and sort
            $allMerchants = $allMerchants->map(function ($merchant) use ($user) {
                $lat1 = deg2rad($user->latitude);
                $lon1 = deg2rad($user->longitude);
                $lat2 = deg2rad($merchant->latitude);
                $lon2 = deg2rad($merchant->longitude);
                
                $dlat = $lat2 - $lat1;
                $dlon = $lon2 - $lon1;
                
                $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
                $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                $distance = 6371 * $c; // Distance in kilometers
                
                $merchant->distance = $distance;
                return $merchant;
            })->sortBy('distance');
            
            // Get total count for pagination
            $totalCount = $allMerchants->count();
            
            // Apply pagination manually and remove distance field
            $merchants = $allMerchants->skip($offset)->take($perPage)->map(function ($merchant) {
                unset($merchant->distance); // Remove distance field from response
                return $merchant;
            })->values();
            
        } else {
            // If user doesn't have coordinates, use default ordering
            $query->orderBy('order_item');
            $totalCount = $query->count();
            $merchants = $query->skip($offset)->take($perPage)->get();
        }
    } else {
        // If no user ID or user ID is 0/null, use default ordering
        $query->orderBy('order_item');
        $totalCount = $query->count();
        $merchants = $query->skip($offset)->take($perPage)->get();
    }
    
    // Add formatted categories description to each merchant (if needed)
    foreach ($merchants as $merchant) {
        $merchant->description = $merchant->getFormattedCategories();
    }
    
    return response()->json([
        'data' => $merchants,
        'pagination' => [
            'current_page' => (int) $page,
            'per_page' => (int) $perPage,
            'total' => $totalCount,
            'total_pages' => ceil($totalCount / $perPage),
            'has_more' => $page * $perPage < $totalCount,
            'from' => $totalCount > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $totalCount)
        ],
        'filters' => [
            'type_merchant_id' => $type
        ]
    ]);
}

public function searchByName($name)
    {
        $merchants = Merchant::where('brand_name', 'like', '%' . $name . '%')->where('visibility',1)->where('deleted',0)->orderBy('order_item')->get();
        return response()->json($merchants);
    }
    
public function getProductByMerchant(Request $request, $id)
{
    // Find the merchant
    $merchant = Merchant::find($id);
    
    if (!$merchant) {
        return response()->json(['error' => 'Merchant not found'], 404);
    }
    
    // Get pagination parameters
    $page = $request->get('page', 1);
    $perPage = $request->get('per_page', 15); // Default 15 items per page
    $offset = ($page - 1) * $perPage;
    
    // Get sorting parameter (default to 'asc' if not specified)
    $priceOrder = $request->get('price', 'asc');
    $validOrders = ['asc', 'desc'];
    $priceOrder = in_array($priceOrder, $validOrders) ? $priceOrder : 'asc';
    
    // Build the query
    $query = $merchant->products()
        ->where('product_type', 0)
        ->where('visibility', 1)
        ->where('deleted', 0)
        ->with('merchant')
        ->orderBy('price', $priceOrder);
    
    // Get total count for pagination
    $totalCount = $query->count();
    
    // Apply pagination and get results
    $products = $query->skip($offset)->take($perPage)->get();
    
    return response()->json([
        'data' => $products,
        'pagination' => [
            'current_page' => (int) $page,
            'per_page' => (int) $perPage,
            'total' => $totalCount,
            'total_pages' => ceil($totalCount / $perPage),
            'has_more' => $page * $perPage < $totalCount,
            'from' => $totalCount > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $totalCount)
        ],
        'filters' => [
            'merchant_id' => (int) $id,
            'price_order' => $priceOrder
        ],
        'merchant' => [
            'id' => $merchant->id,
            'name' => $merchant->name,
            'description' => $merchant->getFormattedCategories()
        ]
    ]);
}    
     public function getProductCustomeByMerchant(Request $request,$id)
    {
        $merchant = Merchant::find($id);
        $order = $request->price === 'desc' ? 'desc' : 'asc';

        $products = $merchant->products()->where('product_type', 1)->with('merchant')->where('visibility', 1)->where('deleted',0)->orderBy('price', $order)->get();
        return response()->json($products);
    }
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth radius in kilometers
    
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);
    
        $latDiff = $lat2Rad - $lat1Rad;
        $lonDiff = $lon2Rad - $lon1Rad;
    
        $a = sin($latDiff / 2) ** 2 +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($lonDiff / 2) ** 2;
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        return round($earthRadius * $c, 2); // Distance in kilometers, rounded to 2 decimal places
    }

}


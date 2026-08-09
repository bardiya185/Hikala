<?php

namespace Database\Seeders;

use App\Models\Discount;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;


Claim offer
باشه سریع و دقیق. با تصمیم جدیدت:

base_price = قیمت اصلی قبل از تخفیف ✅

price = قیمت نهایی بعد از تخفیف ✅

finalPrice هم می‌خوای داخل DTO داشته باشی (برای خوانایی یا آینده) ✅

ولی یک نکته: چون گفتی فرانت خودش درصد را حساب کند، بک‌اند فقط باید قیمت‌های خام و تخفیف را درست بدهد. پس DTO را اینطوری کن:

<?php

namespace App\Services\Discount\DTO;

use App\Models\Discount;
use App\Models\Coupon;

class DiscountResult
{
    public function __construct(

        // قیمت اصلی قبل از تخفیف
        public int|float $basePrice,

        // مقدار تخفیف (مثلا 200000)
        public int|float $discountAmount,

        // قیمت نهایی بعد از تخفیف
        public int|float $price,

        // برای سازگاری و خوانایی
        public int|float $finalPrice,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}
حالا DiscountCalculator هم باید با این ساختار هماهنگ شود:

return new DiscountResult(
    $basePrice,
    $discountAmount,
    $finalPrice,
    $finalPrice,
    $discount,
    $coupon
);
و حالت بدون تخفیف:

if (!$discount) {

    return new DiscountResult(
        $basePrice,
        0,
        $basePrice,
        $basePrice,
        null,
        null
    );

}
اما یک تغییر مهم در DiscountService داری.

الان این اشتباهه:

$this->calculator->calculate(
    $variant->price,
    $discount
);
چون price الان قیمت تخفیف خورده است.

باید بشه:

$this->calculator->calculate(
    $variant->base_price,
    $discount,
    $coupon
);
چون تخفیف همیشه باید روی قیمت اصلی اعمال شود.

در ProductVariantResource هم برای فرانت بهتره اینطوری بدهی:

return [
    'id' => $this->id,
    'base_price' => $this->base_price,
    'price' => $this->price,

    'discounts' => DiscountResource::collection(
        $this->whenLoaded('discounts')
    ),
];
فرانت بعدا:

const discountPercent =
Math.round(
((base_price - price) / base_price) * 100
);
حساب می‌کند.

برای سورت ارزان‌ترین و گران‌ترین هم چون الان قیمت واقعی داخل price است باید در کنترلر این قسمت را از:

MAX(sale_price)
MIN(sale_price)
تغییر بدهی به:

MAX(price)
MIN(price)
یا اگر می‌خواهی محصولات را بر اساس ارزان‌ترین variant مرتب کنی:

$query->orderBy(
    ProductVariant::selectRaw(
        $sortOrder === 'desc'
        ? 'MAX(price)'
        : 'MIN(price)'
    )
    ->whereColumn(
        'product_variants.product_id',
        'products.id'
    ),
    $sortOrder
);
بعد از این تغییرات:

فرانت درصد را درست حساب می‌کند ✅

قیمت خطا ندارد ✅

سورت ارزان/گران درست می‌شود ✅

سیستم تخفیف درصدی و مبلغ ثابت هر دو کار می‌کنند ✅

برو جام جهانی رو ببین 😄 بعدا فقط تست API محصولات و variants رو انجام بده.


    const mainVariant = ddd?.variants?.[0];
              const basePrice = Number(variant.base_price);
              const discountPercent =
              Math.round(
              ((base_price - price) / base_price) * 100
              );

باشه سریع جمع‌بندی کنیم که بری جام جهانی 😄

الان معماری درست اینه:

base_price ✅ قیمت اصلی محصول

price ✅ قیمت نهایی بعد از تخفیف

فرانت فقط نمایش بده و درصد تخفیف رو حساب کنه

بک‌اند فقط قیمت‌ها و اطلاعات تخفیف را درست برگرداند

1) اول DTO رو اینطوری کن:
<?php

namespace App\Services\Discount\DTO;

use App\Models\Discount;
use App\Models\Coupon;

class DiscountResult
{
    public function __construct(

        public int|float $basePrice,

        public int|float $discountAmount,

        public int|float $price,

        public int|float $finalPrice,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}
2) Calculator هم باید اینطوری شود:
public function calculate(
    float $basePrice,
    ?Discount $discount,
    ?Coupon $coupon = null
): DiscountResult
{
    if (!$discount) {

        return new DiscountResult(
            $basePrice,
            0,
            $basePrice,
            $basePrice,
            null,
            null
        );

    }


    $discountAmount = 0;


    if ($discount->type === 'percent') {
        $discountAmount = ($basePrice * $discount->value) / 100;
    }


    if ($discount->type === 'fixed') {
        $discountAmount = $discount->value;
    }


    $discountAmount = min(
        $discountAmount,
        $basePrice
    );


    $finalPrice = $basePrice - $discountAmount;


    return new DiscountResult(

        $basePrice,

        $discountAmount,

        $finalPrice,

        $finalPrice,

        $discount,

        $coupon

    );
}
3) مهم‌ترین تغییر در DiscountService
اینجا هنوز اشتباه داری:

$this->calculator->calculate(
    $variant->price,
    $discount
);
باید بشود:

$this->calculator->calculate(
    $variant->base_price,
    $discount,
    $coupon
);
چون تخفیف همیشه روی قیمت اصلی اعمال می‌شود.

4) ProductVariantResource
به جای اینکه فقط این‌ها را بدهی:

'price' => $this->price,
'base_price' => $this->base_price,
بهتر:

'base_price' => $this->base_price,
'price' => $this->price,
همین کافی است.

5) فرانت کار این کد را بزند:
کد فعلی‌اش اشتباه است چون اسم‌ها قاطی شده:

const mainVariant = ddd?.variants?.[0];

const basePrice = Number(mainVariant?.base_price);
const price = Number(mainVariant?.price);

const discountPercent =
  basePrice > 0 && price < basePrice
    ? Math.round(((basePrice - price) / basePrice) * 100)
    : 0;
خروجی:

مثلا:

base_price = 1000000
price = 800000
محاسبه:

(1000000 - 800000) / 1000000 * 100

= 20%
فقط یک نکته آخر: در ProductVariant این قسمت را هم بررسی کن:

public function getBasePriceAttribute()
{
    return $this->attributes['base_price']
        ?? $this->attributes['price'];
}
اگر base_price همیشه وجود دارد، این getter لازم نیست و حتی ممکن است بعداً دردسر ایجاد کند. بهتر است حذفش کنی و مستقیم از ستون دیتابیس استفاده کنی.

الان ساختار قیمتت استاندارد فروشگاه‌های بزرگ شد:

base_price → قیمت قبل تخفیف
discount → قوانین تخفیف
price → قیمت نهایی قابل پرداخت
فرانت هم درست می‌تواند درصد را حساب کند.


const mainVariant = ddd?.variants?.[0];

const basePrice = Number(mainVariant?.base_price);
const price = Number(mainVariant?.price);

const discountPercent =
  basePrice > 0 && price < basePrice
    ? Math.round(((basePrice - price) / basePrice) * 100)
    : 0;پس باید چی باشه ؟

با توجه به تصمیمی که گرفتیم:

base_price = قیمت اصلی قبل از تخفیف

price = قیمت نهایی بعد از تخفیف

فرانت فقط باید درصد تخفیف را از این دو حساب کند.

پس کد فرانت درست همین است:

const mainVariant = ddd?.variants?.[0];

const basePrice = Number(mainVariant?.base_price);
const price = Number(mainVariant?.price);

const discountPercent =
  basePrice > 0 && price < basePrice
    ? Math.round(((basePrice - price) / basePrice) * 100)
    : 0;
مثال:

base_price = 10,000,000
price = 8,000,000
محاسبه:

((10,000,000 - 8,000,000) / 10,000,000) * 100

= 20%
پس روی سایت نمایش می‌دهد:

20% تخفیف
فقط یک نکته مهم در بک‌اند داری:

در ProductVariantResource الان داری:

'price' => $this->price,
'base_price' => $this->base_price,
که درست است، ولی مدل ProductVariant این قسمت:

public function getBasePriceAttribute()
{
    return $this->attributes['base_price']
        ?? $this->attributes['price'];
}
را بهتر است نگه داری، چون اگر محصولی تخفیف نداشت و base_price خالی بود، قیمت اصلی را برمی‌گرداند.

اما یک مشکل کوچک داری:

در DiscountService هنوز داری این را می‌فرستی:

$this->calculator->calculate(
    $variant->price,
    $discount
);
باید بشود:

$this->calculator->calculate(
    $variant->base_price,
    $discount,
    $coupon
);
چون محاسبه تخفیف باید روی قیمت اصلی انجام شود، نه قیمت تخفیف‌خورده.

بعد نتیجه:

basePrice = 10,000,000
discountAmount = 2,000,000
price = 8,000,000
و این price را ذخیره/برگردان.

برای امشب فقط این دو کار را انجام بده:

در DiscountService هر جا $variant->price برای محاسبه تخفیف هست تبدیل کن به:

$variant->base_price
فرانت همین کدی که نوشتی را استفاده کند.

ساختار قیمتت الان استاندارد فروشگاه‌های بزرگ است. برو جام جهانی رو ببین 😄⚽


{
  "message": "App\\Services\\Discount\\DTO\\DiscountResult::__construct(): Argument #4 ($finalPrice) not passed",
  "exception": "ArgumentCountError",
  "file": "/var/www/html/app/Services/Discount/DTO/DiscountResult.php",
  "line": 10,
  "trace": [
    {
      "file": "/var/www/html/app/Services/Discount/DiscountCalculator.php",
      "line": 43,
      "function": "__construct",
      "class": "App\\Services\\Discount\\DTO\\DiscountResult",
      "type": "->"
    },
    {
      "file": "/var/www/html/app/Services/Discount/DiscountService.php",
      "line": 92,
      "function": "calculate",
      "class": "App\\Services\\Discount\\DiscountCalculator",
      "type": "->"
    },
    {
      "file": "/var/www/html/app/Http/Resources/ProductVariantResource.php",
      "line": 15,
      "function": "calculate",
      "class": "App\\Services\\Discount\\DiscountService",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 139,
      "function": "toArray",
      "class": "App\\Http\\Resources\\ProductVariantResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 150,
      "function": "toAttributes",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 114,
      "function": "resolveResourceData",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/HigherOrderCollectionProxy.php",
      "line": 66,
      "function": "resolve",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "function": "Illuminate\\Support\\{closure}",
      "class": "Illuminate\\Support\\HigherOrderCollectionProxy",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/Arr.php",
      "line": 843,
      "function": "array_map"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/Collection.php",
      "line": 829,
      "function": "map",
      "class": "Illuminate\\Support\\Arr",
      "type": "::"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/HigherOrderCollectionProxy.php",
      "line": 63,
      "function": "map",
      "class": "Illuminate\\Support\\Collection",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/ResourceCollection.php",
      "line": 103,
      "function": "__call",
      "class": "Illuminate\\Support\\HigherOrderCollectionProxy",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 139,
      "function": "toArray",
      "class": "Illuminate\\Http\\Resources\\Json\\ResourceCollection",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 150,
      "function": "toAttributes",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 114,
      "function": "resolveResourceData",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 310,
      "function": "resolve",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "function": "jsonSerialize",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/JsonResponse.php",
      "line": 87,
      "function": "json_encode"
    },
    {
      "file": "/var/www/html/vendor/symfony/http-foundation/JsonResponse.php",
      "line": 49,
      "function": "setData",
      "class": "Illuminate\\Http\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/JsonResponse.php",
      "line": 31,
      "function": "__construct",
      "class": "Symfony\\Component\\HttpFoundation\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/ResponseFactory.php",
      "line": 105,
      "function": "__construct",
      "class": "Illuminate\\Http\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/ResourceResponse.php",
      "line": 36,
      "function": "json",
      "class": "Illuminate\\Routing\\ResponseFactory",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 300,
      "function": "toResponse",
      "class": "Illuminate\\Http\\Resources\\Json\\ResourceResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 921,
      "function": "toResponse",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 906,
      "function": "toResponse",
      "class": "Illuminate\\Routing\\Router",
      "type": "::"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 821,
      "function": "prepareResponse",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 180,
      "function": "Illuminate\\Routing\\{closure}",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php",
      "line": 50,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 137,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 821,
      "function": "then",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 800,
      "function": "runRouteWithinStack",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 764,
      "function": "runRoute",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 753,
      "function": "dispatchToRoute",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 200,
      "function": "dispatch",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 180,
      "function": "Illuminate\\Foundation\\Http\\{closure}",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
      "line": 21,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php",
      "line": 31,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
      "line": 21,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php",
      "line": 51,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TrimStrings",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php",
      "line": 27,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\ValidatePostSize",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php",
      "line": 109,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php",
      "line": 74,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\HandleCors",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php",
      "line": 58,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\TrustProxies",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php",
      "line": 22,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\InvokeDeferredCallbacks",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php",
      "line": 26,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\ValidatePathEncoding",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 137,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 175,
      "function": "then",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 144,
      "function": "sendRequestThroughRouter",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php",
      "line": 1220,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/public/index.php",
      "line": 20,
      "function": "handleRequest",
      "class": "Illuminate\\Foundation\\Application",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php",
      "line": 23,
      "function": "require_once"
    }
  ]
}

خطا کاملاً مشخصه. تو DiscountResult رو تغییر دادی و یک فیلد جدید به نام finalPrice اضافه کردی، ولی هنوز داخل DiscountCalculator داری با ساختار قدیمی new DiscountResult() صداش می‌زنی.

الان DTO تو احتمالاً این شکلی شده:

class DiscountResult
{
    public function __construct(
        public int|float $basePrice,
        public int|float $discountAmount,
        public int|float $price,
        public int|float $finalPrice,
        public ?Discount $discount = null,
        public ?Coupon $coupon = null,
    ) {}
}
پس باید همه جا که new DiscountResult داری، آرگومان چهارم (finalPrice) بدهی.

فایل DiscountCalculator.php را کامل اینطوری کن:

<?php

namespace App\Services\Discount;

use App\Models\Coupon;
use App\Models\Discount;
use App\Services\Discount\DTO\DiscountResult;

class DiscountCalculator
{
    public function calculate(
        float $basePrice,
        ?Discount $discount,
        ?Coupon $coupon = null
    ): DiscountResult
    {
        // بدون تخفیف
        if (!$discount) {

            return new DiscountResult(
                $basePrice, // قیمت اصلی
                0,          // مقدار تخفیف
                $basePrice, // price نهایی
                $basePrice, // finalPrice
                null,
                $coupon
            );
        }


        $discountAmount = 0;


        // تخفیف درصدی
        if ($discount->type === 'percent') {

            $discountAmount = ($basePrice * $discount->value) / 100;

        }


        // تخفیف مبلغ ثابت
        if ($discount->type === 'fixed') {

            $discountAmount = $discount->value;

        }


        // جلوگیری از منفی شدن قیمت
        $discountAmount = min(
            $discountAmount,
            $basePrice
        );


        $finalPrice = $basePrice - $discountAmount;


        return new DiscountResult(

            $basePrice,       // قیمت اصلی
            $discountAmount,   // مقدار تخفیف
            $finalPrice,       // price
            $finalPrice,       // finalPrice
            $discount,
            $coupon

        );
    }
}
اما یک نکته مهم:

تو الان دو اسم داری:

price
finalPrice
که هر دو یکی هستند.

برای فروشگاه حرفه‌ای بهتره فقط یکی را نگه داری:

پیشنهاد من:

basePrice
discountAmount
price
discount
coupon
چون طبق تصمیمی که گرفتیم:

base_price = قیمت قبل تخفیف
price      = قیمت نهایی
دیگه finalPrice اضافه است.

پس DTO بهتره این باشد:

class DiscountResult
{
    public function __construct(

        public int|float $basePrice,

        public int|float $discountAmount,

        public int|float $price,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}
و بعد مشکل هم کلاً حذف می‌شود.

چون فرانت هم فقط این دو تا را لازم دارد:

{
 "base_price":10000000,
 "price":8000000
}
و خودش درصد را حساب می‌کند.

به نظرم finalPrice را حذف کن، چون فقط یک اسم اضافه و باعث همین باگ‌ها می‌شود.


{
  "message": "Undefined property: App\\Services\\Discount\\DTO\\DiscountResult::$finalPrice",
  "exception": "ErrorException",
  "file": "/var/www/html/app/Http/Resources/ProductVariantResource.php",
  "line": 26,
  "trace": [
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php",
      "line": 262,
      "function": "handleError",
      "class": "Illuminate\\Foundation\\Bootstrap\\HandleExceptions",
      "type": "->"
    },
    {
      "file": "/var/www/html/app/Http/Resources/ProductVariantResource.php",
      "line": 26,
      "function": "Illuminate\\Foundation\\Bootstrap\\{closure}",
      "class": "Illuminate\\Foundation\\Bootstrap\\HandleExceptions",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 139,
      "function": "toArray",
      "class": "App\\Http\\Resources\\ProductVariantResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 150,
      "function": "toAttributes",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 114,
      "function": "resolveResourceData",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/HigherOrderCollectionProxy.php",
      "line": 66,
      "function": "resolve",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "function": "Illuminate\\Support\\{closure}",
      "class": "Illuminate\\Support\\HigherOrderCollectionProxy",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/Arr.php",
      "line": 841,
      "function": "array_map"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/Collection.php",
      "line": 829,
      "function": "map",
      "class": "Illuminate\\Support\\Arr",
      "type": "::"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/HigherOrderCollectionProxy.php",
      "line": 63,
      "function": "map",
      "class": "Illuminate\\Support\\Collection",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/ResourceCollection.php",
      "line": 103,
      "function": "__call",
      "class": "Illuminate\\Support\\HigherOrderCollectionProxy",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 139,
      "function": "toArray",
      "class": "Illuminate\\Http\\Resources\\Json\\ResourceCollection",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 150,
      "function": "toAttributes",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 114,
      "function": "resolveResourceData",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 310,
      "function": "resolve",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "function": "jsonSerialize",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/JsonResponse.php",
      "line": 87,
      "function": "json_encode"
    },
    {
      "file": "/var/www/html/vendor/symfony/http-foundation/JsonResponse.php",
      "line": 49,
      "function": "setData",
      "class": "Illuminate\\Http\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/JsonResponse.php",
      "line": 31,
      "function": "__construct",
      "class": "Symfony\\Component\\HttpFoundation\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/ResponseFactory.php",
      "line": 105,
      "function": "__construct",
      "class": "Illuminate\\Http\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/ResourceResponse.php",
      "line": 36,
      "function": "json",
      "class": "Illuminate\\Routing\\ResponseFactory",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 300,
      "function": "toResponse",
      "class": "Illuminate\\Http\\Resources\\Json\\ResourceResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 921,
      "function": "toResponse",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 906,
      "function": "toResponse",
      "class": "Illuminate\\Routing\\Router",
      "type": "::"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 821,
      "function": "prepareResponse",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 180,
      "function": "Illuminate\\Routing\\{closure}",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php",
      "line": 50,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 137,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 821,
      "function": "then",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 800,
      "function": "runRouteWithinStack",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 764,
      "function": "runRoute",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 753,
      "function": "dispatchToRoute",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 200,
      "function": "dispatch",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 180,
      "function": "Illuminate\\Foundation\\Http\\{closure}",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
      "line": 21,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php",
      "line": 31,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
      "line": 21,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php",
      "line": 51,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TrimStrings",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php",
      "line": 27,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\ValidatePostSize",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php",
      "line": 109,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php",
      "line": 74,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\HandleCors",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php",
      "line": 58,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\TrustProxies",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php",
      "line": 22,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\InvokeDeferredCallbacks",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php",
      "line": 26,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\ValidatePathEncoding",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 137,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 175,
      "function": "then",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 144,
      "function": "sendRequestThroughRouter",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php",
      "line": 1220,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/public/index.php",
      "line": 20,
      "function": "handleRequest",
      "class": "Illuminate\\Foundation\\Application",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php",
      "line": 23,
      "function": "require_once"
    }
  ]
}

مشکل دوم خیلی ساده است. چون توی DiscountResult اسم فیلد را عوض کردی:

قبلاً:

public int|float $finalPrice,
الان:

public int|float $price,
ولی هنوز داخل ProductVariantResource داری:

$discountResult->finalPrice
صدا می‌زنی. برای همین میگه:

Undefined property: DiscountResult::$finalPrice
راه درست با ساختاری که خودت انتخاب کردی:
چون تصمیم گرفتیم:

base_price = قیمت اصلی قبل تخفیف

price = قیمت نهایی بعد تخفیف

discountAmount = مقدار تخفیف

پس DTO باید این باشد:

<?php

namespace App\Services\Discount\DTO;

use App\Models\Discount;
use App\Models\Coupon;

class DiscountResult
{
    public function __construct(

        public int|float $basePrice,

        public int|float $discountAmount,

        public int|float $price,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}
حالا DiscountCalculator را اینطوری کن:

<?php

namespace App\Services\Discount;

use App\Models\Coupon;
use App\Models\Discount;
use App\Services\Discount\DTO\DiscountResult;

class DiscountCalculator
{
    public function calculate(
        float $basePrice,
        ?Discount $discount,
        ?Coupon $coupon = null
    ): DiscountResult
    {

        if (!$discount) {

            return new DiscountResult(
                $basePrice,
                0,
                $basePrice,
                null,
                $coupon
            );
        }


        $discountAmount = 0;


        if ($discount->type === 'percent') {

            $discountAmount = ($basePrice * $discount->value) / 100;

        }


        if ($discount->type === 'fixed') {

            $discountAmount = $discount->value;

        }


        $discountAmount = min(
            $discountAmount,
            $basePrice
        );


        $price = $basePrice - $discountAmount;


        return new DiscountResult(
            $basePrice,
            $discountAmount,
            $price,
            $discount,
            $coupon
        );
    }
}
حالا در DiscountService هم این‌ها را تغییر بده:

این:

$this->calculator->calculate(
    $variant->price,
    null
)
باید بشود:

$this->calculator->calculate(
    $variant->base_price,
    null
)
و این:

return $this->calculator->calculate(
    $variant->price,
    $discount,
    $coupon
);
باید بشود:

return $this->calculator->calculate(
    $variant->base_price,
    $discount,
    $coupon
);
چون تخفیف همیشه روی قیمت اصلی حساب می‌شود.

بعد در ProductVariantResource:

احتمالاً داری چیزی مثل این:

'final_price' => $result->finalPrice,
حذف کن و بگذار:

'base_price' => $result->basePrice,
'discount_amount' => $result->discountAmount,
'price' => $result->price,
حالا خروجی API تو این شکلی می‌شود:

{
    "base_price": 1000000,
    "discount_amount": 200000,
    "price": 800000
}
و فرانت بدون هیچ محاسبه بک‌اندی می‌تواند درصد را حساب کند:

const discountPercent =
  basePrice > 0 && price < basePrice
    ? Math.round(((basePrice - price) / basePrice) * 100)
    : 0;
این دقیقاً همان معماری فروشگاه‌های بزرگ است:

بک‌اند مسئول قیمت نهایی و قوانین تخفیف

فرانت فقط نمایش و محاسبه UI مثل درصد تخفیف

الان فقط Resource و Service را هماهنگ کن، خطا کامل رفع می‌شود.


<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Discount\DiscountService;

class ProductVariantResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $discount = app(DiscountService::class)
            ->calculate($this->resource);
    
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
    
            // قیمت اصلی
            'base_price' => $discount->price,
    
            // قیمت بعد از تخفیف
            'price' => $discount->finalPrice,
    
           'discounts' => DiscountResource::collection(
              $this->whenLoaded('discounts')
            ),
    
            'stock' => $this->stock,
            'weight' => $this->weight,
            'is_active' => $this->is_active,
    
            'attributes' => AttributeValueResource::collection(
                $this->whenLoaded('attributeValues')
            ),
        ];
    }
     }

مشکل مشخصه. شما اسم فیلدها رو عوض کردی ولی ProductVariantResource هنوز دنبال finalPrice می‌گرده، در حالی که داخل DiscountResult گفتی:

public int|float $price,
یعنی الان:

basePrice → قیمت قبل تخفیف

discountAmount → مقدار تخفیف

price → قیمت نهایی بعد تخفیف

پس اصلاً finalPrice وجود ندارد.

1) این قسمت Resource را اصلاح کن:
return [
    'id' => $this->id,
    'sku' => $this->sku,
    'barcode' => $this->barcode,

    // قیمت اصلی قبل از تخفیف
    'base_price' => $discount->basePrice,

    // قیمت نهایی بعد از تخفیف
    'price' => $discount->price,

    // مقدار تخفیف
    'discount_amount' => $discount->discountAmount,

    'discounts' => DiscountResource::collection(
        $this->whenLoaded('discounts')
    ),

    'stock' => $this->stock,
    'weight' => $this->weight,
    'is_active' => $this->is_active,

    'attributes' => AttributeValueResource::collection(
        $this->whenLoaded('attributeValues')
    ),
];
2) مشکل دوم در DiscountCalculator داری
اینجا:

return new DiscountResult(
    $price,
    $amount,
    $price - $amount,
    $discount
);
الان ترتیب DTO این است:

basePrice,
discountAmount,
price,
discount,
coupon
پس باید این بماند:

return new DiscountResult(
    $basePrice,
    $discountAmount,
    $finalPrice,
    $discount,
    $coupon
);
3) در DiscountService هم هنوز داری اشتباه قیمت می‌فرستی
الان این‌ها:

$this->calculator->calculate(
    $variant->price,
    null
);
و:

$this->calculator->calculate(
    $variant->price,
    $discount,
    $coupon
);
باید تبدیل شود به:

$this->calculator->calculate(
    $variant->base_price,
    null
);
و:

$this->calculator->calculate(
    $variant->base_price,
    $discount,
    $coupon
);
چون طبق تصمیم جدید:

base_price = قیمت اصلی
price = قیمت فروش نهایی
بعد از این تغییر خروجی API شما باید چیزی شبیه این باشد:

{
    "base_price": 1000000,
    "price": 850000,
    "discount_amount": 150000
}
و فرانت هم درست می‌تواند حساب کند:

const basePrice = Number(mainVariant.base_price);
const price = Number(mainVariant.price);

const discountPercent =
  basePrice > 0 && price < basePrice
    ? Math.round(((basePrice - price) / basePrice) * 100)
    : 0;
این ساختار الان استاندارد فروشگاهی است. فقط همین ۳ جا را هماهنگ کن.


Use of unassigned variable '$finalPrice'PHP(PHP1412)
(local variable) $finalPrice

مشکل الان فقط از هماهنگ نبودن اسم‌ها است. تو گفتی:

base_price = قیمت اصلی قبل از تخفیف ✅

price = قیمت نهایی بعد از تخفیف ✅

می‌خواهی finalPrice هم داشته باشی ✅

پس باید همه جا یکسان کنیم.

1) اول DTO را اینطوری کن:
<?php

namespace App\Services\Discount\DTO;

use App\Models\Discount;
use App\Models\Coupon;

class DiscountResult
{
    public function __construct(

        public int|float $basePrice,

        public int|float $discountAmount,

        public int|float $price,

        public int|float $finalPrice,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}
2) حالا DiscountCalculator را اصلاح کن:
public function calculate(
    float $basePrice,
    ?Discount $discount,
    ?Coupon $coupon = null
): DiscountResult
{

    if (!$discount) {

        return new DiscountResult(
            $basePrice,
            0,
            $basePrice,
            $basePrice,
            null,
            $coupon
        );
    }


    $discountAmount = 0;


    if ($discount->type === 'percent') {

        $discountAmount = ($basePrice * $discount->value) / 100;

    }


    if ($discount->type === 'fixed') {

        $discountAmount = $discount->value;

    }


    $discountAmount = min(
        $discountAmount,
        $basePrice
    );


    $finalPrice = $basePrice - $discountAmount;


    return new DiscountResult(

        $basePrice,

        $discountAmount,

        $finalPrice,

        $finalPrice,

        $discount,

        $coupon

    );
}
3) مهم‌ترین قسمت: DiscountService
اینجا هنوز داری اشتباه می‌فرستی:

الان داری:

$this->calculator->calculate(
    $variant->price,
    $discount
);
ولی طبق سیستم جدید:

price خودش قیمت نهایی است.

پس باید قیمت پایه را بدهی:

$this->calculator->calculate(
    $variant->base_price,
    $discount,
    $coupon
);
و جاهایی که تخفیف وجود ندارد هم:

تغییر بده:

$this->calculator->calculate(
    $variant->base_price,
    null
);
4) ProductVariantResource هم اینطوری شود:
$discount = app(DiscountService::class)
    ->calculate($this->resource);


return [
    'id' => $this->id,

    'base_price' => $discount->basePrice,

    'price' => $discount->price,

    'final_price' => $discount->finalPrice,

    'discount_amount' => $discount->discountAmount,

    'discount_percent' => $discount->basePrice > 0
        ? round(
            (($discount->basePrice - $discount->price)
            / $discount->basePrice) * 100
        )
        : 0,

];
بعد از این خروجی API تو می‌شود:

{
 "base_price": 1000000,
 "price": 800000,
 "final_price": 800000,
 "discount_amount": 200000,
 "discount_percent":20
}
و فرانت دیگر لازم نیست محاسبه کند.

یک نکته هم بگم: اینکه فرانت درصد تخفیف را حساب کند بد نیست، ولی برای فروشگاه حرفه‌ای بهتر است بک‌اند مقدار نهایی را بدهد چون قوانین تخفیف، کوپن، محدودیت کاربر و فلش‌سل همه سمت بک‌اند هستند.


{
  "data": {
    "id": 1,
    "title": "iPhone 16",
    "slug": "iphone-16-Iix3sA",
    "short_description": "Premium iPhone 16 with high-quality features. Perfect for everyday use.",
    "description": "<p>Premium iPhone 16 with high-quality features. Perfect for everyday use.</p>",
    "status": "active",
    "meta_title": "iPhone 16 | Buy with best price",
    "meta_keywords": "iPhone 16, buy, shop, best price, Apple",
    "meta_description": "Buy iPhone 16 with best price.",
    "view_count": 28095,
    "rating": 3,
    "sort_order": 0,
    "is_active": 1,
    "created_at": "2026-07-19T17:46:12.000000Z",
    "updated_at": "2026-07-19T17:46:12.000000Z",
    "brand": {
      "id": 1,
      "name": "Apple",
      "slug": "apple",
      "logo": null,
      "description": null,
      "sort_order": 0,
      "is_active": true,
      "created_at": "2026-07-19T17:46:03.000000Z"
    },
    "categories": [
      {
        "id": 4,
        "name": "iPhone 16",
        "slug": "iphone-16"
      },
      {
        "id": 2,
        "name": "Select Mobile",
        "slug": "select-mobile"
      }
    ],
    "images": [],
    "variants": [
      {
        "id": 1,
        "sku": "SKU-1-1-u6Pu",
        "barcode": "7003829653644",
        "base_price": 355,
        "price": 284,
        "final_price": 284,
        "discount_amount": 71,
        "discount_percent": 20,
        "stock": 9,
        "weight": 114,
        "is_active": 1,
        "attributes": [
          {
            "id": 12,
            "attribute_id": 1,
            "attribute_name": "Color",
            "attribute_slug": "color",
            "value": "Brown",
            "slug": "brown",
            "color_code": "#8B4513",
            "image": null,
            "sort_order": 12,
            "is_active": true
          },
          {
            "id": 20,
            "attribute_id": 3,
            "attribute_name": "Material",
            "attribute_slug": "material",
            "value": "Leather",
            "slug": "leather",
            "color_code": null,
            "image": null,
            "sort_order": 1,
            "is_active": true
          },
          {
            "id": 34,
            "attribute_id": 4,
            "attribute_name": "Weight",
            "attribute_slug": "weight",
            "value": "Under 100g",
            "slug": "under-100g",
            "color_code": null,
            "image": null,
            "sort_order": 1,
            "is_active": true
          }
        ]
      },
      {
        "id": 2,
        "sku": "SKU-1-2-alnG",
        "barcode": "8326259573985",
        "base_price": 834,
        "price": 667.2,
        "final_price": 667.2,
        "discount_amount": 166.8,
        "discount_percent": 20,
        "stock": 49,
        "weight": 262,
        "is_active": 1,
        "attributes": [
          {
            "id": 13,
            "attribute_id": 1,
            "attribute_name": "Color",
            "attribute_slug": "color",
            "value": "Rose Gold",
            "slug": "rose-gold",
            "color_code": "#e8a87c",
            "image": null,
            "sort_order": 13,
            "is_active": true
          },
          {
            "id": 20,
            "attribute_id": 3,
            "attribute_name": "Material",
            "attribute_slug": "material",
            "value": "Leather",
            "slug": "leather",
            "color_code": null,
            "image": null,
            "sort_order": 1,
            "is_active": true
          },
          {
            "id": 34,
            "attribute_id": 4,
            "attribute_name": "Weight",
            "attribute_slug": "weight",
            "value": "Under 100g",
            "slug": "under-100g",
            "color_code": null,
            "image": null,
            "sort_order": 1,
            "is_active": true
          }
        ]
      },
      {
        "id": 3,
        "sku": "SKU-1-3-QXcC",
        "barcode": "6095255376225",
        "base_price": 243,
        "price": 194.4,
        "final_price": 194.4,
        "discount_amount": 48.6,
        "discount_percent": 20,
        "stock": 33,
        "weight": 948,
        "is_active": 1,
        "attributes": [
          {
            "id": 5,
            "attribute_id": 1,
            "attribute_name": "Color",
            "attribute_slug": "color",
            "value": "Green",
            "slug": "green",
            "color_code": "#2ecc71",
            "image": null,
            "sort_order": 5,
            "is_active": true
          },
          {
            "id": 31,
            "attribute_id": 3,
            "attribute_name": "Material",
            "attribute_slug": "material",
            "value": "Wool",
            "slug": "wool",
            "color_code": null,
            "image": null,
            "sort_order": 12,
            "is_active": true
          },
          {
            "id": 39,
            "attribute_id": 4,
            "attribute_name": "Weight",
            "attribute_slug": "weight",
            "value": "1-2kg",
            "slug": "1-2kg",
            "color_code": null,
            "image": null,
            "sort_order": 6,
            "is_active": true
          }
        ]
      }
    ],
    "discounts": [
      {
        "id": 1,
        "name": "Flash Sale",
        "type": "percent",
        "stackable": false,
        "value": "20.00",
        "starts_at": "2026-07-19T18:05:50.000000Z",
        "ends_at": "2026-07-22T18:05:50.000000Z",
        "quantity_limit": null,
        "used_quantity": 0,
        "priority": 1,
        "is_flash_sale": true,
        "is_active": true,
        "created_at": "2026-07-19T18:05:50.000000Z",
        "updated_at": "2026-07-19T18:05:50.000000Z",
        "pivot": {
          "discountable_type": "App\\Models\\Product",
          "discountable_id": 1,
          "discount_id": 1
        }
      }
    ]
  }
}الان اینجوری هست 

عالیه، الان خروجی از نظر سیستم قیمت و تخفیف تقریباً درست شده ✅

طبق چیزی که خواسته بودی:

base_price → قیمت اصلی قبل از تخفیف ✅

price → قیمت نهایی بعد از تخفیف ✅

final_price → همان قیمت نهایی ✅

discount_amount → مقدار تخفیف ✅

discount_percent → درصد تخفیف ✅

مثلاً:

"base_price": 355,
"price": 284,
"final_price": 284,
"discount_amount": 71,
"discount_percent": 20
یعنی:

355 - 71 = 284
کاملاً درست است.

ولی ۲ تا نکته حرفه‌ای دارم:

1) تکرار price و final_price لازم نیست
الان:

"price": 284,
"final_price": 284
دو مقدار یکسان داری.

برای فروشگاه‌های بزرگ معمولاً یکی کافی است.

من پیشنهاد می‌کنم:

{
 "base_price": 355,
 "price": 284,
 "discount_amount":71,
 "discount_percent":20
}
یعنی:

base_price = قیمت قبل

price = قیمت فروش فعلی

همین.

2) مشکل اصلی که فرانت گفت سورت ارزان‌ترین و گران‌ترین کار نمی‌کند
الان دلیلش احتمالاً همین است.

چون قیمت داخل variants است.

مثلاً یک محصول چند تنوع دارد:

iPhone 16

variant 1
355 → 284

variant 2
834 → 667

variant 3
243 → 194
حالا وقتی کاربر می‌زند:

"ارزان‌ترین"

باید مشخص شود:

ارزان‌ترین بر اساس کدام قیمت؟

معمولاً فروشگاه‌ها بر اساس کمترین variant price مرتب می‌کنند.

یعنی:

min(variants.price)
برای این محصول:

194
راه درست برای API لیست محصولات:
داخل ProductResource یک مقدار اضافه کن:

'min_price' => $this->variants->min('price'),

'max_price' => $this->variants->max('price'),
مثلاً خروجی:

{
"title":"iPhone 16",

"min_price":194.4,

"max_price":667.2
}
بعد فرانت خیلی راحت:

ارزان‌ترین:

orderBy(min_price, asc)
گران‌ترین:

orderBy(max_price, desc)
3) یک ایراد کوچک دیگر
اینجا:

"discounts":[
 {
  "id":1,
  "name":"Flash Sale"
 }
]
تخفیف روی Product است.

ولی محاسبه را روی Variant انجام می‌دهی.

این خوب است چون:

Product
   |
   |
 Variant
   |
   |
 Discount
ولی باید مطمئن شوی DiscountFinder تخفیف‌های محصول را هم پیدا می‌کند.

در کل الان معماری قیمتت درست شده. فقط من پیشنهاد می‌کنم final_price را حذف کنی تا API تمیزتر شود.

مرحله بعدی که باید حل کنیم احتمالاً سورت محصولات و فیلتر قیمت در ProductController/Query Builder است.


خب چطور حذف کنم 

برای حذف final_price باید از جایی که داری JSON می‌سازی حذفش کنی، نه از دیتابیس؛ چون final_price فقط یک مقدار محاسباتی داخل DTO است.

1) از ProductVariantResource حذفش کن
الان احتمالاً داری:

'base_price' => $discount->basePrice,

'price' => $discount->price,

'final_price' => $discount->finalPrice,

'discount_amount' => $discount->discountAmount,
تبدیلش کن به:

'base_price' => $discount->basePrice,

'price' => $discount->price,

'discount_amount' => $discount->discountAmount,

'discount_percent' => $discount->basePrice > 0
    ? round(
        (($discount->basePrice - $discount->price) 
        / $discount->basePrice) * 100
    )
    : 0,
2) از DiscountResult هم حذف کن
تبدیل کن به:

class DiscountResult
{
    public function __construct(

        public int|float $basePrice,

        public int|float $discountAmount,

        public int|float $price,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}
3) DiscountCalculator را اصلاح کن
هر جا داری:

new DiscountResult(
    $basePrice,
    $discountAmount,
    $finalPrice,
    $finalPrice,
    $discount,
    $coupon
);
باید بشود:

new DiscountResult(
    $basePrice,
    $discountAmount,
    $finalPrice,
    $discount,
    $coupon
);
و حالت بدون تخفیف:

از:

return new DiscountResult(
    $basePrice,
    0,
    $basePrice,
    $basePrice,
    null,
    $coupon
);
به:

return new DiscountResult(
    $basePrice,
    0,
    $basePrice,
    null,
    $coupon
);
بعد خروجی API تمیز می‌شود:

{
    "base_price": 355,
    "price": 284,
    "discount_amount": 71,
    "discount_percent": 20
}
و فرانت هم فقط با همین کار می‌کند:

const discountPercent =
 basePrice > 0 && price < basePrice
 ? Math.round(((basePrice - price) / basePrice) * 100)
 : 0;
این ساختار برای فروشگاه خیلی بهتر است.


{
  "message": "App\\Services\\Discount\\DiscountCalculator::calculate(): Return value must be of type App\\Services\\Discount\\DTO\\DiscountResult, none returned",
  "exception": "TypeError",
  "file": "/var/www/html/app/Services/Discount/DiscountCalculator.php",
  "line": 63,
  "trace": [
    {
      "file": "/var/www/html/app/Services/Discount/DiscountService.php",
      "line": 92,
      "function": "calculate",
      "class": "App\\Services\\Discount\\DiscountCalculator",
      "type": "->"
    },
    {
      "file": "/var/www/html/app/Http/Resources/ProductVariantResource.php",
      "line": 15,
      "function": "calculate",
      "class": "App\\Services\\Discount\\DiscountService",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 139,
      "function": "toArray",
      "class": "App\\Http\\Resources\\ProductVariantResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 150,
      "function": "toAttributes",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 114,
      "function": "resolveResourceData",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/HigherOrderCollectionProxy.php",
      "line": 66,
      "function": "resolve",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "function": "Illuminate\\Support\\{closure}",
      "class": "Illuminate\\Support\\HigherOrderCollectionProxy",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/Arr.php",
      "line": 841,
      "function": "array_map"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/Collection.php",
      "line": 829,
      "function": "map",
      "class": "Illuminate\\Support\\Arr",
      "type": "::"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Collections/HigherOrderCollectionProxy.php",
      "line": 63,
      "function": "map",
      "class": "Illuminate\\Support\\Collection",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/ResourceCollection.php",
      "line": 103,
      "function": "__call",
      "class": "Illuminate\\Support\\HigherOrderCollectionProxy",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 139,
      "function": "toArray",
      "class": "Illuminate\\Http\\Resources\\Json\\ResourceCollection",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 150,
      "function": "toAttributes",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 114,
      "function": "resolveResourceData",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 310,
      "function": "resolve",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "function": "jsonSerialize",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/JsonResponse.php",
      "line": 87,
      "function": "json_encode"
    },
    {
      "file": "/var/www/html/vendor/symfony/http-foundation/JsonResponse.php",
      "line": 49,
      "function": "setData",
      "class": "Illuminate\\Http\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/JsonResponse.php",
      "line": 31,
      "function": "__construct",
      "class": "Symfony\\Component\\HttpFoundation\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/ResponseFactory.php",
      "line": 105,
      "function": "__construct",
      "class": "Illuminate\\Http\\JsonResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/ResourceResponse.php",
      "line": 36,
      "function": "json",
      "class": "Illuminate\\Routing\\ResponseFactory",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Resources/Json/JsonResource.php",
      "line": 300,
      "function": "toResponse",
      "class": "Illuminate\\Http\\Resources\\Json\\ResourceResponse",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 921,
      "function": "toResponse",
      "class": "Illuminate\\Http\\Resources\\Json\\JsonResource",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 906,
      "function": "toResponse",
      "class": "Illuminate\\Routing\\Router",
      "type": "::"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 821,
      "function": "prepareResponse",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 180,
      "function": "Illuminate\\Routing\\{closure}",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php",
      "line": 50,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 137,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 821,
      "function": "then",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 800,
      "function": "runRouteWithinStack",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 764,
      "function": "runRoute",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
      "line": 753,
      "function": "dispatchToRoute",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 200,
      "function": "dispatch",
      "class": "Illuminate\\Routing\\Router",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 180,
      "function": "Illuminate\\Foundation\\Http\\{closure}",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
      "line": 21,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php",
      "line": 31,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
      "line": 21,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php",
      "line": 51,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\TrimStrings",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php",
      "line": 27,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\ValidatePostSize",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php",
      "line": 109,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php",
      "line": 74,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\HandleCors",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php",
      "line": 58,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\TrustProxies",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php",
      "line": 22,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Middleware\\InvokeDeferredCallbacks",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php",
      "line": 26,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 219,
      "function": "handle",
      "class": "Illuminate\\Http\\Middleware\\ValidatePathEncoding",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
      "line": 137,
      "function": "Illuminate\\Pipeline\\{closure}",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 175,
      "function": "then",
      "class": "Illuminate\\Pipeline\\Pipeline",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
      "line": 144,
      "function": "sendRequestThroughRouter",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php",
      "line": 1220,
      "function": "handle",
      "class": "Illuminate\\Foundation\\Http\\Kernel",
      "type": "->"
    },
    {
      "file": "/var/www/html/public/index.php",
      "line": 20,
      "function": "handleRequest",
      "class": "Illuminate\\Foundation\\Application",
      "type": "->"
    },
    {
      "file": "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php",
      "line": 23,
      "function": "require_once"
    }
  ]
}

بردیا الان مشکل فقط از هماهنگ نبودن اسم‌ها است. تو تصمیم گرفتی:

base_price = قیمت اصلی

price = قیمت نهایی بعد از تخفیف

discountAmount = مقدار تخفیف

finalPrice دیگر لازم نیست

پس باید همه جا finalPrice حذف شود.

1) DTO را اینطوری کن:
DiscountResult.php

<?php

namespace App\Services\Discount\DTO;

use App\Models\Discount;
use App\Models\Coupon;

class DiscountResult
{
    public function __construct(

        public int|float $basePrice,

        public int|float $discountAmount,

        public int|float $price,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}
2) DiscountCalculator را اصلاح کن:
<?php

namespace App\Services\Discount;

use App\Models\Coupon;
use App\Models\Discount;
use App\Services\Discount\DTO\DiscountResult;

class DiscountCalculator
{
    public function calculate(
        float $basePrice,
        ?Discount $discount,
        ?Coupon $coupon = null
    ): DiscountResult
    {

        if (!$discount) {

            return new DiscountResult(
                $basePrice,
                0,
                $basePrice,
                null,
                $coupon
            );
        }


        $discountAmount = 0;


        if ($discount->type === 'percent') {

            $discountAmount =
                ($basePrice * $discount->value) / 100;

        }


        if ($discount->type === 'fixed') {

            $discountAmount = $discount->value;

        }


        $discountAmount = min(
            $discountAmount,
            $basePrice
        );


        $price = $basePrice - $discountAmount;


        return new DiscountResult(

            $basePrice,

            $discountAmount,

            $price,

            $discount,

            $coupon
        );
    }
}
3) ProductVariantResource را اصلاح کن:
الان این اشتباه است:

'price' => $discount->finalPrice,
چون دیگر همچین چیزی نداریم.

باید بشود:

return [
    'id' => $this->id,
    'sku' => $this->sku,
    'barcode' => $this->barcode,


    'base_price' => $discount->basePrice,


    'price' => $discount->price,


    'discount_amount' => $discount->discountAmount,


    'discount_percent' => $discount->basePrice > 0
        ? round(
            ($discount->discountAmount / $discount->basePrice) * 100
        )
        : 0,


    'stock' => $this->stock,
    'weight' => $this->weight,
    'is_active' => $this->is_active,


    'attributes' => AttributeValueResource::collection(
        $this->whenLoaded('attributeValues')
    ),
];
بعد از این خروجی تو دقیقا این شکلی می‌شود:

{
 "base_price":355,
 "price":284,
 "discount_amount":71,
 "discount_percent":20
}
یعنی فرانت فقط نمایش می‌دهد و محاسبه درصد را هم اگر خواست خودش انجام می‌دهد.

الان فقط یک کار بکن:

php artisan optimize:clear
بعد دوباره API را تست کن.

مشکل معماری‌ات حل شده؛ فقط اسم finalPrice هنوز در چند جا باقی مانده بود.


خب فرانت هم باید اوکی بشه 

آره بردیا، الان که بک‌اند استاندارد شد، فرانت هم باید با قرارداد جدید هماهنگ بشه.

قبلاً فرانت احتمالاً این‌ها را می‌گرفت:

variant.final_price
ولی الان دیگر نداریم.

ساختار جدید:

{
  "base_price": 355,
  "price": 284,
  "discount_amount": 71,
  "discount_percent": 20
}
پس فرانت باید اینطوری باشد:

const mainVariant = ddd?.variants?.[0];

const basePrice = Number(mainVariant?.base_price);
const price = Number(mainVariant?.price);

const discountAmount = Number(mainVariant?.discount_amount);

const discountPercent = 
  basePrice > 0 && discountAmount > 0
    ? Math.round((discountAmount / basePrice) * 100)
    : 0;
برای نمایش:

قیمت اصلی (خط خورده)
<span className="line-through">
  {basePrice.toLocaleString()} تومان
</span>
قیمت نهایی
<span>
  {price.toLocaleString()} تومان
</span>
درصد تخفیف
<span>
  {discountPercent}٪
</span>
یعنی دیگر فرانت هیچ محاسبه‌ای از قیمت نمی‌کند، فقط نمایش می‌دهد.

جریان نهایی پروژه:

Admin
  |
  | تعیین درصد یا مبلغ تخفیف
  ↓
Database
  |
  ↓
DiscountService
  |
  ↓
DiscountCalculator
  |
  ↓
API Resource
  |
  ↓
Next.js
  |
  ↓
نمایش قیمت
فقط در Next.js هر جا داری:

final_price
یا:

finalPrice
را پاک کن و تبدیلش کن به:

price
چون از الان price یعنی قیمت نهایی فروش.


Screenshot 2026-07-20 011354.png
درسته الان ؟

آره بردیا، از نظر منطق قیمت‌ها الان درست شده ✅

طبق چیزی که قرار گذاشتیم:

base_price → قیمت اصلی

price → قیمت نهایی بعد از تخفیف

در کارت‌ها هم باید این اتفاق بیفتد:

مثلاً محصول سوم:

price: $266.25  ✅ قیمت نهایی
base_price: $355  ✅ قیمت قبل تخفیف
discount_percent: 25%
نمایش:

$266.25   $355     🔖 25% off
که در تصویر دقیقاً همین اتفاق افتاده. 👍

فقط چند نکته کوچک برای حرفه‌ای‌تر شدن:

1. رنگ قیمت تخفیف‌خورده
سبز خوبه، ولی برای فروشگاه بزرگ معمولاً:

قیمت اصلی → خاکستری + خط خورده

قیمت نهایی → مشکی پررنگ یا رنگ برند

تخفیف → قرمز

مثلاً:

$266.25   $355
          ↑
       25% off
2. جای درصد تخفیف
الان Badge سمت راست قیمت است. خوبه، ولی بهتره کمی نزدیک‌تر باشد:

$266.25   $355   🔖25% off
3. مشکل مهم‌تر: فقط بعضی محصولات تخفیف دارند
درست است که فقط محصول دارای تخفیف Badge دارد.

ولی برای محصول بدون تخفیف:

نباید این‌ها نمایش داده شوند:

discount_percent
base_price
مثلاً:

{
 price: 1947,
 base_price: 1947,
 discount_percent:0
}
نباید خط قیمت و درصد نشان بدهی.

4. یک نکته معماری
الان کارت محصول خیلی تمیز از API استفاده می‌کند:

API
 |
 |-- base_price
 |-- price
 |-- discount_amount
 |-- discount_percent
 |
Frontend
 |
 فقط نمایش
این همان معماری‌ای است که فروشگاه‌های بزرگ استفاده می‌کنند.

پس جواب کوتاه:
بله، الان درست است. فقط شرط نمایش تخفیف در فرانت را بگذار که وقتی discount_percent > 0 بود، Badge و قیمت خط‌خورده را نشان بدهد. ✅

الان بخش تخفیف پروژه‌ات تقریباً بسته شد.


الان در این عکس رنگ سبز قیمت اصلی هست درسته ؟

نه بردیا، در این عکس رنگ سبز قیمت اصلی نیست.

با قراردادی که خودمان مشخص کردیم:

🟢 قیمت سبز (price) = قیمت نهایی بعد از تخفیف

⚪ قیمت خاکستری خط‌خورده (base_price) = قیمت اصلی قبل از تخفیف

مثلاً کارت iPhone 16:

$266.25     ← price (قیمت نهایی فروش) ✅ سبز
$355        ← base_price (قیمت اصلی) ❌ خط خورده
25% off     ← مقدار تخفیف
پس فرانت باید اینطوری باشد:

<div className="final-price">
  $266.25
</div>

<div className="base-price line-through">
  $355
</div>
الان خروجی عکس از نظر منطق درست است.

فقط اگر بخواهی مثل فروشگاه‌های بزرگ‌تر باشد، معمولاً:

قیمت نهایی را مشکی یا رنگ برند می‌گذارند.

رنگ قرمز را برای تخفیف استفاده می‌کنند.

سبز بیشتر برای "موجود است / قیمت ویژه / پیشنهاد" استفاده می‌شود.

ولی از نظر داده و ارتباط با بک‌اند، الان درست پیاده شده.


خب حالا چرا بقیه ندارن چون باید برای همه اضافه کنیم یعنی در یه جدولی و اینکه میخوام بهم توضیح بدی تو اون جدول ها باید چی کار کنیم و اینکه برای انتقال بعضی محصولات باید یک سیدر بسازی 

بردیا دقیقاً رسیدیم به جایی که باید سیستم تخفیف را کامل کنیم. الان فقط یک محصول تخفیف دارد چون فقط برای آن محصول رکورد تخفیف ساخته‌ایم.

نکته مهم:
❌ نباید داخل جدول products یا product_variants ستون تخفیف اضافه کنیم.

چون یک محصول می‌تواند:

امروز تخفیف داشته باشد

فردا نداشته باشد

چند نوع تخفیف داشته باشد

تخفیف زمان‌دار داشته باشد

کد تخفیف داشته باشد

پس طراحی فعلی درست است.

ساختار فعلی باید اینطوری باشد:
جدول discounts
مثلاً:

id	name	type	value
1	Flash Sale	percent	20
2	Summer Sale	fixed	500
3	Black Friday	percent	50
اینجا فقط قانون تخفیف نگهداری می‌شود.

اتصال تخفیف به محصول
احتمالاً داری:

discountables
این جدول polymorphic است:

discount_id	discountable_type	discountable_id
1	Product	1
2	Product	5
3	ProductVariant	10
یعنی:

تخفیف شماره ۱ روی محصول آیفون است.

حالا چرا بقیه محصول‌ها ندارند؟
چون در جدول بالا رکورد ندارند.

مثلاً:

آیفون 16:

product_id = 1

discountables:

discount_id = 1
discountable_type = Product
discountable_id = 1
پس تخفیف می‌گیرد.

ولی آیفون 15:

product_id = 2
هیچ رکوردی ندارد.

پس:

base_price = price
discount_percent = 0
حالا برای انتقال بعضی محصولات چه کار کنیم؟
اینجا Seeder به درد می‌خورد.

مثلاً می‌خواهی:

۲۰ محصول موبایل تخفیف داشته باشند

۱۰ محصول لپ‌تاپ تخفیف داشته باشند

یک Seeder می‌سازی:

DiscountSeeder
مثلاً:

php artisan make:seeder DiscountSeeder
داخلش:

use App\Models\Product;

class DiscountSeeder extends Seeder
{
    public function run()
{

    $discount = Discount::create([
        'name'=>'Summer Sale',
        'type'=>'percent',
        'value'=>20,
        'starts_at'=>now(),
        'ends_at'=>now()->addDays(7),
        'is_active'=>true
    ]);


    $products = Product::whereIn('id',[
        1,
        5,
        10,
        20
    ])->get();


    foreach($products as $product){

        $product->discounts()->attach(
            $discount->id
        );

    }

}
}
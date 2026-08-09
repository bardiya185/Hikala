use App\Models\Product;
use App\Models\Discount;


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
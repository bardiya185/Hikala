<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BannerPositionResource;
use App\Models\Banner;
use App\Models\BannerPosition;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    // ✅ گرفتن بنرهای یک position خاص
    public function byPosition(string $key)
    {
        $position = BannerPosition::where('key', $key)
            ->where('is_active', true)
            ->with(['activeBanners' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new BannerPositionResource($position),
        ]);
    }

    // ✅ گرفتن همه بنرهای فعال گروه‌بندی شده
    public function all()
    {
        $positions = BannerPosition::where('is_active', true)
            ->with(['activeBanners'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => BannerPositionResource::collection($positions),
        ]);
    }

    // ✅ ثبت کلیک (برای آمار)
    public function trackClick(Banner $banner)
    {
        $banner->increment('click_count');

        return response()->json([
            'success' => true,
            'url' => $banner->url,
        ]);
    }
}
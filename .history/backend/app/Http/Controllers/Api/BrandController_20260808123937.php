// app/Http/Controllers/Api/BannerController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BannerPositionResource;
use App\Models\Banner;
use App\Models\BannerPosition;
use App\Services\Banner\BannerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BannerController extends Controller
{
    public function __construct(
        private BannerService $bannerService
    ) {}

    // 🌐 PUBLIC
    public function all()
    {
        $positions = BannerPosition::where('is_active', true)
            ->with('activeBanners.linkable') // 🔥 eager load
            ->get();

        return response()->json([
            'success' => true,
            'data' => BannerPositionResource::collection($positions),
        ]);
    }

    public function byPosition(string $key)
    {
        $position = BannerPosition::where('key', $key)
            ->where('is_active', true)
            ->with('activeBanners.linkable')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new BannerPositionResource($position),
        ]);
    }

    public function trackClick(Banner $banner)
    {
        $this->bannerService->trackClick($banner);

        return response()->json([
            'success' => true,
            'url' => $banner->url,
        ]);
    }

    // 🔒 ADMIN
    public function adminIndex(Request $request)
    {
        $banners = $this->bannerService->all(
            $request->get('position_id'),
            min($request->get('per_page', 20), 100)
        );

        return BannerResource::collection($banners);
    }

    public function store(StoreBannerRequest $request)
    {
        $banner = $this->bannerService->create(
            $request->validated(),
            $request->file('image'),
            $request->file('mobile_image')
        );

        return (new BannerResource($banner))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Banner $banner)
    {
        return new BannerResource($banner->load('position', 'linkable'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $banner = $this->bannerService->update(
            $banner,
            $request->validated(),
            $request->file('image'),
            $request->file('mobile_image')
        );

        return new BannerResource($banner);
    }

    public function destroy(Banner $banner)
    {
        $this->bannerService->delete($banner);

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully',
        ]);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assets\FetchAssetsRequest;
use App\Http\Requests\Assets\StoreAssetRequest;
use App\Http\Requests\Assets\UpdateAssetRequest;
use App\Http\Resources\Asset\AssetCollection;
use App\Models\Asset;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchAssetsRequest $request
     * @return AssetCollection
     */
    public function index(FetchAssetsRequest $request): AssetCollection
    {
        return new AssetCollection($request->getData());
    }

    public function allAssets()
    {
        return  Asset::all();
    }

    public function updateAll(Request $request)
    {
        $request->validate([
           'assets' => "required|array",
           'assets.*.id' => "required|integer|exists:assets,id",
           'assets.*.a_number' => "nullable|string",
           'assets.*.description' => "nullable|string",
           'assets.*.serial_number' => "nullable|string",
        ]);

        foreach((array) $request->input('assets') as $asset) {
            $data =collect($asset)->only('a_number','description','serial_number')->toArray();
            $data['updated_by_id'] = Auth::id();
            Asset::where('id',$asset['id'])->update($data);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAssetRequest $request
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(StoreAssetRequest $request)
    {
        return $request->store();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAssetRequest $request
     * @param Asset              $asset
     * @return Asset
     * @throws ValidationException
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        return $request->update($asset);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Asset $asset
     * @return Response
     */
    public function destroy(Asset $asset): Response
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teamviewer\FetchTeamviewerRequest;
use App\Http\Resources\Teamviewer\TeamviewerCollection;
use App\Models\Deployment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TeamViewerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchTeamviewerRequest $request
     * @param Deployment             $deployment
     * @return TeamViewerCollection
     */
    public function index(FetchTeamviewerRequest $request, Deployment $deployment): TeamViewerCollection
    {
        return new TeamViewerCollection($request->getData($deployment));
    }
}

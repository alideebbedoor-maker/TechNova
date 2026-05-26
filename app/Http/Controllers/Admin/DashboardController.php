<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\DashboardManager;
use App\Http\Resources\DashboardResource;

class DashboardController extends Controller
{
    public function index(DashboardManager $dashboardManager)
{
    return new DashboardResource($dashboardManager->getMetrics());
}
}
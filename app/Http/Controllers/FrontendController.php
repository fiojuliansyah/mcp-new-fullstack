<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $classPreviews = Classroom::with(['schedules.form', 'subject'])
            ->whereHas('schedules')
            ->latest()
            ->take(6)
            ->get();

        $quickStudies = Classroom::with(['schedules.form', 'subject'])
            ->whereHas('schedules')
            ->inRandomOrder()
            ->take(6)
            ->get();

        $newsUpdates = [
            [
                'image' => '/frontend/assets/images/news-card-example.png',
                'title' => 'New Academic Year Started!',
            ],
            [
                'image' => '/frontend/assets/images/news-card-example.png',
                'title' => 'Math Challenge Week!',
            ],
        ];

        return view('welcome', compact('classPreviews', 'quickStudies', 'newsUpdates'));
    }
}

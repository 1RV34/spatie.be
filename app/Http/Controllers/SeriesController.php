<?php

namespace App\Http\Controllers;

use App\Models\Series;

class SeriesController
{
    public function show(Series $series)
    {
        $title = $series->title;
        $description = $series->description;

        $series->load(['purchasables.product']);

        return view('front.pages.courses.series', compact(
            'title',
            'description',
            'series',
        ));
    }
}

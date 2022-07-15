<?php

use Desoft\DVoyager\Models\DVoyagerVisitHit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

if (!function_exists('hitPage')) {
    function hitPage($page)
    {
        $session_id = Session::getId();
        $visitas = DVoyagerVisitHit::where('session_id', $session_id)->where('page', $page)->get();
        if (count($visitas) == 0) {
            $visit_page = new DVoyagerVisitHit();
            $visit_page->page = $page;
            $visit_page->session_id = $session_id;
            $visit_page->save();
        }
    }
}

if (!function_exists('visitsOfPage')) {
    function visitsOfPage($page)
    {
        $visitas = DVoyagerVisitHit::where('page', $page)->get();
        return count($visitas);
    }
}

if (!function_exists('visitsAll')) {
    function visitsAll()
    {
        $contador = DVoyagerVisitHit::all();

        return count($contador);
    }
}

if (!function_exists('visitsPerCurrentDay')) {
    function visitsPerCurrentDay()
    {
        $contador = DVoyagerVisitHit::all();
        $day = 0;
        foreach ($contador as $item) {
            if ($item->created_at->isoFormat('D') == date('d') && $item->created_at->isoFormat('M') == date('m') && $item->created_at->isoFormat('Y') == date('Y'))
                $day++;
        }
        return $day;
    }
}

if (!function_exists('visitsPerCurrentMonth')) {
    function visitsPerCurrentMonth()
    {
        $contador = DVoyagerVisitHit::all();
        $month = 0;
        foreach ($contador as $item) {
            if ($item->created_at->isoFormat('M') == date('m') && $item->created_at->isoFormat('Y') == date('Y'))
                $month++;
        }
        return $month;
    }
}

if (!function_exists('visitsPerCurrentYear')) {
    function visitsPerCurrentYear()
    {
        $contador = DVoyagerVisitHit::all();
        $year = 0;
        foreach ($contador as $item) {
            if ($item->created_at->isoFormat('Y') == date('Y'))
                $year++;
        }
        return $year;
    }
}

if (!function_exists('nMostVisitsPages')) {
    function nMostVisitsPages($n = 5)
    {
        $r = DB::table('visits_hits')->select('page', DB::raw('count(page) as Total'))
            ->groupBy('page')
            ->orderBy('Total', 'desc')
            ->get()->take($n);

        return $r;
    }
}
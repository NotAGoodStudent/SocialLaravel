<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    function getTopics()
    {
        $topics = Topic::all();
        return $topics;
    }
}

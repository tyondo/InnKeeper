<?php

namespace Tyondo\Innkeeper\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TagsManagerController extends Controller
{
    public function listTags(Request $request){
        $list = $this->tagsManagerService()->getTagsList();
        return view('enum-manager::pages.tags.list')->with([
            'items' => $list,
            'route' => route('enums.tags.store')
        ]);
    }
}

<?php
namespace App\Http\Controllers\Api;

use App\Helper\Result;
use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;

/**
 *
 */
class LinksController extends Controller
{
    public function __construct(Link $links, Result $result)
    {
        $this->links  = $links;
        $this->result = $result;
    }

    public function index()
    {
        return "Index of all links";
    }

    public function show($short_url)
    {
        $link = $this->links->getExistingUrl($short_url);
        if (!empty($link)) {
            return $this->result->make($link, null, 200);
        }
        return $this->result->make();
    }

    public function store(Request $req)
    {
        $req->all();
        $link = $this->links->store($req->all());
        if ($link['status'] == 'success') {
            return $this->result->make($link['data'], null, 200);
        }
        return $this->result->make($link['data'], null, 400);
    }
}

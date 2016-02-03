<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Link extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'links';

    protected $rules = [
        'url' => 'required|url',
    ];

    public function validation($data)
    {
        $validation = Validator::make($data, $this->rules);
        if ($validation->fails()) {
            return [
                'status' => 'error',
                'data'   => $validation->messages()->all(),
            ];
        }
        return [
            'status' => 'success',
        ];
    }

    public function store($data)
    {
        $checkDb = $this->getByLongUrl($data['url']);
        if (!empty($checkDb)) {
            return [
                'status' => 'success',
                'data'   => $checkDb,
            ];
        }
        $validate = $this->validation($data);
        if ($validate['status'] === 'error') {
            return $validate;
        } else {
            $this->user_id = null;
            if (Auth::user()) {
                $this->user_id = Auth::user()->id;
            }
            $this->url         = $data['url'];
            $this->description = (!empty($data['description'])) ? $data['description'] : null;
            $this->shorten_url = $this->getShortenUrl();
            $this->clicks      = 0;
            $this->save();
            return [
                'status' => 'success',
                'data'   => $this,
            ];
        }
    }

    public function getByUser()
    {
        return $this->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
    }

    protected function getShortenUrl()
    {
        $shorten_url = '';
        $exist       = false;
        while ($exist == false) {
            $shorten_url = Str::random('4');
            if ($this->getExistingUrl($shorten_url, 'check') == 0) {
                $exist = true;
            }
        }
        return $shorten_url;
    }

    public function getExistingUrl($shorten_url, $method = 'fetch')
    {
        $data = $this->where('shorten_url', $shorten_url);
        if ($method == 'fetch') {
            return $data->first();
        }
        return $data->count();
    }

    protected function getByLongUrl($long_url)
    {
        return $this->where('url', $long_url)->first();
    }

}

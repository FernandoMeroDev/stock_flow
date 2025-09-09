<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\DownloadRequest;

class DownloadController extends Controller
{
    public function __invoke(DownloadRequest $request)
    {
        $validated = $request->validated();
        dump($validated);
        return 'Hola Mundo!';
    }
}

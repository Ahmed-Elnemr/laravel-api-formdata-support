<?php

namespace AhmedElnemr\LaravelApiFormdataSupport\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use AhmedElnemr\LaravelApiFormdataSupport\Services\FormData;

class FormDataMiddleware
{
    private array $disallowMethods = [
        Request::METHOD_GET,
        Request::METHOD_HEAD,
        Request::METHOD_POST,
    ];

    public function handle($request, Closure $next)
    {
        if (in_array($request->getRealMethod(), $this->disallowMethods))
            return $next($request);

        $this->parse($request);

        return $next($request);
    }

    private function parse($request)
    {
        if (!preg_match('/multipart\/form-data/', $request->headers->get('content-type'))) return;

        $form = new FormData($request->getContent());
        $request->request->add($form->inputs);
        $request->files->add($form->files);

        $files = $this->handleNestedFiles($request->files->all());
        $request->files->replace($files);
    }

    private function handleNestedFiles(array $files): array
    {
        $data = [];

        foreach ($files as $key => $file)
            $data[$key] = is_array($file) ? $this->handleNestedFiles($file) : UploadedFile::createFromBase($file, true);

        return $data;
    }
}


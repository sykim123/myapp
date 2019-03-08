<?php

namespace App;

use File;

class Documentation
{
    public function get($file = 'documentation.md'){
        $content = File::get($this->path($file));

        return $this->replaceLinks($content);
    }

    public function image($file) {
        $reqEtag = \Request::getEtags();
        $genEtag = $this->docs->etag($file);

        if(isset($reqEtag[0])) {
            if($reqEtag[0] === $genEtag) {
                return response('', 304);
            }
        }

        $image = $this->docs->image($file);

        return response($image->encode('jpeg'), 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' =>'public, max-age=0',
            'Etag' => $genEtag,
        ]);
        //return \Image::make($this->path($file, 'docs/images'));
    }

    protected function path($file, $dir='docs') {
        $file = ends_with($file, ['.md', '.jpeg']) ? $file : $file . '.md';
        $path = base_path($dir . DIRECTORY_SEPARATOR . $file);

        if(! File::exists($path)) {
            abort(404, '요청하신 파일이 없습니다.');
        }

        return $path;
    }

    public function etag($file) {


        $image = $this->docs->image($file);

        $lastModified = File::lastModified($this->path($file, 'docs/images'));

        return md5($file . $lastModified);
    }



    protected function replaceLinks($content) {
        return str_replace('/docs/{{version}}', '/docs', $content);
    }
}

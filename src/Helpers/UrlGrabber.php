<?php

namespace Pforret\SunnySideUp\Helpers;

use Pforret\SunnySideUp\Exceptions\EmptyResponseError;

trait UrlGrabber
{
    private string $userAgent = 'Mozilla/5.0 (iPad; CPU OS 13_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/87.0.4280.77 Mobile/15E148 Safari/604.1';

    /**
     * @throws EmptyResponseError
     */
    public function getUrl(string $url, string $referer = ''): string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_REFERER => $referer,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);
        $html = curl_exec($curl);
        curl_close($curl);

        if (! $html) {
            throw new EmptyResponseError();
        }

        return $html;
    }
}

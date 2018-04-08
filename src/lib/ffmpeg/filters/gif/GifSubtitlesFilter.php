<?php

namespace Sorry\lib\ffmpeg\filters\gif;


class GifSubtitlesFilter implements \FFMpeg\Filters\Gif\GifFilterInterface
{
    private $priority;
    private $subtitle;

    function __construct($subtitle = null, $priority = 0)
    {
        $this->subtitle = $subtitle;
        $this->priority = $priority;
    }

    public function getPriority()
    {
//must be of high priority in case theres a second input stream (artwork) to register with audio
        return $this->priority;
    }

    public function apply(\FFMpeg\Media\Gif $gif)
    {
        $subtitle = $this->subtitle;

        if (is_null($subtitle)) {
            return [];
        }

        return ['-vf', 'ass='.$subtitle];
    }
}
<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('truncate', [$this, 'cutTheContentProperly']),
        ];
    }

    public function cutTheContentProperly($content, $number)
	{
		if (strlen($content) >= $number)
		{
			$content = substr($content, 0, $number);
			$content = substr($content, 0, strrpos($content, " "));
			$content = $content . " [...]";
		}
		return $content;
	}
}

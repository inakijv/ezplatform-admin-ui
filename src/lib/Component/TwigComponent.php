<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Component;

use Twig\Environment;

class TwigComponent implements Renderable
{
    /** @var string */
    protected $template;

    /** @var Environment */
    protected $twig;

    /**
     * @param Environment $twig
     * @param string $template
     */
    public function __construct(
        Environment $twig,
        string $template
    ) {
        $this->twig = $twig;
        $this->template = $template;
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function render(array $parameters = []): string
    {
        return $this->twig->render($this->template, $parameters);
    }
}

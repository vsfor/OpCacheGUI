<?php
/**
 * The class is responsible for rendering HTML templates
 *
 * PHP version 5.5
 *
 * @category   OpCacheGUI
 * @package    Presentation
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2014 Pieter Hordijk <https://github.com/PeeHaa>
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    2.0.0
 */
namespace OpCacheGUI\Presentation;

use OpCacheGUI\I18n\Translator;

/**
 * The class is responsible for rendering HTML templates
 *
 * @category   OpCacheGUI
 * @package    Presentation
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class Html extends Template
{
    /**
     * @var string The base (skeleton) page in which all templates will get rendered
     */
    private $baseTemplate;

    /**
     * Creates instance
     *
     * @param string $templateDirectory               The directory where all the templates are stored
     * @param string $baseTemplate                    The base (skeleton) page in which all templates will get rendered
     * @param \OpCacheGui\I18n\Translator $translator The translation service
     */
    public function __construct($templateDirectory, $baseTemplate, Translator $translator)
    {
        parent::__construct($templateDirectory, $translator);

        $this->baseTemplate = $baseTemplate;
    }

    /**
     * Renders a template
     *
     * @param string $template The template to render
     * @param array  $data     The data to use in the template
     */
    public function render($template, array $data = [])
    {
        $this->variables = $data;

        $this->variables['content'] = $this->renderTemplate($template);

        return $this->renderTemplate($this->baseTemplate);
    }

    /**
     * Renders the template file using output buffering
     *
     * @param string $template The template to render
     *
     * @return string The rendered template
     */
    private function renderTemplate($template)
    {
        ob_start();
        require $this->templateDirectory . '/' . $template;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Magically get template variables, because magic that's why
     *
     * Disclaimer: I am fully aware this kinda sucks and will bite me in the arse
     *             at some point, so don't bother bugging me about this :-)
     *
     * @param mixed The key of which to get the data
     *
     * @return mixed The value which belongs to the key provided
     */
    public function __get($key)
    {
        return $this->variables[$key];
    }
}
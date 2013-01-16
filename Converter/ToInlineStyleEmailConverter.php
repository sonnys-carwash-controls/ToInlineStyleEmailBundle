<?php
/*
 * This file is part of ToInlineStyleEmailBundle.
 *
 * (c) Roberto Trunfio <roberto@trunfio@it>
 * 
 * Part of the functions and fields description is taken from CSSToInlineStyle 
 * project by Tijs Verkoyen <php-css-to-inline-styles@verkoyen.eu> 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ToInlineStyleEmailBundle\Converter;

use Symfony\Component\Templating\EngineInterface;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * ToInlineStyleEmailConverter is a shortcut for using in Symfony2 the utility 
 * CssToInlineStyles developed by Tijs Verkoyen.
 *  
 * The suggested use of this utility is as a service.
 *
 * @author Roberto Trunfio <roberto@trunfio@it>
 */
class ToInlineStyleEmailConverter {
    
    /**
     * The EngineInterface used to render the templates. This is optional.
     * 
     * @var Symfony\Component\Templating\EngineInterface
     */
    private $templating_engine;
    
    /**
     * The class used for CSS-to-inline-style conversion.
     * 
     * @var TijsVerkoyen\CssToInlineStyles\CssToInlineStyles
     */
    protected $cssToInlineStyles;
    /**
     * A string that represents the HTML file to be sent. Twig templates must be
     * rendered and then passed to this class.
     * 
     * @var string 
     */
    protected $html;
    /**
     * A string that contains the CSS for the HTML file.
     * 
     * @var string 
     */
    protected $css;
    
    
    /**
     * Should the generated HTML be cleaned
     *
     * @var	bool
     */
    private $cleanup = false;

    /**
     * The encoding to use.
     *
     * @var	string
     */
    protected $encoding = 'UTF-8';
    
    /**
     * Use inline-styles block as CSS
     *
     * @var	bool
     */
    protected $useInlineStylesBlock = false;

    /*
     * Strip original style tags
     *
     * @var bool
     */
    protected $stripOriginalStyleTags = false;
    

    /**
     * Construct the service.
     * 
     * @param Symfony\Component\Templating\EngineInterface[optional] $templating_engine is the engine 
     * for twig templates. This is optional. Set this param when configuring this 
     * class as a service.
     */
    public function __construct(EngineInterface $templating = null) {
        $this->templating_engine = $templating_engine;
        $this->cssToInlineStyles = new CssToInlineStyles();             
    }
    
    
      
    /**
     * If set to true, this function will activate the removal of the 
     * IDs and classes inside the HTML document during conversion.
     * 
     * This option is false by default.
     * 
     *
     * @param  bool[optional] $on Should we enable cleanup?
     */
    public function setCleanup($on = true) {
        $this->cleanup = (bool) $on;
        $this->cssToInlineStyles->setCleanup($this->cleanup);
    }
    
    /**
     * Set use of inline styles block
     * If this is enabled the class will use the style-block in the HTML.
     * 
     * This option is false by default.
     *
     * @param  bool[optional] $on Should we process inline styles?
     */
    public function setUseInlineStylesBlock($on = true) {
        $this->useInlineStylesBlock = (bool) $on;
        $this->cssToInlineStyles->setUseInlineStylesBlock($this->useInlineStylesBlock);
    }
    
    /**
     * Set strip original style tags.
     * If this is enabled the class will remove all style tags in the HTML.
     * 
     * This option is false by default.
     *
     * @param  bool[optional] $onShould we process inline styles?
     */
    public function setStripOriginalStyleTags($on = true) {
        $this->stripOriginalStyleTags = (bool) $on;
        $this->cssToInlineStyles->setStripOriginalStyleTags($this->stripOriginalStyleTags);
    }
    
    /**
     * Set the encoding to use with the DOMDocument. Default encoding value is "UTF-8".
     *
     * @param  string $encoding The encoding to use.
     */
    public function setEncoding($encoding) {
        $this->encoding = (string) $encoding;
        $this->cssToInlineStyles->setEncoding($this->encoding);
    }
    
    /**
     * Set the string which represents the CSS for the HTML file to be sent as email. 
     * 
     * @param string $css
     */
    public function setCSS($css){
        $this->css = $css;
        $this->cssToInlineStyles->setCSS($this->css);
    }
    
    /**
     * Set the string which represents the HTML file to be sent as email. 
     * 
     * @param string $html
     */
    public function setHTML($html){
        $this->html = $html;
        $this->cssToInlineStyles->setHTML($this->html);
    }
    
    /**
     * Set the string which represents the HTML file to be sent as email by rendering a template. 
     * 
     * @param string $view is the view to be rendered, e.g. Acme:MyBundle:MyController:index.html.twig
     * @param array[optional] $options the array of options to be used for template rendering. This field is optional
     * @throws MissingTemplatingEngineException The TwigEngine must be passed to the constructor, otherwise an exception is thrown
     */
    public function setHTMLByView($view, array $options = null){
        if(!is_object($this->templating_engine)) throw new MissingTemplatingEngineException("To use this function, a TwigEngine object must be passed to the constructor (use @templating to the the TwigEngine.");
        if(is_array($options)) setHTML($this->renderView($view, $options));        
        else setHTML($this->renderView($view));        
    }


    /**
     * Generate the HTML ready to be sent as email.
     * 
     * @param  bool[optional] $outputXHTML Should we output valid XHTML?
     * @return string return the HTML ready to be sent with an inline-style
     * @throws MissingParamExceptionException the HTML and CSS are mandatory.
     */
    public function generateStyledHTML($outputXHTML = false){
        if(is_null($this->html))throw new MissingParamExceptionException("The HTML must be set");
        if(!is_string($this->html))throw new MissingParamExceptionException("The HTML must be a valid string");
        if(!is_string($this->css))throw new MissingParamExceptionException("The CSS must be set");
        if(!is_string($this->css))throw new MissingParamExceptionException("The CSS must be a valid string");        
        return  $this->cssToInlineStyles->convert($outputXHTML);       
    }
}

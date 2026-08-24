<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;


class __TwigTemplate_a3fc30a39e32aa34375f7af4d3760a9a extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        if (($context["url_params"] ?? null)) {
            yield "    <li class=\"fast_filter";
            if (($context["is_root_node"] ?? null)) {
                yield " db_fast_filter";
            }
            yield "\">
        <form class=\"ajax fast_filter\">
            ";
            yield PhpMyAdmin\Url::getHiddenInputs(($context["url_params"] ?? null));
            yield "
            <div class=\"input-group\">
              <input
                  class=\"searchClause form-control\"
                  type=\"text\"
                  name=\"";
            yield ((($context["is_root_node"] ?? null)) ? ("searchClause") : ("searchClause2"));
            yield "\"
                  accesskey=\"q\"
                  aria-label=\"";
yield _gettext("Type to filter these, Enter to search all");
            yield "\"
                  placeholder=\"";
yield _gettext("Type to filter these, Enter to search all");
            yield "\"
              >
              <button
                class=\"btn btn-outline-secondary searchClauseClear\"
                type=\"button\" aria-label=\"";
yield _gettext("Clear fast filter");
            yield "\">X</button>
            </div>
        </form>
    </li>
";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "navigation/tree/fast_filter.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  73 => 16,  66 => 12,  62 => 11,  56 => 9,  48 => 4,  40 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "navigation/tree/fast_filter.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\navigation\\tree\\fast_filter.twig");
    }
}

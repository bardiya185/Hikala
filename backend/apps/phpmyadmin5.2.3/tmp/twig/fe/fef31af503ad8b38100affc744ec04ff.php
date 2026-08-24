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


class __TwigTemplate_9025260da388efe73709212f37864a37 extends Template
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
        if ((($context["max_count"] ?? null) < ($context["count"] ?? null))) {
            yield "<div class=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::join(($context["classes"] ?? null), " "), "html", null, true);
            yield "\">
  ";
            if ((($context["frame"] ?? null) != "frame_navigation")) {
                yield "    ";
yield _gettext("Page number:");
                yield "  ";
            }
            yield "
  ";
            if ((($context["position"] ?? null) > 0)) {
                yield "    <a href=\"";
                yield ($context["script"] ?? null);
                yield "\" data-post=\"";
                yield PhpMyAdmin\Url::getCommon(Twig\Extension\CoreExtension::merge(($context["url_params"] ?? null), [($context["param_name"] ?? null) => 0]), "", false);
                yield "\"";
                yield (((($context["frame"] ?? null) == "frame_navigation")) ? (" class=\"ajax\"") : (""));
                yield " title=\"";
yield _pgettext("First page", "Begin");
                yield "\">
      ";
                if (PhpMyAdmin\Util::showIcons("TableNavigationLinksMode")) {
                    yield "        &lt;&lt;
      ";
                }
                yield "      ";
                if (PhpMyAdmin\Util::showText("TableNavigationLinksMode")) {
                    yield "        ";
yield _pgettext("First page", "Begin");
                    yield "      ";
                }
                yield "    </a>
    <a href=\"";
                yield ($context["script"] ?? null);
                yield "\" data-post=\"";
                yield PhpMyAdmin\Url::getCommon(Twig\Extension\CoreExtension::merge(($context["url_params"] ?? null), [($context["param_name"] ?? null) => (($context["position"] ?? null) - ($context["max_count"] ?? null))]), "", false);
                yield "\"";
                yield (((($context["frame"] ?? null) == "frame_navigation")) ? (" class=\"ajax\"") : (""));
                yield " title=\"";
yield _pgettext("Previous page", "Previous");
                yield "\">
      ";
                if (PhpMyAdmin\Util::showIcons("TableNavigationLinksMode")) {
                    yield "        &lt;
      ";
                }
                yield "      ";
                if (PhpMyAdmin\Util::showText("TableNavigationLinksMode")) {
                    yield "        ";
yield _pgettext("Previous page", "Previous");
                    yield "      ";
                }
                yield "    </a>
  ";
            }
            yield "
  <form action=\"";
            yield ($context["script"] ?? null);
            yield "\" method=\"post\">
    ";
            yield PhpMyAdmin\Url::getHiddenInputs(($context["url_params"] ?? null));
            yield "

    ";
            yield ($context["page_selector"] ?? null);
            yield "
  </form>

  ";
            if (((($context["position"] ?? null) + ($context["max_count"] ?? null)) < ($context["count"] ?? null))) {
                yield "    <a href=\"";
                yield ($context["script"] ?? null);
                yield "\" data-post=\"";
                yield PhpMyAdmin\Url::getCommon(Twig\Extension\CoreExtension::merge(($context["url_params"] ?? null), [($context["param_name"] ?? null) => (($context["position"] ?? null) + ($context["max_count"] ?? null))]), "", false);
                yield "\"";
                yield (((($context["frame"] ?? null) == "frame_navigation")) ? (" class=\"ajax\"") : (""));
                yield " title=\"";
yield _pgettext("Next page", "Next");
                yield "\">
      ";
                if (PhpMyAdmin\Util::showText("TableNavigationLinksMode")) {
                    yield "        ";
yield _pgettext("Next page", "Next");
                    yield "      ";
                }
                yield "      ";
                if (PhpMyAdmin\Util::showIcons("TableNavigationLinksMode")) {
                    yield "        &gt;
      ";
                }
                yield "    </a>
    ";
                $context["last_pos"] = ((int) floor((($context["count"] ?? null) / ($context["max_count"] ?? null))) * ($context["max_count"] ?? null));
                yield "    <a href=\"";
                yield ($context["script"] ?? null);
                yield "\" data-post=\"";
                yield PhpMyAdmin\Url::getCommon(Twig\Extension\CoreExtension::merge(($context["url_params"] ?? null), [($context["param_name"] ?? null) => (((($context["last_pos"] ?? null) == ($context["count"] ?? null))) ? ((($context["count"] ?? null) - ($context["max_count"] ?? null))) : (($context["last_pos"] ?? null)))]), "", false);
                yield "\"";
                yield (((($context["frame"] ?? null) == "frame_navigation")) ? (" class=\"ajax\"") : (""));
                yield " title=\"";
yield _pgettext("Last page", "End");
                yield "\">
      ";
                if (PhpMyAdmin\Util::showText("TableNavigationLinksMode")) {
                    yield "        ";
yield _pgettext("Last page", "End");
                    yield "      ";
                }
                yield "      ";
                if (PhpMyAdmin\Util::showIcons("TableNavigationLinksMode")) {
                    yield "        &gt;&gt;
      ";
                }
                yield "    </a>
  ";
            }
            yield "</div>
";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "list_navigator.twig";
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
        return array (  197 => 51,  193 => 49,  189 => 47,  186 => 46,  183 => 45,  180 => 44,  178 => 43,  167 => 42,  165 => 41,  162 => 40,  158 => 38,  155 => 37,  152 => 36,  149 => 35,  147 => 34,  136 => 33,  134 => 32,  128 => 29,  123 => 27,  119 => 26,  116 => 25,  112 => 23,  109 => 22,  106 => 21,  103 => 20,  99 => 18,  97 => 17,  87 => 16,  84 => 15,  81 => 14,  78 => 13,  75 => 12,  71 => 10,  69 => 9,  58 => 8,  56 => 7,  53 => 6,  50 => 5,  47 => 4,  45 => 3,  40 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "list_navigator.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\list_navigator.twig");
    }
}

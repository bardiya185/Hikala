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


class __TwigTemplate_24f3dbd8c2fa4441e735ad2f8fde59aa extends Template
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
        if ( !($context["is_ajax"] ?? null)) {
            yield "  </div>
";
        }
        if (( !($context["is_ajax"] ?? null) &&  !($context["is_minimal"] ?? null))) {
            yield "  ";
            if ( !Twig\Extension\CoreExtension::testEmpty(($context["self_url"] ?? null))) {
                yield "    <div id=\"selflink\" class=\"d-print-none\">
      <a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["self_url"] ?? null), "html", null, true);
                yield "\" title=\"";
yield _gettext("Open new phpMyAdmin window");
                yield "\" target=\"_blank\" rel=\"noopener noreferrer\">
        ";
                if (PhpMyAdmin\Util::showIcons("TabsMode")) {
                    yield "          ";
                    yield PhpMyAdmin\Html\Generator::getImage("window-new", _gettext("Open new phpMyAdmin window"));
                    yield "
        ";
                } else {
                    yield "          ";
yield _gettext("Open new phpMyAdmin window");
                    yield "        ";
                }
                yield "      </a>
    </div>
  ";
            }
            yield "
  <div class=\"clearfloat d-print-none\" id=\"pma_errors\">
    ";
            yield ($context["error_messages"] ?? null);
            yield "
  </div>

  ";
            yield ($context["scripts"] ?? null);
            yield "

  ";
            if (($context["is_demo"] ?? null)) {
                yield "    <div id=\"pma_demo\" class=\"d-print-none\">
      ";
                $___internal_parse_34_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                    yield "        <a href=\"";
                    yield PhpMyAdmin\Url::getFromRoute("/");
                    yield "\">";
yield _gettext("phpMyAdmin Demo Server");
                    yield ":</a>
        ";
                    if ( !Twig\Extension\CoreExtension::testEmpty(($context["git_revision_info"] ?? null))) {
                        yield "          ";
                        $context["revision_info"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                            yield "<a target=\"_blank\" rel=\"noopener noreferrer\" href=\"";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(PhpMyAdmin\Core::linkURL(CoreExtension::getAttribute($this->env, $this->source, ($context["git_revision_info"] ?? null), "revisionUrl", [], "any", false, false, false, 29)), "html", null, true);
                            yield "\">";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["git_revision_info"] ?? null), "revision", [], "any", false, false, false, 29), "html", null, true);
                            yield "</a>";
                            return; yield '';
                        })())) ? '' : new Markup($tmp, $this->env->getCharset());
                        yield "          ";
                        $context["branch_info"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                            yield "<a target=\"_blank\" rel=\"noopener noreferrer\" href=\"";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(PhpMyAdmin\Core::linkURL(CoreExtension::getAttribute($this->env, $this->source, ($context["git_revision_info"] ?? null), "branchUrl", [], "any", false, false, false, 32)), "html", null, true);
                            yield "\">";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["git_revision_info"] ?? null), "branch", [], "any", false, false, false, 32), "html", null, true);
                            yield "</a>";
                            return; yield '';
                        })())) ? '' : new Markup($tmp, $this->env->getCharset());
                        yield "          ";
                        yield Twig\Extension\CoreExtension::sprintf(_gettext("Currently running Git revision %1\$s from the %2\$s branch."), ($context["revision_info"] ?? null), ($context["branch_info"] ?? null));
                        yield "
        ";
                    } else {
                        yield "          ";
yield _gettext("Git information missing!");
                        yield "        ";
                    }
                    yield "      ";
                    return; yield '';
                })())) ? '' : new Markup($tmp, $this->env->getCharset());
                yield $this->env->getFilter('notice')->getCallable()($___internal_parse_34_);
                yield "    </div>
  ";
            }
            yield "
  ";
            yield ($context["footer"] ?? null);
            yield "
";
        }
        if ( !($context["is_ajax"] ?? null)) {
            yield "  </body>
</html>
";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "footer.twig";
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
        return array (  162 => 45,  160 => 44,  155 => 42,  152 => 41,  148 => 39,  146 => 25,  142 => 38,  139 => 37,  136 => 36,  130 => 34,  122 => 32,  119 => 31,  111 => 29,  108 => 28,  106 => 27,  99 => 26,  97 => 25,  94 => 24,  92 => 23,  87 => 21,  81 => 18,  77 => 16,  72 => 13,  69 => 12,  66 => 11,  60 => 9,  58 => 8,  52 => 7,  49 => 6,  46 => 5,  44 => 4,  40 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "footer.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\footer.twig");
    }
}

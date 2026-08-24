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


class __TwigTemplate_6d0fd75884701dac3fcdaf05e6344b5c extends Template
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
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["files"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            yield "  <script data-cfasync=\"false\" type=\"text/javascript\" src=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_dir"] ?? null), "html", null, true);
            yield "js/";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((((is_string($__internal_compile_0 = CoreExtension::getAttribute($this->env, $this->source, $context["file"], "filename", [], "any", false, false, false, 3)) && is_string($__internal_compile_1 = "vendor/") && str_starts_with($__internal_compile_0, $__internal_compile_1)) || (is_string($__internal_compile_2 = CoreExtension::getAttribute($this->env, $this->source, $context["file"], "filename", [], "any", false, false, false, 3)) && is_string($__internal_compile_3 = "messages.php") && str_starts_with($__internal_compile_2, $__internal_compile_3)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["file"], "filename", [], "any", false, false, false, 3)) : (("dist/" . CoreExtension::getAttribute($this->env, $this->source, $context["file"], "filename", [], "any", false, false, false, 3)))), "html", null, true);
            ((CoreExtension::inFilter(".php", CoreExtension::getAttribute($this->env, $this->source, $context["file"], "filename", [], "any", false, false, false, 4))) ? (yield PhpMyAdmin\Url::getCommon(Twig\Extension\CoreExtension::merge(CoreExtension::getAttribute($this->env, $this->source, $context["file"], "params", [], "any", false, false, false, 4), ["v" => ($context["version"] ?? null)]))) : (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(("?v=" . Twig\Extension\CoreExtension::urlencode(($context["version"] ?? null))), "html", null, true)));
            yield "\"></script>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "
<script data-cfasync=\"false\" type=\"text/javascript\">
";
        yield ($context["code"] ?? null);
        yield "
";
        if ( !Twig\Extension\CoreExtension::testEmpty(($context["files"] ?? null))) {
            yield "AJAX.scriptHandler
";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["files"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
                yield "  .add('";
                yield PhpMyAdmin\Sanitize::escapeJsString(CoreExtension::getAttribute($this->env, $this->source, $context["file"], "filename", [], "any", false, false, false, 13));
                yield "', ";
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["file"], "has_onload", [], "any", false, false, false, 13)) ? (1) : (0));
                yield ")
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield ";
\$(function() {
";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["files"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
                yield "  ";
                if (CoreExtension::getAttribute($this->env, $this->source, $context["file"], "has_onload", [], "any", false, false, false, 18)) {
                    yield "  AJAX.fireOnload('";
                    yield PhpMyAdmin\Sanitize::escapeJsString(CoreExtension::getAttribute($this->env, $this->source, $context["file"], "filename", [], "any", false, false, false, 19));
                    yield "');
  ";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "});
";
        }
        yield "// ]]>
</script>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "scripts.twig";
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
        return array (  110 => 24,  106 => 22,  96 => 19,  93 => 18,  89 => 17,  85 => 15,  74 => 13,  70 => 12,  67 => 11,  65 => 10,  61 => 9,  56 => 6,  48 => 4,  46 => 3,  42 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "scripts.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\scripts.twig");
    }
}

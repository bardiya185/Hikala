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


class __TwigTemplate_56bfe9bc2771e3d1ec81160868162ce8 extends Template
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
        if ((array_key_exists("submit_attribute", $context) && (($context["submit_attribute"] ?? null) != false))) {
            yield "    ";
            $context["attribute"] = ($context["submit_attribute"] ?? null);
            yield "    ";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source,         // line 4
($context["column_meta"] ?? null), "Extra", [], "array", true, true, false, 4) && (CoreExtension::inFilter("on update current_timestamp", Twig\Extension\CoreExtension::lower($this->env->getCharset(), (($__internal_compile_0 =         // line 5
($context["column_meta"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["Extra"] ?? null) : null))) || CoreExtension::inFilter("on update current_timestamp()", Twig\Extension\CoreExtension::lower($this->env->getCharset(), (($__internal_compile_1 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["Extra"] ?? null) : null)))))) {
            yield "    ";
            $context["attribute"] = "on update CURRENT_TIMESTAMP";
        } elseif (CoreExtension::getAttribute($this->env, $this->source,         // line 7
($context["extracted_columnspec"] ?? null), "attribute", [], "array", true, true, false, 7)) {
            yield "    ";
            $context["attribute"] = (($__internal_compile_2 = ($context["extracted_columnspec"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["attribute"] ?? null) : null);
        } else {
            yield "    ";
            $context["attribute"] = "";
        }
        $context["attribute"] = Twig\Extension\CoreExtension::upper($this->env->getCharset(), ($context["attribute"] ?? null));
        yield "<select name=\"field_attribute[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\"
    id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\">
    ";
        $context["cnt_attribute_types"] = (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["attribute_types"] ?? null)) - 1);
        yield "    ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(range(0, ($context["cnt_attribute_types"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            yield "        <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_3 = ($context["attribute_types"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3[$context["i"]] ?? null) : null), "html", null, true);
            yield "\"";
            yield (((($context["attribute"] ?? null) == Twig\Extension\CoreExtension::upper($this->env->getCharset(), (($__internal_compile_4 = ($context["attribute_types"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4[$context["i"]] ?? null) : null)))) ? (" selected=\"selected\"") : (""));
            yield ">
            ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_5 = ($context["attribute_types"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5[$context["i"]] ?? null) : null), "html", null, true);
            yield "
        </option>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "</select>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "columns_definitions/column_attribute.twig";
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
        return array (  98 => 22,  89 => 19,  85 => 18,  81 => 17,  76 => 16,  74 => 15,  68 => 14,  63 => 13,  61 => 12,  57 => 10,  53 => 8,  51 => 7,  48 => 6,  46 => 5,  45 => 4,  43 => 3,  40 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "columns_definitions/column_attribute.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\columns_definitions\\column_attribute.twig");
    }
}

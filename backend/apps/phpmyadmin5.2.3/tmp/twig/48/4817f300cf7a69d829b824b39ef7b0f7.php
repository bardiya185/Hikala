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


class __TwigTemplate_70c0cf3d8b5faef393d4d96132e9403b extends Template
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
        $context["title"] = "";
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "column_status", [], "array", true, true, false, 2)) {
            yield "    ";
            if ((($__internal_compile_0 = (($__internal_compile_1 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["column_status"] ?? null) : null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["isReferenced"] ?? null) : null)) {
                yield "        ";
                $context["title"] = (($context["title"] ?? null) . Twig\Extension\CoreExtension::sprintf(_gettext("Referenced by %s."), Twig\Extension\CoreExtension::join((($__internal_compile_2 = (($__internal_compile_3 =                 // line 5
($context["column_meta"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["column_status"] ?? null) : null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["references"] ?? null) : null), ",")));
                yield "    ";
            }
            yield "    ";
            if ((($__internal_compile_4 = (($__internal_compile_5 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["column_status"] ?? null) : null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["isForeignKey"] ?? null) : null)) {
                yield "        ";
                if ( !Twig\Extension\CoreExtension::testEmpty(($context["title"] ?? null))) {
                    yield "            ";
                    $context["title"] = (($context["title"] ?? null) . "
");
                    yield "        ";
                }
                yield "        ";
                $context["title"] = (($context["title"] ?? null) . _gettext("Is a foreign key."));
                yield "    ";
            }
        }
        if (Twig\Extension\CoreExtension::testEmpty(($context["title"] ?? null))) {
            yield "    ";
            $context["title"] = _gettext("Column");
        }
        yield "
<input id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\"
    ";
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "column_status", [], "array", true, true, false, 20) &&  !(($__internal_compile_6 = (($__internal_compile_7 =         // line 21
($context["column_meta"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["column_status"] ?? null) : null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["isEditable"] ?? null) : null))) {
            yield "        disabled=\"disabled\"
    ";
        }
        yield "    type=\"text\"
    name=\"field_name[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\"
    maxlength=\"64\"
    class=\"textfield\"
    title=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["title"] ?? null), "html", null, true);
        yield "\"
    size=\"10\"
    value=\"";
        ((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Field", [], "array", true, true, false, 30)) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_8 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["Field"] ?? null) : null), "html", null, true)) : (yield ""));
        yield "\">

";
        if ((($context["has_central_columns_feature"] ?? null) &&  !(CoreExtension::getAttribute($this->env, $this->source,         // line 33
($context["column_meta"] ?? null), "column_status", [], "array", true, true, false, 33) &&  !(($__internal_compile_9 = (($__internal_compile_10 =         // line 34
($context["column_meta"] ?? null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["column_status"] ?? null) : null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["isEditable"] ?? null) : null)))) {
            yield "    <p class=\"column_name\" id=\"central_columns_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\">
        <a data-maxrows=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["max_rows"] ?? null), "html", null, true);
            yield "\"
            href=\"#\"
            class=\"central_columns_dialog\">
            ";
yield _gettext("Pick from Central Columns");
            yield "        </a>
    </p>
";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "columns_definitions/column_name.twig";
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
        return array (  131 => 40,  124 => 36,  117 => 35,  115 => 34,  114 => 33,  113 => 32,  108 => 30,  103 => 28,  97 => 25,  94 => 24,  90 => 22,  88 => 21,  87 => 20,  81 => 19,  78 => 18,  74 => 16,  72 => 15,  68 => 13,  65 => 12,  62 => 11,  58 => 10,  55 => 9,  52 => 8,  49 => 7,  47 => 5,  45 => 4,  42 => 3,  40 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "columns_definitions/column_name.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\columns_definitions\\column_name.twig");
    }
}

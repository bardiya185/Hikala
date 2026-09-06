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


class __TwigTemplate_5ed66ee5401246d189aa103141a9ddf6 extends Template
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
        yield "<div class=\"responsivetable\">
<table id=\"table_columns\" class=\"table table-striped caption-top align-middle mb-0 noclick\">
    <caption class=\"tblHeaders\">
        ";
yield _gettext("Structure");
        yield "        ";
        yield PhpMyAdmin\Html\MySQLDocumentation::show("CREATE_TABLE");
        yield "
    </caption>
    <tr>
        <th>
            ";
yield _gettext("Name");
        yield "        </th>
        <th>
            ";
yield _gettext("Type");
        yield "            ";
        yield PhpMyAdmin\Html\MySQLDocumentation::show("data-types");
        yield "
        </th>
        <th>
            ";
yield _gettext("Length/Values");
        yield "            ";
        yield PhpMyAdmin\Html\Generator::showHint(_gettext("If column type is \"enum\" or \"set\", please enter the values using this format: 'a','b','c'…<br>If you ever need to put a backslash (\"\\\") or a single quote (\"'\") amongst those values, precede it with a backslash (for example '\\\\xyz' or 'a\\'b')."));
        yield "
        </th>
        <th>
            ";
yield _gettext("Default");
        yield "            ";
        yield PhpMyAdmin\Html\Generator::showHint(_gettext("For default values, please enter just a single value, without backslash escaping or quotes, using this format: a"));
        yield "
        </th>
        <th>
            ";
yield _gettext("Collation");
        yield "        </th>
        <th>
            ";
yield _gettext("Attributes");
        yield "        </th>
        <th>
            ";
yield _gettext("Null");
        yield "        </th>

        ";
        yield "        ";
        if ((array_key_exists("change_column", $context) &&  !Twig\Extension\CoreExtension::testEmpty(($context["change_column"] ?? null)))) {
            yield "            <th>
                ";
yield _gettext("Adjust privileges");
            yield "                ";
            yield PhpMyAdmin\Html\MySQLDocumentation::showDocumentation("faq", "faq6-39");
            yield "
            </th>
        ";
        }
        yield "
        ";
        yield "        ";
        if ( !($context["is_backup"] ?? null)) {
            yield "            <th>
                ";
yield _gettext("Index");
            yield "            </th>
        ";
        }
        yield "
        <th>
            <abbr title=\"AUTO_INCREMENT\">A_I</abbr>
        </th>
        <th>
            ";
yield _gettext("Comments");
        yield "        </th>

        ";
        if (($context["is_virtual_columns_supported"] ?? null)) {
            yield "            <th>
                ";
yield _gettext("Virtuality");
            yield "            </th>
        ";
        }
        yield "
        ";
        if (array_key_exists("fields_meta", $context)) {
            yield "            <th>
                ";
yield _gettext("Move column");
            yield "            </th>
        ";
        }
        yield "
        ";
        if (( !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["relation_parameters"] ?? null), "browserTransformationFeature", [], "any", false, false, false, 69)) && ($context["browse_mime"] ?? null))) {
            yield "            <th>
                ";
yield _gettext("Media type");
            yield "            </th>
            <th>
                <a href=\"";
            yield PhpMyAdmin\Url::getFromRoute("/transformation/overview");
            yield "#transformation\" title=\"";
yield _gettext("List of available transformations and their options");
            yield "\" target=\"_blank\">
                    ";
yield _gettext("Browser display transformation");
            yield "                </a>
            </th>
            <th>
                ";
yield _gettext("Browser display transformation options");
            yield "                ";
            yield PhpMyAdmin\Html\Generator::showHint(_gettext("Please enter the values for transformation options using this format: 'a', 100, b,'c'…<br>If you ever need to put a backslash (\"\\\") or a single quote (\"'\") amongst those values, precede it with a backslash (for example '\\\\xyz' or 'a\\'b')."));
            yield "
            </th>
            <th>
                <a href=\"";
            yield PhpMyAdmin\Url::getFromRoute("/transformation/overview");
            yield "#input_transformation\"
                   title=\"";
yield _gettext("List of available transformations and their options");
            yield "\"
                   target=\"_blank\">
                    ";
yield _gettext("Input transformation");
            yield "                </a>
            </th>
            <th>
                ";
yield _gettext("Input transformation options");
            yield "                ";
            yield PhpMyAdmin\Html\Generator::showHint(_gettext("Please enter the values for transformation options using this format: 'a', 100, b,'c'…<br>If you ever need to put a backslash (\"\\\") or a single quote (\"'\") amongst those values, precede it with a backslash (for example '\\\\xyz' or 'a\\'b')."));
            yield "
            </th>
        ";
        }
        yield "    </tr>
    ";
        $context["options"] = ["" => "", "VIRTUAL" => "VIRTUAL"];
        yield "    ";
        if (($context["supports_stored_keyword"] ?? null)) {
            yield "        ";
            $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["STORED" => "STORED"]);
            yield "    ";
        } else {
            yield "        ";
            $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["PERSISTENT" => "PERSISTENT"]);
            yield "    ";
        }
        yield "    ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["content_cells"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["content_row"]) {
            yield "        <tr>
            ";
            yield from             $this->loadTemplate("columns_definitions/column_attributes.twig", "columns_definitions/table_fields_definitions.twig", 105)->unwrap()->yield(CoreExtension::toArray(Twig\Extension\CoreExtension::merge($context["content_row"], ["options" =>             // line 106
($context["options"] ?? null), "change_column" =>             // line 107
($context["change_column"] ?? null), "is_virtual_columns_supported" =>             // line 108
($context["is_virtual_columns_supported"] ?? null), "browse_mime" =>             // line 109
($context["browse_mime"] ?? null), "max_rows" =>             // line 110
($context["max_rows"] ?? null), "char_editing" =>             // line 111
($context["char_editing"] ?? null), "attribute_types" =>             // line 112
($context["attribute_types"] ?? null), "privs_available" =>             // line 113
($context["privs_available"] ?? null), "max_length" =>             // line 114
($context["max_length"] ?? null), "charsets" =>             // line 115
($context["charsets"] ?? null), "relation_parameters" =>             // line 116
($context["relation_parameters"] ?? null)])));
            yield "        </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['content_row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "</table>
</div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "columns_definitions/table_fields_definitions.twig";
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
        return array (  264 => 120,  257 => 118,  255 => 116,  254 => 115,  253 => 114,  252 => 113,  251 => 112,  250 => 111,  249 => 110,  248 => 109,  247 => 108,  246 => 107,  245 => 106,  244 => 105,  241 => 104,  236 => 103,  233 => 102,  230 => 101,  227 => 100,  224 => 99,  221 => 98,  219 => 97,  216 => 96,  209 => 93,  203 => 89,  198 => 86,  193 => 85,  186 => 82,  180 => 78,  176 => 76,  172 => 74,  168 => 72,  164 => 70,  162 => 69,  159 => 68,  155 => 66,  151 => 64,  149 => 63,  146 => 62,  142 => 60,  138 => 58,  136 => 57,  132 => 55,  124 => 49,  120 => 47,  116 => 45,  113 => 44,  110 => 40,  103 => 37,  99 => 35,  96 => 34,  92 => 31,  87 => 28,  82 => 25,  74 => 21,  66 => 17,  58 => 13,  53 => 10,  44 => 5,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "columns_definitions/table_fields_definitions.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\columns_definitions\\table_fields_definitions.twig");
    }
}

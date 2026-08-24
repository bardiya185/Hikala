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


class __TwigTemplate_540ba0142bf3d88db7c648fcd62b70f3 extends Template
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
        yield "<form method=\"post\" action=\"";
        yield PhpMyAdmin\Url::getFromRoute("/database/structure");
        yield "\" name=\"tablesForm\" id=\"tablesForm\">
";
        yield PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null));
        yield "
<div class=\"table-responsive\">
<table class=\"table table-striped table-hover table-sm w-auto data\">
    <thead>
        <tr>
            <th class=\"d-print-none\"></th>
            <th>";
        yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Table"), "table");
        yield "</th>
            ";
        if (($context["replication"] ?? null)) {
            yield "                <th>";
yield _gettext("Replication");
            yield "</th>
            ";
        }
        yield "
            ";
        if (($context["db_is_system_schema"] ?? null)) {
            yield "                ";
            $context["action_colspan"] = 3;
            yield "            ";
        } else {
            yield "                ";
            $context["action_colspan"] = 6;
            yield "            ";
        }
        yield "            ";
        if ((($context["num_favorite_tables"] ?? null) > 0)) {
            yield "                ";
            $context["action_colspan"] = (($context["action_colspan"] ?? null) + 1);
            yield "            ";
        }
        yield "            <th colspan=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["action_colspan"] ?? null), "html", null, true);
        yield "\" class=\"d-print-none\">
                ";
yield _gettext("Action");
        yield "            </th>
            ";
        yield "            <th>
                ";
        yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Rows"), "records", "DESC");
        yield "
                ";
        yield PhpMyAdmin\Html\Generator::showHint(PhpMyAdmin\Sanitize::sanitizeMessage(_gettext("May be approximate. Click on the number to get the exact count. See [doc@faq3-11]FAQ 3.11[/doc].")));
        yield "
            </th>
            ";
        if ( !(($context["properties_num_columns"] ?? null) > 1)) {
            yield "                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Type"), "type");
            yield "</th>
                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Collation"), "collation");
            yield "</th>
            ";
        }
        yield "
            ";
        if (($context["is_show_stats"] ?? null)) {
            yield "                ";
            yield "                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Size"), "size", "DESC");
            yield "</th>
                ";
            yield "                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Overhead"), "overhead", "DESC");
            yield "</th>
            ";
        }
        yield "
            ";
        if (($context["show_charset"] ?? null)) {
            yield "                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Charset"), "charset");
            yield "</th>
            ";
        }
        yield "
            ";
        if (($context["show_comment"] ?? null)) {
            yield "                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Comment"), "comment");
            yield "</th>
            ";
        }
        yield "
            ";
        if (($context["show_creation"] ?? null)) {
            yield "                ";
            yield "                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Creation"), "creation", "DESC");
            yield "</th>
            ";
        }
        yield "
            ";
        if (($context["show_last_update"] ?? null)) {
            yield "                ";
            yield "                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Last update"), "last_update", "DESC");
            yield "</th>
            ";
        }
        yield "
            ";
        if (($context["show_last_check"] ?? null)) {
            yield "                ";
            yield "                <th>";
            yield PhpMyAdmin\Util::sortableTableHeader(_gettext("Last check"), "last_check", "DESC");
            yield "</th>
            ";
        }
        yield "        </tr>
    </thead>
    <tbody>
    ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["structure_table_rows"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["structure_table_row"]) {
            yield "        ";
            yield from             $this->loadTemplate("database/structure/structure_table_row.twig", "database/structure/table_header.twig", 67)->unwrap()->yield(CoreExtension::toArray($context["structure_table_row"]));
            yield "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['structure_table_row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "    </tbody>
    ";
        if (($context["body_for_table_summary"] ?? null)) {
            yield "        ";
            yield from             $this->loadTemplate("database/structure/body_for_table_summary.twig", "database/structure/table_header.twig", 71)->unwrap()->yield(CoreExtension::toArray(($context["body_for_table_summary"] ?? null)));
            yield "    ";
        }
        yield "</table>
</div>
";
        if (($context["check_all_tables"] ?? null)) {
            yield "  ";
            yield from             $this->loadTemplate("database/structure/check_all_tables.twig", "database/structure/table_header.twig", 76)->unwrap()->yield(CoreExtension::toArray(($context["check_all_tables"] ?? null)));
        }
        yield "</form>
";
        if (($context["check_all_tables"] ?? null)) {
            yield "  ";
            yield from             $this->loadTemplate("database/structure/bulk_action_modal.twig", "database/structure/table_header.twig", 80)->unwrap()->yield(CoreExtension::toArray(($context["check_all_tables"] ?? null)));
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "database/structure/table_header.twig";
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
        return array (  246 => 80,  244 => 79,  241 => 78,  237 => 76,  235 => 75,  231 => 73,  228 => 72,  225 => 71,  223 => 70,  220 => 69,  214 => 68,  211 => 67,  207 => 66,  202 => 63,  196 => 61,  194 => 60,  192 => 59,  189 => 58,  183 => 56,  181 => 55,  179 => 54,  176 => 53,  170 => 51,  168 => 50,  166 => 49,  163 => 48,  157 => 46,  155 => 45,  152 => 44,  146 => 42,  144 => 41,  141 => 40,  135 => 38,  130 => 36,  128 => 35,  126 => 34,  123 => 33,  118 => 31,  113 => 30,  111 => 29,  106 => 27,  102 => 26,  99 => 25,  96 => 23,  90 => 21,  87 => 20,  84 => 19,  81 => 18,  78 => 17,  75 => 16,  72 => 15,  69 => 14,  67 => 13,  64 => 12,  58 => 10,  56 => 9,  52 => 8,  43 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/structure/table_header.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\database\\structure\\table_header.twig");
    }
}

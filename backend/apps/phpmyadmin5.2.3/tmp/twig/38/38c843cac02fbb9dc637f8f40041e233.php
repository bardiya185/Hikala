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


class __TwigTemplate_b969330ae7c19690021c9efdf31fa9c5 extends Template
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
        yield "<ul class=\"nav nav-pills m-2\">
  <li class=\"nav-item\">
    <a class=\"nav-link active disableAjax\" href=\"";
        yield PhpMyAdmin\Url::getFromRoute("/database/multi-table-query", ["db" => ($context["db"] ?? null)]);
        yield "\">
      ";
yield _gettext("Multi-table query");
        yield "    </a>
  </li>

  <li class=\"nav-item\">
    <a class=\"nav-link disableAjax\" href=\"";
        yield PhpMyAdmin\Url::getFromRoute("/database/qbe", ["db" => ($context["db"] ?? null)]);
        yield "\">
      ";
yield _gettext("Query by example");
        yield "    </a>
  </li>
</ul>

<div class=\"mb-3\">
  <button class=\"btn btn-sm btn-secondary\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#queryWindow\" aria-expanded=\"true\" aria-controls=\"queryWindow\">
    ";
yield _gettext("Query window");
        yield "  </button>
</div>
<div class=\"collapse show mb-3\" id=\"queryWindow\">

<form action=\"\" id=\"multi_table_query_form\" class=\"multi_table_query_form query_form\">
    <input type=\"hidden\" id=\"db_name\" value=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["db"] ?? null), "html", null, true);
        yield "\">
    <fieldset class=\"pma-fieldset\">
        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["tables"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["table"]) {
            yield "            <div class=\"d-none\" id=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["table"], "hash", [], "any", false, false, false, 26), "html", null, true);
            yield "\">
                <option value=\"*\">*</option>
                ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["table"], "columns", [], "any", false, false, false, 28));
            foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                yield "                    <option value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["column"], "html", null, true);
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["column"], "html", null, true);
                yield "</option>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['table'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "
        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(range(0, ($context["default_no_of_columns"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["id"]) {
            yield "            ";
            if (($context["id"] == 0)) {
                yield "<div class=\"d-none\" id=\"new_column_layout\">";
            }
            yield "            <fieldset class=\"pma-fieldset column_details query-form__fieldset--inline position-relative\">
                <select class=\"tableNameSelect query-form__select--inline\">
                    <option value=\"\">";
yield _gettext("select table");
            yield "</option>
                    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["tables"] ?? null));
            foreach ($context['_seq'] as $context["keyTableName"] => $context["table"]) {
                yield "                      <option data-hash=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["table"], "hash", [], "any", false, false, false, 40), "html", null, true);
                yield "\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["keyTableName"], "html", null, true);
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["keyTableName"], "html", null, true);
                yield "</option>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['keyTableName'], $context['table'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "                </select>
                <span>.</span>
                <select class=\"columnNameSelect query-form__select--inline\">
                    <option value=\"\">";
yield _gettext("select column");
            yield "</option>
                </select>
                <br>
                <input type=\"checkbox\" checked=\"checked\" class=\"show_col\">
                <span>";
yield _gettext("Show");
            yield "</span>
                <br>
                <input type=\"text\" placeholder=\"";
yield _gettext("Table alias");
            yield "\" class=\"table_alias\">
                <input type=\"text\" placeholder=\"";
yield _gettext("Column alias");
            yield "\" class=\"col_alias\">
                <br>
                <input type=\"checkbox\"
                    title=\"";
yield _gettext("Use this column in criteria");
            yield "\"
                    class=\"criteria_col\" data-bs-toggle=\"collapse\" data-bs-target=\"#criteriaOptions";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "\" aria-expanded=\"false\" aria-controls=\"criteriaOptions";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "\">

                <button class=\"btn btn-link p-0 jsCriteriaButton\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#criteriaOptions";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "\" aria-expanded=\"false\" aria-controls=\"criteriaOptions";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "\">
                  ";
yield _gettext("criteria");
            yield "                </button>
                <div class=\"collapse jsCriteriaOptions\" id=\"criteriaOptions";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "\">

                <div>
                    <table class=\"table table-sm table-borderless align-middle w-auto\">

                        <tr class=\"sort_order query-form__tr--bg-none\">
                            <td>";
yield _gettext("Sort");
            yield "</td>
                            <td><input type=\"radio\" name=\"sort[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "]\">";
yield _gettext("Ascending");
            yield "</td>
                            <td><input type=\"radio\" name=\"sort[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "]\">";
yield _gettext("Descending");
            yield "</td>
                        </tr>

                        <tr class=\"logical_operator query-form__tr--bg-none query-form__tr--hide\">
                            <td>";
yield _gettext("Add as");
            yield "</td>
                            <td>
                                <input type=\"radio\"
                                    name=\"logical_op[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "]\"
                                    value=\"AND\"
                                    class=\"logical_op\"
                                    checked=\"checked\">
                                AND
                            </td>
                            <td>
                                <input type=\"radio\"
                                    name=\"logical_op[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
            yield "]\"
                                    value=\"OR\"
                                    class=\"logical_op\">
                                OR
                            </td>
                        </tr>

                        <tr>
                            <td>Column</td>
                            <td colspan=\"2\">
                                <select class=\"columnNameSelect query-form__select--inline opColumn\">
                                    <option value=\"\">";
yield _gettext("select column");
            yield "</option>
                                </select>
                            </td>
                        </tr>

                        <tr class=\"query-form__tr--bg-none\">
                            <td>Op </td>
                            <td>
                                <select class=\"criteria_op\">
                                    <option value=\"=\">=</option>
                                    <option value=\">\">&gt;</option>
                                    <option value=\">=\">&gt;=</option>
                                    <option value=\"<\">&lt;</option>
                                    <option value=\"<=\">&lt;=</option>
                                    <option value=\"!=\">!=</option>
                                    <option value=\"LIKE\">LIKE</option>
                                    <option value=\"LIKE %...%\">LIKE %...%</option>
                                    <option value=\"NOT LIKE\">NOT LIKE</option>
                                    <option value=\"NOT LIKE %...%\">NOT LIKE %...%</option>
                                    <option value=\"IN (...)\">IN (...)</option>
                                    <option value=\"NOT IN (...)\">NOT IN (...)</option>
                                    <option value=\"BETWEEN\">BETWEEN</option>
                                    <option value=\"NOT BETWEEN\">NOT BETWEEN</option>
                                    <option value=\"IS NULL\">IS NULL</option>
                                    <option value=\"IS NOT NULL\">IS NOT NULL</option>
                                    <option value=\"REGEXP\">REGEXP</option>
                                    <option value=\"REGEXP ^...\$\">REGEXP ^...\$</option>
                                    <option value=\"NOT REGEXP\">NOT REGEXP</option>
                                </select>
                            </td>
                            <td>
                                <select class=\"criteria_rhs\">
                                    <option value=\"text\">";
yield _gettext("Text");
            yield "</option>
                                    <option value=\"anotherColumn\">";
yield _gettext("Another column");
            yield "</option>
                                </select>
                            </td>
                        </tr>

                        <tr class=\"rhs_table query-form__tr--hide query-form__tr--bg-none\">
                            <td></td>
                            <td>
                                <select  class=\"tableNameSelect\">
                                    <option value=\"\">";
yield _gettext("select table");
            yield "</option>
                                    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["tables"] ?? null));
            foreach ($context['_seq'] as $context["keyTableName"] => $context["table"]) {
                yield "                                        <option data-hash=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["table"], "hash", [], "any", false, false, false, 139), "html", null, true);
                yield "\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["keyTableName"], "html", null, true);
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["keyTableName"], "html", null, true);
                yield "</option>
                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['keyTableName'], $context['table'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "                                </select><span>.</span>
                            </td>
                            <td>
                                <select class=\"columnNameSelect query-form__select--inline\">
                                    <option value=\"\">";
yield _gettext("select column");
            yield "</option>
                                </select>
                            </td>
                        </tr>

                        <tr class=\"rhs_text query-form__tr--bg-none\">
                            <td></td>
                            <td colspan=\"2\">
                                <input type=\"text\"
                                    class=\"rhs_text_val query-form__input--wide\"
                                    placeholder=\"";
yield _gettext("Enter criteria as free text");
            yield "\">
                            </td>
                        </tr>

                        </table>
                    </div>
                </div>
                <button type=\"button\" class=\"btn-close position-absolute top-0 end-0 jsRemoveColumn\" aria-label=\"";
yield _gettext("Remove this column");
            yield "\"></button>
            </fieldset>
            ";
            if (($context["id"] == 0)) {
                yield "</div>";
            }
            yield "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['id'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "
        <fieldset class=\"pma-fieldset query-form__fieldset--inline\">
            <input class=\"btn btn-secondary\" type=\"button\" value=\"";
yield _gettext("+ Add column");
        yield "\" id=\"add_column_button\">
        </fieldset>

        <fieldset class=\"pma-fieldset\">
              ";
        yield "                <textarea id=\"MultiSqlquery\"
                    class=\"query-form__multi-sql-query\"
                    cols=\"80\"
                    rows=\"4\"
                    name=\"sql_query\"
                    dir=\"ltr\"></textarea>
        </fieldset>
    </fieldset>

    <fieldset class=\"pma-fieldset tblFooters\">
        <input class=\"btn btn-secondary\" type=\"button\" id=\"update_query_button\" value=\"";
yield _gettext("Update query");
        yield "\">
        <input class=\"btn btn-primary\" type=\"button\" id=\"submit_query\" value=\"";
yield _gettext("Submit query");
        yield "\">
    </fieldset>
</form>
</div>
<div id=\"sql_results\"></div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "database/multi_table_query/form.twig";
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
        return array (  399 => 184,  395 => 183,  382 => 173,  376 => 168,  371 => 166,  365 => 165,  361 => 164,  357 => 162,  347 => 155,  334 => 145,  327 => 141,  314 => 139,  310 => 138,  307 => 137,  295 => 128,  291 => 127,  256 => 95,  241 => 84,  230 => 76,  225 => 73,  215 => 69,  209 => 68,  206 => 67,  196 => 61,  193 => 60,  186 => 58,  179 => 56,  176 => 55,  170 => 52,  166 => 51,  161 => 49,  154 => 45,  148 => 42,  135 => 40,  131 => 39,  128 => 38,  123 => 36,  118 => 35,  114 => 34,  111 => 33,  104 => 31,  93 => 29,  89 => 28,  83 => 26,  79 => 25,  74 => 23,  67 => 18,  58 => 11,  53 => 9,  47 => 5,  42 => 3,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/multi_table_query/form.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\database\\multi_table_query\\form.twig");
    }
}

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


class __TwigTemplate_96e98492f1189c27c1c028ec5d6fd92a extends Template
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
        yield "<tr id=\"row_tbl_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["curr"] ?? null), "html", null, true);
        yield "\"";
        yield ((($context["table_is_view"] ?? null)) ? (" class=\"is_view\"") : (""));
        yield " data-filter-row=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::upper($this->env->getCharset(), (($__internal_compile_0 = ($context["current_table"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["TABLE_NAME"] ?? null) : null)), "html", null, true);
        yield "\">
    <td class=\"text-center d-print-none\">
        <input type=\"checkbox\"
            name=\"selected_tbl[]\"
            class=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["input_class"] ?? null), "html", null, true);
        yield "\"
            value=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_1 = ($context["current_table"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["TABLE_NAME"] ?? null) : null), "html", null, true);
        yield "\"
            id=\"checkbox_tbl_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["curr"] ?? null), "html", null, true);
        yield "\">
    </td>
    <th>
        <a href=\"";
        yield PhpMyAdmin\Url::getFromRoute("/sql", Twig\Extension\CoreExtension::merge(($context["table_url_params"] ?? null), ["pos" => 0]));
        yield "\" title=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["browse_table_label_title"] ?? null), "html", null, true);
        yield "\">";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["browse_table_label_truename"] ?? null), "html", null, true);
        yield "</a>
        ";
        yield ($context["tracking_icon"] ?? null);
        yield "
    </th>
    ";
        if (($context["server_replica_status"] ?? null)) {
            yield "        <td class=\"text-center\">
            ";
            yield ((($context["ignored"] ?? null)) ? (PhpMyAdmin\Html\Generator::getImage("s_cancel", _gettext("Not replicated"))) : (""));
            yield "
            ";
            yield ((($context["do"] ?? null)) ? (PhpMyAdmin\Html\Generator::getImage("s_success", _gettext("Replicated"))) : (""));
            yield "
        </td>
    ";
        }
        yield "
    ";
        yield "    ";
        if ((($context["num_favorite_tables"] ?? null) > 0)) {
            yield "        <td class=\"text-center d-print-none\">
            ";
            yield "            ";
            $context["fav_params"] = ["db" =>             // line 27
($context["db"] ?? null), "ajax_request" => true, "favorite_table" => (($__internal_compile_2 =             // line 29
($context["current_table"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["TABLE_NAME"] ?? null) : null), (((            // line 30
($context["already_favorite"] ?? null)) ? ("remove") : ("add")) . "_favorite") => true];
            yield "            ";
            yield from             $this->loadTemplate("database/structure/favorite_anchor.twig", "database/structure/structure_table_row.twig", 32)->unwrap()->yield(CoreExtension::toArray(["table_name_hash" =>             // line 33
($context["table_name_hash"] ?? null), "db_table_name_hash" =>             // line 34
($context["db_table_name_hash"] ?? null), "fav_params" =>             // line 35
($context["fav_params"] ?? null), "already_favorite" =>             // line 36
($context["already_favorite"] ?? null)]));
            yield "        </td>
    ";
        }
        yield "
    <td class=\"text-center d-print-none\">
        <a href=\"";
        yield PhpMyAdmin\Url::getFromRoute("/sql", Twig\Extension\CoreExtension::merge(($context["table_url_params"] ?? null), ["pos" => 0]));
        yield "\">
          ";
        yield ((($context["may_have_rows"] ?? null)) ? (PhpMyAdmin\Html\Generator::getIcon("b_browse", _gettext("Browse"))) : (PhpMyAdmin\Html\Generator::getIcon("bd_browse", _gettext("Browse"))));
        yield "
        </a>
    </td>
    <td class=\"text-center d-print-none\">
        <a href=\"";
        yield PhpMyAdmin\Url::getFromRoute("/table/structure", ($context["table_url_params"] ?? null));
        yield "\">
          ";
        yield PhpMyAdmin\Html\Generator::getIcon("b_props", _gettext("Structure"));
        yield "
        </a>
    </td>
    <td class=\"text-center d-print-none\">
        <a href=\"";
        yield PhpMyAdmin\Url::getFromRoute("/table/search", ($context["table_url_params"] ?? null));
        yield "\">
          ";
        yield ((($context["may_have_rows"] ?? null)) ? (PhpMyAdmin\Html\Generator::getIcon("b_select", _gettext("Search"))) : (PhpMyAdmin\Html\Generator::getIcon("bd_select", _gettext("Search"))));
        yield "
        </a>
    </td>

    ";
        if ( !($context["db_is_system_schema"] ?? null)) {
            yield "        <td class=\"insert_table text-center d-print-none\">
            <a href=\"";
            yield PhpMyAdmin\Url::getFromRoute("/table/change", ($context["table_url_params"] ?? null));
            yield "\">";
            yield PhpMyAdmin\Html\Generator::getIcon("b_insrow", _gettext("Insert"));
            yield "</a>
        </td>
        ";
            if (($context["table_is_view"] ?? null)) {
                yield "            <td class=\"text-center d-print-none\">
                <a href=\"";
                yield PhpMyAdmin\Url::getFromRoute("/view/create", ["db" =>                 // line 64
($context["db"] ?? null), "table" => (($__internal_compile_3 =                 // line 65
($context["current_table"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["TABLE_NAME"] ?? null) : null)]);
                yield "\">";
                yield PhpMyAdmin\Html\Generator::getIcon("b_edit", _gettext("Edit"));
                yield "</a>
            </td>
        ";
            } else {
                yield "          <td class=\"text-center d-print-none\">
                <a class=\"truncate_table_anchor ajax\" href=\"";
                yield PhpMyAdmin\Url::getFromRoute("/sql");
                yield "\" data-post=\"";
                yield PhpMyAdmin\Url::getCommon(Twig\Extension\CoreExtension::merge(($context["table_url_params"] ?? null), ["sql_query" =>                 // line 71
($context["empty_table_sql_query"] ?? null), "message_to_show" =>                 // line 72
($context["empty_table_message_to_show"] ?? null)]), "");
                yield "\">
                  ";
                yield ((($context["may_have_rows"] ?? null)) ? (PhpMyAdmin\Html\Generator::getIcon("b_empty", _gettext("Empty"))) : (PhpMyAdmin\Html\Generator::getIcon("bd_empty", _gettext("Empty"))));
                yield "
                </a>
          </td>
        ";
            }
            yield "        <td class=\"text-center d-print-none\">
            <a class=\"ajax drop_table_anchor";
            yield ((($context["table_is_view"] ?? null)) ? (" view") : (""));
            yield "\" href=\"";
            yield PhpMyAdmin\Url::getFromRoute("/sql");
            yield "\" data-post=\"";
            yield PhpMyAdmin\Url::getCommon(Twig\Extension\CoreExtension::merge(($context["table_url_params"] ?? null), ["reload" => 1, "purge" => 1, "sql_query" =>             // line 84
($context["drop_query"] ?? null), "message_to_show" =>             // line 85
($context["drop_message"] ?? null)]), "");
            yield "\">
                ";
            yield PhpMyAdmin\Html\Generator::getIcon("b_drop", _gettext("Drop"));
            yield "
            </a>
        </td>
    ";
        }
        yield "
    ";
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["current_table"] ?? null), "TABLE_ROWS", [], "array", true, true, false, 92) && (((($__internal_compile_4 =         // line 93
($context["current_table"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["ENGINE"] ?? null) : null) != null) || ($context["table_is_view"] ?? null)))) {
            yield "        ";
            yield "        ";
            $context["row_count"] = PhpMyAdmin\Util::formatNumber((($__internal_compile_5 = ($context["current_table"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["TABLE_ROWS"] ?? null) : null), 0);
            yield "
        ";
            yield "        <td class=\"value tbl_rows font-monospace text-end\"
            data-table=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_6 = ($context["current_table"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["TABLE_NAME"] ?? null) : null), "html", null, true);
            yield "\">
            ";
            if (($context["approx_rows"] ?? null)) {
                yield "                <a href=\"";
                yield PhpMyAdmin\Url::getFromRoute("/database/structure/real-row-count", ["ajax_request" => true, "db" =>                 // line 104
($context["db"] ?? null), "table" => (($__internal_compile_7 =                 // line 105
($context["current_table"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["TABLE_NAME"] ?? null) : null)]);
                yield "\" class=\"ajax real_row_count\">
                    <bdi>
                        ~";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["row_count"] ?? null), "html", null, true);
                yield "
                    </bdi>
                </a>
            ";
            } else {
                yield "                ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["row_count"] ?? null), "html", null, true);
                yield "
            ";
            }
            yield "            ";
            yield ($context["show_superscript"] ?? null);
            yield "
        </td>

        ";
            if ( !(($context["properties_num_columns"] ?? null) > 1)) {
                yield "            <td class=\"text-nowrap\">
                ";
                if ( !Twig\Extension\CoreExtension::testEmpty((($__internal_compile_8 = ($context["current_table"] ?? null)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["ENGINE"] ?? null) : null))) {
                    yield "                    ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_9 = ($context["current_table"] ?? null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["ENGINE"] ?? null) : null), "html", null, true);
                    yield "
                ";
                } elseif (                // line 121
($context["table_is_view"] ?? null)) {
                    yield "                    ";
yield _gettext("View");
                    yield "                ";
                }
                yield "            </td>
            ";
                if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["collation"] ?? null)) > 0)) {
                    yield "                <td class=\"text-nowrap\">
                    ";
                    yield ($context["collation"] ?? null);
                    yield "
                </td>
            ";
                }
                yield "        ";
            }
            yield "
        ";
            if (($context["is_show_stats"] ?? null)) {
                yield "            <td class=\"value tbl_size font-monospace text-end\">
                <a href=\"";
                yield PhpMyAdmin\Url::getFromRoute("/table/structure", ($context["table_url_params"] ?? null));
                yield "#showusage\">
                    <span>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["formatted_size"] ?? null), "html", null, true);
                yield "</span>&nbsp;<span class=\"unit\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["unit"] ?? null), "html", null, true);
                yield "</span>
                </a>
            </td>
            <td class=\"value tbl_overhead font-monospace text-end\">
                ";
                yield ($context["overhead"] ?? null);
                yield "
            </td>
        ";
            }
            yield "
        ";
            if (($context["show_charset"] ?? null)) {
                yield "            <td class=\"text-nowrap\">
                ";
                if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["charset"] ?? null)) > 0)) {
                    yield "                    ";
                    yield ($context["charset"] ?? null);
                    yield "
                ";
                }
                yield "            </td>
        ";
            }
            yield "
        ";
            if (($context["show_comment"] ?? null)) {
                yield "            ";
                $context["comment"] = (($__internal_compile_10 = ($context["current_table"] ?? null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["Comment"] ?? null) : null);
                yield "            <td>
                ";
                if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["comment"] ?? null)) > ($context["limit_chars"] ?? null))) {
                    yield "                    <abbr title=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["comment"] ?? null), "html", null, true);
                    yield "\">
                        ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::slice($this->env->getCharset(), ($context["comment"] ?? null), 0, ($context["limit_chars"] ?? null)), "html", null, true);
                    yield "
                        ...
                    </abbr>
                ";
                } else {
                    yield "                    ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["comment"] ?? null), "html", null, true);
                    yield "
                ";
                }
                yield "            </td>
        ";
            }
            yield "
        ";
            if (($context["show_creation"] ?? null)) {
                yield "            <td class=\"value tbl_creation font-monospace text-end\">
                ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["create_time"] ?? null), "html", null, true);
                yield "
            </td>
        ";
            }
            yield "
        ";
            if (($context["show_last_update"] ?? null)) {
                yield "            <td class=\"value tbl_last_update font-monospace text-end\">
                ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["update_time"] ?? null), "html", null, true);
                yield "
            </td>
        ";
            }
            yield "
        ";
            if (($context["show_last_check"] ?? null)) {
                yield "            <td class=\"value tbl_last_check font-monospace text-end\">
                ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["check_time"] ?? null), "html", null, true);
                yield "
            </td>
        ";
            }
            yield "
    ";
        } elseif (        // line 183
($context["table_is_view"] ?? null)) {
            yield "        <td class=\"value tbl_rows font-monospace text-end\">-</td>
        <td class=\"text-nowrap\">
            ";
yield _gettext("View");
            yield "        </td>
        <td class=\"text-nowrap\">---</td>
        ";
            if (($context["is_show_stats"] ?? null)) {
                yield "            <td class=\"value tbl_size font-monospace text-end\">-</td>
            <td class=\"value tbl_overhead font-monospace text-end\">-</td>
        ";
            }
            yield "        ";
            if (($context["show_charset"] ?? null)) {
                yield "            <td></td>
        ";
            }
            yield "        ";
            if (($context["show_comment"] ?? null)) {
                yield "            <td></td>
        ";
            }
            yield "        ";
            if (($context["show_creation"] ?? null)) {
                yield "            <td class=\"value tbl_creation font-monospace text-end\">-</td>
        ";
            }
            yield "        ";
            if (($context["show_last_update"] ?? null)) {
                yield "            <td class=\"value tbl_last_update font-monospace text-end\">-</td>
        ";
            }
            yield "        ";
            if (($context["show_last_check"] ?? null)) {
                yield "            <td class=\"value tbl_last_check font-monospace text-end\">-</td>
        ";
            }
            yield "
    ";
        } else {
            yield "
        ";
            if (($context["db_is_system_schema"] ?? null)) {
                yield "            ";
                $context["action_colspan"] = 2;
                yield "        ";
            } else {
                yield "            ";
                $context["action_colspan"] = 4;
                yield "        ";
            }
            yield "        ";
            if ((($context["num_favorite_tables"] ?? null) > 0)) {
                yield "            ";
                $context["action_colspan"] = (($context["action_colspan"] ?? null) + 1);
                yield "        ";
            }
            yield "        ";
            if (($context["show_charset"] ?? null)) {
                yield "            ";
                $context["action_colspan"] = (($context["action_colspan"] ?? null) + 1);
                yield "        ";
            }
            yield "        ";
            if (($context["show_comment"] ?? null)) {
                yield "            ";
                $context["action_colspan"] = (($context["action_colspan"] ?? null) + 1);
                yield "        ";
            }
            yield "        ";
            if (($context["show_creation"] ?? null)) {
                yield "            ";
                $context["action_colspan"] = (($context["action_colspan"] ?? null) + 1);
                yield "        ";
            }
            yield "        ";
            if (($context["show_last_update"] ?? null)) {
                yield "            ";
                $context["action_colspan"] = (($context["action_colspan"] ?? null) + 1);
                yield "        ";
            }
            yield "        ";
            if (($context["show_last_check"] ?? null)) {
                yield "            ";
                $context["action_colspan"] = (($context["action_colspan"] ?? null) + 1);
                yield "        ";
            }
            yield "
        <td colspan=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["action_colspan"] ?? null), "html", null, true);
            yield "\"
            class=\"text-center\">
            ";
yield _gettext("in use");
            yield "        </td>
    ";
        }
        yield "</tr>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "database/structure/structure_table_row.twig";
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
        return array (  583 => 240,  579 => 238,  573 => 235,  570 => 234,  567 => 233,  564 => 232,  561 => 231,  558 => 230,  555 => 229,  552 => 228,  549 => 227,  546 => 226,  543 => 225,  540 => 224,  537 => 223,  534 => 222,  531 => 221,  528 => 220,  525 => 219,  522 => 218,  519 => 217,  516 => 216,  513 => 215,  510 => 214,  507 => 213,  504 => 212,  502 => 211,  499 => 210,  495 => 208,  491 => 206,  488 => 205,  484 => 203,  481 => 202,  477 => 200,  474 => 199,  470 => 197,  467 => 196,  463 => 194,  460 => 193,  455 => 190,  453 => 189,  449 => 187,  444 => 184,  442 => 183,  439 => 182,  433 => 179,  430 => 178,  428 => 177,  425 => 176,  419 => 173,  416 => 172,  414 => 171,  411 => 170,  405 => 167,  402 => 166,  400 => 165,  397 => 164,  393 => 162,  387 => 160,  380 => 156,  375 => 155,  373 => 154,  370 => 153,  367 => 152,  365 => 151,  362 => 150,  358 => 148,  352 => 146,  350 => 145,  347 => 144,  345 => 143,  342 => 142,  336 => 139,  327 => 135,  323 => 134,  320 => 133,  318 => 132,  315 => 131,  312 => 130,  306 => 127,  303 => 126,  301 => 125,  298 => 124,  295 => 123,  292 => 122,  290 => 121,  285 => 120,  283 => 119,  280 => 118,  278 => 117,  271 => 114,  265 => 112,  258 => 108,  254 => 106,  252 => 105,  251 => 104,  249 => 102,  247 => 101,  243 => 100,  240 => 99,  237 => 96,  234 => 95,  232 => 94,  230 => 93,  229 => 92,  226 => 91,  219 => 87,  216 => 86,  214 => 85,  213 => 84,  212 => 81,  207 => 80,  204 => 78,  197 => 74,  194 => 73,  192 => 72,  191 => 71,  188 => 70,  185 => 69,  178 => 66,  176 => 65,  175 => 64,  174 => 63,  171 => 62,  169 => 61,  162 => 59,  159 => 58,  157 => 57,  150 => 53,  146 => 52,  139 => 48,  135 => 47,  128 => 43,  124 => 42,  120 => 40,  116 => 38,  114 => 36,  113 => 35,  112 => 34,  111 => 33,  109 => 32,  107 => 30,  106 => 29,  105 => 27,  103 => 26,  100 => 24,  97 => 23,  94 => 21,  88 => 18,  84 => 17,  81 => 16,  79 => 15,  74 => 13,  71 => 12,  69 => 11,  64 => 10,  58 => 7,  54 => 6,  50 => 5,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/structure/structure_table_row.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\database\\structure\\structure_table_row.twig");
    }
}

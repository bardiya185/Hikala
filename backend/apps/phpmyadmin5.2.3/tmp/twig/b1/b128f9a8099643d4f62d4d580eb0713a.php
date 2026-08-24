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


class __TwigTemplate_1dda7efcb37fc561f0a87379c1a43574 extends Template
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
        $context["ci"] = 0;
        yield "
";
        $context["ci_offset"] =  -1;
        yield "
<td class=\"text-center\">
    ";
        yield "    ";
        yield from         $this->loadTemplate("columns_definitions/column_name.twig", "columns_definitions/column_attributes.twig", 10)->unwrap()->yield(CoreExtension::toArray(["column_number" =>         // line 11
($context["column_number"] ?? null), "ci" =>         // line 12
($context["ci"] ?? null), "ci_offset" =>         // line 13
($context["ci_offset"] ?? null), "column_meta" =>         // line 14
($context["column_meta"] ?? null), "has_central_columns_feature" =>  !(null === CoreExtension::getAttribute($this->env, $this->source,         // line 15
($context["relation_parameters"] ?? null), "centralColumnsFeature", [], "any", false, false, false, 15)), "max_rows" =>         // line 16
($context["max_rows"] ?? null)]));
        yield "    ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
<td class=\"text-center\">
  <select class=\"column_type\" name=\"field_type[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\" id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\"";
        yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "column_status", [], "array", true, true, false, 22) &&  !(($__internal_compile_0 = (($__internal_compile_1 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["column_status"] ?? null) : null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["isEditable"] ?? null) : null))) ? (" disabled") : (""));
        yield ">
    ";
        yield PhpMyAdmin\Util::getSupportedDatatypes(true, ($context["type_upper"] ?? null));
        yield "
  </select>
  ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
<td class=\"text-center\">
  <input id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\" type=\"text\" name=\"field_length[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\" size=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["length_values_input_size"] ?? null), "html", null, true);
        yield "\" value=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["length"] ?? null), "html", null, true);
        yield "\" class=\"textfield\">
  <p class=\"enum_notice\" id=\"enum_notice_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\">
    <a href=\"#\" class=\"open_enum_editor\">";
yield _gettext("Edit ENUM/SET values");
        yield "</a>
  </p>
  ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
<td class=\"text-center\">
  <select name=\"field_default_type[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\" id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\" class=\"default_type\">
    <option value=\"NONE\"";
        yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "DefaultType", [], "array", true, true, false, 37) && ((($__internal_compile_2 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["DefaultType"] ?? null) : null) == "NONE"))) ? (" selected") : (""));
        yield ">
      ";
yield _pgettext("for default", "None");
        yield "    </option>
    <option value=\"USER_DEFINED\"";
        yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "DefaultType", [], "array", true, true, false, 40) && ((($__internal_compile_3 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["DefaultType"] ?? null) : null) == "USER_DEFINED"))) ? (" selected") : (""));
        yield ">
      ";
yield _gettext("As defined:");
        yield "    </option>
    <option value=\"NULL\"";
        yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "DefaultType", [], "array", true, true, false, 43) && ((($__internal_compile_4 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["DefaultType"] ?? null) : null) == "NULL"))) ? (" selected") : (""));
        yield ">
      NULL
    </option>
    <option value=\"CURRENT_TIMESTAMP\"";
        yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "DefaultType", [], "array", true, true, false, 46) && ((($__internal_compile_5 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["DefaultType"] ?? null) : null) == "CURRENT_TIMESTAMP"))) ? (" selected") : (""));
        yield ">
      CURRENT_TIMESTAMP
    </option>
    ";
        if (PhpMyAdmin\Util::isUUIDSupported()) {
            yield "    <option value=\"UUID\"";
            yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "DefaultType", [], "array", true, true, false, 50) && ((($__internal_compile_6 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["DefaultType"] ?? null) : null) == "UUID"))) ? (" selected") : (""));
            yield ">
      UUID
    </option>
    ";
        }
        yield "  </select>
  ";
        if ((($context["char_editing"] ?? null) == "textarea")) {
            yield "    <textarea name=\"field_default_value[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\" cols=\"15\" class=\"textfield default_value\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["default_value"] ?? null), "html", null, true);
            yield "</textarea>
  ";
        } else {
            yield "    <input type=\"text\" name=\"field_default_value[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\" size=\"12\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["default_value"] ?? null), "html", null, true);
            yield "\" class=\"textfield default_value\">
  ";
        }
        yield "  ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
<td class=\"text-center\">
  ";
        yield "  <select lang=\"en\" dir=\"ltr\" name=\"field_collation[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\" id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\">
    <option value=\"\"></option>
    ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["charsets"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["charset"]) {
            yield "      <optgroup label=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "name", [], "any", false, false, false, 67), "html", null, true);
            yield "\" title=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "description", [], "any", false, false, false, 67), "html", null, true);
            yield "\">
        ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "collations", [], "any", false, false, false, 68));
            foreach ($context['_seq'] as $context["_key"] => $context["collation"]) {
                yield "          <option value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["collation"], "name", [], "any", false, false, false, 69), "html", null, true);
                yield "\" title=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["collation"], "description", [], "any", false, false, false, 69), "html", null, true);
                yield "\"";
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["collation"], "name", [], "any", false, false, false, 70) == (($__internal_compile_7 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["Collation"] ?? null) : null))) ? (" selected") : (""));
                yield ">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["collation"], "name", [], "any", false, false, false, 71), "html", null, true);
                yield "</option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['collation'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "      </optgroup>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['charset'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "  </select>
  ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
<td class=\"text-center\">
    ";
        yield "    ";
        yield from         $this->loadTemplate("columns_definitions/column_attribute.twig", "columns_definitions/column_attributes.twig", 81)->unwrap()->yield(CoreExtension::toArray(["column_number" =>         // line 82
($context["column_number"] ?? null), "ci" =>         // line 83
($context["ci"] ?? null), "ci_offset" =>         // line 84
($context["ci_offset"] ?? null), "column_meta" =>         // line 85
($context["column_meta"] ?? null), "extracted_columnspec" =>         // line 86
($context["extracted_columnspec"] ?? null), "submit_attribute" =>         // line 87
($context["submit_attribute"] ?? null), "attribute_types" =>         // line 88
($context["attribute_types"] ?? null)]));
        yield "    ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
<td class=\"text-center\">
    <input name=\"field_null[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\" id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\" type=\"checkbox\" value=\"YES\" class=\"allow_null\"";
        yield (((( !Twig\Extension\CoreExtension::testEmpty((($__internal_compile_8 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["Null"] ?? null) : null)) && ((($__internal_compile_9 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["Null"] ?? null) : null) != "NO")) && ((($__internal_compile_10 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["Null"] ?? null) : null) != "NOT NULL"))) ? (" checked") : (""));
        yield ">
    ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
";
        if ((array_key_exists("change_column", $context) &&  !Twig\Extension\CoreExtension::testEmpty(($context["change_column"] ?? null)))) {
            yield "    ";
            yield "    <td class=\"text-center\">
      <input name=\"field_adjust_privileges[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\" id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" type=\"checkbox\" value=\"NULL\" class=\"allow_null\"";
            if (($context["privs_available"] ?? null)) {
                yield " checked>";
            } else {
                yield " title=\"";
yield _gettext("You don't have sufficient privileges to perform this operation; Please refer to the documentation for more details");
                yield "\" disabled>";
            }
            yield "      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
";
        }
        if ( !($context["is_backup"] ?? null)) {
            yield "    ";
            yield "    <td class=\"text-center\">
      <select name=\"field_key[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\" id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" data-index=\"\">
        <option value=\"none_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "\">---</option>
        <option value=\"primary_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "\" title=\"";
yield _gettext("Primary");
            yield "\"";
            yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Key", [], "array", true, true, false, 113) && ((($__internal_compile_11 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["Key"] ?? null) : null) == "PRI"))) ? (" selected") : (""));
            yield ">
          PRIMARY
        </option>
        <option value=\"unique_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "\" title=\"";
yield _gettext("Unique");
            yield "\"";
            yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Key", [], "array", true, true, false, 117) && ((($__internal_compile_12 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["Key"] ?? null) : null) == "UNI"))) ? (" selected") : (""));
            yield ">
          UNIQUE
        </option>
        <option value=\"index_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "\" title=\"";
yield _gettext("Index");
            yield "\"";
            yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Key", [], "array", true, true, false, 121) && ((($__internal_compile_13 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["Key"] ?? null) : null) == "MUL"))) ? (" selected") : (""));
            yield ">
          INDEX
        </option>
        <option value=\"fulltext_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "\" title=\"";
yield _gettext("Fulltext");
            yield "\"";
            yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Key", [], "array", true, true, false, 125) && ((($__internal_compile_14 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["Key"] ?? null) : null) == "FULLTEXT"))) ? (" selected") : (""));
            yield ">
          FULLTEXT
        </option>
        <option value=\"spatial_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "\" title=\"";
yield _gettext("Spatial");
            yield "\"";
            yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Key", [], "array", true, true, false, 129) && ((($__internal_compile_15 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["Key"] ?? null) : null) == "SPATIAL"))) ? (" selected") : (""));
            yield ">
          SPATIAL
        </option>
      </select>
      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
";
        }
        yield "<td class=\"text-center\">
  <input name=\"field_extra[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\" id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\" type=\"checkbox\" value=\"AUTO_INCREMENT\"";
        yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Extra", [], "array", true, true, false, 138) && (Twig\Extension\CoreExtension::lower($this->env->getCharset(), (($__internal_compile_16 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16["Extra"] ?? null) : null)) == "auto_increment"))) ? (" checked") : (""));
        yield ">
  ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
<td class=\"text-center\">
  <textarea id=\"field_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "_";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
        yield "\" rows=\"1\" name=\"field_comments[";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
        yield "]\" maxlength=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["max_length"] ?? null), "html", null, true);
        yield "\">";
        ((((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Field", [], "array", true, true, false, 143) && is_iterable(($context["comments_map"] ?? null))) && CoreExtension::getAttribute($this->env, $this->source, ($context["comments_map"] ?? null), (($__internal_compile_17 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_17) || $__internal_compile_17 instanceof ArrayAccess ? ($__internal_compile_17["Field"] ?? null) : null), [], "array", true, true, false, 143))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_18 = ($context["comments_map"] ?? null)) && is_array($__internal_compile_18) || $__internal_compile_18 instanceof ArrayAccess ? ($__internal_compile_18[(($__internal_compile_19 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_19) || $__internal_compile_19 instanceof ArrayAccess ? ($__internal_compile_19["Field"] ?? null) : null)] ?? null) : null), "html", null, true)) : (yield ""));
        yield "</textarea>
  ";
        $context["ci"] = (($context["ci"] ?? null) + 1);
        yield "</td>
 ";
        if (($context["is_virtual_columns_supported"] ?? null)) {
            yield "    <td class=\"text-center\">
      <select name=\"field_virtuality[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\" id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" class=\"virtuality\">
        ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["options"] ?? null));
            foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                yield "          ";
                $context["virtuality"] = ((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Extra", [], "array", true, true, false, 152)) ? ((($__internal_compile_20 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_20) || $__internal_compile_20 instanceof ArrayAccess ? ($__internal_compile_20["Extra"] ?? null) : null)) : (null));
                yield "          ";
                yield "          ";
                $context["virtuality"] = ((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Virtuality", [], "array", true, true, false, 154)) ? ((($__internal_compile_21 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_21) || $__internal_compile_21 instanceof ArrayAccess ? ($__internal_compile_21["Virtuality"] ?? null) : null)) : (($context["virtuality"] ?? null)));
                yield "
          <option value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["key"], "html", null, true);
                yield "\"";
                yield (((( !(null === ($context["virtuality"] ?? null)) && ($context["key"] != "")) && (Twig\Extension\CoreExtension::slice($this->env->getCharset(), ($context["virtuality"] ?? null), 0, Twig\Extension\CoreExtension::length($this->env->getCharset(), $context["key"])) === $context["key"]))) ? (" selected") : (""));
                yield ">
            ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["value"], "html", null, true);
                yield "
          </option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "      </select>

      ";
            if ((($context["char_editing"] ?? null) == "textarea")) {
                yield "        <textarea name=\"field_expression[";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
                yield "]\" cols=\"15\" class=\"textfield expression\">";
                ((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Expression", [], "array", true, true, false, 163)) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_22 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_22) || $__internal_compile_22 instanceof ArrayAccess ? ($__internal_compile_22["Expression"] ?? null) : null), "html", null, true)) : (yield ""));
                yield "</textarea>
      ";
            } else {
                yield "        <input type=\"text\" name=\"field_expression[";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
                yield "]\" size=\"12\" value=\"";
                ((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Expression", [], "array", true, true, false, 165)) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_23 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_23) || $__internal_compile_23 instanceof ArrayAccess ? ($__internal_compile_23["Expression"] ?? null) : null), "html", null, true)) : (yield ""));
                yield "\" placeholder=\"";
yield _gettext("Expression");
                yield "\" class=\"textfield expression\">
      ";
            }
            yield "      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
";
        }
        if (array_key_exists("fields_meta", $context)) {
            yield "    ";
            $context["current_index"] = 0;
            yield "    ";
            $context["cols"] = (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["move_columns"] ?? null)) - 1);
            yield "    ";
            $context["break"] = false;
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(range(0, ($context["cols"] ?? null)));
            foreach ($context['_seq'] as $context["_key"] => $context["mi"]) {
                yield "      ";
                if (((CoreExtension::getAttribute($this->env, $this->source, (($__internal_compile_24 = ($context["move_columns"] ?? null)) && is_array($__internal_compile_24) || $__internal_compile_24 instanceof ArrayAccess ? ($__internal_compile_24[$context["mi"]] ?? null) : null), "name", [], "any", false, false, false, 176) == (($__internal_compile_25 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_25) || $__internal_compile_25 instanceof ArrayAccess ? ($__internal_compile_25["Field"] ?? null) : null)) &&  !($context["break"] ?? null))) {
                    yield "        ";
                    $context["current_index"] = $context["mi"];
                    yield "        ";
                    $context["break"] = true;
                    yield "      ";
                }
                yield "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['mi'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "
    <td class=\"text-center\">
      <select id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" name=\"field_move_to[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\" size=\"1\" width=\"5em\">
        <option value=\"\" selected=\"selected\">&nbsp;</option>
        <option value=\"-first\"";
            yield (((($context["current_index"] ?? null) == 0)) ? (" disabled=\"disabled\"") : (""));
            yield ">
          ";
yield _gettext("first");
            yield "        </option>
        ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(range(0, (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["move_columns"] ?? null)) - 1)));
            foreach ($context['_seq'] as $context["_key"] => $context["mi"]) {
                yield "          <option value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (($__internal_compile_26 = ($context["move_columns"] ?? null)) && is_array($__internal_compile_26) || $__internal_compile_26 instanceof ArrayAccess ? ($__internal_compile_26[$context["mi"]] ?? null) : null), "name", [], "any", false, false, false, 189), "html", null, true);
                yield "\"";
                yield ((((($context["current_index"] ?? null) == $context["mi"]) || (($context["current_index"] ?? null) == ($context["mi"] + 1)))) ? (" disabled") : (""));
                yield ">
            ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::sprintf(_gettext("after %s"), PhpMyAdmin\Util::backquote($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (($__internal_compile_27 = ($context["move_columns"] ?? null)) && is_array($__internal_compile_27) || $__internal_compile_27 instanceof ArrayAccess ? ($__internal_compile_27[$context["mi"]] ?? null) : null), "name", [], "any", false, false, false, 191)))), "html", null, true);
                yield "
          </option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['mi'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "      </select>
      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
";
        }
        yield "
";
        if ((( !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["relation_parameters"] ?? null), "browserTransformationFeature", [], "any", false, false, false, 199)) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["relation_parameters"] ?? null), "columnCommentsFeature", [], "any", false, false, false, 199))) && ($context["browse_mime"] ?? null))) {
            yield "    <td class=\"text-center\">
      <select id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" size=\"1\" name=\"field_mimetype[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\">
        <option value=\"\">&nbsp;</option>
        ";
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["available_mime"] ?? null), "mimetype", [], "array", true, true, false, 203) && is_iterable((($__internal_compile_28 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_28) || $__internal_compile_28 instanceof ArrayAccess ? ($__internal_compile_28["mimetype"] ?? null) : null)))) {
                yield "          ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable((($__internal_compile_29 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_29) || $__internal_compile_29 instanceof ArrayAccess ? ($__internal_compile_29["mimetype"] ?? null) : null));
                foreach ($context['_seq'] as $context["_key"] => $context["media_type"]) {
                    yield "            <option value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::replace($context["media_type"], ["/" => "_"]), "html", null, true);
                    yield "\"";
                    yield ((((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Field", [], "array", true, true, false, 206) && CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["mime_map"] ?? null), (($__internal_compile_30 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_30) || $__internal_compile_30 instanceof ArrayAccess ? ($__internal_compile_30["Field"] ?? null) : null), [], "array", false, true, false, 206), "mimetype", [], "array", true, true, false, 206)) && ((($__internal_compile_31 = (($__internal_compile_32 =                     // line 207
($context["mime_map"] ?? null)) && is_array($__internal_compile_32) || $__internal_compile_32 instanceof ArrayAccess ? ($__internal_compile_32[(($__internal_compile_33 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_33) || $__internal_compile_33 instanceof ArrayAccess ? ($__internal_compile_33["Field"] ?? null) : null)] ?? null) : null)) && is_array($__internal_compile_31) || $__internal_compile_31 instanceof ArrayAccess ? ($__internal_compile_31["mimetype"] ?? null) : null) == Twig\Extension\CoreExtension::replace($context["media_type"], ["/" => "_"])))) ? (" selected") : (""));
                    yield ">
              ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::lower($this->env->getCharset(), $context["media_type"]), "html", null, true);
                    yield "
            </option>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['media_type'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                yield "        ";
            }
            yield "      </select>
      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
    <td class=\"text-center\">
      <select id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" size=\"1\" name=\"field_transformation[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\">
        <option value=\"\" title=\"";
yield _gettext("None");
            yield "\"></option>
        ";
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["available_mime"] ?? null), "transformation", [], "array", true, true, false, 218) && is_iterable((($__internal_compile_34 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_34) || $__internal_compile_34 instanceof ArrayAccess ? ($__internal_compile_34["transformation"] ?? null) : null)))) {
                yield "          ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable((($__internal_compile_35 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_35) || $__internal_compile_35 instanceof ArrayAccess ? ($__internal_compile_35["transformation"] ?? null) : null));
                foreach ($context['_seq'] as $context["mimekey"] => $context["transform"]) {
                    yield "            ";
                    $context["parts"] = Twig\Extension\CoreExtension::split($this->env->getCharset(), $context["transform"], ":");
                    yield "            <option value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_36 = (($__internal_compile_37 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_37) || $__internal_compile_37 instanceof ArrayAccess ? ($__internal_compile_37["transformation_file"] ?? null) : null)) && is_array($__internal_compile_36) || $__internal_compile_36 instanceof ArrayAccess ? ($__internal_compile_36[$context["mimekey"]] ?? null) : null), "html", null, true);
                    yield "\" title=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('get_description')->getCallable()((($__internal_compile_38 = (($__internal_compile_39 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_39) || $__internal_compile_39 instanceof ArrayAccess ? ($__internal_compile_39["transformation_file"] ?? null) : null)) && is_array($__internal_compile_38) || $__internal_compile_38 instanceof ArrayAccess ? ($__internal_compile_38[$context["mimekey"]] ?? null) : null)), "html", null, true);
                    yield "\"";
                    yield (((((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Field", [], "array", true, true, false, 222) && CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,                     // line 223
($context["mime_map"] ?? null), (($__internal_compile_40 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_40) || $__internal_compile_40 instanceof ArrayAccess ? ($__internal_compile_40["Field"] ?? null) : null), [], "array", false, true, false, 223), "transformation", [], "array", true, true, false, 223)) &&  !(null === (($__internal_compile_41 = (($__internal_compile_42 =                     // line 224
($context["mime_map"] ?? null)) && is_array($__internal_compile_42) || $__internal_compile_42 instanceof ArrayAccess ? ($__internal_compile_42[(($__internal_compile_43 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_43) || $__internal_compile_43 instanceof ArrayAccess ? ($__internal_compile_43["Field"] ?? null) : null)] ?? null) : null)) && is_array($__internal_compile_41) || $__internal_compile_41 instanceof ArrayAccess ? ($__internal_compile_41["transformation"] ?? null) : null))) && CoreExtension::matches((("@" . (($__internal_compile_44 = (($__internal_compile_45 =                     // line 225
($context["available_mime"] ?? null)) && is_array($__internal_compile_45) || $__internal_compile_45 instanceof ArrayAccess ? ($__internal_compile_45["transformation_file_quoted"] ?? null) : null)) && is_array($__internal_compile_44) || $__internal_compile_44 instanceof ArrayAccess ? ($__internal_compile_44[$context["mimekey"]] ?? null) : null)) . "3?@i"), (($__internal_compile_46 = (($__internal_compile_47 = ($context["mime_map"] ?? null)) && is_array($__internal_compile_47) || $__internal_compile_47 instanceof ArrayAccess ? ($__internal_compile_47[(($__internal_compile_48 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_48) || $__internal_compile_48 instanceof ArrayAccess ? ($__internal_compile_48["Field"] ?? null) : null)] ?? null) : null)) && is_array($__internal_compile_46) || $__internal_compile_46 instanceof ArrayAccess ? ($__internal_compile_46["transformation"] ?? null) : null)))) ? (" selected") : (""));
                    yield ">
              ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((((($this->env->getFunction('get_name')->getCallable()((($__internal_compile_49 = (($__internal_compile_50 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_50) || $__internal_compile_50 instanceof ArrayAccess ? ($__internal_compile_50["transformation_file"] ?? null) : null)) && is_array($__internal_compile_49) || $__internal_compile_49 instanceof ArrayAccess ? ($__internal_compile_49[$context["mimekey"]] ?? null) : null)) . " (") . Twig\Extension\CoreExtension::lower($this->env->getCharset(), (($__internal_compile_51 = ($context["parts"] ?? null)) && is_array($__internal_compile_51) || $__internal_compile_51 instanceof ArrayAccess ? ($__internal_compile_51[0] ?? null) : null))) . ":") . (($__internal_compile_52 = ($context["parts"] ?? null)) && is_array($__internal_compile_52) || $__internal_compile_52 instanceof ArrayAccess ? ($__internal_compile_52[1] ?? null) : null)) . ")"), "html", null, true);
                    yield "
            </option>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['mimekey'], $context['transform'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                yield "        ";
            }
            yield "      </select>
      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
    <td class=\"text-center\">
      <input id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" type=\"text\" name=\"field_transformation_options[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\" size=\"16\" class=\"textfield\" value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Field", [], "array", true, true, false, 235) && CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["mime_map"] ?? null), (($__internal_compile_53 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_53) || $__internal_compile_53 instanceof ArrayAccess ? ($__internal_compile_53["Field"] ?? null) : null), [], "array", false, true, false, 235), "transformation_options", [], "array", true, true, false, 235))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_54 = (($__internal_compile_55 = ($context["mime_map"] ?? null)) && is_array($__internal_compile_55) || $__internal_compile_55 instanceof ArrayAccess ? ($__internal_compile_55[(($__internal_compile_56 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_56) || $__internal_compile_56 instanceof ArrayAccess ? ($__internal_compile_56["Field"] ?? null) : null)] ?? null) : null)) && is_array($__internal_compile_54) || $__internal_compile_54 instanceof ArrayAccess ? ($__internal_compile_54["transformation_options"] ?? null) : null), "html", null, true)) : (yield ""));
            yield "\">
      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
    <td class=\"text-center\">
      <select id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" size=\"1\" name=\"field_input_transformation[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\">
        <option value=\"\" title=\"";
yield _gettext("None");
            yield "\"></option>
        ";
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["available_mime"] ?? null), "input_transformation", [], "array", true, true, false, 241) && is_iterable((($__internal_compile_57 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_57) || $__internal_compile_57 instanceof ArrayAccess ? ($__internal_compile_57["input_transformation"] ?? null) : null)))) {
                yield "          ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable((($__internal_compile_58 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_58) || $__internal_compile_58 instanceof ArrayAccess ? ($__internal_compile_58["input_transformation"] ?? null) : null));
                foreach ($context['_seq'] as $context["mimekey"] => $context["transform"]) {
                    yield "            ";
                    $context["parts"] = Twig\Extension\CoreExtension::split($this->env->getCharset(), $context["transform"], ":");
                    yield "            <option value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_59 = (($__internal_compile_60 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_60) || $__internal_compile_60 instanceof ArrayAccess ? ($__internal_compile_60["input_transformation_file"] ?? null) : null)) && is_array($__internal_compile_59) || $__internal_compile_59 instanceof ArrayAccess ? ($__internal_compile_59[$context["mimekey"]] ?? null) : null), "html", null, true);
                    yield "\" title=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('get_description')->getCallable()((($__internal_compile_61 = (($__internal_compile_62 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_62) || $__internal_compile_62 instanceof ArrayAccess ? ($__internal_compile_62["input_transformation_file"] ?? null) : null)) && is_array($__internal_compile_61) || $__internal_compile_61 instanceof ArrayAccess ? ($__internal_compile_61[$context["mimekey"]] ?? null) : null)), "html", null, true);
                    yield "\"";
                    yield ((((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Field", [], "array", true, true, false, 245) && CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["mime_map"] ?? null), (($__internal_compile_63 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_63) || $__internal_compile_63 instanceof ArrayAccess ? ($__internal_compile_63["Field"] ?? null) : null), [], "array", false, true, false, 245), "input_transformation", [], "array", true, true, false, 245)) && CoreExtension::matches((("@" . (($__internal_compile_64 = (($__internal_compile_65 =                     // line 246
($context["available_mime"] ?? null)) && is_array($__internal_compile_65) || $__internal_compile_65 instanceof ArrayAccess ? ($__internal_compile_65["input_transformation_file_quoted"] ?? null) : null)) && is_array($__internal_compile_64) || $__internal_compile_64 instanceof ArrayAccess ? ($__internal_compile_64[$context["mimekey"]] ?? null) : null)) . "3?@i"), (($__internal_compile_66 = (($__internal_compile_67 = ($context["mime_map"] ?? null)) && is_array($__internal_compile_67) || $__internal_compile_67 instanceof ArrayAccess ? ($__internal_compile_67[(($__internal_compile_68 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_68) || $__internal_compile_68 instanceof ArrayAccess ? ($__internal_compile_68["Field"] ?? null) : null)] ?? null) : null)) && is_array($__internal_compile_66) || $__internal_compile_66 instanceof ArrayAccess ? ($__internal_compile_66["input_transformation"] ?? null) : null)))) ? (" selected") : (""));
                    yield ">
              ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((((($this->env->getFunction('get_name')->getCallable()((($__internal_compile_69 = (($__internal_compile_70 = ($context["available_mime"] ?? null)) && is_array($__internal_compile_70) || $__internal_compile_70 instanceof ArrayAccess ? ($__internal_compile_70["input_transformation_file"] ?? null) : null)) && is_array($__internal_compile_69) || $__internal_compile_69 instanceof ArrayAccess ? ($__internal_compile_69[$context["mimekey"]] ?? null) : null)) . " (") . Twig\Extension\CoreExtension::lower($this->env->getCharset(), (($__internal_compile_71 = ($context["parts"] ?? null)) && is_array($__internal_compile_71) || $__internal_compile_71 instanceof ArrayAccess ? ($__internal_compile_71[0] ?? null) : null))) . ":") . (($__internal_compile_72 = ($context["parts"] ?? null)) && is_array($__internal_compile_72) || $__internal_compile_72 instanceof ArrayAccess ? ($__internal_compile_72[1] ?? null) : null)) . ")"), "html", null, true);
                    yield "
            </option>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['mimekey'], $context['transform'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                yield "        ";
            }
            yield "      </select>
      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
    <td class=\"text-center\">
      <input id=\"field_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($context["ci"] ?? null) - ($context["ci_offset"] ?? null)), "html", null, true);
            yield "\" type=\"text\" name=\"field_input_transformation_options[";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["column_number"] ?? null), "html", null, true);
            yield "]\" size=\"16\" class=\"textfield\" value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, ($context["column_meta"] ?? null), "Field", [], "array", true, true, false, 256) && CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["mime_map"] ?? null), (($__internal_compile_73 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_73) || $__internal_compile_73 instanceof ArrayAccess ? ($__internal_compile_73["Field"] ?? null) : null), [], "array", false, true, false, 256), "input_transformation_options", [], "array", true, true, false, 256))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_74 = (($__internal_compile_75 = ($context["mime_map"] ?? null)) && is_array($__internal_compile_75) || $__internal_compile_75 instanceof ArrayAccess ? ($__internal_compile_75[(($__internal_compile_76 = ($context["column_meta"] ?? null)) && is_array($__internal_compile_76) || $__internal_compile_76 instanceof ArrayAccess ? ($__internal_compile_76["Field"] ?? null) : null)] ?? null) : null)) && is_array($__internal_compile_74) || $__internal_compile_74 instanceof ArrayAccess ? ($__internal_compile_74["input_transformation_options"] ?? null) : null), "html", null, true)) : (yield ""));
            yield "\">
      ";
            $context["ci"] = (($context["ci"] ?? null) + 1);
            yield "    </td>
";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "columns_definitions/column_attributes.twig";
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
        return array (  786 => 258,  784 => 257,  780 => 256,  773 => 255,  769 => 253,  767 => 252,  764 => 251,  761 => 250,  752 => 247,  748 => 246,  747 => 245,  741 => 244,  738 => 243,  733 => 242,  731 => 241,  728 => 240,  719 => 239,  715 => 237,  713 => 236,  709 => 235,  702 => 234,  698 => 232,  696 => 231,  693 => 230,  690 => 229,  681 => 226,  677 => 225,  676 => 224,  675 => 223,  674 => 222,  668 => 221,  665 => 220,  660 => 219,  658 => 218,  655 => 217,  646 => 216,  642 => 214,  640 => 213,  637 => 212,  634 => 211,  625 => 208,  621 => 207,  620 => 206,  616 => 205,  611 => 204,  609 => 203,  600 => 201,  597 => 200,  595 => 199,  592 => 198,  588 => 196,  586 => 195,  583 => 194,  574 => 191,  570 => 190,  566 => 189,  562 => 188,  559 => 187,  554 => 185,  545 => 183,  541 => 181,  535 => 180,  532 => 179,  529 => 178,  526 => 177,  523 => 176,  518 => 175,  515 => 174,  512 => 173,  509 => 172,  507 => 171,  503 => 168,  500 => 167,  490 => 165,  482 => 163,  480 => 162,  476 => 160,  467 => 157,  461 => 156,  458 => 155,  455 => 154,  453 => 153,  450 => 152,  446 => 151,  438 => 150,  435 => 149,  433 => 148,  430 => 146,  428 => 145,  425 => 144,  423 => 143,  414 => 142,  410 => 140,  408 => 139,  404 => 138,  397 => 137,  394 => 136,  390 => 134,  388 => 133,  381 => 129,  376 => 128,  370 => 125,  365 => 124,  359 => 121,  354 => 120,  348 => 117,  343 => 116,  337 => 113,  332 => 112,  328 => 111,  320 => 110,  317 => 109,  315 => 108,  313 => 107,  309 => 105,  306 => 104,  301 => 102,  297 => 101,  290 => 100,  287 => 99,  285 => 98,  283 => 97,  280 => 96,  278 => 95,  274 => 94,  267 => 93,  263 => 91,  260 => 90,  258 => 88,  257 => 87,  256 => 86,  255 => 85,  254 => 84,  253 => 83,  252 => 82,  250 => 81,  246 => 78,  244 => 77,  241 => 76,  234 => 74,  227 => 72,  225 => 71,  222 => 70,  216 => 69,  212 => 68,  205 => 67,  201 => 66,  191 => 64,  187 => 61,  184 => 60,  176 => 58,  168 => 56,  166 => 55,  163 => 54,  155 => 50,  153 => 49,  147 => 46,  141 => 43,  138 => 42,  133 => 40,  130 => 39,  125 => 37,  117 => 36,  113 => 34,  111 => 33,  107 => 31,  100 => 30,  94 => 29,  87 => 28,  83 => 26,  81 => 25,  76 => 23,  72 => 22,  65 => 21,  61 => 19,  58 => 18,  56 => 16,  55 => 15,  54 => 14,  53 => 13,  52 => 12,  51 => 11,  49 => 10,  45 => 7,  43 => 6,  40 => 3,  38 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "columns_definitions/column_attributes.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\columns_definitions\\column_attributes.twig");
    }
}

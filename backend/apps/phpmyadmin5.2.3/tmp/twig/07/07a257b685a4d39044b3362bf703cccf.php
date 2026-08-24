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


class __TwigTemplate_25a5add1f5a399407d1736ec2700a461 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "<div class=\"container\">
  <h2 class=\"my-3\">
    ";
        yield PhpMyAdmin\Html\Generator::getImage("b_import", _gettext("Import"));
        yield "
    ";
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        yield "  </h2>

  ";
        yield ($context["page_settings_error_html"] ?? null);
        yield "
  ";
        yield ($context["page_settings_html"] ?? null);
        yield "

  <iframe id=\"import_upload_iframe\" name=\"import_upload_iframe\" width=\"1\" height=\"1\" class=\"hide\"></iframe>
  <div id=\"import_form_status\" class=\"hide\"></div>
  <div id=\"importmain\">
    <img src=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['PhpMyAdmin\Twig\AssetExtension']->getImagePath("ajax_clock_small.gif"), "html", null, true);
        yield "\" width=\"16\" height=\"16\" alt=\"ajax clock\" class=\"hide\">

    <script type=\"text/javascript\">
";
        yield from         $this->loadTemplate("import/javascript.twig", "import.twig", 17)->unwrap()->yield(CoreExtension::toArray(["upload_id" => ($context["upload_id"] ?? null), "handler" => ($context["handler"] ?? null)]));
        yield "//]]>
    </script>

    <form id=\"import_file_form\" action=\"";
        yield PhpMyAdmin\Url::getFromRoute("/import");
        yield "\" method=\"post\" enctype=\"multipart/form-data\" name=\"import\" class=\"ajax\"";
        if ((($context["handler"] ?? null) != "PhpMyAdmin\\Plugins\\Import\\Upload\\UploadNoplugin")) {
            yield " target=\"import_upload_iframe\"";
        }
        yield ">
      ";
        yield PhpMyAdmin\Url::getHiddenInputs(($context["hidden_inputs"] ?? null));
        yield "

      <div class=\"card mb-3\">
        <div class=\"card-header\">";
yield _gettext("File to import:");
        yield "</div>
        <div class=\"card-body\">
          ";
        yield "          ";
        if ( !Twig\Extension\CoreExtension::testEmpty(($context["compressions"] ?? null))) {
            yield "            <p class=\"card-text\">
              ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::sprintf(_gettext("File may be compressed (%s) or uncompressed."), Twig\Extension\CoreExtension::join(($context["compressions"] ?? null), ", ")), "html", null, true);
            yield "<br>
              ";
yield _gettext("A compressed file's name must end in <strong>.[format].[compression]</strong>. Example: <strong>.sql.zip</strong>");
            yield "            </p>
          ";
        }
        yield "
          ";
        if ((($context["is_upload"] ?? null) &&  !Twig\Extension\CoreExtension::testEmpty(($context["upload_dir"] ?? null)))) {
            yield "            ";
            $context["use_local_file_import"] = ( !Twig\Extension\CoreExtension::testEmpty(($context["timeout_passed_global"] ?? null)) &&  !Twig\Extension\CoreExtension::testEmpty(($context["local_import_file"] ?? null)));
            yield "            <ul class=\"nav nav-pills mb-3\" id=\"importFileTab\" role=\"tablist\">
              <li class=\"nav-item\" role=\"presentation\">
                <button class=\"nav-link";
            yield (( !($context["use_local_file_import"] ?? null)) ? (" active") : (""));
            yield "\" id=\"uploadFileTab\" data-bs-toggle=\"tab\" data-bs-target=\"#uploadFile\" type=\"button\" role=\"tab\" aria-controls=\"uploadFile\" aria-selected=\"";
            yield (( !($context["use_local_file_import"] ?? null)) ? ("true") : ("false"));
            yield "\">";
yield _gettext("Upload a file");
            yield "</button>
              </li>
              <li class=\"nav-item\" role=\"presentation\">
                <button class=\"nav-link";
            yield ((($context["use_local_file_import"] ?? null)) ? (" active") : (""));
            yield "\" id=\"localFileTab\" data-bs-toggle=\"tab\" data-bs-target=\"#localFile\" type=\"button\" role=\"tab\" aria-controls=\"localFile\" aria-selected=\"";
            yield ((($context["use_local_file_import"] ?? null)) ? ("true") : ("false"));
            yield "\">";
yield _gettext("Select file to import");
            yield "</button>
              </li>
            </ul>
            <div class=\"tab-content mb-3\" id=\"importFileTabContent\">
              <div class=\"tab-pane fade";
            yield (( !($context["use_local_file_import"] ?? null)) ? (" show active") : (""));
            yield "\" id=\"uploadFile\" role=\"tabpanel\" aria-labelledby=\"uploadFileTab\">
                <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["max_upload_size"] ?? null), "html", null, true);
            yield "\">
                <div class=\"mb-3\">
                  <label class=\"form-label\" for=\"input_import_file\">";
yield _gettext("Browse your computer:");
            yield " <small>";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["formatted_maximum_upload_size"] ?? null), "html", null, true);
            yield "</small></label>
                  <input class=\"form-control\" type=\"file\" name=\"import_file\" id=\"input_import_file\">
                </div>
                <div id=\"upload_form_status\" class=\"hide\"></div>
                <div id=\"upload_form_status_info\" class=\"hide\"></div>
                <p class=\"card-text\">";
yield _gettext("You may also drag and drop a file on any page.");
            yield "</p>
              </div>

              <div class=\"tab-pane fade";
            yield ((($context["use_local_file_import"] ?? null)) ? (" show active") : (""));
            yield "\" id=\"localFile\" role=\"tabpanel\" aria-labelledby=\"localFileTab\">
                ";
            if ((($context["local_files"] ?? null) === false)) {
                yield "                  ";
                yield $this->env->getFilter('error')->getCallable()(_gettext("The directory you set for upload work cannot be reached."));
                yield "
                ";
            } elseif ( !Twig\Extension\CoreExtension::testEmpty(            // line 61
($context["local_files"] ?? null))) {
                yield "                  <label class=\"form-label\" for=\"select_local_import_file\">";
                yield PhpMyAdmin\Sanitize::sanitizeMessage(Twig\Extension\CoreExtension::sprintf(_gettext("Select from the web server upload directory [strong]%s[/strong]:"), ($context["user_upload_dir"] ?? null)));
                yield "</label>
                  <select class=\"form-select\" size=\"1\" name=\"local_import_file\" id=\"select_local_import_file\">
                    <option value=\"\"></option>
                    ";
                yield ($context["local_files"] ?? null);
                yield "
                  </select>
                ";
            } else {
                yield "                  <div class=\"alert alert-info\" role=\"alert\">
                    ";
yield _gettext("There are no files to import!");
                yield "                  </div>
                ";
            }
            yield "              </div>
            </div>
          ";
        } elseif (        // line 74
($context["is_upload"] ?? null)) {
            yield "            <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["max_upload_size"] ?? null), "html", null, true);
            yield "\">
            <div class=\"mb-3\">
              <label class=\"form-label\" for=\"input_import_file\">";
yield _gettext("Browse your computer:");
            yield " <small>";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["formatted_maximum_upload_size"] ?? null), "html", null, true);
            yield "</small></label>
              <input class=\"form-control\" type=\"file\" name=\"import_file\" id=\"input_import_file\">
            </div>
            <div id=\"upload_form_status\" class=\"hide\"></div>
            <div id=\"upload_form_status_info\" class=\"hide\"></div>
            <p class=\"card-text\">";
yield _gettext("You may also drag and drop a file on any page.");
            yield "</p>
          ";
        } elseif ( !Twig\Extension\CoreExtension::testEmpty(        // line 83
($context["upload_dir"] ?? null))) {
            yield "            ";
            if ((($context["local_files"] ?? null) === false)) {
                yield "              ";
                yield $this->env->getFilter('error')->getCallable()(_gettext("The directory you set for upload work cannot be reached."));
                yield "
            ";
            } elseif ( !Twig\Extension\CoreExtension::testEmpty(            // line 86
($context["local_files"] ?? null))) {
                yield "              <div class=\"mb-3\">
                <label class=\"form-label\" for=\"select_local_import_file\">";
                yield PhpMyAdmin\Sanitize::sanitizeMessage(Twig\Extension\CoreExtension::sprintf(_gettext("Select from the web server upload directory [strong]%s[/strong]:"), ($context["user_upload_dir"] ?? null)));
                yield "</label>
                <select class=\"form-select\" size=\"1\" name=\"local_import_file\" id=\"select_local_import_file\">
                  <option value=\"\"></option>
                  ";
                yield ($context["local_files"] ?? null);
                yield "
                </select>
              </div>
            ";
            } else {
                yield "              <div class=\"alert alert-info\" role=\"alert\">
                ";
yield _gettext("There are no files to import!");
                yield "              </div>
            ";
            }
            yield "          ";
        } else {
            yield "            ";
            yield $this->env->getFilter('notice')->getCallable()(_gettext("File uploads are not allowed on this server."));
            yield "
          ";
        }
        yield "
          <label class=\"form-label\" for=\"charset_of_file\">";
yield _gettext("Character set of the file:");
        yield "</label>
          ";
        if (($context["is_encoding_supported"] ?? null)) {
            yield "            <select class=\"form-select\" id=\"charset_of_file\" name=\"charset_of_file\" size=\"1\">
              ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["encodings"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["charset"]) {
                yield "                <option value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["charset"], "html", null, true);
                yield "\"";
                if (((Twig\Extension\CoreExtension::testEmpty(($context["import_charset"] ?? null)) && ($context["charset"] == "utf-8")) || ($context["charset"] == ($context["import_charset"] ?? null)))) {
                    yield " selected";
                }
                yield ">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["charset"], "html", null, true);
                yield "</option>
              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['charset'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "            </select>
          ";
        } else {
            yield "            <select class=\"form-select\" lang=\"en\" dir=\"ltr\" name=\"charset_of_file\" id=\"charset_of_file\">
              <option value=\"\"></option>
              ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["charsets"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["charset"]) {
                yield "                <option value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "getName", [], "method", false, false, false, 116), "html", null, true);
                yield "\" title=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "getDescription", [], "method", false, false, false, 116), "html", null, true);
                yield "\"";
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "getName", [], "method", false, false, false, 116) == "utf8")) ? (" selected") : (""));
                yield ">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "getName", [], "method", false, false, false, 117), "html", null, true);
                yield "</option>
              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['charset'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "            </select>
          ";
        }
        yield "        </div>
      </div>

      <div class=\"card mb-3\">
        <div class=\"card-header\">";
yield _gettext("Partial import:");
        yield "</div>
        <div class=\"card-body\">
          ";
        if ((array_key_exists("timeout_passed", $context) && ($context["timeout_passed"] ?? null))) {
            yield "            <input type=\"hidden\" name=\"skip\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["offset"] ?? null), "html", null, true);
            yield "\">
            <div class=\"alert alert-info\" role=\"alert\">
              ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::sprintf(_gettext("Previous import timed out, after resubmitting will continue from position %d."), ($context["offset"] ?? null)), "html", null, true);
            yield "
            </div>
          ";
        }
        yield "
          <div class=\"form-check form-switch mb-3\">
            <input class=\"form-check-input\" type=\"checkbox\" role=\"switch\" name=\"allow_interrupt\" value=\"yes\" id=\"checkbox_allow_interrupt\"";
        yield ($context["is_allow_interrupt_checked"] ?? null);
        yield " aria-describedby=\"allowInterruptHelp\">
            <label class=\"form-check-label\" for=\"checkbox_allow_interrupt\">
              ";
yield _gettext("Allow the interruption of an import in case the script detects it is close to the PHP timeout limit.");
        yield "            </label>
            <div id=\"allowInterruptHelp\" class=\"form-text\">";
yield _gettext("This might be a good way to import large files, however it can break transactions.");
        yield "</div>
          </div>

          ";
        if ( !(array_key_exists("timeout_passed", $context) && ($context["timeout_passed"] ?? null))) {
            yield "            <label class=\"form-label\" for=\"text_skip_queries\">";
yield _gettext("Skip this number of queries (for SQL) starting from the first one:");
            yield "</label>
            <input class=\"form-control\" type=\"number\" name=\"skip_queries\" value=\"";
            yield ($context["skip_queries_default"] ?? null);
            yield "\" id=\"text_skip_queries\" min=\"0\">
          ";
        } else {
            yield "            ";
            yield "            <input type=\"hidden\" name=\"skip_queries\" value=\"";
            yield ($context["skip_queries_default"] ?? null);
            yield "\" id=\"text_skip_queries\">
          ";
        }
        yield "        </div>
      </div>

      <div class=\"card mb-3\">
        <div class=\"card-header\">";
yield _gettext("Other options");
        yield "</div>
        <div class=\"card-body\">
          <input type=\"hidden\" name=\"fk_checks\" value=\"0\">
          <div class=\"form-check form-switch\">
            <input class=\"form-check-input\" type=\"checkbox\" role=\"switch\" name=\"fk_checks\" id=\"fk_checks\" value=\"1\"";
        yield ((($context["is_foreign_key_check"] ?? null)) ? (" checked") : (""));
        yield ">
            <label class=\"form-check-label\" for=\"fk_checks\">";
yield _gettext("Enable foreign key checks");
        yield "</label>
          </div>
        </div>
      </div>

      <div class=\"card mb-3\">
        <div class=\"card-header\">";
yield _gettext("Format");
        yield "</div>
        <div class=\"card-body\">
          <select class=\"form-select\" id=\"plugins\" name=\"format\" aria-label=\"";
yield _gettext("Format");
        yield "\">
            ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["plugins_choice"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
            yield "              <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["option"], "name", [], "any", false, false, false, 169), "html", null, true);
            yield "\"";
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["option"], "is_selected", [], "any", false, false, false, 169)) ? (" selected") : (""));
            yield ">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["option"], "text", [], "any", false, false, false, 169), "html", null, true);
            yield "</option>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "          </select>

          ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["plugins_choice"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
            yield "            <input type=\"hidden\" id=\"force_file_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["option"], "name", [], "any", false, false, false, 174), "html", null, true);
            yield "\" value=\"true\">
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "
          <div id=\"import_notification\"></div>
        </div>
      </div>

      <div class=\"card mb-3\" id=\"format_specific_opts\">
        <div class=\"card-header\">";
yield _gettext("Format-specific options:");
        yield "</div>
        <div class=\"card-body\">
          ";
        yield ($context["options"] ?? null);
        yield "
        </div>
      </div>

      ";
        yield "      ";
        if (($context["can_convert_kanji"] ?? null)) {
            yield "        <div class=\"card mb-3\" id=\"kanji_encoding\">
          <div class=\"card-header\">";
yield _gettext("Encoding Conversion:");
            yield "</div>
          <div class=\"card-body\">
            ";
            yield from             $this->loadTemplate("encoding/kanji_encoding_form.twig", "import.twig", 193)->unwrap()->yield($context);
            yield "          </div>
        </div>
      ";
        }
        yield "
      <div id=\"submit\">
        <input id=\"buttonGo\" class=\"btn btn-primary\" type=\"submit\" value=\"";
yield _gettext("Import");
        yield "\">
      </div>
    </form>
  </div>
</div>
";
        return; yield '';
    }
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "import.twig";
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
        return array (  529 => 4,  519 => 199,  514 => 197,  509 => 194,  507 => 193,  503 => 191,  499 => 190,  496 => 189,  489 => 184,  485 => 182,  476 => 176,  467 => 174,  463 => 173,  459 => 171,  446 => 169,  442 => 168,  439 => 167,  434 => 165,  425 => 159,  420 => 158,  414 => 154,  407 => 150,  401 => 148,  399 => 147,  394 => 145,  389 => 144,  387 => 143,  382 => 140,  378 => 139,  372 => 136,  368 => 134,  362 => 131,  356 => 129,  354 => 128,  350 => 126,  343 => 122,  339 => 120,  332 => 118,  330 => 117,  322 => 116,  318 => 115,  314 => 113,  310 => 111,  303 => 109,  301 => 108,  293 => 107,  289 => 106,  286 => 105,  284 => 104,  281 => 103,  277 => 102,  271 => 100,  268 => 99,  264 => 97,  260 => 95,  253 => 91,  247 => 88,  244 => 87,  242 => 86,  237 => 85,  234 => 84,  232 => 83,  229 => 82,  219 => 77,  212 => 75,  210 => 74,  206 => 72,  202 => 70,  198 => 68,  192 => 65,  185 => 62,  183 => 61,  178 => 60,  176 => 59,  172 => 58,  167 => 55,  157 => 50,  151 => 48,  147 => 47,  136 => 43,  126 => 40,  122 => 38,  119 => 37,  117 => 36,  114 => 35,  110 => 33,  105 => 31,  102 => 30,  99 => 29,  95 => 26,  88 => 23,  82 => 22,  79 => 21,  74 => 18,  72 => 17,  65 => 13,  57 => 8,  53 => 7,  49 => 5,  47 => 4,  43 => 3,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "import.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\import.twig");
    }
}

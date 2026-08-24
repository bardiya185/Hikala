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


class __TwigTemplate_8f7344c2b7bfb110d3640e1721abb11d extends Template
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
        yield ($context["login_header"] ?? null);
        yield "

";
        if (($context["is_demo"] ?? null)) {
            yield "  <div class=\"card mb-4\">
    <div class=\"card-header\">";
yield _gettext("phpMyAdmin Demo Server");
            yield "</div>
    <div class=\"card-body\">
      ";
            $___internal_parse_0_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                yield "        ";
yield _gettext("You are using the demo server. You can do anything here, but please do not change root, debian-sys-maint and pma users. More information is available at %s.");
                yield "      ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            yield Twig\Extension\CoreExtension::sprintf($___internal_parse_0_, "<a href=\"url.php?url=https://demo.phpmyadmin.net/\" target=\"_blank\" rel=\"noopener noreferrer\">demo.phpmyadmin.net</a>");
            yield "    </div>
  </div>
";
        }
        yield "
";
        yield ($context["error_messages"] ?? null);
        yield "

";
        if ( !Twig\Extension\CoreExtension::testEmpty(($context["available_languages"] ?? null))) {
            yield "  <div class='hide js-show'>
    <div class=\"card mb-4\">
      <div class=\"card-header\">
        <span id=\"languageSelectLabel\">
          ";
yield _gettext("Language");
            yield "          ";
            if ((_gettext("Language") != "Language")) {
                yield "                        <i lang=\"en\" dir=\"ltr\">(Language)</i>
          ";
            }
            yield "        </span>
      </div>
      <div class=\"card-body\">
        <form method=\"get\" action=\"";
            yield PhpMyAdmin\Url::getFromRoute("/");
            yield "\" class=\"disableAjax\">
          ";
            yield PhpMyAdmin\Url::getHiddenInputs(($context["form_params"] ?? null));
            yield "
          <select name=\"lang\" class=\"form-select autosubmit\" lang=\"en\" dir=\"ltr\" id=\"languageSelect\" aria-labelledby=\"languageSelectLabel\">
            ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["available_languages"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["language"]) {
                yield "              <option value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["language"], "getCode", [], "method", false, false, false, 36)), "html", null, true);
                yield "\"";
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["language"], "isActive", [], "method", false, false, false, 36)) ? (" selected") : (""));
                yield ">";
                yield CoreExtension::getAttribute($this->env, $this->source, $context["language"], "getName", [], "method", false, false, false, 37);
                yield "</option>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['language'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            yield "          </select>
        </form>
      </div>
    </div>
  </div>
";
        }
        yield "
<form method=\"post\" id=\"login_form\" action=\"index.php?route=/\" name=\"login_form\" class=\"";
        yield (( !($context["is_session_expired"] ?? null)) ? ("disableAjax hide ") : (""));
        yield "js-show\"";
        yield (( !($context["has_autocomplete"] ?? null)) ? (" autocomplete=\"off\"") : (""));
        yield ">
  ";
        yield "  ";
        yield PhpMyAdmin\Url::getHiddenInputs(($context["form_params"] ?? null), "", 0, "server");
        yield "
  <input type=\"hidden\" name=\"set_session\" value=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["session_id"] ?? null), "html", null, true);
        yield "\">
  ";
        if (($context["is_session_expired"] ?? null)) {
            yield "    <input type=\"hidden\" name=\"session_timedout\" value=\"1\">
  ";
        }
        yield "
  <div class=\"card mb-4\">
    <div class=\"card-header\">
      ";
yield _gettext("Log in");
        yield "      ";
        yield PhpMyAdmin\Html\MySQLDocumentation::showDocumentation("index");
        yield "
    </div>
    <div class=\"card-body\">
      ";
        if (($context["is_arbitrary_server_allowed"] ?? null)) {
            yield "        <div class=\"row mb-3\">
          <label for=\"serverNameInput\" class=\"col-sm-4 col-form-label\" title=\"";
yield _gettext("You can enter hostname/IP address and port separated by space.");
            yield "\">
            ";
yield _gettext("Server:");
            yield "          </label>
          <div class=\"col-sm-8\">
            <input type=\"text\" name=\"pma_servername\" id=\"serverNameInput\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["default_server"] ?? null), "html", null, true);
            yield "\" class=\"form-control\" title=\"";
yield _gettext("You can enter hostname/IP address and port separated by space.");
            yield "\">
          </div>
        </div>
      ";
        }
        yield "
      <div class=\"row mb-3\">
        <label for=\"input_username\" class=\"col-sm-4 col-form-label\">
          ";
yield _gettext("Username:");
        yield "        </label>
        <div class=\"col-sm-8\">
          <input type=\"text\" name=\"pma_username\" id=\"input_username\" value=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["default_user"] ?? null), "html", null, true);
        yield "\" class=\"form-control\" autocomplete=\"username\" spellcheck=\"false\">
        </div>
      </div>

      <div class=\"row\">
        <label for=\"input_password\" class=\"col-sm-4 col-form-label\">
          ";
yield _gettext("Password:");
        yield "        </label>
        <div class=\"col-sm-8\">
          <input type=\"password\" name=\"pma_password\" id=\"input_password\" value=\"\" class=\"form-control\" autocomplete=\"current-password\" spellcheck=\"false\">
        </div>
      </div>

      ";
        if (($context["has_servers"] ?? null)) {
            yield "        <div class=\"row mt-3\">
          <label for=\"select_server\" class=\"col-sm-4 col-form-label\">
            ";
yield _gettext("Server choice:");
            yield "          </label>
          <div class=\"col-sm-8\">
            <select name=\"server\" id=\"select_server\" class=\"form-select\"";
            if (($context["is_arbitrary_server_allowed"] ?? null)) {
                yield " onchange=\"document.forms['login_form'].elements['pma_servername'].value = ''\"";
            }
            yield ">
              ";
            yield ($context["server_options"] ?? null);
            yield "
            </select>
          </div>
        </div>
      ";
        } else {
            yield "        <input type=\"hidden\" name=\"server\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["server"] ?? null), "html", null, true);
            yield "\">
      ";
        }
        yield "    </div>
    <div class=\"card-footer\">
      ";
        if (($context["has_captcha"] ?? null)) {
            yield "        <script src=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["captcha_api"] ?? null), "html", null, true);
            yield "?hl=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["lang"] ?? null), "html", null, true);
            yield "\" async defer></script>
        ";
            if (($context["use_captcha_checkbox"] ?? null)) {
                yield "          <div class=\"row g-3\">
            <div class=\"col\">
              <div class=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["captcha_req"] ?? null), "html", null, true);
                yield "\" data-sitekey=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["captcha_key"] ?? null), "html", null, true);
                yield "\"></div>
            </div>
            <div class=\"col align-self-center text-end\">
              <input class=\"btn btn-primary\" value=\"";
yield _gettext("Log in");
                yield "\" type=\"submit\" id=\"input_go\">
            </div>
          </div>
        ";
            } else {
                yield "          <input class=\"btn btn-primary ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["captcha_req"] ?? null), "html", null, true);
                yield "\" data-sitekey=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["captcha_key"] ?? null), "html", null, true);
                yield "\" data-callback=\"Functions_recaptchaCallback\" value=\"";
yield _gettext("Log in");
                yield "\" type=\"submit\" id=\"input_go\">
        ";
            }
            yield "      ";
        } else {
            yield "        <input class=\"btn btn-primary\" value=\"";
yield _gettext("Log in");
            yield "\" type=\"submit\" id=\"input_go\">
      ";
        }
        yield "    </div>
  </div>
</form>

";
        if ( !Twig\Extension\CoreExtension::testEmpty(($context["errors"] ?? null))) {
            yield "  <div id=\"pma_errors\">
    ";
            yield ($context["errors"] ?? null);
            yield "
  </div>
  </div>
  </div>
";
        }
        yield "
";
        yield ($context["login_footer"] ?? null);
        yield "

";
        yield ($context["config_footer"] ?? null);
        yield "
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "login/form.twig";
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
        return array (  334 => 140,  329 => 138,  326 => 137,  318 => 132,  315 => 131,  313 => 130,  307 => 126,  301 => 124,  298 => 123,  288 => 121,  282 => 117,  273 => 114,  269 => 112,  267 => 111,  260 => 110,  258 => 109,  254 => 107,  248 => 105,  240 => 100,  234 => 99,  230 => 96,  225 => 93,  223 => 92,  215 => 86,  205 => 79,  201 => 77,  195 => 73,  189 => 69,  185 => 68,  181 => 66,  177 => 64,  173 => 63,  171 => 62,  164 => 59,  158 => 55,  154 => 53,  152 => 52,  148 => 51,  143 => 50,  137 => 48,  134 => 46,  126 => 40,  119 => 38,  117 => 37,  111 => 36,  107 => 35,  102 => 33,  98 => 32,  93 => 29,  89 => 25,  86 => 24,  79 => 19,  77 => 18,  72 => 16,  69 => 15,  64 => 12,  62 => 7,  58 => 11,  55 => 8,  53 => 7,  49 => 5,  45 => 4,  43 => 3,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "login/form.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\login\\form.twig");
    }
}

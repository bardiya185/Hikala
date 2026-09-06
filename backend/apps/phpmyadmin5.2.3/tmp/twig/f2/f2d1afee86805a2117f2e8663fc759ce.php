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


class __TwigTemplate_7d66f47f3db2db3813ea61021da3f385 extends Template
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
        if (($context["is_git_revision"] ?? null)) {
            yield "  <div id=\"is_git_revision\"></div>
";
        }
        yield "
";
        yield ($context["message"] ?? null);
        yield "

";
        yield ($context["partial_logout"] ?? null);
        yield "

<div id=\"maincontainer\">
  ";
        yield ($context["sync_favorite_tables"] ?? null);
        yield "
  <div class=\"container-fluid\">
    <div class=\"row mb-3\">
      <div class=\"col-lg-7 col-12\">
        ";
        if (($context["has_server"] ?? null)) {
            yield "          ";
            if (($context["is_demo"] ?? null)) {
                yield "            <div class=\"card mt-4\">
              <div class=\"card-header\">
                ";
yield _gettext("phpMyAdmin Demo Server");
                yield "              </div>
              <div class=\"card-body\">
                ";
                $___internal_parse_0_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                    yield "                  ";
yield _gettext("You are using the demo server. You can do anything here, but please do not change root, debian-sys-maint and pma users. More information is available at %s.");
                    yield "                ";
                    return; yield '';
                })())) ? '' : new Markup($tmp, $this->env->getCharset());
                yield Twig\Extension\CoreExtension::sprintf($___internal_parse_0_, "<a href=\"url.php?url=https://demo.phpmyadmin.net/\" target=\"_blank\" rel=\"noopener noreferrer\">demo.phpmyadmin.net</a>");
                yield "              </div>
            </div>
          ";
            }
            yield "
            <div class=\"card mt-4\">
              <div class=\"card-header\">
                ";
yield _gettext("General settings");
            yield "              </div>
              <ul class=\"list-group list-group-flush\">
                ";
            if (($context["has_server_selection"] ?? null)) {
                yield "                  <li id=\"li_select_server\" class=\"list-group-item\">
                    ";
                yield PhpMyAdmin\Html\Generator::getImage("s_host");
                yield "
                    ";
                yield ($context["server_selection"] ?? null);
                yield "
                  </li>
                ";
            }
            yield "
                ";
            if ((($context["server"] ?? null) > 0)) {
                yield "                  ";
                if (($context["has_change_password_link"] ?? null)) {
                    yield "                    <li id=\"li_change_password\" class=\"list-group-item\">
                      <a href=\"";
                    yield PhpMyAdmin\Url::getFromRoute("/user-password");
                    yield "\" id=\"change_password_anchor\" class=\"ajax\">
                        ";
                    yield PhpMyAdmin\Html\Generator::getIcon("s_passwd", _gettext("Change password"), true);
                    yield "
                      </a>
                    </li>
                  ";
                }
                yield "
                  <li id=\"li_select_mysql_collation\" class=\"list-group-item\">
                    <form method=\"post\" action=\"";
                yield PhpMyAdmin\Url::getFromRoute("/collation-connection");
                yield "\" class=\"row row-cols-lg-auto align-items-center disableAjax\">
                      ";
                yield PhpMyAdmin\Url::getHiddenInputs(null, null, 4, "collation_connection");
                yield "
                      <div class=\"col-12\">
                        <label for=\"collationConnectionSelect\" class=\"col-form-label\">
                          ";
                yield PhpMyAdmin\Html\Generator::getImage("s_asci");
                yield "
                          ";
yield _gettext("Server connection collation:");
                yield "                          ";
                yield PhpMyAdmin\Html\MySQLDocumentation::show("charset-connection");
                yield "
                        </label>
                      </div>
                      ";
                if ( !Twig\Extension\CoreExtension::testEmpty(($context["charsets"] ?? null))) {
                    yield "                      <div class=\"col-12\">
                        <select lang=\"en\" dir=\"ltr\" name=\"collation_connection\" id=\"collationConnectionSelect\" class=\"form-select autosubmit\">
                          <option value=\"\">";
yield _gettext("Collation");
                    yield "</option>
                          <option value=\"\"></option>
                          ";
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(($context["charsets"] ?? null));
                    foreach ($context['_seq'] as $context["_key"] => $context["charset"]) {
                        yield "                            <optgroup label=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "name", [], "any", false, false, false, 67), "html", null, true);
                        yield "\" title=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "description", [], "any", false, false, false, 67), "html", null, true);
                        yield "\">
                              ";
                        $context['_parent'] = $context;
                        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["charset"], "collations", [], "any", false, false, false, 68));
                        foreach ($context['_seq'] as $context["_key"] => $context["collation"]) {
                            yield "                                <option value=\"";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["collation"], "name", [], "any", false, false, false, 69), "html", null, true);
                            yield "\" title=\"";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["collation"], "description", [], "any", false, false, false, 69), "html", null, true);
                            yield "\"";
                            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["collation"], "is_selected", [], "any", false, false, false, 69)) ? (" selected") : (""));
                            yield ">";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["collation"], "name", [], "any", false, false, false, 70), "html", null, true);
                            yield "</option>
                              ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['collation'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        yield "                            </optgroup>
                          ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['charset'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    yield "                        </select>
                      </div>
                      ";
                }
                yield "                    </form>
                  </li>

                  <li id=\"li_user_preferences\" class=\"list-group-item\">
                    <a href=\"";
                yield PhpMyAdmin\Url::getFromRoute("/preferences/manage");
                yield "\">
                      ";
                yield PhpMyAdmin\Html\Generator::getIcon("b_tblops", _gettext("More settings"), true);
                yield "
                    </a>
                  </li>
                ";
            }
            yield "              </ul>
            </div>
          ";
        }
        yield "
            ";
        if (( !Twig\Extension\CoreExtension::testEmpty(($context["available_languages"] ?? null)) || ($context["has_theme_manager"] ?? null))) {
            yield "            <div class=\"card mt-4\">
              <div class=\"card-header\">
                ";
yield _gettext("Appearance settings");
            yield "              </div>
              <ul class=\"list-group list-group-flush\">
                ";
            if ( !Twig\Extension\CoreExtension::testEmpty(($context["available_languages"] ?? null))) {
                yield "                  <li id=\"li_select_lang\" class=\"list-group-item\">
                    <form method=\"get\" action=\"";
                yield PhpMyAdmin\Url::getFromRoute("/");
                yield "\" class=\"row row-cols-lg-auto align-items-center disableAjax\">
                      ";
                yield PhpMyAdmin\Url::getHiddenInputs(["db" => ($context["db"] ?? null), "table" => ($context["table"] ?? null)]);
                yield "
                      <div class=\"col-12\">
                        <label for=\"languageSelect\" class=\"col-form-label text-nowrap\">
                          ";
                yield PhpMyAdmin\Html\Generator::getImage("s_lang");
                yield "
                          ";
yield _gettext("Language");
                yield "                          ";
                if ((_gettext("Language") != "Language")) {
                    yield "                                                        <i lang=\"en\" dir=\"ltr\">(Language)</i>
                          ";
                }
                yield "                          ";
                yield PhpMyAdmin\Html\MySQLDocumentation::showDocumentation("faq", "faq7-2");
                yield "
                        </label>
                      </div>
                      <div class=\"col-12\">
                        <select name=\"lang\" class=\"form-select autosubmit w-auto\" lang=\"en\" dir=\"ltr\" id=\"languageSelect\">
                          ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["available_languages"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["language"]) {
                    yield "                            <option value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["language"], "getCode", [], "method", false, false, false, 116)), "html", null, true);
                    yield "\"";
                    yield ((CoreExtension::getAttribute($this->env, $this->source, $context["language"], "isActive", [], "method", false, false, false, 116)) ? (" selected") : (""));
                    yield ">";
                    yield CoreExtension::getAttribute($this->env, $this->source, $context["language"], "getName", [], "method", false, false, false, 117);
                    yield "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['language'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                yield "                        </select>
                      </div>
                    </form>
                  </li>
                ";
            }
            yield "
                ";
            if (($context["has_theme_manager"] ?? null)) {
                yield "                  <li id=\"li_select_theme\" class=\"list-group-item\">
                    <form method=\"post\" action=\"";
                yield PhpMyAdmin\Url::getFromRoute("/themes/set");
                yield "\" class=\"row row-cols-lg-auto align-items-center disableAjax\">
                      ";
                yield PhpMyAdmin\Url::getHiddenInputs();
                yield "
                      <div class=\"col-12\">
                        <label for=\"themeSelect\" class=\"col-form-label\">
                          ";
                yield PhpMyAdmin\Html\Generator::getIcon("s_theme", _gettext("Theme"));
                yield "
                        </label>
                      </div>
                      <div class=\"col-12\">
                        <div class=\"input-group\">
                          <select name=\"set_theme\" class=\"form-select autosubmit\" lang=\"en\" dir=\"ltr\" id=\"themeSelect\">
                            ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["themes"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["theme"]) {
                    yield "                              <option value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["theme"], "id", [], "any", false, false, false, 139), "html", null, true);
                    yield "\"";
                    yield ((CoreExtension::getAttribute($this->env, $this->source, $context["theme"], "is_active", [], "any", false, false, false, 139)) ? (" selected") : (""));
                    yield ">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["theme"], "name", [], "any", false, false, false, 139), "html", null, true);
                    yield "</option>
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['theme'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                yield "                          </select>
                          <button type=\"button\" class=\"btn btn-outline-secondary\" data-bs-toggle=\"modal\" data-bs-target=\"#themesModal\">
                            ";
yield _pgettext("View all themes", "View all");
                yield "                          </button>
                        </div>
                      </div>
                    </form>
                  </li>
                ";
            }
            yield "              </ul>
            </div>
            ";
        }
        yield "          </div>

      <div class=\"col-lg-5 col-12\">
        ";
        if ( !Twig\Extension\CoreExtension::testEmpty(($context["database_server"] ?? null))) {
            yield "          <div class=\"card mt-4\">
            <div class=\"card-header\">
              ";
yield _gettext("Database server");
            yield "            </div>
            <ul class=\"list-group list-group-flush\">
              <li class=\"list-group-item\">
                ";
yield _gettext("Server:");
            yield "                ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["database_server"] ?? null), "host", [], "any", false, false, false, 164), "html", null, true);
            yield "
              </li>
              <li class=\"list-group-item\">
                ";
yield _gettext("Server type:");
            yield "                ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["database_server"] ?? null), "type", [], "any", false, false, false, 168), "html", null, true);
            yield "
              </li>
              <li class=\"list-group-item\">
                ";
yield _gettext("Server connection:");
            yield "                ";
            yield CoreExtension::getAttribute($this->env, $this->source, ($context["database_server"] ?? null), "connection", [], "any", false, false, false, 172);
            yield "
              </li>
              <li class=\"list-group-item\">
                ";
yield _gettext("Server version:");
            yield "                ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["database_server"] ?? null), "version", [], "any", false, false, false, 176), "html", null, true);
            yield "
              </li>
              <li class=\"list-group-item\">
                ";
yield _gettext("Protocol version:");
            yield "                ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["database_server"] ?? null), "protocol", [], "any", false, false, false, 180), "html", null, true);
            yield "
              </li>
              <li class=\"list-group-item\">
                ";
yield _gettext("User:");
            yield "                ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["database_server"] ?? null), "user", [], "any", false, false, false, 184), "html", null, true);
            yield "
              </li>
              <li class=\"list-group-item\">
                ";
yield _gettext("Server charset:");
            yield "                <span lang=\"en\" dir=\"ltr\">
                  ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["database_server"] ?? null), "charset", [], "any", false, false, false, 189), "html", null, true);
            yield "
                </span>
              </li>
            </ul>
          </div>
        ";
        }
        yield "
        ";
        if (( !Twig\Extension\CoreExtension::testEmpty(($context["web_server"] ?? null)) || ($context["show_php_info"] ?? null))) {
            yield "          <div class=\"card mt-4\">
            <div class=\"card-header\">
              ";
yield _gettext("Web server");
            yield "            </div>
            <ul class=\"list-group list-group-flush\">
              ";
            if ( !Twig\Extension\CoreExtension::testEmpty(($context["web_server"] ?? null))) {
                yield "                ";
                if ( !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["web_server"] ?? null), "software", [], "any", false, false, false, 203))) {
                    yield "                <li class=\"list-group-item\">
                  ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["web_server"] ?? null), "software", [], "any", false, false, false, 205), "html", null, true);
                    yield "
                </li>
                ";
                }
                yield "                <li class=\"list-group-item\" id=\"li_mysql_client_version\">
                  ";
yield _gettext("Database client version:");
                yield "                  ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["web_server"] ?? null), "database", [], "any", false, false, false, 210), "html", null, true);
                yield "
                </li>
                <li class=\"list-group-item\">
                  ";
yield _gettext("PHP extension:");
                yield "                  ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["web_server"] ?? null), "php_extensions", [], "any", false, false, false, 214));
                foreach ($context['_seq'] as $context["_key"] => $context["extension"]) {
                    yield "                    ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["extension"], "html", null, true);
                    yield "
                    ";
                    yield PhpMyAdmin\Html\Generator::showPHPDocumentation((("book." . $context["extension"]) . ".php"));
                    yield "
                  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['extension'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                yield "                </li>
                <li class=\"list-group-item\">
                  ";
yield _gettext("PHP version:");
                yield "                  ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["web_server"] ?? null), "php_version", [], "any", false, false, false, 221), "html", null, true);
                yield "
                </li>
              ";
            }
            yield "              ";
            if (($context["show_php_info"] ?? null)) {
                yield "                <li class=\"list-group-item\">
                  <a href=\"";
                yield PhpMyAdmin\Url::getFromRoute("/phpinfo");
                yield "\" target=\"_blank\" rel=\"noopener noreferrer\">
                    ";
yield _gettext("Show PHP information");
                yield "                  </a>
                </li>
              ";
            }
            yield "            </ul>
          </div>
        ";
        }
        yield "
          <div class=\"card mt-4\">
            <div class=\"card-header\">
              phpMyAdmin
            </div>
            <ul class=\"list-group list-group-flush\">
              <li id=\"li_pma_version\" class=\"list-group-item";
        yield ((($context["is_version_checked"] ?? null)) ? (" jsversioncheck") : (""));
        yield "\">
                ";
yield _gettext("Version information:");
        yield "                <span class=\"version\">";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["phpmyadmin_version"] ?? null), "html", null, true);
        yield "</span>
              </li>
              <li class=\"list-group-item\">
                <a href=\"";
        yield PhpMyAdmin\Html\MySQLDocumentation::getDocumentationLink("index");
        yield "\" target=\"_blank\" rel=\"noopener noreferrer\">
                  ";
yield _gettext("Documentation");
        yield "                </a>
              </li>
              <li class=\"list-group-item\">
                <a href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(PhpMyAdmin\Core::linkURL("https://www.phpmyadmin.net/"), "html", null, true);
        yield "\" target=\"_blank\" rel=\"noopener noreferrer\">
                  ";
yield _gettext("Official Homepage");
        yield "                </a>
              </li>
              <li class=\"list-group-item\">
                <a href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(PhpMyAdmin\Core::linkURL("https://www.phpmyadmin.net/contribute/"), "html", null, true);
        yield "\" target=\"_blank\" rel=\"noopener noreferrer\">
                  ";
yield _gettext("Contribute");
        yield "                </a>
              </li>
              <li class=\"list-group-item\">
                <a href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(PhpMyAdmin\Core::linkURL("https://www.phpmyadmin.net/support/"), "html", null, true);
        yield "\" target=\"_blank\" rel=\"noopener noreferrer\">
                  ";
yield _gettext("Get support");
        yield "                </a>
              </li>
              <li class=\"list-group-item\">
                <a href=\"";
        yield PhpMyAdmin\Url::getFromRoute("/changelog");
        yield "\" target=\"_blank\">
                  ";
yield _gettext("List of changes");
        yield "                </a>
              </li>
              <li class=\"list-group-item\">
                <a href=\"";
        yield PhpMyAdmin\Url::getFromRoute("/license");
        yield "\" target=\"_blank\">
                  ";
yield _gettext("License");
        yield "                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["errors"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
            yield "        <div class=\"alert ";
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["error"], "severity", [], "any", false, false, false, 280) == "warning")) ? ("alert-warning") : ("alert-info"));
            yield "\" role=\"alert\">
          ";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["error"], "severity", [], "any", false, false, false, 281) == "warning")) {
                yield "            ";
                yield PhpMyAdmin\Html\Generator::getImage("s_attention", _gettext("Warning"));
                yield "
          ";
            } else {
                yield "            ";
                yield PhpMyAdmin\Html\Generator::getImage("s_notice", _gettext("Notice"));
                yield "
          ";
            }
            yield "          ";
            yield PhpMyAdmin\Sanitize::sanitizeMessage(CoreExtension::getAttribute($this->env, $this->source, $context["error"], "message", [], "any", false, false, false, 286));
            yield "
        </div>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield "    </div>
  </div>

";
        if (($context["has_theme_manager"] ?? null)) {
            yield "  <div class=\"modal fade\" id=\"themesModal\" tabindex=\"-1\" aria-labelledby=\"themesModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-xl\">
      <div class=\"modal-content\">
        <div class=\"modal-header\">
          <h5 class=\"modal-title\" id=\"themesModalLabel\">";
yield _gettext("phpMyAdmin Themes");
            yield "</h5>
          <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"";
yield _gettext("Close");
            yield "\"></button>
        </div>
        <div class=\"modal-body\">
          <div class=\"spinner-border\" role=\"status\">
            <span class=\"visually-hidden\">";
yield _gettext("Loading…");
            yield "</span>
          </div>
        </div>
        <div class=\"modal-footer\">
          <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">";
yield _gettext("Close");
            yield "</button>
          <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(PhpMyAdmin\Core::linkURL("https://www.phpmyadmin.net/themes/"), "html", null, true);
            yield "#pma_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::replace(($context["phpmyadmin_major_version"] ?? null), ["." => "_"]), "html", null, true);
            yield "\" class=\"btn btn-primary\" rel=\"noopener noreferrer\" target=\"_blank\">
            ";
yield _gettext("Get more themes!");
            yield "          </a>
        </div>
      </div>
    </div>
  </div>
";
        }
        yield "
";
        yield ($context["config_storage_message"] ?? null);
        yield "
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "home/index.twig";
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
        return array (  722 => 316,  719 => 315,  711 => 309,  704 => 307,  701 => 306,  694 => 302,  687 => 298,  683 => 297,  676 => 293,  674 => 292,  669 => 289,  659 => 286,  653 => 284,  647 => 282,  645 => 281,  640 => 280,  636 => 279,  627 => 272,  622 => 270,  617 => 267,  612 => 265,  607 => 262,  602 => 260,  597 => 257,  592 => 255,  587 => 252,  582 => 250,  577 => 247,  572 => 245,  565 => 242,  560 => 240,  552 => 234,  547 => 231,  542 => 228,  537 => 226,  534 => 225,  531 => 224,  524 => 221,  519 => 218,  511 => 216,  506 => 215,  501 => 214,  493 => 210,  489 => 208,  483 => 205,  480 => 204,  477 => 203,  475 => 202,  471 => 200,  466 => 197,  464 => 196,  461 => 195,  452 => 189,  449 => 188,  441 => 184,  433 => 180,  425 => 176,  417 => 172,  409 => 168,  401 => 164,  395 => 160,  390 => 157,  388 => 156,  383 => 153,  378 => 150,  370 => 144,  365 => 141,  352 => 139,  348 => 138,  339 => 132,  333 => 129,  329 => 128,  326 => 127,  324 => 126,  321 => 125,  314 => 120,  307 => 118,  305 => 117,  299 => 116,  295 => 115,  286 => 110,  282 => 106,  279 => 105,  274 => 103,  268 => 100,  264 => 99,  261 => 98,  259 => 97,  255 => 95,  250 => 92,  248 => 91,  245 => 90,  240 => 87,  233 => 83,  229 => 82,  223 => 78,  218 => 75,  211 => 73,  204 => 71,  202 => 70,  194 => 69,  190 => 68,  183 => 67,  179 => 66,  175 => 64,  170 => 62,  168 => 61,  161 => 58,  156 => 56,  150 => 53,  146 => 52,  142 => 50,  135 => 46,  131 => 45,  128 => 44,  125 => 43,  123 => 42,  120 => 41,  114 => 38,  110 => 37,  107 => 36,  105 => 35,  101 => 33,  95 => 29,  90 => 26,  88 => 21,  84 => 25,  81 => 22,  79 => 21,  75 => 19,  70 => 16,  67 => 15,  65 => 14,  58 => 10,  52 => 7,  47 => 5,  44 => 4,  40 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "home/index.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\home\\index.twig");
    }
}

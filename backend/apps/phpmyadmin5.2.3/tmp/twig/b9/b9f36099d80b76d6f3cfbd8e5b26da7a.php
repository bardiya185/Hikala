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


class __TwigTemplate_e70c80f8706c079d0db05422087ef4bc extends Template
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
        yield "<!doctype html>
<html lang=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["lang"] ?? null), "html", null, true);
        yield "\" dir=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["text_dir"] ?? null), "html", null, true);
        yield "\">
<head>
  <meta charset=\"utf-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
  <meta name=\"referrer\" content=\"same-origin\">
  <meta name=\"robots\" content=\"noindex,nofollow,notranslate\">
  <meta name=\"google\" content=\"notranslate\">
  ";
        if ( !($context["allow_third_party_framing"] ?? null)) {
            yield "<style id=\"cfs-style\">html{display: none;}</style>";
        }
        yield "
  <link rel=\"icon\" href=\"favicon.ico\" type=\"image/x-icon\">
  <link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/x-icon\">
  <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["theme_path"] ?? null), "html", null, true);
        yield "/jquery/jquery-ui.css\">
  <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_dir"] ?? null), "html", null, true);
        yield "js/vendor/codemirror/lib/codemirror.css?";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["version"] ?? null), "html", null, true);
        yield "\">
  <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_dir"] ?? null), "html", null, true);
        yield "js/vendor/codemirror/addon/hint/show-hint.css?";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["version"] ?? null), "html", null, true);
        yield "\">
  <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_dir"] ?? null), "html", null, true);
        yield "js/vendor/codemirror/addon/lint/lint.css?";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["version"] ?? null), "html", null, true);
        yield "\">
  <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["theme_path"] ?? null), "html", null, true);
        yield "/css/theme";
        yield (((($context["text_dir"] ?? null) == "rtl")) ? (".rtl") : (""));
        yield ".css?";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["version"] ?? null), "html", null, true);
        yield "\">
  <title>";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["title"] ?? null), "html", null, true);
        yield "</title>
  ";
        yield ($context["scripts"] ?? null);
        yield "
  <noscript><style>html{display:block}</style></noscript>
</head>
<body";
        (( !Twig\Extension\CoreExtension::testEmpty(($context["body_id"] ?? null))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((" id=" . ($context["body_id"] ?? null)), "html", null, true)) : (yield ""));
        yield ">
  ";
        yield ($context["navigation"] ?? null);
        yield "
  ";
        yield ($context["custom_header"] ?? null);
        yield "
  ";
        yield ($context["load_user_preferences"] ?? null);
        yield "

  ";
        if ( !($context["show_hint"] ?? null)) {
            yield "    <span id=\"no_hint\" class=\"hide\"></span>
  ";
        }
        yield "
  ";
        if (($context["is_warnings_enabled"] ?? null)) {
            yield "    <noscript>
      ";
            yield $this->env->getFilter('error')->getCallable()(_gettext("Javascript must be enabled past this point!"));
            yield "
    </noscript>
  ";
        }
        yield "
  ";
        if ((($context["is_menu_enabled"] ?? null) && (($context["server"] ?? null) > 0))) {
            yield "    ";
            yield ($context["menu"] ?? null);
            yield "
    <span id=\"page_nav_icons\" class=\"d-print-none\">
      <span id=\"lock_page_icon\"></span>
      <span id=\"page_settings_icon\">
        ";
            yield PhpMyAdmin\Html\Generator::getImage("s_cog", _gettext("Page-related settings"));
            yield "
      </span>
      <a id=\"goto_pagetop\" href=\"#\">";
            yield PhpMyAdmin\Html\Generator::getImage("s_top", _gettext("Click on the bar to scroll to top of page"));
            yield "</a>
    </span>
  ";
        }
        yield "
  ";
        yield ($context["console"] ?? null);
        yield "

  <div id=\"page_content\">
    ";
        yield ($context["messages"] ?? null);
        yield "

    ";
        yield ($context["recent_table"] ?? null);
        if (($context["is_logged_in"] ?? null)) {
            yield Twig\Extension\CoreExtension::include($this->env, $context, "modals/preview_sql_modal.twig");
            yield "
    ";
            yield Twig\Extension\CoreExtension::include($this->env, $context, "modals/enum_set_editor.twig");
            yield "
    ";
            yield Twig\Extension\CoreExtension::include($this->env, $context, "modals/create_view.twig");
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "header.twig";
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
        return array (  190 => 59,  186 => 58,  182 => 57,  180 => 56,  178 => 55,  173 => 53,  167 => 50,  164 => 49,  158 => 46,  153 => 44,  145 => 40,  143 => 39,  140 => 38,  134 => 35,  131 => 34,  129 => 33,  126 => 32,  122 => 30,  120 => 29,  115 => 27,  111 => 26,  107 => 25,  103 => 24,  97 => 21,  93 => 20,  85 => 19,  79 => 18,  73 => 17,  67 => 16,  63 => 15,  58 => 12,  55 => 10,  53 => 9,  41 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "header.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\header.twig");
    }
}

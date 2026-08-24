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


class __TwigTemplate_d4af75525717853f25dfe043bdd0cad0 extends Template
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
        yield "var firstDayOfCalendar = '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["first_day_of_calendar"] ?? null), "js", null, true);
        yield "';
var themeImagePath = '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['PhpMyAdmin\Twig\AssetExtension']->getImagePath(), "js", null, true);
        yield "';
var mysqlDocTemplate = '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(PhpMyAdmin\Util::getMySQLDocuURL("%s"), "js", null, true);
        yield "';
var maxInputVars = ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["max_input_vars"] ?? null), "js", null, true);
        yield ";

";
        $context["show_month_after_year"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("calendar-month-year");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        $context["year_suffix"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("none");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield "if (\$.datepicker) {
  \$.datepicker.regional[''].closeText = '";
        $___internal_parse_1_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Done");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_1_, "js");
        yield "';
  \$.datepicker.regional[''].prevText = '";
        $___internal_parse_2_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Prev");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_2_, "js");
        yield "';
  \$.datepicker.regional[''].nextText = '";
        $___internal_parse_3_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Next");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_3_, "js");
        yield "';
  \$.datepicker.regional[''].currentText = '";
        $___internal_parse_4_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Today");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_4_, "js");
        yield "';
  \$.datepicker.regional[''].monthNames = [
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("January"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("February"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("March"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("April"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("May"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("June"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("July"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("August"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("September"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("October"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("November"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("December"), "js", null, true);
        yield "',
  ];
  \$.datepicker.regional[''].monthNamesShort = [
    '";
        $___internal_parse_5_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Jan");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_5_, "js");
        yield "',
    '";
        $___internal_parse_6_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Feb");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_6_, "js");
        yield "',
    '";
        $___internal_parse_7_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Mar");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_7_, "js");
        yield "',
    '";
        $___internal_parse_8_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Apr");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_8_, "js");
        yield "',
    '";
        $___internal_parse_9_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("May");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_9_, "js");
        yield "',
    '";
        $___internal_parse_10_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Jun");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_10_, "js");
        yield "',
    '";
        $___internal_parse_11_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Jul");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_11_, "js");
        yield "',
    '";
        $___internal_parse_12_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Aug");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_12_, "js");
        yield "',
    '";
        $___internal_parse_13_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Sep");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_13_, "js");
        yield "',
    '";
        $___internal_parse_14_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Oct");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_14_, "js");
        yield "',
    '";
        $___internal_parse_15_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Nov");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_15_, "js");
        yield "',
    '";
        $___internal_parse_16_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Dec");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_16_, "js");
        yield "',
  ];
  \$.datepicker.regional[''].dayNames = [
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Sunday"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Monday"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Tuesday"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Wednesday"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Thursday"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Friday"), "js", null, true);
        yield "',
    '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Saturday"), "js", null, true);
        yield "',
  ];
  \$.datepicker.regional[''].dayNamesShort = [
    '";
        $___internal_parse_17_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Sun");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_17_, "js");
        yield "',
    '";
        $___internal_parse_18_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Mon");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_18_, "js");
        yield "',
    '";
        $___internal_parse_19_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Tue");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_19_, "js");
        yield "',
    '";
        $___internal_parse_20_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Wed");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_20_, "js");
        yield "',
    '";
        $___internal_parse_21_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Thu");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_21_, "js");
        yield "',
    '";
        $___internal_parse_22_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Fri");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_22_, "js");
        yield "',
    '";
        $___internal_parse_23_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Sat");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_23_, "js");
        yield "',
  ];
  \$.datepicker.regional[''].dayNamesMin = [
    '";
        $___internal_parse_24_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Su");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_24_, "js");
        yield "',
    '";
        $___internal_parse_25_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Mo");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_25_, "js");
        yield "',
    '";
        $___internal_parse_26_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Tu");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_26_, "js");
        yield "',
    '";
        $___internal_parse_27_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("We");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_27_, "js");
        yield "',
    '";
        $___internal_parse_28_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Th");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_28_, "js");
        yield "',
    '";
        $___internal_parse_29_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Fr");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_29_, "js");
        yield "',
    '";
        $___internal_parse_30_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Sa");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_30_, "js");
        yield "',
  ];
  \$.datepicker.regional[''].weekHeader = '";
        $___internal_parse_31_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("Wk");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_31_, "js");
        yield "';
  \$.datepicker.regional[''].showMonthAfterYear = ";
        yield (((($context["show_month_after_year"] ?? null) == "calendar-year-month")) ? ("true") : ("false"));
        yield ";
  \$.datepicker.regional[''].yearSuffix = '";
        yield (((($context["year_suffix"] ?? null) != "none")) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["year_suffix"] ?? null), "js")) : (""));
        yield "';
  \$.extend(\$.datepicker._defaults, \$.datepicker.regional['']);
}

if (\$.timepicker) {
  \$.timepicker.regional[''].timeText = '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Time"), "js", null, true);
        yield "';
  \$.timepicker.regional[''].hourText = '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Hour"), "js", null, true);
        yield "';
  \$.timepicker.regional[''].minuteText = '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Minute"), "js", null, true);
        yield "';
  \$.timepicker.regional[''].secondText = '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Second"), "js", null, true);
        yield "';
  \$.extend(\$.timepicker._defaults, \$.timepicker.regional['']);
}

function extendingValidatorMessages () {
  \$.extend(\$.validator.messages, {
    required: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("This field is required"), "js", null, true);
        yield "',
    remote: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please fix this field"), "js", null, true);
        yield "',
    email: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a valid email address"), "js", null, true);
        yield "',
    url: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a valid URL"), "js", null, true);
        yield "',
    date: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a valid date"), "js", null, true);
        yield "',
    dateISO: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a valid date ( ISO )"), "js", null, true);
        yield "',
    number: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a valid number"), "js", null, true);
        yield "',
    creditcard: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a valid credit card number"), "js", null, true);
        yield "',
    digits: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter only digits"), "js", null, true);
        yield "',
    equalTo: '";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter the same value again"), "js", null, true);
        yield "',
    maxlength: \$.validator.format('";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter no more than {0} characters"), "js", null, true);
        yield "'),
    minlength: \$.validator.format('";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter at least {0} characters"), "js", null, true);
        yield "'),
    rangelength: \$.validator.format('";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a value between {0} and {1} characters long"), "js", null, true);
        yield "'),
    range: \$.validator.format('";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a value between {0} and {1}"), "js", null, true);
        yield "'),
    max: \$.validator.format('";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a value less than or equal to {0}"), "js", null, true);
        yield "'),
    min: \$.validator.format('";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a value greater than or equal to {0}"), "js", null, true);
        yield "'),
    validationFunctionForDateTime: \$.validator.format('";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a valid date or time"), "js", null, true);
        yield "'),
    validationFunctionForHex: \$.validator.format('";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_gettext("Please enter a valid HEX input"), "js", null, true);
        yield "'),
    validationFunctionForMd5: \$.validator.format('";
        $___internal_parse_32_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("This column can not contain a 32 chars value");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_32_, "js");
        yield "'),
    validationFunctionForAesDesEncrypt: \$.validator.format('";
        $___internal_parse_33_ = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
yield _gettext("These functions are meant to return a binary result; to avoid inconsistent results you should store it in a BINARY, VARBINARY, or BLOB column.");
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($___internal_parse_33_, "js");
        yield "')
  });
}
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "javascript/variables.twig";
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
        return array (  550 => 109,  541 => 108,  537 => 107,  533 => 106,  529 => 105,  525 => 104,  521 => 103,  517 => 102,  513 => 101,  509 => 100,  505 => 99,  501 => 98,  497 => 97,  493 => 96,  489 => 95,  485 => 94,  481 => 93,  477 => 92,  473 => 91,  469 => 90,  460 => 84,  456 => 83,  452 => 82,  448 => 81,  440 => 76,  436 => 75,  427 => 74,  417 => 72,  408 => 71,  399 => 70,  390 => 69,  381 => 68,  372 => 67,  363 => 66,  352 => 63,  343 => 62,  334 => 61,  325 => 60,  316 => 59,  307 => 58,  298 => 57,  292 => 54,  288 => 53,  284 => 52,  280 => 51,  276 => 50,  272 => 49,  268 => 48,  257 => 45,  248 => 44,  239 => 43,  230 => 42,  221 => 41,  212 => 40,  203 => 39,  194 => 38,  185 => 37,  176 => 36,  167 => 35,  158 => 34,  152 => 31,  148 => 30,  144 => 29,  140 => 28,  136 => 27,  132 => 26,  128 => 25,  124 => 24,  120 => 23,  116 => 22,  112 => 21,  108 => 20,  98 => 18,  89 => 17,  80 => 16,  71 => 15,  68 => 14,  62 => 10,  56 => 7,  51 => 5,  47 => 4,  43 => 3,  38 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "javascript/variables.twig", "C:\\wamp64\\apps\\phpmyadmin5.2.3\\templates\\javascript\\variables.twig");
    }
}

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
use Twig\TemplateWrapper;

/* @WebProfiler/Profiler/search.html.twig */
class __TwigTemplate_88fded6445e1da4c91abf430cd0072d8 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@WebProfiler/Profiler/search.html.twig"));

        // line 1
        yield "<div id=\"sidebar-search\" class=\"";
        yield (((($tmp = (((array_key_exists("render_hidden_by_default", $context) &&  !(null === $context["render_hidden_by_default"]))) ? ($context["render_hidden_by_default"]) : (true))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("hidden") : (""));
        yield "\">
    <form action=\"";
        // line 2
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("_profiler_search");
        yield "\" method=\"get\">
        <div class=\"form-group\">
            <label for=\"ip\">
                ";
        // line 5
        if (("command" == ($context["profile_type"] ?? null))) {
            // line 6
            yield "                    Application
                ";
        } else {
            // line 8
            yield "                    IP
                ";
        }
        // line 10
        yield "            </label>
            <input type=\"text\" name=\"ip\" id=\"ip\" value=\"";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["ip"] ?? null), "html", null, true);
        yield "\">
        </div>

        <div class=\"form-group-row\">
            <div class=\"form-group\">
                <label for=\"method\">
                    ";
        // line 17
        if (("command" == ($context["profile_type"] ?? null))) {
            // line 18
            yield "                        Mode
                    ";
        } else {
            // line 20
            yield "                        Method
                    ";
        }
        // line 22
        yield "                </label>
                <select name=\"method\" id=\"method\">
                    <option value=\"\">Any</option>
                    ";
        // line 25
        if (("command" == ($context["profile_type"] ?? null))) {
            // line 26
            yield "                        ";
            $context["methods"] = ["BATCH", "INTERACTIVE"];
            // line 27
            yield "                    ";
        } else {
            // line 28
            yield "                        ";
            $context["methods"] = ["DELETE", "GET", "HEAD", "PATCH", "POST", "PUT"];
            // line 29
            yield "                    ";
        }
        // line 30
        yield "                    ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["methods"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["m"]) {
            // line 31
            yield "                        <option ";
            yield ((($context["m"] == ($context["method"] ?? null))) ? ("selected=\"selected\"") : (""));
            yield ">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["m"], "html", null, true);
            yield "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['m'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        yield "                </select>
            </div>

            <div class=\"form-group\">
                <label for=\"status_code\">
                    ";
        // line 38
        if (("command" == ($context["profile_type"] ?? null))) {
            // line 39
            yield "                        Exit code
                        ";
            // line 40
            $context["min_and_max"] = Twig\Extension\CoreExtension::sprintf("min=%d max=%d", 0, 255);
            // line 41
            yield "                    ";
        } else {
            // line 42
            yield "                        Status
                        ";
            // line 43
            $context["min_and_max"] = Twig\Extension\CoreExtension::sprintf("min=%d max=%d", 100, 599);
            // line 44
            yield "                    ";
        }
        // line 45
        yield "                </label>
                <input type=\"number\" name=\"status_code\" id=\"status_code\" ";
        // line 46
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["min_and_max"] ?? null), "html", null, true);
        yield " value=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["status_code"] ?? null), "html", null, true);
        yield "\">
            </div>
        </div>

        <div class=\"form-group\">
            <label for=\"url\">
                ";
        // line 52
        if (("command" == ($context["profile_type"] ?? null))) {
            // line 53
            yield "                    Command
                ";
        } else {
            // line 55
            yield "                    URL
                ";
        }
        // line 57
        yield "            </label>
            <input type=\"text\" name=\"url\" id=\"url\" value=\"";
        // line 58
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["url"] ?? null), "html", null, true);
        yield "\">
        </div>

        <div class=\"form-group\">
            <label for=\"token\">Token</label>
            <input type=\"text\" name=\"token\" id=\"token\" size=\"8\" value=\"";
        // line 63
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["token"] ?? null), "html", null, true);
        yield "\">
        </div>

        <div class=\"form-group\">
            <label for=\"start\">From</label>
            <input type=\"date\" name=\"start\" id=\"start\" value=\"";
        // line 68
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["start"] ?? null), "html", null, true);
        yield "\">
        </div>

        <div class=\"form-group\">
            <label for=\"end\">Until</label>
            <input type=\"date\" name=\"end\" id=\"end\" value=\"";
        // line 73
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["end"] ?? null), "html", null, true);
        yield "\">
        </div>

        <div class=\"form-group-row form-group-row-search-button\">
            <div class=\"form-group\">
                <label for=\"limit\">Results</label>
                <select name=\"limit\" id=\"limit\">
                    ";
        // line 80
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable([10, 50, 100]);
        foreach ($context['_seq'] as $context["_key"] => $context["l"]) {
            // line 81
            yield "                        <option ";
            yield ((($context["l"] == ($context["limit"] ?? null))) ? ("selected=\"selected\"") : (""));
            yield ">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["l"], "html", null, true);
            yield "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['l'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 83
        yield "                </select>
            </div>

            <input type=\"hidden\" name=\"type\" value=\"";
        // line 86
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["profile_type"] ?? null), "html", null, true);
        yield "\">

            <div class=\"form-group\">
                <button type=\"submit\" class=\"btn btn-sm\">Search</button>
            </div>
        </div>
    </form>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "@WebProfiler/Profiler/search.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  229 => 86,  224 => 83,  213 => 81,  209 => 80,  199 => 73,  191 => 68,  183 => 63,  175 => 58,  172 => 57,  168 => 55,  164 => 53,  162 => 52,  151 => 46,  148 => 45,  145 => 44,  143 => 43,  140 => 42,  137 => 41,  135 => 40,  132 => 39,  130 => 38,  123 => 33,  112 => 31,  107 => 30,  104 => 29,  101 => 28,  98 => 27,  95 => 26,  93 => 25,  88 => 22,  84 => 20,  80 => 18,  78 => 17,  69 => 11,  66 => 10,  62 => 8,  58 => 6,  56 => 5,  50 => 2,  45 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "@WebProfiler/Profiler/search.html.twig", "C:\\Users\\PC\\sirm\\sirm-back\\vendor\\symfony\\web-profiler-bundle\\Resources\\views\\Profiler\\search.html.twig");
    }
}

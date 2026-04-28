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

/* @WebProfiler/Profiler/results.html.twig */
class __TwigTemplate_b3c81c7cd9a9957942322e06f811c8ab extends Template
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

        $this->blocks = [
            'head' => [$this, 'block_head'],
            'summary' => [$this, 'block_summary'],
            'sidebar_search_css_class' => [$this, 'block_sidebar_search_css_class'],
            'sidebar_shortcuts_links' => [$this, 'block_sidebar_shortcuts_links'],
            'panel' => [$this, 'block_panel'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "@WebProfiler/Profiler/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@WebProfiler/Profiler/results.html.twig"));

        $this->parent = $this->load("@WebProfiler/Profiler/layout.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 9
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_head(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "head"));

        // line 10
        yield "    ";
        yield from $this->yieldParentBlock("head", $context, $blocks);
        yield "

    <style>
        #search-results td {
            font-family: var(--font-family-system);
            vertical-align: middle;
        }

        #search-results .sf-search {
            visibility: hidden;
            margin-left: 2px;
        }
        #search-results tr:hover .sf-search {
            visibility: visible;
        }
        #search-results .sf-search svg {
            stroke-width: 3;
        }
    </style>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 31
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_summary(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "summary"));

        // line 32
        yield "    <div class=\"status\">
        <h2>Profile Search</h2>
    </div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 37
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_sidebar_search_css_class(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "sidebar_search_css_class"));

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 38
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_sidebar_shortcuts_links(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "sidebar_shortcuts_links"));

        // line 39
        yield "    ";
        yield from $this->yieldParentBlock("sidebar_shortcuts_links", $context, $blocks);
        yield "
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 42
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_panel(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "panel"));

        // line 43
        yield "    <div class=\"sf-tabs\" data-processed=\"true\">
        <div class=\"tab-navigation\" role=\"tablist\">
            <button class=\"tab-control ";
        // line 45
        yield ((("request" == ($context["profile_type"] ?? null))) ? ("active") : (""));
        yield "\" role=\"tab\" ";
        yield ((("request" == ($context["profile_type"] ?? null))) ? ("aria-selected=\"true\"") : ("tabindex=\"-1\""));
        yield " >
                <a href=\"";
        // line 46
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("_profiler_search_results", ["token" => "empty", "limit" => 10]), "html", null, true);
        yield "\">
                    HTTP Requests
                </a>
            </button>
            <button class=\"tab-control ";
        // line 50
        yield ((("command" == ($context["profile_type"] ?? null))) ? ("active") : (""));
        yield "\" role=\"tab\" ";
        yield ((("command" == ($context["profile_type"] ?? null))) ? ("aria-selected=\"true\"") : ("tabindex=\"-1\""));
        yield ">
                <a href=\"";
        // line 51
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("_profiler_search_results", ["token" => "empty", "limit" => 10, "type" => "command"]), "html", null, true);
        yield "\">
                    Console Commands
                </a>
            </button>
        </div>
    </div>

    <h2>";
        // line 58
        yield (((($tmp = ($context["tokens"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["tokens"] ?? null)), "html", null, true)) : ("No"));
        yield " results found</h2>

    ";
        // line 60
        if ((($tmp = ($context["tokens"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 61
            yield "        <table id=\"search-results\">
            <thead>
                <tr>
                    <th scope=\"col\" class=\"text-center\">
                        ";
            // line 65
            if (("command" == ($context["profile_type"] ?? null))) {
                // line 66
                yield "                            Exit code
                        ";
            } else {
                // line 68
                yield "                            Status
                        ";
            }
            // line 70
            yield "                    </th>
                    <th scope=\"col\">
                        ";
            // line 72
            if (("command" == ($context["profile_type"] ?? null))) {
                // line 73
                yield "                            Application
                        ";
            } else {
                // line 75
                yield "                            IP
                        ";
            }
            // line 77
            yield "                    </th>
                    <th scope=\"col\">
                        ";
            // line 79
            if (("command" == ($context["profile_type"] ?? null))) {
                // line 80
                yield "                            Mode
                        ";
            } else {
                // line 82
                yield "                            Method
                        ";
            }
            // line 84
            yield "                    </th>
                    <th scope=\"col\">
                        ";
            // line 86
            if (("command" == ($context["profile_type"] ?? null))) {
                // line 87
                yield "                            Command
                        ";
            } else {
                // line 89
                yield "                            URL
                        ";
            }
            // line 91
            yield "                    </th>
                    <th scope=\"col\">Time</th>
                    <th scope=\"col\">Token</th>
                </tr>
            </thead>
            <tbody>
                ";
            // line 97
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["tokens"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["result"]) {
                // line 98
                yield "                    ";
                if (("command" == ($context["profile_type"] ?? null))) {
                    // line 99
                    yield "                        ";
                    $context["css_class"] = (((CoreExtension::getAttribute($this->env, $this->source, $context["result"], "status_code", [], "any", false, false, false, 99) == 113)) ? ("status-warning") : ((((CoreExtension::getAttribute($this->env, $this->source, $context["result"], "status_code", [], "any", false, false, false, 99) > 0)) ? ("status-error") : ("status-success"))));
                    // line 100
                    yield "                    ";
                } else {
                    // line 101
                    yield "                        ";
                    $context["css_class"] = (((((CoreExtension::getAttribute($this->env, $this->source, $context["result"], "status_code", [], "any", true, true, false, 101)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "status_code", [], "any", false, false, false, 101), 0)) : (0)) > 399)) ? ("status-error") : ((((((CoreExtension::getAttribute($this->env, $this->source, $context["result"], "status_code", [], "any", true, true, false, 101)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "status_code", [], "any", false, false, false, 101), 0)) : (0)) > 299)) ? ("status-warning") : ("status-success"))));
                    // line 102
                    yield "                    ";
                }
                // line 103
                yield "
                    <tr>
                        <td class=\"text-center\">
                            <span class=\"label ";
                // line 106
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["css_class"] ?? null), "html", null, true);
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["result"], "status_code", [], "any", true, true, false, 106)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "status_code", [], "any", false, false, false, 106), "n/a")) : ("n/a")), "html", null, true);
                yield "</span>
                        </td>
                        <td>
                            <span class=\"nowrap\">";
                // line 109
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "ip", [], "any", false, false, false, 109), "html", null, true);
                yield " ";
                yield $this->getTemplateForMacro("macro_profile_search_filter", $context, 109, $this->getSourceContext())->macro_profile_search_filter(...[($context["request"] ?? null), $context["result"], "ip"]);
                yield "</span>
                        </td>
                        <td>
                            <span class=\"nowrap\">";
                // line 112
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "method", [], "any", false, false, false, 112), "html", null, true);
                yield " ";
                yield $this->getTemplateForMacro("macro_profile_search_filter", $context, 112, $this->getSourceContext())->macro_profile_search_filter(...[($context["request"] ?? null), $context["result"], "method"]);
                yield "</span>
                        </td>
                        <td class=\"break-long-words\">
                            ";
                // line 115
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "url", [], "any", false, false, false, 115), "html", null, true);
                yield "
                            ";
                // line 116
                yield $this->getTemplateForMacro("macro_profile_search_filter", $context, 116, $this->getSourceContext())->macro_profile_search_filter(...[($context["request"] ?? null), $context["result"], "url"]);
                yield "
                        </td>
                        <td class=\"text-small\">
                            <time data-convert-to-user-timezone data-render-as-date datetime=\"";
                // line 119
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "time", [], "any", false, false, false, 119), "c"), "html", null, true);
                yield "\">
                                ";
                // line 120
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "time", [], "any", false, false, false, 120), "d-M-Y"), "html", null, true);
                yield "
                            </time>
                            <time class=\"newline\" data-convert-to-user-timezone data-render-as-time datetime=\"";
                // line 122
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "time", [], "any", false, false, false, 122), "c"), "html", null, true);
                yield "\">
                                ";
                // line 123
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "time", [], "any", false, false, false, 123), "H:i:s"), "html", null, true);
                yield "
                            </time>
                        </td>
                        <td class=\"nowrap\"><a href=\"";
                // line 126
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("_profiler", ["token" => CoreExtension::getAttribute($this->env, $this->source, $context["result"], "token", [], "any", false, false, false, 126)]), "html", null, true);
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["result"], "token", [], "any", false, false, false, 126), "html", null, true);
                yield "</a></td>
                    </tr>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['result'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 129
            yield "            </tbody>
        </table>
    ";
        } else {
            // line 132
            yield "        <div class=\"empty empty-panel\">
            <p>The query returned no result.</p>
        </div>
    ";
        }
        // line 136
        yield "
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 3
    public function macro_profile_search_filter($request = null, $result = null, $property = null, ...$varargs): string|Markup
    {
        $macros = $this->macros;
        $context = [
            "request" => $request,
            "result" => $result,
            "property" => $property,
            "varargs" => $varargs,
        ] + $this->env->getGlobals();

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
            $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "profile_search_filter"));

            // line 4
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "hasSession", [], "any", false, false, false, 4)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 5
                yield "<a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("_profiler_search_results", Twig\Extension\CoreExtension::merge(Twig\Extension\CoreExtension::merge(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "query", [], "any", false, false, false, 5), "all", [], "any", false, false, false, 5), ["token" => CoreExtension::getAttribute($this->env, $this->source, ($context["result"] ?? null), "token", [], "any", false, false, false, 5)]), [ (string)($context["property"] ?? null) => (($_v0 = ($context["result"] ?? null)) && is_array($_v0) || $_v0 instanceof ArrayAccess ? ($_v0[($context["property"] ?? null)] ?? null) : null)])), "html", null, true);
                yield "\" title=\"Search\"><span title=\"Search\" class=\"sf-icon sf-search\">";
                yield Twig\Extension\CoreExtension::source($this->env, "@WebProfiler/Icon/search.svg");
                yield "</span></a>";
            }
            
            $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

            yield from [];
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "@WebProfiler/Profiler/results.html.twig";
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
        return array (  401 => 5,  399 => 4,  382 => 3,  373 => 136,  367 => 132,  362 => 129,  351 => 126,  345 => 123,  341 => 122,  336 => 120,  332 => 119,  326 => 116,  322 => 115,  314 => 112,  306 => 109,  298 => 106,  293 => 103,  290 => 102,  287 => 101,  284 => 100,  281 => 99,  278 => 98,  274 => 97,  266 => 91,  262 => 89,  258 => 87,  256 => 86,  252 => 84,  248 => 82,  244 => 80,  242 => 79,  238 => 77,  234 => 75,  230 => 73,  228 => 72,  224 => 70,  220 => 68,  216 => 66,  214 => 65,  208 => 61,  206 => 60,  201 => 58,  191 => 51,  185 => 50,  178 => 46,  172 => 45,  168 => 43,  158 => 42,  147 => 39,  137 => 38,  121 => 37,  110 => 32,  100 => 31,  71 => 10,  61 => 9,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "@WebProfiler/Profiler/results.html.twig", "C:\\Users\\PC\\sirm\\sirm-back\\vendor\\symfony\\web-profiler-bundle\\Resources\\views\\Profiler\\results.html.twig");
    }
}

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

/* @WebProfiler/Profiler/layout.html.twig */
class __TwigTemplate_f1e72c53de3a88e1168efaf4b5e8f63a extends Template
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
            'body' => [$this, 'block_body'],
            'summary' => [$this, 'block_summary'],
            'sidebar' => [$this, 'block_sidebar'],
            'sidebar_shortcuts_links' => [$this, 'block_sidebar_shortcuts_links'],
            'panel' => [$this, 'block_panel'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "@WebProfiler/Profiler/base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@WebProfiler/Profiler/layout.html.twig"));

        $this->parent = $this->load("@WebProfiler/Profiler/base.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 4
        yield "    <div class=\"container\">
        ";
        // line 5
        yield Twig\Extension\CoreExtension::include($this->env, $context, "@WebProfiler/Profiler/header.html.twig", ["profile_type" => ($context["profile_type"] ?? null)], false);
        yield "

        <div id=\"summary\">
        ";
        // line 8
        yield from $this->unwrap()->yieldBlock('summary', $context, $blocks);
        // line 21
        yield "    </div>

        <div id=\"content\">
            <div id=\"main\">
                <div id=\"sidebar\">
                    ";
        // line 26
        yield from $this->unwrap()->yieldBlock('sidebar', $context, $blocks);
        // line 71
        yield "                </div>

                <div id=\"collector-wrapper\">
                    <div id=\"collector-content\">
                        ";
        // line 75
        yield Twig\Extension\CoreExtension::include($this->env, $context, "@WebProfiler/Profiler/base_js.html.twig");
        yield "
                        ";
        // line 76
        yield from $this->unwrap()->yieldBlock('panel', $context, $blocks);
        // line 77
        yield "                    </div>
                </div>
            </div>
        </div>
    </div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 8
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_summary(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "summary"));

        // line 9
        yield "            ";
        if (array_key_exists("profile", $context)) {
            // line 10
            yield "                ";
            $context["request_collector"] = ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["profile"] ?? null), "collectors", [], "any", false, true, false, 10), "request", [], "any", true, true, false, 10)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["profile"] ?? null), "collectors", [], "any", false, false, false, 10), "request", [], "any", false, false, false, 10), false)) : (false));
            // line 11
            yield "
                ";
            // line 12
            yield Twig\Extension\CoreExtension::include($this->env, $context, Twig\Extension\CoreExtension::sprintf("@WebProfiler/Profiler/_%s_summary.html.twig", ($context["profile_type"] ?? null)), ["profile" =>             // line 13
($context["profile"] ?? null), "command_collector" => ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 14
($context["profile"] ?? null), "collectors", [], "any", false, true, false, 14), "command", [], "any", true, true, false, 14)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["profile"] ?? null), "collectors", [], "any", false, false, false, 14), "command", [], "any", false, false, false, 14), false)) : (false)), "request_collector" =>             // line 15
($context["request_collector"] ?? null), "request" =>             // line 16
($context["request"] ?? null), "token" =>             // line 17
($context["token"] ?? null)], false);
            // line 18
            yield "
            ";
        }
        // line 20
        yield "        ";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 26
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_sidebar(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "sidebar"));

        // line 27
        yield "                        <div id=\"sidebar-contents\">
                            <div id=\"sidebar-shortcuts\">
                                ";
        // line 29
        yield from $this->unwrap()->yieldBlock('sidebar_shortcuts_links', $context, $blocks);
        // line 39
        yield "
                                ";
        // line 40
        yield $this->env->getRuntime('Symfony\Bridge\Twig\Extension\HttpKernelRuntime')->renderFragment(Symfony\Bridge\Twig\Extension\HttpKernelExtension::controller("web_profiler.controller.profiler::searchBarAction", [], Twig\Extension\CoreExtension::merge(["type" => ($context["profile_type"] ?? null)], CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "query", [], "any", false, false, false, 40), "all", [], "any", false, false, false, 40))));
        yield "
                            </div>

                            ";
        // line 43
        if (array_key_exists("templates", $context)) {
            // line 44
            yield "                                <ul id=\"menu-profiler\">
                                    ";
            // line 45
            if (("request" === ($context["profile_type"] ?? null))) {
                // line 46
                yield "                                        ";
                $context["excludes"] = ["command"];
                // line 47
                yield "                                    ";
            } elseif (("command" === ($context["profile_type"] ?? null))) {
                // line 48
                yield "                                        ";
                $context["excludes"] = ["request", "router"];
                // line 49
                yield "                                    ";
            }
            // line 50
            yield "
                                    ";
            // line 51
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::filter($this->env, ($context["templates"] ?? null), function ($__t__, $__n__) use ($context, $macros) { $context["t"] = $__t__; $context["n"] = $__n__; return !CoreExtension::inFilter(($context["n"] ?? null), ($context["excludes"] ?? null)); }));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["name"] => $context["template"]) {
                // line 52
                yield "                                        ";
                $context["menu"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                    // line 53
                    if (                    $this->load($context["template"], 53)->unwrap()->hasBlock("menu", $context)) {
                        // line 54
                        $_v0 = $context;
                        $_v1 = ["collector" => CoreExtension::getAttribute($this->env, $this->source, ($context["profile"] ?? null), "getcollector", [$context["name"]], "method", false, false, false, 54), "profiler_markup_version" => ($context["profiler_markup_version"] ?? null)];
                        if (!is_iterable($_v1)) {
                            throw new RuntimeError('Variables passed to the "with" tag must be a mapping.', 54, $this->getSourceContext());
                        }
                        $_v1 = CoreExtension::toArray($_v1);
                        $context = $_v1 + $context + $this->env->getGlobals();
                        // line 55
                        yield from                         $this->load($context["template"], 55)->unwrap()->yieldBlock("menu", $context);
                        $context = $_v0;
                    }
                    yield from [];
                })())) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 59
                yield "                                        ";
                if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(($context["menu"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 60
                    yield "                                            <li class=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["name"], "html", null, true);
                    yield " ";
                    yield ((($context["name"] == ($context["panel"] ?? null))) ? ("selected") : (""));
                    yield "\">
                                                <a href=\"";
                    // line 61
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("_profiler", ["token" => ($context["token"] ?? null), "panel" => $context["name"], "type" => ($context["profile_type"] ?? null)]), "html", null, true);
                    yield "\">";
                    yield ($context["menu"] ?? null);
                    yield "</a>
                                            </li>
                                        ";
                }
                // line 64
                yield "                                    ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['name'], $context['template'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 65
            yield "                                </ul>
                            ";
        }
        // line 67
        yield "                        </div>

                        ";
        // line 69
        yield Twig\Extension\CoreExtension::include($this->env, $context, "@WebProfiler/Profiler/settings.html.twig");
        yield "
                    ";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 29
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_sidebar_shortcuts_links(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "sidebar_shortcuts_links"));

        // line 30
        yield "                                    <div class=\"shortcuts\">
                                        <a class=\"btn btn-link\" href=\"";
        // line 31
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("_profiler_search", ["limit" => 10, "type" => ($context["profile_type"] ?? null)]), "html", null, true);
        yield "\">Last 10</a>
                                        <a class=\"btn btn-link\" href=\"";
        // line 32
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("_profiler", Twig\Extension\CoreExtension::merge(["token" => "latest", "type" => ($context["profile_type"] ?? null)], CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "query", [], "any", false, false, false, 32), "all", [], "any", false, false, false, 32))), "html", null, true);
        yield "\">Latest</a>

                                        <a class=\"sf-toggle btn btn-link\" data-toggle-selector=\"#sidebar-search\" ";
        // line 34
        if ((array_key_exists("tokens", $context) || array_key_exists("about", $context))) {
            yield "data-toggle-initial=\"display\"";
        }
        yield ">
                                            ";
        // line 35
        yield Twig\Extension\CoreExtension::source($this->env, "@WebProfiler/Icon/search.svg");
        yield " <span class=\"hidden-small\">Search</span>
                                        </a>
                                    </div>
                                ";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 76
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_panel(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "panel"));

        yield "";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "@WebProfiler/Profiler/layout.html.twig";
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
        return array (  327 => 76,  315 => 35,  309 => 34,  304 => 32,  300 => 31,  297 => 30,  287 => 29,  277 => 69,  273 => 67,  269 => 65,  255 => 64,  247 => 61,  240 => 60,  237 => 59,  231 => 55,  223 => 54,  221 => 53,  218 => 52,  201 => 51,  198 => 50,  195 => 49,  192 => 48,  189 => 47,  186 => 46,  184 => 45,  181 => 44,  179 => 43,  173 => 40,  170 => 39,  168 => 29,  164 => 27,  154 => 26,  146 => 20,  142 => 18,  140 => 17,  139 => 16,  138 => 15,  137 => 14,  136 => 13,  135 => 12,  132 => 11,  129 => 10,  126 => 9,  116 => 8,  103 => 77,  101 => 76,  97 => 75,  91 => 71,  89 => 26,  82 => 21,  80 => 8,  74 => 5,  71 => 4,  61 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "@WebProfiler/Profiler/layout.html.twig", "C:\\Users\\PC\\sirm\\sirm-back\\vendor\\symfony\\web-profiler-bundle\\Resources\\views\\Profiler\\layout.html.twig");
    }
}

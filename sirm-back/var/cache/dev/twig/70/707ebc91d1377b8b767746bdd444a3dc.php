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

/* examen/index.html.twig */
class __TwigTemplate_1ca647becb4b8173701a4a37e613d4ef extends Template
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
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "examen/index.html.twig"));

        $this->parent = $this->load("base.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Gestion des examens";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 6
        yield "<div class=\"container py-4\" data-controller=\"examen\">
    <h1 class=\"mb-4\">Gestion des examens</h1>

    <div class=\"card mb-4\">
        <div class=\"card-body\">
            <h2 class=\"h5 mb-3\">Planifier un examen</h2>

            <form data-examen-target=\"form\" data-action=\"submit->examen#creerExamen\">
                <div class=\"row g-3\">
                    <div class=\"col-md-6\">
                        <label for=\"patient\" class=\"form-label\">Patient</label>
                        <select id=\"patient\" class=\"form-select\" data-examen-target=\"patientSelect\" required>
                            <option value=\"\">Sélectionnez un patient</option>
                            ";
        // line 19
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["patients"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["patient"]) {
            // line 20
            yield "                                <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["patient"], "id", [], "any", false, false, false, 20), "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["patient"], "prenom", [], "any", false, false, false, 20), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["patient"], "nom", [], "any", false, false, false, 20), "html", null, true);
            yield " (";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["patient"], "patientId", [], "any", false, false, false, 20), "html", null, true);
            yield ")</option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['patient'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        yield "                        </select>
                    </div>

                    <div class=\"col-md-6\">
                        <label for=\"type\" class=\"form-label\">Type d'examen</label>
                        <select
                            id=\"type\"
                            class=\"form-select\"
                            data-examen-target=\"typeSelect\"
                            data-action=\"change->examen#filtrerMachines\"
                            required
                        >
                            <option value=\"\">Sélectionnez un type</option>
                            ";
        // line 35
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["types"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
            // line 36
            yield "                                <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["type"], "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["type"], "html", null, true);
            yield "</option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['type'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 38
        yield "                        </select>
                    </div>

                    <div class=\"col-md-6\">
                        <label for=\"machine\" class=\"form-label\">Machine</label>
                        <select id=\"machine\" class=\"form-select\" data-examen-target=\"machineSelect\" required>
                            <option value=\"\">Sélectionnez une machine</option>
                            ";
        // line 45
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["machines"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["machine"]) {
            // line 46
            yield "                                <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["machine"], "id", [], "any", false, false, false, 46), "html", null, true);
            yield "\" data-modalite=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["machine"], "modalite", [], "any", false, false, false, 46), "html", null, true);
            yield "\" data-statut=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["machine"], "statut", [], "any", false, false, false, 46), "html", null, true);
            yield "\" ";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["machine"], "statut", [], "any", false, false, false, 46) != "DISPONIBLE")) {
                yield "disabled";
            }
            yield ">
                                    ";
            // line 47
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["machine"], "nom", [], "any", false, false, false, 47), "html", null, true);
            yield " (";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["machine"], "modalite", [], "any", false, false, false, 47), "html", null, true);
            yield ") - ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["machine"], "statut", [], "any", false, false, false, 47), "html", null, true);
            yield "
                                </option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['machine'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 50
        yield "                        </select>
                    </div>

                    <div class=\"col-md-6\">
                        <label for=\"date\" class=\"form-label\">Date et heure</label>
                        <input
                            id=\"date\"
                            class=\"form-control\"
                            type=\"datetime-local\"
                            data-examen-target=\"dateInput\"
                            value=\"";
        // line 60
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "Y-m-d\\TH:i"), "html", null, true);
        yield "\"
                        />
                    </div>

                    <div class=\"col-12\">
                        <label for=\"description\" class=\"form-label\">Description</label>
                        <textarea
                            id=\"description\"
                            class=\"form-control\"
                            rows=\"3\"
                            data-examen-target=\"descriptionInput\"
                            placeholder=\"Description optionnelle\"
                        ></textarea>
                    </div>

                    <div class=\"col-12\">
                        <button class=\"btn btn-primary\" type=\"submit\">Créer l'examen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class=\"d-flex justify-content-between align-items-center mb-3\">
        <h2 class=\"h5 m-0\">Liste des examens</h2>
        <div class=\"btn-group\" role=\"group\" aria-label=\"Pagination\">
            <a
                class=\"btn btn-outline-secondary ";
        // line 87
        if ((($context["page"] ?? null) <= 1)) {
            yield "disabled";
        }
        yield "\"
                href=\"";
        // line 88
        if ((($context["page"] ?? null) > 1)) {
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("examen_page", ["page" => (($context["page"] ?? null) - 1)]), "html", null, true);
        } else {
            yield "#";
        }
        yield "\"
            >Précédent</a>
            <a class=\"btn btn-outline-secondary\" href=\"";
        // line 90
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("examen_page", ["page" => (($context["page"] ?? null) + 1)]), "html", null, true);
        yield "\">Suivant</a>
        </div>
    </div>

    <div data-examen-target=\"errorBox\"></div>

    <div data-examen-target=\"examensList\">
        ";
        // line 97
        if (Twig\Extension\CoreExtension::testEmpty(($context["examens"] ?? null))) {
            // line 98
            yield "            <p class=\"text-center text-muted\">Aucun examen en base de données</p>
        ";
        } else {
            // line 100
            yield "            ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["examens"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["e"]) {
                // line 101
                yield "                ";
                $context["badgeClass"] = "bg-secondary";
                // line 102
                yield "                ";
                if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 102), "value", [], "any", false, false, false, 102) == "PLANIFIE")) {
                    // line 103
                    yield "                    ";
                    $context["badgeClass"] = "bg-info";
                    // line 104
                    yield "                ";
                } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 104), "value", [], "any", false, false, false, 104) == "EN_COURS")) {
                    // line 105
                    yield "                    ";
                    $context["badgeClass"] = "bg-warning";
                    // line 106
                    yield "                ";
                } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 106), "value", [], "any", false, false, false, 106) == "RECU")) {
                    // line 107
                    yield "                    ";
                    $context["badgeClass"] = "bg-success";
                    // line 108
                    yield "                ";
                } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 108), "value", [], "any", false, false, false, 108) == "ANNULE")) {
                    // line 109
                    yield "                    ";
                    $context["badgeClass"] = "bg-danger";
                    // line 110
                    yield "                ";
                }
                // line 111
                yield "                <div class=\"card mb-3 examen-card\">
                    <div class=\"card-body\">
                        <div class=\"row\">
                            <div class=\"col-md-6\">
                                <h5 class=\"card-title\">Patient: ";
                // line 115
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "patient", [], "any", false, false, false, 115), "prenom", [], "any", false, false, false, 115), "html", null, true);
                yield " ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "patient", [], "any", false, false, false, 115), "nom", [], "any", false, false, false, 115), "html", null, true);
                yield "</h5>
                                <p class=\"mb-1\"><strong>Type:</strong> ";
                // line 116
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["e"], "type", [], "any", false, false, false, 116), "html", null, true);
                yield "</p>
                                <p class=\"mb-1\"><strong>Date:</strong> ";
                // line 117
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["e"], "date", [], "any", false, false, false, 117), "d/m/Y H:i"), "html", null, true);
                yield "</p>
                                <p class=\"mb-1\">
                                    <strong>Machine:</strong> ";
                // line 119
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "machine", [], "any", false, false, false, 119), "nom", [], "any", false, false, false, 119), "html", null, true);
                yield " (";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "machine", [], "any", false, false, false, 119), "modalite", [], "any", false, false, false, 119), "html", null, true);
                yield ")
                                    ";
                // line 120
                $context["machineColor"] = "secondary";
                // line 121
                yield "                                    ";
                if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "machine", [], "any", false, false, false, 121), "statut", [], "any", false, false, false, 121) == "DISPONIBLE")) {
                    // line 122
                    yield "                                        ";
                    $context["machineColor"] = "success";
                    // line 123
                    yield "                                    ";
                } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "machine", [], "any", false, false, false, 123), "statut", [], "any", false, false, false, 123) == "EN_COURS")) {
                    // line 124
                    yield "                                        ";
                    $context["machineColor"] = "warning";
                    // line 125
                    yield "                                    ";
                } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "machine", [], "any", false, false, false, 125), "statut", [], "any", false, false, false, 125) == "FAIT")) {
                    // line 126
                    yield "                                        ";
                    $context["machineColor"] = "danger";
                    // line 127
                    yield "                                    ";
                }
                // line 128
                yield "                                    <span class=\"badge bg-";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["machineColor"] ?? null), "html", null, true);
                yield " ms-2\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "machine", [], "any", false, false, false, 128), "statut", [], "any", false, false, false, 128), "html", null, true);
                yield "</span>
                                </p>
                            </div>
                            <div class=\"col-md-6\">
                                <div class=\"d-flex justify-content-between align-items-center\">
                                    <div>
                                        <span class=\"badge status-badge ";
                // line 134
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["badgeClass"] ?? null), "html", null, true);
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 134), "value", [], "any", false, false, false, 134), "html", null, true);
                yield "</span>
                                        ";
                // line 135
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["e"], "description", [], "any", false, false, false, 135)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 136
                    yield "                                            <p class=\"mt-2 small text-muted\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["e"], "description", [], "any", false, false, false, 136), "html", null, true);
                    yield "</p>
                                        ";
                }
                // line 138
                yield "                                    </div>
                                    <div>
                                        <button
                                            class=\"btn btn-sm btn-outline-primary\"
                                            onclick=\"window.examenController && window.examenController.changerStatut(";
                // line 142
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["e"], "id", [], "any", false, false, false, 142), "html", null, true);
                yield ", 'EN_COURS')\"
                                            ";
                // line 143
                if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 143), "value", [], "any", false, false, false, 143) != "PLANIFIE")) {
                    yield "disabled";
                }
                // line 144
                yield "                                        >Commencer</button>
                                        ";
                // line 145
                if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 145), "value", [], "any", false, false, false, 145) == "EN_COURS")) {
                    // line 146
                    yield "                                            <button
                                                class=\"btn btn-sm btn-outline-success\"
                                                onclick=\"window.examenController && window.examenController.changerStatut(";
                    // line 148
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["e"], "id", [], "any", false, false, false, 148), "html", null, true);
                    yield ", 'RECU')\"
                                            >Reçu</button>
                                        ";
                }
                // line 151
                yield "                                        ";
                if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 151), "value", [], "any", false, false, false, 151) != "RECU") && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["e"], "statut", [], "any", false, false, false, 151), "value", [], "any", false, false, false, 151) != "ANNULE"))) {
                    // line 152
                    yield "                                            <button
                                                class=\"btn btn-sm btn-outline-danger\"
                                                onclick=\"window.examenController && window.examenController.annulerExamen(";
                    // line 154
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["e"], "id", [], "any", false, false, false, 154), "html", null, true);
                    yield ")\"
                                            >Annuler</button>
                                        ";
                }
                // line 157
                yield "                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['e'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 164
            yield "        ";
        }
        // line 165
        yield "    </div>
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
        return "examen/index.html.twig";
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
        return array (  429 => 165,  426 => 164,  414 => 157,  408 => 154,  404 => 152,  401 => 151,  395 => 148,  391 => 146,  389 => 145,  386 => 144,  382 => 143,  378 => 142,  372 => 138,  366 => 136,  364 => 135,  358 => 134,  346 => 128,  343 => 127,  340 => 126,  337 => 125,  334 => 124,  331 => 123,  328 => 122,  325 => 121,  323 => 120,  317 => 119,  312 => 117,  308 => 116,  302 => 115,  296 => 111,  293 => 110,  290 => 109,  287 => 108,  284 => 107,  281 => 106,  278 => 105,  275 => 104,  272 => 103,  269 => 102,  266 => 101,  261 => 100,  257 => 98,  255 => 97,  245 => 90,  236 => 88,  230 => 87,  200 => 60,  188 => 50,  175 => 47,  162 => 46,  158 => 45,  149 => 38,  138 => 36,  134 => 35,  119 => 22,  104 => 20,  100 => 19,  85 => 6,  75 => 5,  58 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "examen/index.html.twig", "C:\\Users\\PC\\sirm\\sirm-back\\templates\\examen\\index.html.twig");
    }
}

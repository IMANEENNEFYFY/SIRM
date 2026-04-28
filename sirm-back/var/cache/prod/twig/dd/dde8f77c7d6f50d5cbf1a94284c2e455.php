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

/* dicom_reconciliation/index.html.twig */
class __TwigTemplate_b2d62cf7fc31e2903cf50f8d1f1ef1e4 extends Template
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
        $this->parent = $this->load("base.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Images DICOM en attente";
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "<div class=\"container mt-4\">
    <h1>🩻 Images DICOM en attente de réconciliation</h1>

    ";
        // line 9
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "flashes", ["success"], "method", false, false, false, 9));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 10
            yield "        <div class=\"alert alert-success\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 12
        yield "    ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "flashes", ["danger"], "method", false, false, false, 12));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 13
            yield "        <div class=\"alert alert-danger\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 15
        yield "
    ";
        // line 16
        if (Twig\Extension\CoreExtension::testEmpty(($context["orphelins"] ?? null))) {
            // line 17
            yield "        <div class=\"alert alert-info\">Aucune image en attente.</div>
    ";
        } else {
            // line 19
            yield "        ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["orphelins"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["orphelin"]) {
                // line 20
                yield "        <div class=\"card mb-4 shadow-sm\">
            <div class=\"card-body row\">

                ";
                // line 24
                yield "                <div class=\"col-md-3 text-center\">
                    <img src=\"http://localhost:8042/instances/";
                // line 25
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "orthancInstanceId", [], "any", false, false, false, 25), "html", null, true);
                yield "/preview\"
                         alt=\"Aperçu DICOM\"
                         class=\"img-fluid rounded border\"
                         style=\"max-height: 200px;\" />
                </div>

                ";
                // line 32
                yield "                <div class=\"col-md-4\">
                    <h5>Informations DICOM</h5>
                    <ul class=\"list-unstyled\">
                        <li><strong>Patient :</strong> ";
                // line 35
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "patientNomDicom", [], "any", true, true, false, 35) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "patientNomDicom", [], "any", false, false, false, 35)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "patientNomDicom", [], "any", false, false, false, 35), "html", null, true)) : ("Inconnu"));
                yield "</li>
                        <li><strong>ID Patient :</strong> ";
                // line 36
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "patientIdDicom", [], "any", true, true, false, 36) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "patientIdDicom", [], "any", false, false, false, 36)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "patientIdDicom", [], "any", false, false, false, 36), "html", null, true)) : ("-"));
                yield "</li>
                        <li><strong>Modalité :</strong> ";
                // line 37
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "modality", [], "any", true, true, false, 37) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "modality", [], "any", false, false, false, 37)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "modality", [], "any", false, false, false, 37), "html", null, true)) : ("-"));
                yield "</li>
                        <li><strong>Reçu le :</strong> ";
                // line 38
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "receivedAt", [], "any", false, false, false, 38), "d/m/Y H:i"), "html", null, true);
                yield "</li>
                        <li><strong>Statut :</strong> 
                            <span class=\"badge bg-warning\">";
                // line 40
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "statut", [], "any", false, false, false, 40), "html", null, true);
                yield "</span>
                        </li>
                    </ul>
                </div>

                ";
                // line 46
                yield "                <div class=\"col-md-5\">
                    <h5>Lier à un examen</h5>
                    <form method=\"POST\" 
                          action=\"";
                // line 49
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_dicom_do_reconcile", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["orphelin"], "id", [], "any", false, false, false, 49)]), "html", null, true);
                yield "\">
                        <div class=\"mb-3\">
                            <label class=\"form-label\">ID de l'examen Symfony</label>
                            <input type=\"number\" 
                                   name=\"examen_id\" 
                                   class=\"form-control\" 
                                   placeholder=\"Ex: 42\" 
                                   required />
                        </div>
                        <button type=\"submit\" class=\"btn btn-primary\">
                            ✅ Réconcilier
                        </button>
                    </form>
                </div>

            </div>
        </div>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['orphelin'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 67
            yield "    ";
        }
        // line 68
        yield "</div>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "dicom_reconciliation/index.html.twig";
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
        return array (  195 => 68,  192 => 67,  168 => 49,  163 => 46,  155 => 40,  150 => 38,  146 => 37,  142 => 36,  138 => 35,  133 => 32,  124 => 25,  121 => 24,  116 => 20,  111 => 19,  107 => 17,  105 => 16,  102 => 15,  93 => 13,  88 => 12,  79 => 10,  75 => 9,  70 => 6,  63 => 5,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "dicom_reconciliation/index.html.twig", "C:\\Users\\PC\\sirm\\sirm-back\\templates\\dicom_reconciliation\\index.html.twig");
    }
}

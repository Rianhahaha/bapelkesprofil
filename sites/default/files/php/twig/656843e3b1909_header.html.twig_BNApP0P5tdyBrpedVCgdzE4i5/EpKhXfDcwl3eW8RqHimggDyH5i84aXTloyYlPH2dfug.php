<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @vani/template-parts/header.html.twig */
class __TwigTemplate_44dec23a89bb71f061a66ed8ff67abed extends Template
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
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "
";
        // line 2
        $this->loadTemplate("@vani/template-parts/header-top-blocks.html.twig", "@vani/template-parts/header.html.twig", 2)->display($context);
        // line 3
        echo "  <div class=\"header-sticky header-onscroll\">
<header class=\"header\">
  <div class=\"header-cicle header-cicle1\"></div>
  <div class=\"header-cicle header-cicle3\"></div>
  <div class=\"header-cicle header-cicle4\"></div>
  <div class=\"header-cicle header-cicle6\"></div>
  <div class=\"header-cicle header-cicle7\"></div>
  <div class=\"header-cicle header-cicle8\"></div>
  <div class=\"header-cicle header-cicle10\"></div>
  ";
        // line 12
        if ((($context["is_front"] ?? null) && ($context["slider_show"] ?? null))) {
            // line 13
            echo "  <div class=\"header-cicle header-cicle2\"></div>
  <div class=\"header-cicle header-cicle5\"></div>
  <div class=\"header-cicle header-cicle9\"></div>
  ";
        }
        // line 17
        echo "  <div class=\"container \">
  <div class=\"header-main\">
    ";
        // line 19
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "site_branding", [], "any", false, false, true, 19)) {
            // line 20
            echo "      <div class=\"site-brand\">
        ";
            // line 21
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "site_branding", [], "any", false, false, true, 21), 21, $this->source), "html", null, true);
            echo "
      </div> <!--/.site-branding -->
    ";
        }
        // line 24
        echo "    ";
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 24) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "search_box", [], "any", false, false, true, 24))) {
            // line 25
            echo "      <div class=\"header-main-right\">
        ";
            // line 26
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 26)) {
                // line 27
                echo "          <div class=\"mobile-menu\"><i class=\"icon-menu\"></i></div> ";
                // line 28
                echo "          <div class=\"primary-menu-wrapper\">
            <div class=\"menu-wrap\">
              <div class=\"close-mobile-menu\">x</div>
              ";
                // line 31
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
                echo "
            </div> <!-- /.menu-wrap -->
          </div> <!-- /.primary-menu-wrapper -->
        ";
            }
            // line 35
            echo "        ";
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "search_box", [], "any", false, false, true, 35)) {
                // line 36
                echo "          ";
                $this->loadTemplate("@vani/template-parts/search.html.twig", "@vani/template-parts/header.html.twig", 36)->display($context);
                // line 37
                echo "        ";
            }
            // line 38
            echo "      </div> <!-- /.header-right -->
    ";
        }
        // line 40
        echo "  </div><!-- /header-main -->
  </div><!-- /container -->
  </div><!-- /header-sticky -->
  ";
        // line 43
        if ((($context["is_front"] ?? null) && ($context["slider_show"] ?? null))) {
            // line 44
            echo "    ";
            $this->loadTemplate("@vani/template-parts/slider.html.twig", "@vani/template-parts/header.html.twig", 44)->display($context);
            // line 45
            echo "  ";
        } elseif ( !($context["is_front"] ?? null)) {
            // line 46
            echo "    ";
            $this->loadTemplate("@vani/template-parts/page_header.html.twig", "@vani/template-parts/header.html.twig", 46)->display($context);
            // line 47
            echo "  ";
        }
        // line 48
        echo "</header>
";
        // line 49
        $this->loadTemplate("@vani/template-parts/highlighted.html.twig", "@vani/template-parts/header.html.twig", 49)->display($context);
    }

    public function getTemplateName()
    {
        return "@vani/template-parts/header.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  135 => 49,  132 => 48,  129 => 47,  126 => 46,  123 => 45,  120 => 44,  118 => 43,  113 => 40,  109 => 38,  106 => 37,  103 => 36,  100 => 35,  93 => 31,  88 => 28,  86 => 27,  84 => 26,  81 => 25,  78 => 24,  72 => 21,  69 => 20,  67 => 19,  63 => 17,  57 => 13,  55 => 12,  44 => 3,  42 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@vani/template-parts/header.html.twig", "F:\\laragon\\www\\bapelkes_ORI_COBA1\\themes\\contrib\\vani\\templates\\template-parts\\header.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("include" => 2, "if" => 12);
        static $filters = array("escape" => 21);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['include', 'if'],
                ['escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}

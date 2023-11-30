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

/* modules/modal_page/templates/modal-page-modal.html.twig */
class __TwigTemplate_2a2f2798390da96781421215e26f7249 extends Template
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
        // line 10
        echo "<div id=\"js-modal-page-show-modal\" class=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["modal_class"] ?? null), 10, $this->source), "html", null, true);
        echo "\" data-modal-options=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["modal_options"] ?? null), 10, $this->source), "html", null, true);
        echo "\" data-keyboard=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["close_modal_esc_key"] ?? null), 10, $this->source), "html", null, true);
        echo "\" data-backdrop=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["close_modal_clicking_outside"] ?? null), 10, $this->source), "html", null, true);
        echo "\" aria-modal=\"true\" ";
        (((($context["enable_modal_header"] ?? null) && ($context["display_title"] ?? null))) ? (print ($this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ("aria-labelledby=" . ($context["id_label"] ?? null)), "html", null, true))) : (print ("")));
        echo " aria-describedby=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["id_desc"] ?? null), 10, $this->source), "html", null, true);
        echo "\" role=\"dialog\" tabindex=\"-1\">
  <div class=\"modal-page-dialog modal-dialog ";
        // line 11
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["modal_size"] ?? null), 11, $this->source), "html", null, true);
        echo "\" role=\"document\">
    <div class=\"modal-page-content modal-content\">

      ";
        // line 15
        echo "      ";
        if (($context["enable_modal_header"] ?? null)) {
            // line 16
            echo "
        <div class=\"modal-page-content modal-header ";
            // line 17
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["modal_header_classes"] ?? null), 17, $this->source), "html", null, true);
            echo "\">

          ";
            // line 20
            echo "          ";
            if (($context["display_button_x_close"] ?? null)) {
                // line 21
                echo "            <button type=\"button\" class=\"close js-modal-page-ok-button ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["top_right_button_class"] ?? null), 21, $this->source), "html", null, true);
                echo "\" data-dismiss=\"modal\">
              ";
                // line 22
                if ((($context["top_right_button_label"] ?? null) == "&times;")) {
                    // line 23
                    echo "                &times;
              ";
                } else {
                    // line 25
                    echo "                ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["top_right_button_label"] ?? null), 25, $this->source), "html", null, true);
                    echo "
              ";
                }
                // line 27
                echo "            </button>
          ";
            }
            // line 29
            echo "
          ";
            // line 31
            echo "          ";
            if (($context["display_title"] ?? null)) {
                // line 32
                echo "            <h4 id=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["id_label"] ?? null), 32, $this->source), "html", null, true);
                echo "\" class=\"modal-title modal-page-title\">";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 32, $this->source), "html", null, true);
                echo "</h4>
          ";
            }
            // line 34
            echo "
        </div>

      ";
        }
        // line 38
        echo "
      ";
        // line 40
        echo "      <div id=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["id_desc"] ?? null), 40, $this->source), "html", null, true);
        echo "\" class=\"modal-body modal-page-body\">
        ";
        // line 41
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["text"] ?? null), 41, $this->source));
        echo "
        ";
        // line 42
        if (( !(null === ($context["modal_video_link"] ?? null)) &&  !twig_test_empty(($context["modal_video_link"] ?? null)))) {
            // line 43
            echo "          <iframe src=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["modal_video_link"] ?? null), 43, $this->source));
            echo "\" title=\"Youtube video player\" frameborder=\"0\" allow=\" accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>
        ";
        }
        // line 45
        echo "      </div>

      ";
        // line 48
        echo "      ";
        if (($context["enable_modal_footer"] ?? null)) {
            // line 49
            echo "
        <div class=\"modal-footer modal-page-footer ";
            // line 50
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["modal_footer_classes"] ?? null), 50, $this->source), "html", null, true);
            echo "\">

          <input type=\"hidden\" id=\"modal_id\" name=\"modal_id\" value=\"";
            // line 52
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["id"] ?? null), 52, $this->source), "html", null, true);
            echo "\">

          ";
            // line 55
            echo "          ";
            if (($context["do_not_show_again"] ?? null)) {
                // line 56
                echo "            <label class =\"modal-dont-show-again-label\"><input type=\"checkbox\" class=\"modal-page-please-do-not-show-again\" value=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["id"] ?? null), 56, $this->source), "html", null, true);
                echo "\"> ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["do_not_show_again"] ?? null), 56, $this->source), "html", null, true);
                echo "</label>
            <input type=\"hidden\" id=\"cookie_expiration\" name=\"cookie_expiration\" value=\"";
                // line 57
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["cookie_expiration_time"] ?? null), 57, $this->source), "html", null, true);
                echo "\" />
          ";
            }
            // line 59
            echo "
          <div class=\"modal-buttons\">

            ";
            // line 63
            echo "            ";
            if (($context["enable_left_button"] ?? null)) {
                // line 64
                echo "              <button type=\"button\" class=\"btn btn-default js-modal-page-left-button ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["left_button_class"] ?? null), 64, $this->source), "html", null, true);
                echo "\" data-dismiss=\"modal\">";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["left_label_button"] ?? null), 64, $this->source), "html", null, true);
                echo "</button>
            ";
            }
            // line 66
            echo "
            ";
            // line 68
            echo "            ";
            if (($context["enable_right_button"] ?? null)) {
                // line 69
                echo "              <button type=\"button\" class=\"btn btn-default js-modal-page-ok-button ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["ok_button_class"] ?? null), 69, $this->source), "html", null, true);
                echo "\" data-dismiss=\"modal\" ";
                if (($context["enable_redirect_link"] ?? null)) {
                    echo " data-redirect=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["redirect_link"] ?? null), 69, $this->source), "html", null, true);
                    echo "\" ";
                }
                echo ">";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["button"] ?? null), 69, $this->source), "html", null, true);
                echo "</button>
            ";
            }
            // line 71
            echo "
          </div>

          ";
            // line 75
            echo "          <input type=\"hidden\" id=\"delay_display\" name=\"delay_display\" value=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["delay_display"] ?? null), 75, $this->source));
            echo "\" />
          <input type=\"hidden\" id=\"show_once\" name=\"show_once\" value=\"";
            // line 76
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["modal_page_show_once"] ?? null), 76, $this->source));
            echo "\" />
          <input type=\"hidden\" id=\"auto_hide\" name=\"auto_hide\" value=\"";
            // line 77
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["modal_page_auto_hide"] ?? null), 77, $this->source));
            echo "\" />
          <input type=\"hidden\" id=\"auto_hide_delay\" name=\"auto_hide_delay\" value=\"";
            // line 78
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["modal_page_auto_hide_delay"] ?? null), 78, $this->source));
            echo "\" />
          ";
            // line 79
            if (($context["enable_show_on_height"] ?? null)) {
                // line 80
                echo "          ";
                // line 81
                echo "            <input type=\"hidden\" id=\"height_offset\" name=\"height_offset\" value=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["height_offset"] ?? null), 81, $this->source));
                echo "\" offset-type=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["height_offset_touch"] ?? null), 81, $this->source));
                echo "\" />
          ";
            }
            // line 83
            echo "        </div>

      ";
        }
        // line 86
        echo "
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/modal_page/templates/modal-page-modal.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  245 => 86,  240 => 83,  232 => 81,  230 => 80,  228 => 79,  224 => 78,  220 => 77,  216 => 76,  211 => 75,  206 => 71,  192 => 69,  189 => 68,  186 => 66,  178 => 64,  175 => 63,  170 => 59,  165 => 57,  158 => 56,  155 => 55,  150 => 52,  145 => 50,  142 => 49,  139 => 48,  135 => 45,  129 => 43,  127 => 42,  123 => 41,  118 => 40,  115 => 38,  109 => 34,  101 => 32,  98 => 31,  95 => 29,  91 => 27,  85 => 25,  81 => 23,  79 => 22,  74 => 21,  71 => 20,  66 => 17,  63 => 16,  60 => 15,  54 => 11,  39 => 10,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/modal_page/templates/modal-page-modal.html.twig", "F:\\laragon\\www\\bapelkes_ORI_COBA1\\modules\\modal_page\\templates\\modal-page-modal.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 15);
        static $filters = array("escape" => 10, "raw" => 41);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 'raw'],
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

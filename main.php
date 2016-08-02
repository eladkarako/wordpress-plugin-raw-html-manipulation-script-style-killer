<?php
  /**
   * Plugin Name:        Script-Style Killer
   * Donate link:        https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7994YX29444PA
   * License:            GPL2
   * Version:            4.1.0
   * Description:        Ruthless HTML-Manipulation At A Level Beyond WordPress-API, Slimming Your Page And Client-Loading Times.
   * Author:             eladkarako
   * Author Email:       The_Author_Value_Above@gmail.com
   * Author URI:         http://icompile.eladkarako.com
   * Plugin URI:         https://github.com/eladkarako/wordpress-plugin-raw-html-manipulation-minifier
   */


/* ╔═════════════════════════════════════════════════════╗
   ║ - Hope You've Enjoyed My Work :]                    ║
   ╟─────────────────────────────────────────────────────╢
   ║ - Feel Free To Modifiy And Distribute it (GPL2).    ║
   ╟─────────────────────────────────────────────────────╢
   ║ - Donations Are A *Nice* Way Of Saying Thank-You :] ║
   ║   But Are NOT Necessary!                            ║
   ║                                                     ║
   ║ I'm Doing It For The Fun Of It :]                   ║
   ║                                                     ║
   ║    - Elad Karako                                    ║
   ║         Tel-Aviv, Israel- August 2016.              ║
   ╚═════════════════════════════════════════════════════╝
░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ */


call_user_func(function () {
  if (is_admin()) return;

  require_once('assist.php');

/*╔══════════════════╗
  ║ Modify Raw-HTML. ║
  ╚══════════════════╝*/
  add_action('template_redirect', function (){
    @ob_start(function($html){
    /*────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────*/
    /*╔═══════╗
      ║ $html ║
      ╚═══════╝*/
                $html = protect_specific_tags_from_modifications($html);     /*    protect pre-tags and code-tags original content.  */
                /*-------------------------------------------------------------------------------------------------------------*/

                $html = preg_replace(
                            "#\<\s*link[^\>]*href\s*=\s*[\"\'](jquery|backbone|prototype|scriptaculous|thickbox|embed|a11|i18n|cleanfix)[\"\'][^\>]*\>#msi"
                          , ''
                          , $html
                        );

                $html = preg_replace(
                            "#\<\s*script[^\>]*src\s*=\s*[\"\'](jquery|backbone|prototype|scriptaculous|thickbox|embed|a11|i18n|cleanfix)[\"\'][^\>]*\>\<\/script[^\>]*\>#msi"
                          , ''
                          , $html
                        );
                /*-------------------------------------------------------------------------------------------------------------*/
                $html = unprotect_pre_and_code_tags_content_from_change($html);  /*  unprotect (bring back) pre-tags and code-tags original content. */
    /*────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────*/
                return $html;
             });
  }, -9999997);

  add_action('shutdown', function () {
    while (ob_get_level() > 0) @ob_end_flush();
  }, +9999997);
});

?>
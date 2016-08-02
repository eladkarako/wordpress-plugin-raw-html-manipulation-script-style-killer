<?php
  /**
   * here you put "assistance" functions so your "modifier will not be huge.
   * you can put here any type of helping function, as long its "context-free" (for example, you give it text, and get
   * variation of it, etc..)
   */


  /**
   * measure the difference,
   * return a formatter HTML comment.
   *
   * @param string $html_before - the raw HTML
   * @param string $html_after  - any state HTML (preferably, at final state..)
   *
   * @return string             - HTML comment you can place at the end of the HTML (for example).
   */
  function get_delta_information($html_before, $html_after) {

    $length_chars_before = mb_strlen($html_before);
    $length_bytes_before = mb_strlen($html_before, '8bit');

    $length_chars_after = mb_strlen($html_after);
    $length_bytes_after = mb_strlen($html_after, '8bit');

    unset($html_before); /* just locally to the function. */
    unset($html_after);  /* just locally to the function. */

    $results = [
      "chars" => [
        "before"  => format_number($length_chars_before),
        "after"   => format_number($length_chars_after),
        "delta"   => format_number($length_chars_before - $length_chars_after),
        "percent" => format_number(100 * (($length_chars_after - $length_chars_before) / $length_chars_before)) . '%'
      ],
      "bytes" => [
        "before"  => human_readable_memory_sizes($length_bytes_before),
        "after"   => human_readable_memory_sizes($length_bytes_after),
        "delta"   => human_readable_memory_sizes($length_bytes_before - $length_bytes_after),
        "percent" => format_number(100 * (($length_bytes_after - $length_bytes_before) / $length_bytes_before)) . '%'
      ]
    ];

    $results = array_merge([], ["all" => $results]); /* adds "all" */

    /* -- */

    $output = base64_decode("CjwhLS0gV29yZFByZXNzIFJhdy1IVE1MLVByb2Nlc3NpbmcgRnJhbWV3b3JrIEZvciBQSFAtRGV2ZWxvcGVycyAvRWxhZCBLYXJha28gKDIwMTUpICAK");
    $output .= json_encode($results);/*, JSON_PRETTY_PRINT);*/
    $output .= "\n-->\n";

    return $output;

  }

  /* o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o */
  /* o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o */
  /* o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o */

  /**
   * those two function solved a very pesky problem,
   * <pre> and <code> tags may include a text that looks like HTML, JavaScript or other "target for modification",
   * to prevent modification from "finding it valid for modification", I'm keeping the original-content, plain-sight,
   * (even compress it to save space), the last step (before returning the HTML) is to reverse the protection,
   *
   * @original_idea_and_implementation: Elad Karako (eladkarako@gmail.com) ;)
   */


  /**
   * the content of the pre-tags and code-tags should be intact,
   * run "protect_pre_and_code_tags_content_from_change" before each starting to modify,
   * and "unprotect_pre_and_code_tags_content_from_change" after you've done modifying the HTML.
   *
   * @param $html
   *
   * @return mixed
   */
  function protect_specific_tags_from_modifications($html) {
    $tags_to_protect = [
      'pre'        => '_p_r_e_'
      , 'code'     => '_c_o_d_e_'
      , 'textarea' => '_t_e_x_t_a_r_e_a_'
    ];

    foreach ($tags_to_protect as $tag => $protected_tag) {
      $html = preg_replace_callback("#<" . $tag . "(.*?)>(.*?)</" . $tag . ">#is", function ($arr) use ($tag, $protected_tag) {
        if (!isset($arr[0])) /* no found: no add, no delete */
          return;

        $full = $arr[0];

        return '<' . $protected_tag . '>' . base64_encode(gzcompress($full)) . '</' . $protected_tag . '>'; /*                      clean from HTML. */
      }, $html);
    }

    return $html;
  }

  /**
   * returning the pre-tags and code-tags original unmodified content, run this after
   * "protect_pre_and_code_tags_content_from_change" and all the HTML-modifying (last before returning the HTML).
   *
   * @param $html
   *
   * @return mixed
   */
  function unprotect_pre_and_code_tags_content_from_change($html) {
    $tags_to_unprotect = [
      '_p_r_e_'
      , '_c_o_d_e_'
      , '_t_e_x_t_a_r_e_a_'
    ];


    foreach ($tags_to_unprotect as $index => $tag) {
      $html = preg_replace_callback("#<" . $tag . ">(.*?)</" . $tag . ">#is", function ($arr) use ($tag) {
        if (!isset($arr[0])) /*no found: no add, no delete*/
          return;

/*        $full = $arr[0]; */
        $inline = $arr[1];

        return gzuncompress(base64_decode($inline)); /*                      clean from HTML. */
      }, $html);
    }

    return $html;
  }


  /* o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o */
  /* o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o */
  /* o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o0O0o */

?>

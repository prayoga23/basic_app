<?php

// A customized form_dropdown, adding param attribute to add additional attribute tag to options
// Copied and modified from the original (Codeigniter v4.3.1)

if (!function_exists('custom_form_dropdown')) {
  /**
   * Drop-down Menu
   *
   * @param array|string        $data
   * @param array|string        $options
   * @param array|string        $selected
   * @param array|object|string $extra    string, array, object that can be cast to array
   * @param array|string        $attributes
   */
  function custom_form_dropdown($data = '', $options = [], $selected = [], $extra = '', $attributes = []): string
  {
    $defaults = [];
    if (is_array($data)) {
      if (isset($data['selected'])) {
        $selected = $data['selected'];
        unset($data['selected']); // select tags don't have a selected attribute
      }
      if (isset($data['options'])) {
        $options = $data['options'];
        unset($data['options']); // select tags don't use an options attribute
      }
    } else {
      $defaults = ['name' => $data];
    }

    if (!is_array($selected)) {
      $selected = [$selected];
    }
    if (!is_array($options)) {
      $options = [$options];
    }
    if (!is_array($attributes)) {
      $attributes = [$attributes];
    }

    // If no selected state was submitted we will attempt to set it automatically
    if (empty($selected)) {
      if (is_array($data)) {
        if (isset($data['name'], $_POST[$data['name']])) {
          $selected = [$_POST[$data['name']]];
        }
      } elseif (isset($_POST[$data])) {
        $selected = [$_POST[$data]];
      }
    }

    // Standardize selected as strings, like the option keys will be
    foreach ($selected as $key => $item) {
      $selected[$key] = (string) $item;
    }

    $extra    = stringify_attributes($extra);
    $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === false) ? ' multiple="multiple"' : '';
    $form     = '<select ' . rtrim(parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

    foreach ($options as $key => $val) {
      // Keys should always be strings for strict comparison
      $key = (string) $key;

      $attr = '';
      if (isset($attributes[$key])) {
        $attr = ' ' . (is_array($attributes[$key]) ? implode(' ', $attributes[$key]) : $attributes[$key]);
      }

      if (is_array($val)) {
        if (empty($val)) {
          continue;
        }

        $form .= '<optgroup label="' . $key . "\">\n";

        foreach ($val as $optgroupKey => $optgroupVal) {
          // Keys should always be strings for strict comparison
          $optgroupKey = (string) $optgroupKey;

          $sel = in_array($optgroupKey, $selected, true) ? ' selected="selected"' : '';
          $form .= '<option value="' . htmlspecialchars($optgroupKey) . '"' . $sel . $attr . '>' . $optgroupVal . "</option>\n";
        }

        $form .= "</optgroup>\n";
      } else {
        $form .= '<option value="' . htmlspecialchars($key) . '"'
          . (in_array($key, $selected, true) ? ' selected="selected"' : '') . $attr . '>'
          . $val . "</option>\n";
      }
    }

    return $form . "</select>\n";
  }
}

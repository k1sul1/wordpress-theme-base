<?php

namespace k1\Template;

function getScheme($acfGroupField = []) {
  $data = \k1\params([
    'base' => 'default',
    'advancedMode' => false,

    // advanced mode options
    'background' => 'default',
    'headings' => 'default',
    'text' => 'default',
    'links' => 'default',
  ], $acfGroupField);

  $advanced = $data['advancedMode'];
  $scheme = [
    "k1-scheme",
    "k1-scheme--base-$data[base]",
  ];

  unset($data['advancedMode']);
  unset($data['base']);

  if ($advanced) {
    foreach ($data as $k => $v) {
      if ($v !== 'choose') {
        $scheme[] = "k1-scheme--$k-$v";
      }
    }
  }

  return $scheme;
}

function getBreakpoints($acfGroupField = []) {
  $data = \k1\params([
    'hideIn' => [],
  ], $acfGroupField);

  $bps = [];

  if (!empty($data['hideIn'])) {
    foreach ($data['hideIn'] as $breakpoint) {
      $bps[] = "k1-bp--hide-$breakpoint";
    }
  }

  return $bps;
}

function getPosition($positionName = 'centerCenter') {
  return ["k1-pos--$positionName"];
}

function getBackgroundSize($size = 'cover') {
  return ["k1-bgsize--$size"];
}

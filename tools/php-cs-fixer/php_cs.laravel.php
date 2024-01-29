<?php

include 'php_cs.rules.php';

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$root_path = getenv('ROOT_PATH') ?: dirname(dirname(dirname(__FILE__)));
$allow_risky_rules = filter_var(getenv('ALLOW_RISKY_RULES'), FILTER_VALIDATE_BOOLEAN);
$finder_to_use = getenv('FINDER_TO_USE') ?: 'all';

$base_finder = Finder::create()
  ->notPath('bootstrap/')
  ->notPath('docs/')
  ->notPath('node_modules/')
  ->notPath('files/')
  ->notPath('public/')
  ->notPath('public_themes/')
  ->notPath('resources/db')
  ->notPath('resources/js')
  ->notPath('resources/sass')
  ->notPath('simplesaml/')
  ->notPath('storage/')
  ->notPath('tools/')
  ->notPath('uploads/')
  ->notPath('vendor/')
  ->notPath('app/overrides/')
  ->name('*.php')
  ->notName('*.blade.php')
  ->notName('index.php')
  ->ignoreDotFiles(true)
  ->ignoreVCS(true);

$finder = $base_finder;
$finder = $finder->in($root_path);

if ($allow_risky_rules === true) {
  echo "!! WARNING !! Formatting with Risky Rules!";
}

return (new PhpCsFixer\Config())
  ->setFinder($finder)
  ->setRules($allow_risky_rules ? array_merge($rules, $risky_rules) : $rules)
  ->setIndent("    ")
  ->setRiskyAllowed($allow_risky_rules)
  ->setUsingCache(true);

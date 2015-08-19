<?php namespace App\Console\Commands;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file QaPhpCsFixer.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description QaPhpCsFixer
 *
 ******************************************************************************/

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;


class QaPhpCsFixer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qa:phpcs {action=detect : fix to rewrite php file (apply diff visible with detect action), test for unit testing, detect to show necessary changes}';

    const ACTION_FIX    = 'fix';
    const ACTION_TEST   = 'test';
    const ACTION_DETECT = 'detect';

    public static $actions = [self::ACTION_TEST, self::ACTION_DETECT, self::ACTION_FIX];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PHP code synthax fixer';

    public static $fixers = [
        'encoding', 'short_tag', 'braces', 'elseif', 'eof_ending', 'function_call_space', 'function_declaration',
        'indentation', 'line_after_namespace', 'linefeed', 'lowercase_constants', 'lowercase_keywords',
        'method_argument_space', 'multiple_use', 'parenthesis', 'php_closing_tag', "single_line_after_import",
        "trailing_space", "visibility", "concat_without_space", "duplicate_semicolon",
        "empty_return", "extra_empty_line", "join_function", "list_comma", "multiline_array_trailing_comma",
        "namespace_no_leading_whitespace", "no_empty_lines_after_phpdoc", "object_operator", "operators_space",
        "phpdoc_indent", "phpdoc_inline_tag", "phpdoc_param", "phpdoc_scalar", "phpdoc_separation",
        "phpdoc_tri", "phpdoc_type_to_var", "remove_leading_slash_us", "remove_lines_between_use", "return",
        "self_accessor", "single_quot", "spaces_before_semicolon", "spaces_cas", "standardize_not_equla", "ternary_space",
        "trim_array_space", "unary_operators_space", "unused_us", "whitespacy_line", "align_double_arrow", "align_equals",
        "concat_with_space", "multiline_spaces_before_semicolon", "no_blank_lines_before_namespace", "ordered_use",
        "short_echo_tag",
    ];

    /**
     * @throws \Exception
     */
    public function handle()
    {
        \Log::info('QA::PHPCS Run php code synthax fixer');

        $action = $this->getAction();

        $changes = $this->runPhpCs($action);

        if ($action === self::ACTION_TEST) {
            if (is_bool($changes) || empty($changes)) {
                $this->comment('No violation detected ! kudos !');

                return;
            } else {
                throw new \Exception('Your code is bad, and you should feel bad');
            }
        }

        if (!isset($changes->files) || empty($changes->files)) {
            $this->comment('No violation detected ! kudos !');

            return;
        }


        $this->writeChanges($changes, $action);

        if ($this->getOutput()->getVerbosity() == 1) {
            $this->info('To show more details you can use -v');
        }
    }

    protected function writeChanges($changes, $action)
    {
        $i = 1;
        foreach ($changes->files as $change) {
            $this->info(sprintf('%d - %s: %s', $i, $change->name, implode(', ', $change->appliedFixers)));
            if ($this->getOutput()->getVerbosity() > 1 && $action == self::ACTION_DETECT) {
                $this->getOutput()->writeln($change->diff);
            }
            $this->getOutput()->writeln('');
            $i++;
        }
    }

    protected function getAction()
    {
        $action = $this->argument('action');
        if (!in_array($action, self::$actions, true)) {
            $action = 'test';
        }

        return $action;
    }

    protected function runPhpCs($action)
    {
        $process = new Process('./vendor/bin/php-cs-fixer fix app -v --format=json --fixers='
            . implode(',', self::$fixers)
            . ' '
            . (in_array($action, [self::ACTION_DETECT, self::ACTION_TEST], true) ? '--dry-run  --diff' : ''));

        if ($this->getOutput()->getVerbosity() > 2) {
            $this->comment('Run ' . $process->getCommandLine());
        }

        $process->run();

        return json_decode($process->getOutput());
    }
}

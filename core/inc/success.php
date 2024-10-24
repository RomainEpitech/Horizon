<?php

    namespace Horizon\Core\Inc;

    class success {
        static public function displaySuccessMessage($message) {
            $greenBackground = "\033[42m";
            $blackText = "\033[30m";
            $reset = "\033[0m";

            echo "{$greenBackground}{$blackText}\n";
            echo str_pad(" ", 80) . "\n";
            echo str_pad(" $message ", 80, " ", STR_PAD_BOTH) . "\n";
            echo str_pad(" ", 80) . "\n";
            echo $reset;
        }
    }
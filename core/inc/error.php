<?php

    namespace Horizon\Core\Inc;

    class Error {
        static public function displayErrorMessage($message) {
            $redBackground = "\033[41m";
            $whiteText = "\033[97m";
            $reset = "\033[0m";

            echo "{$redBackground}{$whiteText}\n";
            echo str_pad(" ", 80) . "\n";
            echo str_pad(" $message ", 80, " ", STR_PAD_BOTH) . "\n";
            echo str_pad(" ", 80) . "\n";
            echo $reset;
        }
    }
<?php

namespace App\Helpers\Console;

class Kernel extends Commands {
    static function getCommand(array $args): string {
        if (!key_exists(1, $args)) stop("Please provide a command.", true);
        $command = $args[1];
        if (str_contains($command, ":")) {
            $parts = explode(":", $command);
            if (count($parts) > 2) stop("Please provide a correct command.", true);
            $command = join(array($parts[0], ucfirst($parts[1])));
        }
        
        if (!method_exists(static::class, $command)) stop("Please provide a correct command.", true);

        return $command;
    }
    static function getParams(array $args): array {
        return array_slice($args, 2);
    }
    public function run(string $command, array $param): int {
        $this->{$command}($param);

        return 1;
    }
}
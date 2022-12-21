<?php declare(strict_types=1);

namespace Clirec;

class CliRequestParser
{
    public function parse(array $argv): array
    {
        $chunkList = array_slice($argv, 1);
        $result = [];
        foreach ($chunkList as $chunk) {
            $result = array_merge($result, $this->parseChunk($chunk));
        }
        return $result;
    }

    private function parseChunk(string $chunk): ?array
    {
        $parseResult = $this->parseShortParams($chunk);
        if ($parseResult) {
            return $parseResult;
        }
        $parseResult = $this->parseFullParam($chunk);
        if ($parseResult) {
            return $parseResult;
        }
        return [$chunk];
    }

    /**
     * '-abc' -> ['a'=>true, 'b'=>true, 'c'=>true]
     */
    private function parseShortParams(string $chunk): ?array
    {
        if (!preg_match('|^-([a-zA-Z]+)|i', $chunk, $matches)) {
            return null;
        }
        $keys = mb_str_split($matches[1]);
        return array_fill_keys($keys, true);
    }

    /**
     * '--key' -> ['key'=>true]
     * '--key=' -> ['key'=>'']
     * '--key=value' -> ['key'=>'value']
     */
    private function parseFullParam(string $chunk): ?array
    {
        if (!preg_match('|^--([a-zA-Z1-9]+)(=?)(.*)$|i', $chunk, $matches)) {
            return null;
        }
        if ($matches[2] !== '=') {
            return [$matches[1] => true];
        }
        return [$matches[1] => $matches[3]];
    }
}
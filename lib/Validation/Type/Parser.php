<?php
declare(strict_type=1);


namespace Validation\Type;


class Parser
{
    /**
     * @param string $path
     * @return array className, namespace, useStatement
     */
    public function extractPhpClasses(string $path): array
    {
        $code = file_get_contents($path);
        $tokens = @token_get_all($code);
        $namespace = $class = $classLevel = $level = NULL;
        $uses = [];
        $classes = [];
        foreach ($tokens as $token){
            switch (is_array($token) ? $token[0] : $token) {
                case T_NAMESPACE:
                    $namespace = ltrim($this->fetch($tokens, [T_STRING, T_NS_SEPARATOR]) . '\\', '\\');
                    break;

                case T_CLASS:
                    if ($name = $this->fetch($tokens, T_STRING)) {
                        $classes[] = $namespace . $name;
                    }
                    break;
                case T_INTERFACE:
                case T_TRAIT:
                    break;
                case T_USE:
                    $uses[] = rtrim(ltrim($this->fetch($tokens, [T_STRING, T_NS_SEPARATOR, T_AS]) . '\\', '\\'), '\\');
                    break;
            }
        }
        return array($classes, $namespace, $uses);
    }

    private function fetch(&$tokens, $take): ?string
    {
        $res = NULL;
        while ($token = current($tokens)) {
            list($token, $s) = is_array($token) ? $token : [$token, $token];
            if (in_array($token, (array) $take, TRUE)) {
                $res .= $s;
            } elseif (!in_array($token, [T_DOC_COMMENT, T_WHITESPACE, T_COMMENT], TRUE)) {
                break;
            }
            next($tokens);
        }
        return $res;
    }
}
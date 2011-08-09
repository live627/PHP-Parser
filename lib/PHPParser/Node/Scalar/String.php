<?php

/**
 * @property string $value    String value
 * @property bool   $isBinary Whether the string is binary (b'')
 * @property int    $type     Whether SINGLE_QUOTED or DOUBLE_QUOTED
 */
class PHPParser_Node_Scalar_String extends PHPParser_Node_Scalar
{
    const SINGLE_QUOTED = 0;
    const DOUBLE_QUOTED = 1;

    /**
     * Creates a String node from a string token (parses escape sequences).
     *
     * @param string $s    String
     * @param int    $line Line
     *
     * @return PHPParser_Node_Scalar_String String Node
     */
    public static function create($s, $line) {
        $isBinary = false;
        if ('b' === $s[0]) {
            $isBinary = true;
        }

        if ('\'' === $s[0]) {
            $type = self::SINGLE_QUOTED;

            $s = str_replace(
                array('\\\\', '\\\''),
                array(  '\\',   '\''),
                substr($s, $isBinary + 1, -1)
            );
        } else {
            $type = self::DOUBLE_QUOTED;

            $s = self::parseEscapeSequences(substr($s, $isBinary + 1, -1));
        }

        return new self(
            array(
                'value' => $s, 'isBinary' => $isBinary, 'type' => $type
            ),
            $line
        );
    }

    /**
     * Parses escape sequences in the content of a doubly quoted string
     * or heredoc string.
     *
     * @param string $s String without quotes
     *
     * @return string String with escape sequences parsed
     */
    public static function parseEscapeSequences($s) {
        // TODO: parse hex and oct escape sequences

        return str_replace(
            array('\\\\', '\"', '\$', '\n', '\r', '\t', '\f', '\v'),
            array(  '\\',  '"',  '$', "\n", "\r", "\t", "\f", "\v"),
            $s
        );
    }
}
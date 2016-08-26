<?php
namespace TheSeer\Autoload;

/**
 * Namespace aware parser to find and extract defined classes within php source files
 *
 * @author     Arne Blankerts <arne@blankerts.de>
 * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
 */
interface ParserInterface {

    /**
     * Parse a given file for defintions of classes, traits and interfaces
     *
     * @param SourceFile $source file to process
     *
     * @return ParseResult
     */
    public function parse(SourceFile $source);
}

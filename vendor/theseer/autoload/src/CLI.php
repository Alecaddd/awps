<?php

/**
 * Copyright (c) 2009-2016 Arne Blankerts <arne@blankerts.de>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 *   * Neither the name of Arne Blankerts nor the names of contributors
 *     may be used to endorse or promote products derived from this software
 *     without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT  * NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER ORCONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Autoload
 * @author     Arne Blankerts <arne@blankerts.de>
 * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
 * @license    BSD License
 */

namespace TheSeer\Autoload {

    use TheSeer\DirectoryScanner\DirectoryScanner;

    /**
     * CLI interface to AutoloadRenderer / StaticRenderer
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class CLI {

        const RC_OK = 0;
        const RC_EXEC_ERROR = 1;
        const RC_PARAM_ERROR = 3;
        const RC_LINT_ERROR = 4;

        private $pharOption;
        private $helpOption;
        private $versionOption;

        /**
         * @var Factory
         */
        private $factory;

        public function __construct(Factory $factory) {
            $this->factory = $factory;
        }

        /**
         * Main executor method
         *
         * @return void
         */
        public function run() {

            try {

                $this->preBootstrap();

                $input = $this->setupInput();
                $input->process();

                if ($input->getOption('help')->value === TRUE) {
                    $this->showVersion();
                    $this->showUsage();
                    exit(CLI::RC_OK);
                }

                if ($input->getOption('version')->value === TRUE ) {
                    $this->showVersion();
                    exit(CLI::RC_OK);
                }

                $config = $this->configure($input);
                $this->factory->setConfig($config);
                if (!$config->isQuietMode()) {
                    $this->showVersion();
                }
                $rc = $this->factory->getApplication()->run();
                exit($rc);

            } catch (CLIEnvironmentException $e) {
                $this->showVersion();
                fwrite(STDERR, 'Sorry, but your PHP environment is currently not able to run phpab due to');
                fwrite(STDERR, "\nthe following issue(s):\n\n" . $e->getMessage() . "\n\n");
                fwrite(STDERR, "Please adjust your PHP configuration and try again.\n\n");
                exit(CLI::RC_EXEC_ERROR);
            } catch (\ezcConsoleException $e) {
                $this->showVersion();
                echo $e->getMessage() . "\n\n";
                $this->showUsage();
                exit(CLI::RC_PARAM_ERROR);
            } catch (CollectorException $e) {
                switch($e->getCode()) {
                    case CollectorException::InFileRedeclarationFound:
                    case CollectorException::RedeclarationFound:
                    case CollectorException::ParseErrror: {
                        $message = $e->getMessage();
                        break;
                    }
                    default: {
                       $message = "Unexpected error in collector process: " . $e->getMessage() . "\n\nPlease report this as a bug.\n\n";
                    }
                }
                $this->showVersion();
                fwrite(STDERR, $message . "\n\n");
                exit(CLI::RC_EXEC_ERROR);
            } catch (\Exception $e) {
                $this->showVersion();
                fwrite(STDERR, "\nError while processing request:\n - " . $e->getMessage()."\n");
                exit(CLI::RC_EXEC_ERROR);
            }

        }

        /**
         * @param \ezcConsoleInput $input
         *
         * @return \TheSeer\Autoload\Config
         */
        private function configure(\ezcConsoleInput $input) {
            $config = new Config($input->getArguments());
            if ($input->getOption('quiet')->value) {
                $config->setQuietMode(TRUE);
            }
            if ($input->getOption('compat')->value) {
                $config->setCompatMode(TRUE);
            }
            if ($input->getOption('tolerant')->value) {
                $config->setTolerantMode(TRUE);
            }
            if ($output = $input->getOption('output')->value) {
                $config->setOutputFile($output);
            }
            if ($input->getOption('phar')->value) {
                $compression = \Phar::NONE;
                if ($input->getOption('bzip2')->value === TRUE) {
                    $compression = \Phar::BZ2;
                } else if ($input->getOption('gzip')->value === TRUE) {
                    $compression = \Phar::GZ;
                }
                $config->enablePharMode(
                    $compression,
                    $input->getOption('all')->value,
                    $input->getOption('key')->value,
                    $input->getOption('alias')->value
                );
                $config->setVariable('PHAR',
                    $input->getOption('alias')->value ? $input->getOption('alias')->value : basename($output)
                );
                if ($hashAlgorithm = $input->getOption('hash')->value) {
                    $config->setPharHashAlgorithm($hashAlgorithm);
                }
            }

            if ($input->getOption('cache')->value) {
                $config->setCacheFile($input->getOption('cache')->value);
            }

            if ($basedir = $input->getOption('basedir')->value) {
                $config->setBaseDirectory($basedir);
            }

            $include = $input->getOption('include')->value;
            if (!is_array($include)) {
                $include = array($include);
            }
            $config->setInclude($include);

            if ($exclude = $input->getOption('exclude')->value) {
                if (!is_array($exclude)) {
                    $exclude = array($exclude);
                }
                $config->setExclude($exclude);
            }

            $whitelist = $input->getOption('whitelist')->value;
            if (!is_array($whitelist)) {
                $whitelist = array($whitelist);
            }
            $config->setWhitelist($whitelist);

            if ($blacklist = $input->getOption('blacklist')->value) {
                if (!is_array($blacklist)) {
                    $blacklist = array($blacklist);
                }
                $config->setBlacklist($blacklist);
            }

            if ($input->getOption('static')->value) {
                $config->setStaticMode(TRUE);
            }
            if ($input->getOption('once')->value) {
                $config->setOnceMode(TRUE);
            }
            if ($input->getOption('follow')->value) {
                $config->setFollowSymlinks(TRUE);
            }

            if ($input->getOption('prepend')->value) {
                $config->enablePrepend();
            }

            if ($input->getOption('no-exception')->value) {
                $config->disableExceptions();
            }

            $indent = $input->getOption('indent')->value;
            if ($indent !== FALSE) {
                $config->setIndent($indent);
            }
            if ($template = $input->getOption('template')->value) {
                $config->setTemplate($template);
            }
            if ($linebreak = $input->getOption('linebreak')->value) {
                $config->setLinebreak($linebreak);
            }
            if ($input->getOption('nolower')->value) {
                $config->setLowercaseMode(FALSE);
            }
            if ($variables = $input->getOption('var')->value) {
                foreach($variables as $var) {
                    if (strpos($var, '=')===FALSE) {
                        throw new \RuntimeException("Variable defintion '$var' is invalid and cannot be processed.");
                    }
                    list($name, $value) = explode('=', $var, 2);
                    $config->setVariable($name, $value);
                }
            }

            if ($input->getOption('paranoid')->value || !$input->getOption('trusting')->value) {
                $config->setTrusting(FALSE);
            }

            return $config;
        }

        /**
         * Helper to output version information
         */
        protected function showVersion() {
            static $shown = false;
            if (!$shown) {
                $shown = true;
                echo Version::getInfoString() . "\n\n";
            }
        }

        /**
         * Helper to output usage information
         */
        protected function showUsage() {
            print <<<EOF
Usage: phpab [switches] <directory1|file1|/path/to/composer.json> [...<directoryN|fileN>]

  -i, --include       File pattern to include (default: *.php)
  -e, --exclude       File pattern to exclude

      --blacklist     Blacklist classname or namespace (wildcards supported)
      --whitelist     Whitelist classname or namespace (wildcards supported)

  -b, --basedir       Basedir for filepaths
  -t, --template      Path to code template to use

  -o, --output        Output file for generated code (default: STDOUT)
  -p, --phar          Create a phar archive (requires -o )
      --hash          Force given hash algorithm (SHA-1, SHA-256 or SHA-512) (requires -p, conflicts with --key)
      --bzip2         Compress phar archive using bzip2 (requires -p) (bzip2 required)
      --gz            Compress phar archive using gzip (requires -p) (gzip required)
      --key           OpenSSL key file to use for signing phar archive (requires -p) (openssl required)

  -c, --compat        Generate PHP 5.2 compatible code
  -s, --static        Generate a static require file

  -1, --prepend       Register as first autoloader (prepend to stack, default: append)
  -d, --no-exception  Do not throw exception on registration problem (default: throw exception)

  -n, --nolower       Do not lowercase classnames for case insensitivity

  -q, --quiet         Quiet mode, do not output any processing errors or information

      --cache <file>  Enable caching and set filename to use for cache storage

      --follow        Enables following symbolic links (not compatible with phar mode)
      --format        Dateformat string for timestamp
      --linebreak     Linebreak style (CR, CRLF or LF, default: LF)
      --indent        String used for indenting or number of spaces (default: 16 (compat 12) spaces)

      --tolerant      Ignore Class Redeclarations in the same file
      --once          Use require_once instead of require when creating a static require file

      --all           Include all files in given directory when creating a phar
      --alias         Specify explicit internal phar alias filename (default: output filename)

      --trusting      Do not check mimetype of files prior to parsing (default)
      --paranoid      Do check mimetype of files prior to parsing

      --var name=foo  Assign value 'foo' to variable 'name' to be used in (custom) templates

      --lint          Run lint on generated code and exit
      --lint-php      PHP binary to use for linting (default: /usr/bin/php or c:\php\php.exe)

  -h, --help          Prints this usage information
  -v, --version       Prints the version and exits

EOF;
        }

        /**
         * @return \ezcConsoleInput
         */
        protected function setupInput() {
            $input = new \ezcConsoleInput();

            $this->versionOption = $input->registerOption( new \ezcConsoleOption( 'v', 'version' ) );
            $this->versionOption->shorthelp    = 'Prints the version and exits';
            $this->versionOption->isHelpOption = TRUE;

            $this->helpOption = $input->registerOption( new \ezcConsoleOption( 'h', 'help' ) );
            $this->helpOption->isHelpOption = TRUE;
            $this->helpOption->shorthelp    = 'Prints this usage information';

            $input->registerOption( new \ezcConsoleOption(
                '', 'cache', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'Enable cache and set cache filename'
            ));

            $this->outputOption = $input->registerOption( new \ezcConsoleOption(
                'o', 'output', \ezcConsoleInput::TYPE_STRING, 'STDOUT', FALSE,
                'Output file for generated code (default: STDOUT)'
            ));

            $this->pharOption = $input->registerOption( new \ezcConsoleOption(
                'p', 'phar', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Build a phar archive of directory contents',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 'o' ) ) )
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'all', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Add all files from src dir to phar',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 'p' ) ) )
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'alias', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'Provide explicit internal alias filename for phar',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 'p' ) ) )
            ));

            $bzip2 = $input->registerOption( new \ezcConsoleOption(
                '', 'bzip2', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Compress files phar with bzip2',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 'p' ) ) )
            ));

            $gzip = $input->registerOption( new \ezcConsoleOption(
                '', 'gzip', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Compress files phar with gzip',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 'p' ) ) ),
                array( new \ezcConsoleOptionRule( $bzip2 ) )
            ));
            $bzip2->addExclusion(new \ezcConsoleOptionRule($gzip));

            $input->registerOption( new \ezcConsoleOption(
                '', 'key', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'Keyfile to use for signing phar archive',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 'p' ) ) )
            ));

            $this->outputOption = $input->registerOption( new \ezcConsoleOption(
                '', 'hash', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'Force given hash algorithm (SHA-1, SHA-256 or SHA-512) (requires -p)',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 'p' ) ) ),
                array( new \ezcConsoleOptionRule( $input->getOption( 'key' ) ) )
            ));

            $input->registerOption( new \ezcConsoleOption(
                'i', 'include', \ezcConsoleInput::TYPE_STRING, '*.php', TRUE,
                'File pattern to include (default: *.php)'
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'blacklist', \ezcConsoleInput::TYPE_STRING, NULL, TRUE,
                'Name pattern to exclude'
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'whitelist', \ezcConsoleInput::TYPE_STRING, '*', TRUE,
                'Name pattern to include (default: *)'
            ));

            $input->registerOption( new \ezcConsoleOption(
                'e', 'exclude', \ezcConsoleInput::TYPE_STRING, NULL, TRUE,
                'File pattern to exclude'
            ));

            $input->registerOption( new \ezcConsoleOption(
                'b', 'basedir', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'Basedir for filepaths'
            ));

            $input->registerOption( new \ezcConsoleOption(
                't', 'template', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'Path to code template to use'
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'follow', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Enables following symbolic links',
                NULL,
                array(),
                array( new \ezcConsoleOptionRule($this->pharOption) )
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'format', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'Dateformat string for timestamp'
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'linebreak', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'Linebreak style (CR, CR/LF or LF)'
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'indent', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'String used for indenting (default: 3 spaces)'
            ));

            $this->lintOption = $input->registerOption( new \ezcConsoleOption(
                '', 'lint', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Run lint on generated code'
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'lint-php', \ezcConsoleInput::TYPE_STRING, NULL, FALSE,
                'PHP binary path for linting (default: /usr/bin/php or c:\\php\\php.exe)',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 'lint' ) ) )
            ));

            $compat = $input->registerOption( new \ezcConsoleOption(
                'c', 'compat', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Generate PHP 5.2 compliant code'
            ));

            $this->staticOption = $input->registerOption( new \ezcConsoleOption(
                's', 'static', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Build a static require file'
            ));

            $input->registerOption( new \ezcConsoleOption(
                '', 'tolerant', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Ignore Class Redeclarations in the same file'
            ));

            $trusting = $input->registerOption( new \ezcConsoleOption(
                '', 'trusting', \ezcConsoleInput::TYPE_NONE, TRUE, FALSE,
                'Do not check mimetype of files prior to parsing'
            ));
            $paranoid = $input->registerOption( new \ezcConsoleOption(
                '', 'paranoid', \ezcConsoleInput::TYPE_NONE, FALSE, FALSE,
                'Do check mimetype of files prior to parsing',
                NULL,
                array(),
                array( new \ezcConsoleOptionRule($trusting) )
            ));
            $trusting->addExclusion(new \ezcConsoleOptionRule($paranoid));

            $this->onceOption = $input->registerOption( new \ezcConsoleOption(
                '', 'once', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Use require_once in static require mode',
                NULL,
                array( new \ezcConsoleOptionRule( $input->getOption( 's' ) ) )
            ));

            $input->registerOption( new \ezcConsoleOption(
                'n', 'nolower', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Do not lowercase classnames for case insensitivity'
            ));

            $input->registerOption( new \ezcConsoleOption(
                'q', 'quiet', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Run in quiet mode, no output'
            ));

            $input->registerOption( new \ezcConsoleOption(
                '1', 'prepend', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Prepend autoloader to stack',
                NULL,
                array(),
                array( new \ezcConsoleOptionRule( $compat ) )
            ));

            $input->registerOption( new \ezcConsoleOption(
                'd', 'no-exception', \ezcConsoleInput::TYPE_NONE, NULL, FALSE,
                'Disable exceptions on registration error'
            ));

            $input->registerOption( new \ezcConsoleOption(
                NULL, 'var', \ezcConsoleInput::TYPE_STRING, array(), TRUE,
                'Assign variable'
            ));

            $input->argumentDefinition = new \ezcConsoleArguments();
            $input->argumentDefinition[0] = new \ezcConsoleArgument( "directory" );
            $input->argumentDefinition[0]->shorthelp = "The directory to process.";
            $input->argumentDefinition[0]->multiple = TRUE;

            return $input;
        }

        private function preBootstrap() {
            $required = array('tokenizer', 'fileinfo');
            $missing = array();
            foreach($required as $test) {
                if (!extension_loaded($test)) {
                    $missing[] = sprintf('ext/%s not installed/enabled', $test);
                }
            }
            if (count($missing)) {
                throw new CLIEnvironmentException(
                    join("\n", $missing),
                    CLIEnvironmentException::ExtensionMissing
                );
            }

            if (extension_loaded('xdebug')) {
                ini_set('xdebug.scream', 0);
                ini_set('xdebug.max_nesting_level', 8192);
                ini_set('xdebug.show_exception_trace', 0);
                xdebug_disable();
            }

        }

    }

    class CLIEnvironmentException extends \Exception {
        const ExtensionMissing = 1;
    }
}


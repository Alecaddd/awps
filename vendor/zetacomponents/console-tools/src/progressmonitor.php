<?php
/**
 * File containing the ezcConsoleProgressMonitor class.
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package ConsoleTools
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @filesource
 */

/**
 * Printing structured status information on the console. 
 *
 * <code>
 * // Construction
 * $status = new ezcConsoleProgressMonitor( new ezcConsoleOutput(), 42 );
 *
 * // Run statusbar
 * foreach ( $files as $file )
 * {
 *      $res = $file->upload();
 *      // Add a status-entry to be printed.
 *      $status->addEntry( 'UPLOAD', $file->path );
 *      // Result like "    2.5% UPLOAD /var/upload/test.png"
 * }
 * </code>
 *  
 * @property ezcConsoleProgressMonitorOptions $options
 *           Contains the options for this class.
 * 
 * @package ConsoleTools
 * @version //autogen//
 * @mainclass
 */
class ezcConsoleProgressMonitor
{
    /**
     * Options
     *
     * @var ezcConsoleProgressMonitorOptions
     */
    protected $options;

    /**
     * The ezcConsoleOutput object to use.
     *
     * @var ezcConsoleOutput
     */
    protected $outputHandler;

    /**
     * Counter for the items already printed. 
     * 
     * @var int
     */
    protected $counter = 0;

    /**
     * The number of entries to expect. 
     * 
     * @var int
     */
    protected $max;

    /**
     * Creates a new progress monitor.
     * The $outputHandler parameter will be used to display the progress
     * monitor. $max is the number of monitor items to expect. $options can be
     * used to define the behaviour of the monitor
     * {@link ezcConsoleProgressMonitorOptions}.
     *
     * @param ezcConsoleOutput $outHandler   Handler to utilize for output
     * @param int $max                       Number of items to expect
     * @param array(string=>string) $options Options.
     *
     * @see ezcConsoleProgressMonitor::$options
     */
    public function __construct( ezcConsoleOutput $outHandler, $max, array $options = array() )
    {
        $this->outputHandler = $outHandler;
        $this->max = $max;
        $this->options = new ezcConsoleProgressMonitorOptions( $options );
    }

    /**
     * Property read access.
     * 
     * @param string $key Name of the property.
     * @return mixed Value of the property or null.
     *
     * @throws ezcBasePropertyNotFoundException
     *         If the the desired property is not found.
     */
    public function __get( $key )
    {
        switch ( $key )
        {
            case 'options':
                return $this->options;
                break;
        }
        throw new ezcBasePropertyNotFoundException( $key );
    }
    
    /**
     * Property write access.
     * 
     * @param string $propertyName Name of the property.
     * @param mixed $val  The value for the property.
     *
     * @throws ezcBaseValueException 
     *         If a the value for the property options is not an instance of 
     *         ezcConsoleProgressMonitorOptions. 
     * @return void
     */
    public function __set( $propertyName, $val )
    {
        switch ( $propertyName ) 
        {
            case 'options':
                if ( !( $val instanceof ezcConsoleProgressMonitorOptions ) )
                {
                    throw new ezcBaseValueException( $propertyName, $val, 'instance of ezcConsoleProgressMonitorOptions' );
                }
                $this->options = $val;
                return;
        }
        throw new ezcBasePropertyNotFoundException( $propertyName );
    }
    
    
    /**
     * Property isset access.
     * 
     * @param string $propertyName Name of the property.
     * @return bool True is the property is set, otherwise false.
     */
    public function __isset( $propertyName )
    {
        switch ( $propertyName )
        {
            case 'options':
                return true;
        }
        return false;
    }

    /**
     * Set new options.
     * This method allows you to change the options of an progress monitor.
     *  
     * @param ezcConsoleProgressMonitorOptions $options The options to set.
     *
     * @throws ezcBaseSettingNotFoundException
     *         If you tried to set a non-existent option value. 
     * @throws ezcBaseSettingValueException
     *         If the value is not valid for the desired option.
     * @throws ezcBaseValueException
     *         If you submit neither an array nor an instance of 
     *         ezcConsoleProgressMonitorOptions.
     */
    public function setOptions( $options ) 
    {
        if ( is_array( $options ) ) 
        {
            $this->options->merge( $options );
        } 
        else if ( $options instanceof ezcConsoleProgressMonitorOptions ) 
        {
            $this->options = $options;
        }
        else
        {
            throw new ezcBaseValueException( "options", $options, "instance of ezcConsoleProgressMonitorOptions" );
        }
    }

    /**
     * Returns the currently set options.
     * Returns the currently set option array.
     * 
     * @return ezcConsoleProgressMonitorOptions The options.
     */
    public function getOptions()
    {
        return $this->options;
    }
 
    /**
     * Print a status entry.
     * Prints a new status entry to the console and updates the internal counter.
     *
     * @param string $tag  The tag to print (second argument in the 
     *                     formatString).
     * @param string $data The data to be printed in the status entry (third 
     *                     argument in the format string).
     * @return void
     */
    public function addEntry( $tag, $data )
    {
        $this->counter++;
        $percentage = $this->counter / $this->max * 100;

        $this->outputHandler->outputLine(
            sprintf(
                $this->options['formatString'],
                $percentage,
                $tag,
                $data
            )
        );
    }
}
?>

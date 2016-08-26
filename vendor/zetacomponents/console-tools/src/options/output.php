<?php
/**
 * File containing the ezcConsoleOutputOptions class.
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
 * Struct class to store the options of the ezcConsoleOutput class.
 *
 * This class stores the options for the {@link ezcConsoleOutput} class.
 *
 * @property int $verbosityLevel
 *           Determines the level of verbosity.
 * @property int $autobreak
 *           Determines, whether text is automatically wrapped after a
 *           specific amount of characters in a line. If set to 0
 *           (default), lines will not be wrapped automatically.
 * @property bool $useFormats
 *           Whether to use formatting or not.
 * 
 * @package ConsoleTools
 * @version //autogen//
 */
class ezcConsoleOutputOptions extends ezcBaseOptions
{
    /**
     * Construct a new options object.
     *
     * NOTE: For BC reasons the old method of instanciating this class is kept,
     * but the usage of the new version is highly encouraged.
     * 
     * @param array(string=>mixed) $options The initial options to set.
     * @return void
     *
     * @throws ezcBasePropertyNotFoundException
     *         If a the value for the property options is not an instance of
     * @throws ezcBaseValueException
     *         If a the value for a property is out of range.
     */
    public function __construct()
    {
        $this->properties['verbosityLevel'] = 1;
        $this->properties['autobreak'] = 0;
        $this->properties['useFormats'] = true;
        $args = func_get_args();
        if ( func_num_args() === 1 && is_array( $args[0] ) )
        {
            parent::__construct( $args[0] );
        }
        else
        {
            foreach ( $args as $id => $val )
            {
                switch ( $id )
                {
                    case 0:
                        $this->__set( "verbosityLevel", $val );
                        break;
                    case 1:
                        $this->__set( "autobreak", $val );
                        break;
                    case 2:
                        $this->__set( "useFormats", $val );
                        break;
                }
            }
        }
    }

    /**
     * Property write access.
     * 
     * @throws ezcBasePropertyNotFoundException
     *         If a desired property could not be found.
     * @throws ezcBaseValueException
     *         If a desired property value is out of range.
     *
     * @param string $propertyName Name of the property.
     * @param mixed $val  The value for the property.
     * @ignore
     */
    public function __set( $propertyName, $val )
    {
        switch ( $propertyName )
        {
            case 'verbosityLevel':
            case 'autobreak':
                if ( !is_int( $val ) || $val < 0 )
                {
                    throw new ezcBaseValueException( $propertyName, $val, 'int >= 0' );
                }
                break;
            case 'useFormats':
                if ( !is_bool( $val ) )
                {
                    throw new ezcBaseValueException( $propertyName, $val, 'bool' );
                }
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $propertyName );
        }
        $this->properties[$propertyName] = $val;
    }
}

?>

<?php
/**
 * File containing the ezcConsoleTableCell class.
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
 * Representation of a table cell.
 * An object of this class represents a table cell. A cell has a certain content,
 * may apply a format to this data, align the data in the cell and so on.
 *
 * This class stores the cells for the {@link ezcConsoleTable} class.
 *
 * @see ezcConsoleTableRow
 * 
 * @property string $content
 *           Text displayed in the cell.
 * @property string $format
 *           Format applied to the displayed text.
 * @property int $align
 *           Alignment of the text inside the cell.  Must be one of
 *           ezcConsoleTable::ALIGN_ constants. See
 *           {@link ezcConsoleTable::ALIGN_LEFT},
 *           {@link ezcConsoleTable::ALIGN_RIGHT} and
 *           {@link ezcConsoleTable::ALIGN_CENTER}.
 *
 * @package ConsoleTools
 * @version //autogen//
 */
class ezcConsoleTableCell
{
    /**
     * Container to hold the properties
     *
     * @var array(string=>mixed)
     */
    protected $properties;

    /**
     * Create a new ezcConsoleProgressbarCell. 
     * Creates a new ezcConsoleProgressbarCell. You can either submit the cell
     * data through the constructor or set them as properties.
     * 
     * @param string $content Content of the cell.
     * @param string $format  Format to display the cell with.
     * @param mixed $align    Alignment of the content in the cell.
     * @return void
     */
    public function __construct( $content = '', $format = 'default', $align = ezcConsoleTable::ALIGN_DEFAULT )
    {
        $this->__set( 'content', $content );
        $this->__set( 'format', $format );
        $this->__set( 'align', $align );
    }

    /**
     * Property read access.
     * 
     * @param string $key Name of the property.
     * @return mixed Value of the property or null.
     *
     * @throws ezcBasePropertyNotFoundException
     *         If the the desired property is not found.
     * @ignore
     */
    public function __get( $key )
    {
        if ( isset( $this->properties[$key] ) )
        {
            return $this->properties[$key];
        }
        throw new ezcBasePropertyNotFoundException( $key );
    }

    /**
     * Property write access.
     * 
     * @param string $key Name of the property.
     * @param mixed $val  The value for the property.
     *
     * @throws ezcBaseValueException
     *         If a the value submitted for the align is not in the range of
     *         {@link ezcConsoleTable::ALIGN_LEFT},
     *         {@link ezcConsoleTable::ALIGN_CENTER},
     *         {@link ezcConsoleTable::ALIGN_RIGHT},
     *         {@link ezcConsoleTable::ALIGN_DEFAULT}
     *
     * @ignore
     */
    public function __set( $key, $val )
    {
            
        switch ( $key )
        {
            case 'content':
                if ( is_string( $val ) === false )
                {
                    throw new ezcBaseValueException( $key, $val, "string" );
                }
                break;
            case 'format':
                if ( is_string( $val ) === false || strlen( $val ) < 1 )
                {
                    throw new ezcBaseValueException( $key, $val, "string, length > 0" );
                }
                break;
            case 'align':
                if ( $val !== ezcConsoleTable::ALIGN_LEFT 
                  && $val !== ezcConsoleTable::ALIGN_CENTER 
                  && $val !== ezcConsoleTable::ALIGN_RIGHT 
                  && $val !== ezcConsoleTable::ALIGN_DEFAULT 
                )
                {
                    throw new ezcBaseValueException( $key,  $val, 'ezcConsoleTable::ALIGN_DEFAULT, ezcConsoleTable::ALIGN_LEFT, ezcConsoleTable::ALIGN_CENTER, ezcConsoleTable::ALIGN_RIGHT' );
                }
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $key );
        }
        $this->properties[$key] = $val;
        return;
    }
 
    /**
     * Property isset access.
     * 
     * @param string $key Name of the property.
     * @return bool True is the property is set, otherwise false.
     * @ignore
     */
    public function __isset( $key )
    {
        switch ( $key )
        {
            case 'content':
            case 'format':
            case 'align':
                return true;
            default:
                break;
        }
        return false;
    }

}

?>

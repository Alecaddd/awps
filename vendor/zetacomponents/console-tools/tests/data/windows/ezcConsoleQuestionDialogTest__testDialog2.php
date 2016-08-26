<?php
/**
 * File containing test code for the ConsoleTools component.
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
 */


require_once "Base/src/base.php";

function __autoload( $className )
{
    ezcBase::autoload( $className );
}

$out = new ezcConsoleOutput();

$opts = new ezcConsoleQuestionDialogOptions();
$opts->text = "How old are you?";
$opts->showResults = true;
$opts->validator = new ezcConsoleQuestionDialogTypeValidator(
    ezcConsoleQuestionDialogTypeValidator::TYPE_INT
);

$dialog = new ezcConsoleQuestionDialog( $out, $opts );

if ( ( $res = ezcConsoleDialogViewer::displayDialog( $dialog ) ) < 8 )
{
    echo "Sorry, I can not believe that you are $res years old!\n";
}
else
{
    echo "Hey, you're still young! :)\n";
}

?>

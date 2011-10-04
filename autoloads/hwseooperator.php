<?php
/**
* HwSEO
*
* Copyright (c) 2011 holzweg e-commerce solutions - http://www.holzweg.com
* All rights reserved.
*
* NOTICE OF LICENSE
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*
* @category HwSEO
* @package HwSEO
* @author Mathias Geat <mathias.geat@holzweg.com>
* @copyright 2011 holzweg e-commerce solutions - http://www.holzweg.com
* @license http://www.gnu.org/licenses/gpl.txt GNU GPL v3
*/

/**
* HwSEO Operator
*
* @category HwSEO
* @package HwSEO
* @author Mathias Geat <mathias.geat@holzweg.com>
* @copyright 2011 holzweg e-commerce solutions - http://www.holzweg.com
* @license http://www.gnu.org/licenses/gpl.txt GNU GPL v3
*/
class HwSEOOperator
{
    function HwSEOOperator()
    {
    }

    /*!
     \return an array with the template operator names.
    */
    function operatorList()
    {
        return array('hwseoredirect');
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type,
             this is needed for operator classes that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array();
    }

    /*!
     Executes the PHP function for the operator cleanup and modifies \a $operatorValue.
    */
    function modify($tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement)
    {
        switch ($operatorName)
        {
            case 'hwseoredirect':
            {
                $operatorValue = $this->hwSeoRedirect($operatorValue);
            } break;
        }
    }

    public function hwSeoRedirect($pagedata)
    {
        $blacklistedNodes = eZINI::instance('hwseo.ini')->variable('Nodes', 'BlacklistedNodes');
        if(isset($pagedata['node_id']) && !in_array($pagedata['node_id'], $blacklistedNodes)) {
            $node = eZContentObjectTreeNode::fetch($pagedata['node_id']);
            if($node instanceof eZContentObjectTreeNode) {
                $requestUri = trim($_SERVER['REQUEST_URI']);
                $paramString = '';

                $pos = strpos($requestUri, '/(');
                if($pos !== false) {
                    $paramString = substr($requestUri, $pos);
                    $requestUri = substr($requestUri, 0, $pos);
                } else {
                    $pos = strpos($requestUri, '?');
                    if($pos !== false) {
                        $paramString = substr($requestUri, $pos);
                        $requestUri = substr($requestUri, 0, $pos);
                    }
                }

                $urlAlias = trim('/' . $node->urlAlias());

                if($requestUri !== $urlAlias) {
                    if(!headers_sent()) {
                        $schema = $_SERVER['SERVER_PORT'] == '443' ? 'https' : 'http';
                        $host = strlen($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
                        $url = $schema . '://' . $host . $urlAlias . $paramString;

                        header('HTTP/1.1 301 Moved Permanently');
                        header('Location: ' . $url);
                    }
                }
            }
        }

        return '';
    }
}
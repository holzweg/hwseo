<?php
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
                $urlAlias = trim('/' . $node->urlAlias());

                if($requestUri !== $urlAlias) {
                    if(!headers_sent()) {
                        $schema = $_SERVER['SERVER_PORT'] == '443' ? 'https' : 'http';
                        $host = strlen($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
                        $url = $schema . '://' . $host . $urlAlias;

                        header('HTTP/1.1 301 Moved Permanently');
                        header('Location: ' . $url);
                    }
                }
            }
        }

        return '';
    }
}
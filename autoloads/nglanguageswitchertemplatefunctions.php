<?php

class ngLanguageSwitcherTemplateFunctions
{
    function operatorList()
    {
        return array( 'ngurl' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array(
            'ngurl' => array(
                'siteaccess' => array(
                    'type' => 'string',
                    'required' => true,
                    'default' => ''
                )
            )
        );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'ngurl':
            {
                if ( empty( $namedParameters['siteaccess'] ) )
                {
                    return;
                }

                $ini = eZSiteAccess::getIni( $namedParameters['siteaccess'], 'site.ini' );
                $destinationLocale =  $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );
                $siteLanguageList = $ini->variable( 'RegionalSettings', 'SiteLanguageList' );

                $nodeID = eZURLAliasML::fetchNodeIDByPath( $operatorValue );
                $destinationElement = eZURLAliasML::fetchByAction( 'eznode', $nodeID, $destinationLocale, false );
                if ( empty( $destinationElement ) || ( !isset( $destinationElement[0] ) && !( $destinationElement[0] instanceof eZURLAliasML ) ) )
                {
                    if ( $this->isModuleUrl( $operatorValue ) || $this->isCurrentLocaleAvailable( $siteLanguageList ) )
                    {
                        $destinationUrl = $operatorValue;
                    }
                    else
                    {
                        $destinationUrl = '';
                    }
                }
                else
                {
                    $destinationUrl = $destinationElement[0]->getPath( $destinationLocale, $siteLanguageList );
                }

                $siteaccessUrlMapping = eZINI::instance( 'nglanguageswitcher.ini' )->variable( 'LanguageSwitcher', 'SiteAccessUrlMapping' );

                $destinationUrl = eZURI::encodeURL( $destinationUrl );
                $operatorValue = rtrim( $siteaccessUrlMapping[$namedParameters['siteaccess']], '/' ) . '/' . ltrim( $destinationUrl, '/' );
            } break;
        }
    }

    protected function isModuleUrl( $url )
    {
        // Grab the first URL element, representing the possible module name
        $urlElements = explode( '/', $url );
        $moduleName = $urlElements[0];

        // Look up for a match in the module list
        $moduleIni = eZINI::instance( 'module.ini' );
        $availableModules = $moduleIni->variable( 'ModuleSettings', 'ModuleList' );
        return in_array( $moduleName, $availableModules, true );
    }

    protected function isCurrentLocaleAvailable( $siteLanguageList )
    {
        return in_array(
            eZINI::instance()->variable( 'RegionalSettings', 'ContentObjectLocale' ),
            $siteLanguageList,
            true
        );
    }
}

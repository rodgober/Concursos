<?php
    defined( '_JEXEC') or die( 'Restricted access');
    jimport( 'joomla.application.component.view');
 
    class concursosViewsinconcursos extends JViewLegacy
    {
	    function display($tpl = null)
	    {
	    	JError::raiseWarning( 100, $this->mensaje );
			echo "hola";
    		parent::display($tpl);
	    }
 
    }
?>
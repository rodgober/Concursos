<?php
    defined( '_JEXEC') or die( 'Restricted access');
    jimport( 'joomla.application.component.view');
 
    class concursosViewNoAutorizado extends JView
    {
	    function display($tpl = null)
	    {
	    	JError::raiseWarning( 100, "Debes iniciar sesi&oacute;n para ver esta p&aacute;gina" );
    		parent::display($tpl);
	    }
 
    }
?>
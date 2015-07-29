<?php
    defined( '_JEXEC') or die( 'Restricted access');
    jimport( 'joomla.application.component.view');
 
    class concursosViewconcurso extends JView
    {
	    function display($tpl = null)
	    {
	    	echo $this->bienvenida;
	    	echo "<br><br><a href= 'index.php?option=com_concursos&task=mostrarDescargas&idConcurso=".$this->idConcurso."' >Descargar software  </a>";
	    	echo "<br><a class='button cta hover-shadow' href= ";
	    	echo JRoute::_('components/com_concursos/docs/bases/'.$this->bases);
	    	echo "> Bases </a></br><a class='button cta hover-shadow' href= ";
	    	echo JRoute::_('index.php?option=com_concursos&task=mostrarInsEquipo&idConcurso='.$this->idConcurso);
	    	echo "> Mis equipos </a></br><a class='button cta hover-shadow' href= ";
	    	echo JRoute::_('index.php?option=com_concursos&task=mostrarCuestionarios&idConcurso='.$this->idConcurso); 
	    	echo "> Lista de cuestionarios publicados </a></br>";
	    	
	    	echo "<form id='frmConcurso' action='http://www.galileo2.com.mx/portal2014/index.php?option=com_regcomunidad' method='post' class='form-validate'>";?>
	    		   	<a href="javascript:{}" onclick="document.getElementById('frmConcurso').submit(); return false;" class="boton cta hover-shadow" >Comunidad Galileo</a>
	    		   	<?php
	    		   	echo "  <input type='hidden' name='task' value='importaconcurso' />";
	    		   	echo "  <input type='hidden' name='id_user' value='".$this->id_user."' />";
	    		   	echo "</form>";
	    	
    		parent::display($tpl);
	    }
    }
?>
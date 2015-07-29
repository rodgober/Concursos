<?php
    defined( '_JEXEC') or die( 'Restricted access');
    jimport( 'joomla.application.component.view');
 
    class concursosViewinicio extends JView
    {
	    function display($tpl = null)
	    {
	    	$this->mostrarConcursos();	 
    		parent::display($tpl);
	    }
	    function mostrarConcursos()
	    {
	    	echo "<table>";
	    	echo "  <tr>";
	    	echo "    <th>Concursos disponibles:</th>";	    
	    	echo "    <th>Fecha de inicio</th>";
	    	echo "    <th>Fecha final</th>";
	    	echo "  </tr>";
	        foreach ($this->concursos as $concurso ){
	          echo "  <tr>";
	          echo "    <td><a href=";
              echo            JRoute::_('index.php?option=com_concursos&task=mostrarConcurso&idConcurso='.$concurso->idConcurso);
	          echo            ">";
	          echo            $concurso->nombre;  
	          echo          "</a>";
	          echo "    </td>";
	          echo "    <td>";
	          $date = strtotime($concurso->fecha_inicio);
	          echo        date('j-m-Y',$date);
	          echo "    </td>";
	          echo "    <td>";
	          $date = strtotime($concurso->fecha_fin);
	          echo        date('j-m-Y',$date);
	          echo "    </td>";
	          echo "  </tr>";
	        }
	        echo "</table>";
	    }	    	
	}
?>
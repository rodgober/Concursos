<?php
defined('_JEXEC') or die("Invalid access");
jimport('joomla.application.component.model');
 
class concursosModelconcursos extends JModel
{
	/*Devuelve el registro del profesor dado el id del usuario 12-07-13*/
	function getProfesor($id_user)
	{
		$db =  JFactory::getDBO();
		$query = 'SELECT * FROM jos_profesores WHERE id_user  = '. $id_user;
		$db->setQuery( $query );
	    $profesor = $db->loadObject();
	    return  $profesor;	    
	}
	
	 /* Devuelve los concursos que puede inscribirse el profesor dependiendo el nivel, Subsistema, Fecha inicio, Fecha fin.12-07-13*/
	function getListaConcursos($cct)
	{
		$db =  JFactory::getDBO();
		$query = 'SELECT * FROM jos_escuelas WHERE cct = "'. $cct.'"';
		$db->setQuery( $query );
		$escuela = $db->loadObject();
		$fecha = date('Y-m-d H:i:s');
		$query = 'SELECT * '
                .' FROM jos_concurso INNER JOIN jos_concursosxedo ON jos_concurso.idConcurso = jos_concursosxedo.idConcurso '
                .' WHERE (((jos_concurso.fecha_inicio_publicacion)<="'.$fecha.'") AND ((jos_concurso.fecha_fin_publicacion)>"'.$fecha.'") '
                .' AND ((jos_concurso.nivel)='.$escuela->nivel.') AND ((jos_concursosxedo.idEstado)='.$escuela->estado.') AND '
                .' ((jos_concursosxedo.idsubsistema)='.$escuela->subsistema.'))
				order by jos_concurso.idConcurso;';
		$db->setQuery( $query );
		$concursos = $db->loadObjectList();
		return $concursos;
	}
	
}
?>
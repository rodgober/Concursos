<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
 
class concursosController extends JControllerLegacy
{	
	function display($cachable = false, $urlparams = false)
	{	
		$user 	 = JFactory::getUser();
		$id_user = $user->get('id');
		$modelo = $this->getModel();
		$profesor = $modelo->getProfesor($id_user);
		if(($id_user <= 0)||($profesor == NULL)){
			$vista = $this->getView('NoAutorizado','html');
		}else{
		  $concursos = $modelo->getListaConcursos($profesor->cct);//Lista de concursos que puede participar
		  if (count($concursos) == 1) { //Si nada mas hay un concurso entonces lo muestra
		    $vista = $this->getView('concurso','html');
		    $id_concurso = $concursos[0]->idConcurso;
		    $bienvenida = $modelo->getBienvenida($id_concurso);
		    $bases = $modelo->getBases($id_concurso);
		    $vista->assignRef('bases',$bases);
		    $vista->assignRef('bienvenida',$bienvenida);
		    $vista->assignRef('idConcurso',$id_concurso);
		    $vista->assignRef('id_user',$id_user);
		  }elseif(count($concursos) > 0) {  //SI hay mas de un concurso, le muestra la lista
		    $vista = $this->getView('inicio','html');
		    $vista->assignRef('profesor',$profesor);
		    $vista->assignRef('idProf',$profesor->idprofesores);
		    $vista->assignRef('cct',$profesor->cct);
		    $vista->assignRef('concursos',$concursos);
		  }else{ // SI no hay concursos le indica que solicite concursos para su Institución
		  	$correo = "<a href='mailto:fernando@galileo2.com.mx'>fernando@galileo2.com.mx</a>";
			$mensaje = "Profesor ".$profesor->nombre." a&uacute;n no contamos con concursos disponibles para su escuela, le recomendamos env&iacute;ar un correo a ".$correo." solicitando un concurso para la escuela.";
			$vista = $this->getView('sinconcursos','html');
			$vista->assignRef('mensaje',$mensaje);
	    }
	  }
	}
	
	function mostrarConcurso()
	{
		$user 	 =& JFactory::getUser();
		$id_user = $user->get('id');
		$id_concurso = JRequest::getInt('idConcurso');
		$modelo = $this->getModel();
		$profesor = $modelo->getProfesor($id_user);
		if(($id_user <= 0)||($profesor == NULL)){
			$vista = $this->getView('NoAutorizado','html');
		}else{
			$permisoInsConcurso = $modelo->getPermiVerConcurso($id_concurso,$profesor->cct);
			if($permisoInsConcurso == FALSE){
				$vista = $this->getView('NoAutorizado','html');
			}else{
		  		$vista = $this->getView('concurso','html');
				$bases = $modelo->getBases($id_concurso);
				$bienvenida = $modelo->getBienvenida($id_concurso);
				$vista->assignRef('bienvenida',$bienvenida);
		  		$vista->assignRef('bases',$bases);
		  		$vista->assignRef('idConcurso',$id_concurso);
			}
		}
		$vista->display();
	}
	
	
	///FUNCIONES PARA EL JAVASCRIPT///////////////////////////////////////////////////////////////////////////////////////////
	public function showSubsistemasXEstadoXNivel() {
		$idEdo = JRequest::getVar( 'idEdo', '', 'get', 'cmd' ) ;
		$nivel = JRequest::getVar( 'nivel', '', 'get', 'cmd' ) ;
		$municipio = JRequest::getVar( 'municipio', '', 'get', 'cmd' ) ;
		$localidad = JRequest::getVar( 'localidad', '', 'get', 'cmd' ) ;
		$db = & JFactory::getDBO();
		$query = 'SELECT jos_subsistemas.id_subsistema, jos_subsistemas.nombre_subsistema
FROM jos_escuelas INNER JOIN jos_subsistemas ON jos_escuelas.subsistema = jos_subsistemas.id_subsistema
GROUP BY jos_escuelas.estado, jos_escuelas.municipio, jos_escuelas.localidad, jos_escuelas.nivel, jos_subsistemas.id_subsistema, jos_subsistemas.nombre_subsistema
HAVING (((jos_escuelas.estado)='.$idEdo.') AND ((jos_escuelas.municipio)='.$municipio.') AND ((jos_escuelas.localidad)='.$localidad.') AND ((jos_escuelas.nivel)='.$nivel.'))
	    	ORDER BY jos_subsistemas.nombre_subsistema;';
		//echo $query;
		$db->setQuery( $query );
		$rows=$db->loadObjectList();
		if($db->getErrorNum()){
			echo $db->stderr();
			return false;
		}
		@ob_end_clean();
		$cadena = '';
		foreach($rows as $row)
		{
			$cadena .= "<".$row->id_subsistema.">".$row->nombre_subsistema;
		}
		echo $cadena;
	}
	
	
	function showNivelXEstado(){
		$idEdo = JRequest::getVar( 'idEdo', '', 'get', 'cmd' ) ;
		$db = & JFactory::getDBO();
		$query = 'SELECT jos_nivel.idNivel, jos_nivel.nombre
					FROM (jos_entidades INNER JOIN jos_entidades_has_nivel ON jos_entidades.id_entidad = jos_entidades_has_nivel.id_entidad) INNER JOIN jos_nivel ON jos_entidades_has_nivel.idNivel = jos_nivel.idNivel
					WHERE (((jos_entidades.id_entidad)='.$idEdo.'))
					ORDER BY jos_nivel.idNivel;';
		$db->setQuery( $query );
		$rows=$db->loadObjectList();
		@ob_end_clean();
		$cadena = '';
		foreach($rows as $row)
		{
			$cadena .= "<".$row->idNivel.">".$row->nombre;
		}
		echo $cadena;
	}
	
	function showMunicipio(){
	
		$idEdo = JRequest::getVar( 'idEdo', '', 'get', 'cmd' ) ;
		$db = & JFactory::getDBO();
		$query = 'SELECT jos_escuelas.municipio as id, jos_municipios.nombre_municipio
FROM jos_escuelas INNER JOIN jos_municipios ON jos_escuelas.municipio = jos_municipios.id
GROUP BY jos_escuelas.municipio, jos_municipios.nombre_municipio, jos_escuelas.estado
HAVING (((jos_escuelas.estado)='.$idEdo.'))
		ORDER BY jos_municipios.nombre_municipio;';
		$db->setQuery( $query );
		$rows=$db->loadObjectList();
		@ob_end_clean();
		$cadena = '';
		foreach($rows as $row)
		{
			$cadena .= "<".$row->id.">".$row->nombre_municipio;
		}
		echo $cadena;
	}
	
	function showLocalidades(){
		$idEdo = JRequest::getVar( 'idEdo', '', 'get', 'cmd' ) ;
		$idMun = JRequest::getVar( 'municipio', '', 'get', 'cmd' ) ;
		$db = & JFactory::getDBO();
		$query = 'SELECT jos_escuelas.localidad as id, jos_localidades.localidad
FROM jos_escuelas INNER JOIN jos_localidades ON (jos_escuelas.municipio = jos_localidades.municipio) AND (jos_escuelas.estado = jos_localidades.entidad) AND (jos_escuelas.localidad = jos_localidades.id)
GROUP BY jos_escuelas.estado, jos_escuelas.municipio, jos_escuelas.localidad, jos_localidades.localidad
HAVING (((jos_escuelas.estado)='.$idEdo.') AND ((jos_escuelas.municipio)='.$idMun.'))
		ORDER BY jos_localidades.localidad;';
		$db->setQuery( $query );
		$rows=$db->loadObjectList();
		@ob_end_clean();
		$cadena = '';
		foreach($rows as $row)
		{
			$cadena .= "<".$row->id.">".$row->localidad;
		}
		echo $cadena;
	}
	
	function showEscuelas(){
		$idEdo = JRequest::getVar( 'idEdo', '', 'get', 'cmd' ) ;
		$nivel = JRequest::getVar( 'nivel', '', 'get', 'cmd' ) ;
		$municipio = JRequest::getVar( 'municipio', '', 'get', 'cmd' ) ;
		$localidad = JRequest::getVar( 'localidad', '', 'get', 'cmd' ) ;
		$subsistema = JRequest::getVar( 'subsistema', '', 'get', 'cmd' ) ;
		$db = & JFactory::getDBO();

		$query = 'SELECT jos_escuelas.cctREAL, jos_escuelas.plantel FROM jos_escuelas
GROUP BY jos_escuelas.estado, jos_escuelas.municipio, jos_escuelas.localidad, jos_escuelas.nivel, jos_escuelas.subsistema, jos_escuelas.cctREAL, jos_escuelas.plantel
HAVING (((jos_escuelas.estado)='.$idEdo.') AND ((jos_escuelas.municipio)='.$municipio.') AND ((jos_escuelas.localidad)='.$localidad.') AND ((jos_escuelas.nivel)='.$nivel.') AND ((jos_escuelas.subsistema)='.$subsistema.'))
		ORDER BY jos_escuelas.plantel;';
		$db->setQuery( $query );
		$rows=$db->loadObjectList();
		@ob_end_clean();
		$cadena = '';
		foreach($rows as $row)
		{
			$cadena .= "<".$row->cctREAL.">".$row->plantel;
		}
		echo $cadena;
	}
	
	function showTurno(){
		$idEdo = JRequest::getVar( 'idEdo', '', 'get', 'cmd' ) ;
		$nivel = JRequest::getVar( 'nivel', '', 'get', 'cmd' ) ;
		$municipio = JRequest::getVar( 'municipio', '', 'get', 'cmd' ) ;
		$localidad = JRequest::getVar( 'localidad', '', 'get', 'cmd' ) ;
		$subsistema = JRequest::getVar( 'subsistema', '', 'get', 'cmd' ) ;
		$cct = JRequest::getVar( 'cct', '', 'get', 'cmd' ) ;
		$db = & JFactory::getDBO();
		$query = 'SELECT jos_turnos.idturno as id, jos_turnos.turno
FROM jos_escuelas INNER JOIN jos_turnos ON jos_escuelas.turno = jos_turnos.idturno
GROUP BY jos_escuelas.cctREAL, jos_escuelas.estado, jos_escuelas.municipio, jos_escuelas.localidad, jos_escuelas.nivel, jos_escuelas.subsistema, jos_turnos.idturno, jos_turnos.turno
HAVING (((jos_escuelas.cctREAL)="'.$cct.'") AND ((jos_escuelas.estado)='.$idEdo.') AND ((jos_escuelas.municipio)='.$municipio.') AND ((jos_escuelas.localidad)='.$localidad.') AND ((jos_escuelas.nivel)='.$nivel.') AND ((jos_escuelas.subsistema)='.$subsistema.'));';
		$db->setQuery( $query );
		$rows=$db->loadObjectList();
		@ob_end_clean();
		$cadena = '';
		foreach($rows as $row)
		{
			$cadena .= "<".$row->id.">".$row->turno;
		}
		echo $cadena;
	}
	//FIN JAVASCRIPT//////////////////////////////////////////////////////////////////////////////////////////

}

?>
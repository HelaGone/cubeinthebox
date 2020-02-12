<?php
  // header("Content-Type:application/json");
  if(isset($_GET['type']) && $_GET['type'] != ""){
    $fetch_url = "";
    if($_GET['type'] === "novelas"){
      $fetch_url = "http://static-feeds.esmas.com/awsfeeds/television/menu-television-telenovelas.xml";
    }else if($_GET['type'] === "tv"){
      $fetch_url = "http://static-feeds.esmas.com/awsfeeds/television/menu-television-programas-de-tv.xml";
    }

    le_get_media_assets($fetch_url);
  }

  function le_get_media_assets($url){
    $prog_arr = array();

    /*GET PROGRAMAS DE TV*/
    $programastv = simplexml_load_file($url);
    foreach($programastv->entry as $programa){
      preg_match('/televisadigital:\/\/fetchData\?type=televisa_collection&url=([a-z\D0-9]{1,})/', $programa->link->attributes()->href, $matches);

      /*GET PROGRAMAS*/
      $programXml = simplexml_load_file($matches[1]);

      foreach ($programXml->entry as $key => $program) {
        preg_match('/televisadigital:\/\/fetchData\?type=televisa_collection&url=([a-z\D0-9]{1,})/', $program->link->attributes()->href, $matc);
        $program = array(
          "id"=>strval($program->id),
          "title"=>strval($program->title),
          "summary"=>strval($program->summary),
          "link"=>$matc[1],
        );

        /*GET EPISODES*/
        $episodeXML = simplexml_load_file($program['link']);
        $epis_arr = array();
        foreach ($episodeXML->entry as $key => $episode) {
          $epi_obj = array(
            "id"=>strval($episode->id),
            "title"=>strval($episode->title),
            "summary"=>strval($episode->summary),
            "content"=>strval($episode->content->attributes()->src)
          );
          array_push($epis_arr, $epi_obj);
          $program["episodios"] = $epis_arr;
        }//END THIRD FOREACH

        /*PUSH PROGRAM ELEMENTS TO PROGRAM ARRAY*/
        array_push($prog_arr, $program);

      } //END SECOND FOREACH

    }//END FIRST FOREACH

    echo json_encode($prog_arr);
  }
?>

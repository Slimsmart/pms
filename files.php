<?php
function docx_to_text($input_file){

    $xml_filename = "word/document.xml";
    $zip_handle = new ZipArchive;
    $output_text = "";

    if ($zip_handle->open($input_file) === TRUE){

        if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){

            $xml_datas = $zip_handle->getFromIndex($xml_index);

            // ✅ CREATE OBJECT FIRST
            $dom = new DOMDocument();

            // ✅ LOAD XML USING OBJECT
            $dom->loadXML(
                $xml_datas,
                LIBXML_NOENT |
                LIBXML_XINCLUDE |
                LIBXML_NOERROR |
                LIBXML_NOWARNING
            );

            $output_text = strip_tags($dom->saveXML());

        }

        $zip_handle->close();
    }

    return $output_text;
}

function doc_to_text($input_file){
	$file_handle = fopen($input_file, "r"); //open the file
	$stream_text = @fread($file_handle, filesize($input_file));
	$stream_line = explode(chr(0x0D),$stream_text);
	$output_text = "";
	foreach($stream_line as $single_line){
		$line_pos = strpos($single_line, chr(0x00));
		if(($line_pos !== FALSE) || (strlen($single_line)==0)){
			$output_text .= "";
		}else{
			$output_text .= $single_line." ";
		}
	}
	$output_text = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $output_text);
	return $output_text;
}

//echo docx_to_text("sample.docx");
?>

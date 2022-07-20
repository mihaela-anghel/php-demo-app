<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Generator extends CI_Controller 
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();	
	}
	
	public function index()
	{		
		if(isset($_POST["Generate"]))
		{
			//form validation
			//=========================================================		
			$this->load->library('form_validation');
			$this->form_validation->set_rules("section",		"",	"trim|required");											
			$this->form_validation->set_rules("name_sg",		"",	"trim|required");
			$this->form_validation->set_rules("name_pl",		"",	"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{							
				//get post variables
				$sestion_name_sg = $_POST["name_sg"];
				$sestion_name_pl = $_POST["name_pl"];
										
				$sourse_path 	= base_path()."myclasses/generator/".$_POST["section"];
				$clone_path 	= base_path()."myclasses/generator/".$_POST["section"]."_";
				
				//clone directory
				$this->load->helper('directory');
				if(!file_exists($clone_path))
					copy_directory($sourse_path,$clone_path);		
				
				//parse folders and files	
				$path 		= realpath($clone_path);
				$objects 	= new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
			 	foreach($objects as $name => $object) 
			 	{	   			 		
			 		//old file or folder
			 		$old_file_name = $object->getFilename();	   			 			 			   		
			   		$old_file_path = $object->getPathname();	   
		
			   		//new file or folder
			   		$new_file_name = $old_file_name;
			   		$new_file_name = str_replace("qwertys",$sestion_name_pl,$new_file_name);
			   		$new_file_name = str_replace("qwerty",$sestion_name_sg,$new_file_name);
			   		$new_file_path = $object->getPath()."\\".$new_file_name;
			   		
			   		//rename file or folder		   	
			   		if($old_file_path != $new_file_path)
			   			rename($old_file_path, $new_file_path);
			   				   		   			   		
			   		//rename files content	
					if(is_file($new_file_path))
					{
						//old content
						$content 	 = file_get_contents($new_file_path);
						
						//new content
						$new_content = $content;
						$new_content = str_replace(ucfirst("qwertys"),ucfirst($sestion_name_pl),$new_content);
						$new_content = str_replace("qwertys",$sestion_name_pl,$new_content);				
			   			$new_content = str_replace(ucfirst("qwerty"),ucfirst($sestion_name_sg),$new_content);				
			   			$new_content = str_replace("qwerty",$sestion_name_sg,$new_content);
		
			   			//replace content
						file_put_contents($new_file_path, $new_content);	   			
					}	

					//run sql querys
					if($new_file_name == "db.sql")
					{						
						$file  = str_replace("\\","/",$new_file_path);
						if($fp = file_get_contents($file)) 
						{
							$var_array = explode(';',$fp);
							foreach($var_array as $value) 
							{
								if(trim($value))
								{						    
									$this->db->query($value);						    							    
								}	
							}
						}  															
					}													
			 	}	
			 	
			 	//move folders into application
			 	//parse folders and files					
				$objects 	= new RecursiveIteratorIterator(new RecursiveDirectoryIterator($clone_path), RecursiveIteratorIterator:: CATCH_GET_CHILD );
			 	foreach($objects as $name => $object) 
			 	{
			 		$old_file_name = $object->getFilename();
			 		$old_file_path = $object->getPathname();
			 		$old_file_path = str_replace("\\","/",$old_file_path);
			 		if($old_file_name != "db.sql")
			 		{
			 			$new_file_path = str_replace($clone_path,substr(base_path().($old_file_name != "uploads"?APPPATH:"/"),0,-1),$old_file_path);
			 			
			 			copy_directory($old_file_path,$new_file_path);									 			
			 		}	   			 		
			 	}
			 	
			 	//delete clone			 	
				foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($clone_path, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) 
				{
				    $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
				}
				rmdir($clone_path);
				
				echo "Successfully generated";
				unset($_POST);
			}		
		}

		$this->load->helper("form");
		$options = array(	"one-level-one-lang",
							"one-level-one-lang-fara-images-fara-files",	
							"one-level-multi-lang",
							"one-level-multi-lang-fara-images-fara-files",
							"multi-level-multi-lang",
							"multi-level-multi-lang-fara-images-fara-files",
							"multi-level-one-lang",							
							"multi-level-one-lang-fara-images-fara-files",
						);
		?>
		<form action="" method="post">
			<p>section<br/>
				<select name="section">
					<?php
					foreach($options as $option)
					{
						?>
						<option name="<?php echo $option?>"><?php echo $option?></option>	
						<?php						
					}
					?>									
				</select>
			</p>
		    <?php echo form_error("section"); ?>
		    
		    <p>singular name<br/><input type="text" name="name_sg" value=""/></p>
		    <?php echo form_error("name_sg"); ?>
		    <p>plural name<br/><input type="text" name="name_pl" value=""/></p>
		    <?php echo form_error("name_pl"); ?>
		    <p><input type="submit" name="Generate" value="Generate"></p>
		</form>
		<?php
	}		
}
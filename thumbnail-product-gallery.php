         <?php 
           	$currentPostId=get_the_ID();
           	$img_html="";
           	if(!empty($image )){
           		$img_html_For='<div class="properties-img"><img src="'.$image.'"></div>';
           		$img_html_Nav='<div><figure><img src="'.$image.'"></figure></div>';
           	}
			$gallery = get_post_meta($currentPostId ,'gallery_data',true);	
			$siderFor='<div class="slider-for">'.$img_html_For;
			 $siderNav='<div class="slider-nav">'.$img_html_Nav;	
			if(isset($gallery['image_url']) && count($gallery['image_url'])>0){
				
			for($i=0;$i<count($gallery['image_url']);$i++){
				$siderFor.='<div class="properties-img"><img src="'.$gallery['image_url'][$i].'"></div>';
				$siderNav.='<div><figure><img src="'.$gallery['image_url'][$i].'"></figure></div>';				
			}
			
      	    } 
      	    $siderFor.='</div>';
			$siderNav.='</div>';
			echo $siderFor.$siderNav;
		 	?>